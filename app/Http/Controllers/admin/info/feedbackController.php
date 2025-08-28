<?php

namespace App\Http\Controllers\admin\info;

use App\Http\Controllers\Controller;
use App\Models\system\Feedback;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class feedbackController extends Controller
{
    private Getters $getters;
    private int $paginate;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
        $this->paginate = 20;
    }

    public function index(): View|Application|Factory|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/feedback')) return $redirect;
        $this->getters->setSEOTitle('feedback');
        $paginate = $this->paginate;

        $query = Feedback::orderByDesc('created_at');
        $data['data'] = $query->paginate($paginate);

        $data['items'] = [];

        foreach ($data['data'] as $item) {
            $data['items'][] = [
                'id' => $item['id'],
                'theme' => $item['theme'],
                'name' => $item['name'],
                'email' => $item['email'],
                'message' => $item['message'],
                'date_added' => date('d.m.Y', strtotime($item['created_at'])),
            ];
        }

        $data['elements'] = $this->getters->getAdminHeaderFooter();

        return view('admin/info/feedback', ['data' => $data]);
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('feedback.index');
    }

    public function store(Request $request): RedirectResponse
    {
        return redirect()->route('feedback.index');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id): RedirectResponse
    {
        return redirect()->route('feedback.index');
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        return redirect()->route('feedback.index');
    }

    public function destroy(string $id): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/review_delete')) return $redirect;
        Feedback::where('id', $id)->delete();
        return redirect()->route('feedback.index')->with('success', __('admin/info/feedback.notify_deleted'));
    }
}
