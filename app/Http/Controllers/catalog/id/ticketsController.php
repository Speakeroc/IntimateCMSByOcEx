<?php

namespace App\Http\Controllers\catalog\id;

use App\Http\Controllers\Controller;
use App\Models\system\Getters;
use App\Models\system\TicketMessages;
use App\Models\system\Tickets;
use App\Models\system\TicketStatuses;
use App\Models\Users;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ticketsController extends Controller
{
    private Getters $getters;

    public function __construct()
    {
        $this->getters = new Getters;
    }

    public function index()
    {
        $data['title'] = __('catalog/id/tickets.page_main');
        $this->getters->setMetaInfo(title: $data['title'], url: route('client.auth.posts'));

        $data['data'] = Tickets::where('user_id', Auth::id())->orderByDesc('id')->paginate(20);

        $data['items'] = [];

        foreach ($data['data'] as $item) {
            $user = Users::where('id', Auth::id())->first();
            $ticket_first_content = TicketMessages::where('ticket_id', $item['id'])->orderByDesc('id')->first();
            $messages = TicketMessages::where('ticket_id', $item['id'])->where('id', '!=', $ticket_first_content['id'])->count();
            $answer = TicketMessages::where('ticket_id', $item['id'])->where('is_admin', true)->first();

            $data['items'][] = [
                'id' => $item['id'],
                'user' => $user['name'],
                'subject' => $item['subject'],
                'messages' => $messages,
                'created' => $this->getters->dateText($item['created_at']),
                'answer' => (!empty($answer)) ? $this->getters->dateText($answer['created_at']) : __('catalog/id/tickets.no_answer'),
                'status_id' => $item['status_id'],
                'status' => TicketStatuses::where('id', $item['status_id'])->value('name'),
                'link' => route('client.auth.tickets.item', ['id' => base64_encode($item['id'])]),
            ];
        }

        $breadcrumbs = [
            ['link' => route('client.auth.tickets'), 'title' => __('catalog/id/tickets.page_main')],
        ];
        $data['breadcrumb'] = $this->getters->breadcrumbPages($breadcrumbs);
        $data['elements'] = $this->getters->getHeaderFooter();

        return view('catalog/id/tickets/list', ['data' => $data]);
    }

    public function create(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $title = __('catalog/id/tickets.page_create');
        $this->getters->setMetaInfo(title: $title, url: route('client.auth.post.create'));

        $breadcrumbs = [
            ['link' => route('client.auth.tickets'), 'title' => __('catalog/id/tickets.page_main')],
            ['link' => route('client.auth.tickets.create'), 'title' => __('catalog/id/tickets.page_create')],
        ];

        $data = [
            'title' => $title,
            'breadcrumb' => $this->getters->breadcrumbPages($breadcrumbs),
            'elements' => $this->getters->getHeaderFooter(),
        ];

        return view('catalog/id/tickets/create', ['data' => $data]);
    }

    public function createPost(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'ticket_subject' => 'required|min:5|max:250',
            'ticket_content' => 'required|min:5|max:3000',
        ]);

        $user_id = (int)Auth::id();
        $subject = $request->input('ticket_subject');
        $content = $request->input('ticket_content');

        $ticket = Tickets::create([
            'user_id' => $user_id,
            'subject' => $subject,
            'status_id' => 1,
            'view_admin' => 1,
        ]);

        $ticket_id = $ticket->id;

        TicketMessages::create([
            'ticket_id' => $ticket_id,
            'user_id' => $user_id,
            'content' => $this->getters->convertTextData($content),
            'status_id' => 1,
        ]);

        return redirect()->route('client.auth.tickets')->with('success', __('catalog/id/tickets.notify_ticket_success'));
    }

    public function item($id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        $id = base64_decode($id);
        $title = __('catalog/id/tickets.page_item', ['num' => $id]);
        $this->getters->setMetaInfo(title: $title, url: route('client.auth.post.create'));

        $ticket = Tickets::where('id', $id)->first();
        $ticket_first_content = TicketMessages::where('ticket_id', $id)->orderBy('id')->first();

        if (Auth::id() != $ticket['user_id']) return redirect()->route('client.errors', ['code' => 404]);

        $breadcrumbs = [
            ['link' => route('client.auth.tickets'), 'title' => __('catalog/id/tickets.page_main')],
            ['link' => route('client.auth.tickets.item', ['id' => base64_encode($id)]), 'title' => $title],
        ];

        $data = [
            'title' => $title,
            'breadcrumb' => $this->getters->breadcrumbPages($breadcrumbs),
            'elements' => $this->getters->getHeaderFooter(),
        ];

        $data['id'] = $id;
        $data['subject'] = $ticket['subject'];
        $data['status_id'] = $ticket['status_id'];
        $data['first_content'] = $this->getters->reverseTextData($ticket_first_content['content']);

        $data['created'] = $this->getters->dateText($ticket['created_at']);
        $answer = TicketMessages::where('ticket_id', $id)->where('is_admin', true)->first();
        $data['answer'] = (!empty($answer)) ? $this->getters->dateText($answer['created_at']) : __('catalog/id/tickets.no_answer');

        $all_messages = TicketMessages::where('ticket_id', $id)->where('id', '!=', $ticket_first_content['id'])->orderBy('created_at')->get();
        $data['all_messages'] = [];

        foreach ($all_messages as $message) {
            $user_name = Users::where('id', $message['user_id'])->value('name');
            $data['all_messages'][] = [
                'id' => $message['id'],
                'name' => ($message['is_admin']) ? __('catalog/id/tickets.support', ['name' => $user_name]) : $user_name,
                'user_id' => $message['user_id'],
                'content' => $this->getters->reverseTextData($message['content']),
                'is_admin' => $message['is_admin'],
                'created_at' => $this->getters->dateText($message['created_at'])
            ];
        }

        $data['message_count'] = TicketMessages::where('ticket_id', $id)->where('id', '!=', $ticket_first_content['id'])->count();

        Tickets::where('id', $id)->update(['view_user' => 0]);

        return view('catalog/id/tickets/item', ['data' => $data]);
    }

    public function writeTicket(Request $request): JsonResponse
    {
        $ticket_id = $request->input('ticket_id');
        $ticket_content = $request->input('ticket_content');
        $is_admin = ($request->input('is_admin')) ? 1 : 0;

        $ticket = Tickets::where('id', $ticket_id)->first();

        if ($ticket['status_id'] == 2) {
            return response()->json(['status' => 'error', 'message' => __('catalog/id/tickets.notify_ticket_closed')]);
        }

        if (empty($ticket_content)) {
            return response()->json(['status' => 'error', 'message' => __('catalog/id/tickets.notify_write_no_content')]);
        }

        $ticket_content = $this->getters->convertTextData($request->input('ticket_content'));

        if ($ticket_id) {
            Tickets::where('id', $ticket_id)->update([
                'view_admin' => (!$is_admin) ? 1 : 0,
                'view_user' => ($is_admin) ? 1 : 0,
            ]);
            $message = TicketMessages::create(['ticket_id' => $ticket_id, 'user_id' => Auth::id(), 'content' => $ticket_content, 'is_admin' => $is_admin]);
            $message_id = $message->id;
            return response()->json(['status' => 'success', 'message' => __('catalog/id/tickets.notify_write_success'), 'view' => $this->loadMessage($message_id)]);
        }

        return response()->json(['status' => 'error', 'message' => __('catalog/id/tickets.notify_write_no_content')]);
    }


    public function loadMessage($message_id): string
    {
        $message = TicketMessages::where('id', $message_id)->first();

        $data['exists'] = false;
        if ($message) {
            $data['exists'] = true;
            $user_name = Users::where('id', $message['user_id'])->value('name');
            $data['name'] = ($message['is_admin']) ? __('admin/info/tickets.support', ['name' => $user_name]) : $user_name;
            $data['content'] = $this->getters->reverseTextData($message['content']);
            $data['is_admin'] = $message['is_admin'];
            $data['created_at'] = $this->getters->dateText($message['created_at']);
        }

        return view('catalog/id/tickets/item_render', ['data' => $data])->render();
    }

    public function closeTicket(Request $request): JsonResponse
    {
        $ticket_id = $request->input('ticket_id');
        $is_admin = $request->input('is_admin') ?? null;

        $ticket = Tickets::where('id', $ticket_id)->where('status_id', 1)->first();

        if (isset($is_admin) && $is_admin) {
            Tickets::where('id', $ticket_id)->update(['status_id' => 2]);
            return response()->json(['status' => 'success', 'message' => __('catalog/id/tickets.notify_ticket_close')]);
        }

        if (isset($ticket['user_id']) && $ticket['user_id'] == Auth::id()) {
            Tickets::where('id', $ticket_id)->update(['status_id' => 2]);
            return response()->json(['status' => 'success', 'message' => __('catalog/id/tickets.notify_ticket_close')]);
        }

        return response()->json(['status' => 'error', 'message' => __('catalog/id/tickets.notify_error_close')]);
    }
}
