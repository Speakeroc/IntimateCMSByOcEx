<?php

namespace App\Http\Controllers\admin\posts;

use App\Http\Controllers\Controller;
use App\Models\posts\Category;
use App\Models\system\Getters;
use App\Models\Users;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class categoryController extends Controller
{
    private Getters $getters;
    private int $paginate;

    public function __construct() {
        $this->getters = new Getters;
        $this->users = new Users;
        $this->paginate = 20;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $this->getters->setSEOTitle('category');

        $paginate = $this->paginate;
        $data['data'] = Category::paginate($paginate);

        $data['items'] = [];

        foreach ($data['data'] as $item) {
            $data['items'][] = [
                'id' => $item['id'],
                'title' => $item['title'],
                'only_verify' => $item['only_verify'] ? __('admin/posts/category.only_verify') :  __('admin/posts/category.no_only_verify'),
                'status' => $item['status'],
            ];
        }

        $data['elements'] = $this->getters->getAdminHeaderFooter();

        return view('admin/posts/category/index', ['data' => $data]);
    }

    public function create(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $this->getters->setSEOTitle('category_add');
        $data['elements'] = $this->getters->getAdminHeaderFooter();
        return view('admin/posts/category/create', ['data' => $data]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'title' => ['required', 'min:3', 'unique:ex_posts_category'],
            'status' => 'required'
        ]);

        Category::create([
            'title' => $request->input('title'),
            'description' => $this->getters->convertTextData($request->input('description')),
            'meta_title' => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
            'slug' => Str::slug($request->input('title')),
            'only_verify' => $request->input('only_verify') ?? 0,
            'status' => $request->input('status')
        ]);

        return redirect()->route('category.index')->with('success', __('admin/posts/category.notify_created'));
    }

    public function show(string $id) {
        //
    }

    public function edit(string $id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $this->getters->setSEOTitle('category_edit');
        $item = Category::where('id', $id)->first();
        $data['id'] = $id;
        $data['title'] = $item['title'];
        $data['description'] = $this->getters->reverseTextData($item['description']);
        $data['meta_title'] = $item['meta_title'];
        $data['meta_description'] = $item['meta_description'];
        $data['slug'] = $item['slug'];
        $data['only_verify'] = $item['only_verify'];
        $data['status'] = $item['status'];
        $data['elements'] = $this->getters->getAdminHeaderFooter();
        return view('admin/posts/category/edit', ['data' => $data]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $this->validate($request, [
            'title' => 'required|min:3',
            'status' => 'required'
        ]);
        Category::where('id', '=', $id)->update([
            'title' => $request->input('title'),
            'description' => $this->getters->convertTextData($request->input('description')),
            'meta_title' => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
            'slug' => Str::slug($request->input('title')),
            'status' => $request->input('status'),
            'only_verify' => $request->input('only_verify') ?? 0
        ]);
        return redirect()->route('category.index')->with('success', __('admin/posts/category.notify_updated'));
    }

    public function destroy(string $id): RedirectResponse
    {
        Category::where('id', $id)->delete();
        return redirect()->route('category.index')->with('success', __('admin/posts/category.notify_deleted'));
    }
}
