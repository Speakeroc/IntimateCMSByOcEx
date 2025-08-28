<?php

namespace App\Http\Controllers\admin\info;

use App\Http\Controllers\Controller;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use App\Models\system\TicketMessages;
use App\Models\system\Tickets;
use App\Models\system\TicketStatuses;
use App\Models\Users;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ticketsController extends Controller
{
    private Getters $getters;
    private int $paginate;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
        $this->paginate = 20;
    }

    public function index()
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/ticket_system')) return $redirect;
        $this->getters->setSEOTitle('ticket_system');
        $paginate = $this->paginate;

        $open = Tickets::where('status_id', 1)->orderBy('created_at')->limit(1000000000);
        $close = Tickets::where('status_id', 2)->orderBy('created_at')->limit(1000000000);
        $data['data'] = $items = $open->union($close)->paginate($paginate);

        $data['items'] = [];

        foreach ($items as $item) {
            $user = Users::where('id', $item['user_id'])->first();
            $ticket_first_content = TicketMessages::where('ticket_id', $item['id'])->orderByDesc('id')->first();
            $messages = TicketMessages::where('ticket_id', $item['id'])->where('id', '!=', $ticket_first_content['id'])->count();

            $data['items'][] = [
                'id' => $item['id'],
                'user' => $user['name'],
                'user_link' => route('users.index') . '?user_id=' . $item['user_id'],
                'subject' => $item['subject'],
                'messages' => $messages,
                'created' => $this->getters->dateText($item['created_at']),
                'status_id' => $item['status_id'],
                'view_admin_id' => $item['view_admin'],
                'view_admin' => ($item['view_admin']) ? __('admin/info/tickets.view_admin_1') : __('admin/info/tickets.view_admin_0'),
                'status' => TicketStatuses::where('id', $item['status_id'])->value('name'),
            ];
        }

        $data['elements'] = $this->getters->getAdminHeaderFooter();

        return view('admin/info/tickets/index', ['data' => $data]);
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('ticket_system.index');
    }

    public function store(Request $request): RedirectResponse
    {
        return redirect()->route('ticket_system.index');
    }

    public function show(string $id): View|Application|Factory|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/ticket_system_write')) return $redirect;
        $this->getters->setSEOTitle('ticket_system');

        $ticket = Tickets::where('id', $id)->first();
        $ticket_first_content = TicketMessages::where('ticket_id', $id)->orderBy('id')->first();

        $data['id'] = $id;
        $data['subject'] = $ticket['subject'];
        $data['status_id'] = $ticket['status_id'];
        $data['status'] = TicketStatuses::where('id', $ticket['status_id'])->value('name');
        $data['first_content'] = $this->getters->reverseTextData($ticket_first_content['content']);
        $data['message_count'] = TicketMessages::where('ticket_id', $id)->where('id', '!=', $ticket_first_content['id'])->count();
        $data['created'] = $this->getters->dateText($ticket['created_at']);
        $answer = TicketMessages::where('ticket_id', $id)->where('is_admin', true)->first();
        $data['answer'] = (!empty($answer)) ? $this->getters->dateText($answer['created_at']) : __('admin/info/tickets.no_answer');

        //Messages
        $all_messages = TicketMessages::where('ticket_id', $id)->where('id', '!=', $ticket_first_content['id'])->orderBy('created_at')->get();
        $data['all_messages'] = [];

        foreach ($all_messages as $message) {
            $user_name = Users::where('id', $message['user_id'])->value('name');
            $data['all_messages'][] = [
                'id' => $message['id'],
                'name' => ($message['is_admin']) ? __('admin/info/tickets.support', ['name' => $user_name]) : $user_name,
                'user_id' => $message['user_id'],
                'content' => $this->getters->reverseTextData($message['content']),
                'is_admin' => $message['is_admin'],
                'created_at' => $this->getters->dateText($message['created_at'])
            ];
        }

        $data['title'] = __('admin/info/tickets.ticket_id', ['num' => $id]);

        $data['elements'] = $this->getters->getAdminHeaderFooter();

        Tickets::where('id', $id)->update(['view_admin' => 0]);

        return view('admin/info/tickets/show', ['data' => $data]);
    }

    public function edit(string $id)
    {
        return redirect()->route('ticket_system.index');
    }

    public function update(Request $request, string $id)
    {
        return redirect()->route('ticket_system.index');
    }

    public function destroy(string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/ticket_system_delete')) return $redirect;
        Tickets::where('id', $id)->delete();
        TicketMessages::where('ticket_id', $id)->delete();

        return redirect()->route('ticket_system.index')->with('success', __('admin/info/tickets.notify_deleted'));
    }
}
