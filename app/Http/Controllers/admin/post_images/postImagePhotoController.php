<?php

namespace App\Http\Controllers\admin\post_images;

use App\Http\Controllers\Controller;
use App\Models\posts\PostContent;
use App\Models\system\Getters;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;

class postImagePhotoController extends Controller
{
    private Getters $getters;
    private int $paginate;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->paginate = 90;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/post_content_photo')) return $redirect;
        $this->getters->setSEOTitle('post_images_photo');
        $data['heading_title'] = __('admin/page_titles.post_images_photo');
        $paginate = $this->paginate;

        $data['main_data'] = $this->getters->getMainPostData();
        $query = PostContent::query();
        $query->where('type','photo');
        $query->orderBy('file');
        $data['data'] = $query->paginate($paginate);

        $data['items'] = [];

        foreach ($data['data'] as $item) {
            $data['items'][] = ['id' => $item['id'], 'file' => $item['file'], 'link_delete' => route('content_photo.destroy', $item['id'])];
        }

        $data['elements'] = $this->getters->getAdminHeaderFooter();

        return view('admin/post_images/main', ['data' => $data]);
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('content_photo.index');
    }

    public function store(): RedirectResponse
    {
        return redirect()->route('content_photo.index');
    }

    public function show(): RedirectResponse
    {
        return redirect()->route('content_photo.index');
    }

    public function edit(): RedirectResponse
    {
        return redirect()->route('content_photo.index');
    }

    public function update(): RedirectResponse
    {
        return redirect()->route('content_photo.index');
    }

    public function destroy(string $id): JsonResponse|RedirectResponse
    {
        if (request()->ajax()) {
            if ($this->getters->getAdminAccess(type: 'modify', key: 'edit/post_content_photo_delete')) return response()->json(['success' => true, 'message' => 'Доступ запрещен']);
        } else {
            if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
            if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/post_content_photo_delete')) return $redirect;
        }

        $file = PostContent::where('id', $id)->first();

        PostContent::where('id', $id)->delete();

        if ($file) {
            $filePath = public_path($file->file);
            $directoryPath = dirname($filePath);

            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            $this->deleteEmptyDirectories($directoryPath);

            $directoryMain = dirname($directoryPath);
            $this->deleteEmptyDirectories($directoryMain);
        }

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => __('admin/posts/post.notify_image_deleted')]);
        } else {
            return redirect()->route('content_photo.index')->with('success', __('admin/posts/post.notify_image_deleted'));
        }
    }

    private function deleteEmptyDirectories(string $directoryPath): void
    {
        foreach (File::directories($directoryPath) as $subDirectory) {
            $this->deleteEmptyDirectories($subDirectory);
        }

        if (File::isDirectory($directoryPath) && count(File::allFiles($directoryPath)) === 0 && count(File::directories($directoryPath)) === 0) {
            File::deleteDirectory($directoryPath);
        }
    }
}
