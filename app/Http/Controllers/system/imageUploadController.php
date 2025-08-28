<?php

namespace App\Http\Controllers\system;

use App\Http\Controllers\Controller;
use App\Models\system\Getters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class imageUploadController extends Controller
{
    private Getters $getters;

    public function __construct()
    {
        $this->getters = new Getters;
    }

    public function uploadImage(Request $request): JsonResponse
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $request->input('path');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '_' . uniqid() . '.' . $extension;
            $path = public_path($path);

            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }

            $file->move($path, $filename);

            return response()->json(['success' => true, 'filename' => $filename]);
        }

        return response()->json(['success' => false], 400);
    }

    public function multiUploadImage(Request $request): JsonResponse
    {
        $random_id = $request->input('random_id');
        $photo_type = $request->input('photo_type');

        $allowedFormats = [];
        $maxWidth = null;
        $maxHeight = null;
        $maxSize = null;

        // Настройки на основе типа файла
        switch ($photo_type) {
            case 'main':
                $setting = $this->getters->getSetting('post_main_photo');
                $allowedFormats = array_filter(array_map('trim', explode(",", $setting['access_format']))) ?: [];
                $maxWidth = (int)$setting['max_width'];
                $maxHeight = (int)$setting['max_height'];
                $maxSize = (int)$setting['size'] * 1024 * 1024;
                break;

            case 'photos':
                $setting = $this->getters->getSetting('post_photo');
                $allowedFormats = array_filter(array_map('trim', explode(",", $setting['access_format']))) ?: [];
                $maxWidth = (int)$setting['max_width'];
                $maxHeight = (int)$setting['max_height'];
                $maxSize = (int)$setting['size'] * 1024 * 1024;
                break;

            case 'selfies':
                $setting = $this->getters->getSetting('post_selfie');
                $allowedFormats = array_filter(array_map('trim', explode(",", $setting['access_format']))) ?: [];
                $maxWidth = (int)$setting['max_width'];
                $maxHeight = (int)$setting['max_height'];
                $maxSize = (int)$setting['size'] * 1024 * 1024;
                break;

            case 'videos':
                $setting = $this->getters->getSetting('post_video');
                $allowedFormats = array_filter(array_map('trim', explode(",", $setting['access_format']))) ?: [];
                $allowedFormats = str_replace('quicktime','mov',$allowedFormats);
                $maxSize = (int)$setting['size'] * 1024 * 1024;
                break;

            case 'verify':
                $setting = $this->getters->getSetting('post_verify');
                $allowedFormats = array_filter(array_map('trim', explode(",", $setting['access_format']))) ?: [];
                $maxWidth = (int)$setting['max_width'];
                $maxHeight = (int)$setting['max_height'];
                $maxSize = (int)$setting['size'] * 1024 * 1024;
                break;

            case 'salon_photos':
                $setting = $this->getters->getSetting('salon_photo');
                $allowedFormats = array_filter(array_map('trim', explode(",", $setting['access_format']))) ?: [];
                $maxWidth = (int)$setting['max_width'];
                $maxHeight = (int)$setting['max_height'];
                $maxSize = (int)$setting['size'] * 1024 * 1024;
                break;

        }

        if ($request->hasFile('images')) {
            $uploadedFiles = [];
            $errors = [];

            foreach ($request->file('images') as $file) {
                $extension = strtolower($file->extension());
                $fileSize = $file->getSize();

                if (!empty($allowedFormats) && !in_array($extension, $allowedFormats)) {
                    $errors[] = 'Не верный формат изображения - Формат файла:'. $extension .' - Формат разрешенный:' . json_encode($allowedFormats).' - Файл:'.$file->getClientOriginalName();
                    continue;
                }

                if ($fileSize > $maxSize) {
                    $errors[] = 'Размер файла превышает лимит - Размер файла:'. $fileSize .' - Максимальный размер:' . $maxSize.' - Файл:'.$file->getClientOriginalName();
                    continue;
                }

                if ($photo_type !== 'videos') {
                    [$width, $height] = getimagesize($file->getPathname());
                    if ($width > $maxWidth || $height > $maxHeight) {
                        $errors[] = 'Стороны изображения превышают допустимые лимиты - Размер:'. $width.'x'.$height .' - Максимальный размер:' . $maxWidth.'x'.$maxHeight.' - Файл:'.$file->getClientOriginalName();
                        continue;
                    }
                }

                $filename = uniqid() . '_' . rand(1, 1000) . '.' . $extension;
                $path = 'images/temp/posts/post_images/' . $photo_type . '/' . $random_id . '/' . $filename;
                $file->move(public_path('images/temp/posts/post_images/' . $photo_type . '/' . $random_id), $filename);
                $uploadedFiles[] = $path;
            }

            if (!empty($uploadedFiles)) {
                return response()->json(['files' => $uploadedFiles, 'errors' => $errors ?: null]);
            } else {
                return response()->json(['error' => $errors ?: 'No valid files uploaded.'], 400);
            }
        }

        return response()->json(['error' => 'No images found.'], 400);
    }

    public function multiUploadImageSalon(Request $request): JsonResponse
    {
        $random_id = $request->input('random_id');
        $photo_type = $request->input('photo_type');

        $allowedFormats = [];
        $maxWidth = null;
        $maxHeight = null;
        $maxSize = null;

        // Настройки на основе типа файла
        switch ($photo_type) {
            case 'main':
                $setting = $this->getters->getSetting('salon_main_photo');
                $allowedFormats = array_filter(array_map('trim', explode(",", $setting['access_format']))) ?: [];
                $maxWidth = (int)$setting['max_width'];
                $maxHeight = (int)$setting['max_height'];
                $maxSize = (int)$setting['size'] * 1024 * 1024;
                break;

            case 'photos':
                $setting = $this->getters->getSetting('salon_photo');
                $allowedFormats = array_filter(array_map('trim', explode(",", $setting['access_format']))) ?: [];
                $maxWidth = (int)$setting['max_width'];
                $maxHeight = (int)$setting['max_height'];
                $maxSize = (int)$setting['size'] * 1024 * 1024;
                break;
        }

        if ($request->hasFile('images')) {
            $uploadedFiles = [];
            $errors = [];

            foreach ($request->file('images') as $file) {
                $extension = strtolower($file->extension());
                $fileSize = $file->getSize();

                if (!empty($allowedFormats) && !in_array($extension, $allowedFormats)) {
                    $errors[] = 'Не верный формат изображения - Формат файла:'. $extension .' - Формат разрешенный:' . json_encode($allowedFormats).' - Файл:'.$file->getClientOriginalName();
                    continue;
                }

                if ($fileSize > $maxSize) {
                    $errors[] = 'Размер файла превышает лимит - Размер файла:'. $fileSize .' - Максимальный размер:' . $maxSize.' - Файл:'.$file->getClientOriginalName();
                    continue;
                }

                if ($photo_type !== 'videos') {
                    [$width, $height] = getimagesize($file->getPathname());
                    if ($width > $maxWidth || $height > $maxHeight) {
                        $errors[] = 'Стороны изображения превышают допустимые лимиты - Размер:'. $width.'x'.$height .' - Максимальный размер:' . $maxWidth.'x'.$maxHeight.' - Файл:'.$file->getClientOriginalName();
                        continue;
                    }
                }

                $filename = uniqid() . '_' . rand(1, 1000) . '.' . $extension;
                $path = 'images/temp/posts/post_images/' . $photo_type . '/' . $random_id . '/' . $filename;
                $file->move(public_path('images/temp/posts/post_images/' . $photo_type . '/' . $random_id), $filename);
                $uploadedFiles[] = $path;
            }

            if (!empty($uploadedFiles)) {
                return response()->json(['files' => $uploadedFiles, 'errors' => $errors ?: null]);
            } else {
                return response()->json(['error' => $errors ?: 'No valid files uploaded.'], 400);
            }
        }

        return response()->json(['error' => 'No images found.'], 400);
    }

    public function deleteImageToPath(Request $request): JsonResponse
    {
        $image_path = $request->input('image_path');
        $full_path = public_path($image_path);

        if (File::exists($full_path)) {
            File::delete($full_path);
            return response()->json(['success' => 'Файл удален']);
        }

        return response()->json(['error' => 'Файл не найден']);
    }
}
