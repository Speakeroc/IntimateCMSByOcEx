<?php

namespace App\Http\Controllers\catalog\Info\news;

use App\Http\Controllers\Controller;
use App\Models\Info\News;
use App\Models\system\NewsRateHistory;
use Illuminate\Http\Request;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class newsPageController extends Controller
{
    private Getters $getters;
    private ImageConverter $imageConverter;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
    }

    public function index($news_id, $title): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        $news = News::where('id', $news_id)->first();
        $current_name = Str::slug($news['title']);

        //Redirect checked
        if (!empty($current_name) && $title != $current_name) return redirect()->route('client.news', ['news_id' => $news_id, 'title' => $current_name]);
        if (empty($current_name)) return redirect()->route('client.errors', ['code' => 404]);

        $data['news_id'] = $news_id;
        $data['title'] = $news['title'];

        $title = $news['meta_title'] ?? $news['title'];
        $description = ($news['meta_description']) ? Str::limit($news['meta_description'], 150) : Str::limit($this->getters->reverseTextData($news['desc']), 150);
        $this->getters->setMetaInfo(title: $title, description: $description, url: route('client.news', ['news_id' => $news_id, 'title' => $current_name]));

        //Content
        if (!empty($news['image']) && File::exists(public_path($news['image']))) {
            $data['image'] = url($this->imageConverter->toMini($news['image']));
        } else {
            $data['image'] = null;
        }

        //Description
        $data['id'] = $news['id'];
        $data['desc'] = $this->getters->reverseTextData($news['desc']);
        $data['views'] = $news['views'];
        $data['like'] = $news['like'];
        $data['dislike'] = $news['dislike'];
        $data['created_at'] = $this->getters->dateText($news['created_at']);

        //Microdata Article
        $data['microdata_article'] = $this->getters->microDataNewsArticle($news_id);

        $breadcrumbs = [
            ['link' => route('client.news.all', ['news_id' => $news_id, 'title' => $current_name]), 'title' => __('catalog/page_titles.post_news')],
            ['link' => route('client.news', ['news_id' => $news_id, 'title' => $current_name]), 'title' => $title],
        ];
        $data['breadcrumb'] = $this->getters->breadcrumbPages($breadcrumbs);
        $data['elements'] = $this->getters->getHeaderFooter();

        return view('catalog/info/news/news', ['data' => $data]);
    }

    public function rateNews(Request $request): JsonResponse
    {
        $news_id = $request->input('news_id');
        $type = $request->input('type');

        $result = [
            'like' => 0,
            'dislike' => 0,
            'active' => false
        ];

        $news_info = News::where('id', $news_id)->first();

        if ($news_info) {
            $result['like'] = (int)$news_info['like'];
            $result['dislike'] = (int)$news_info['dislike'];

            $rate_sign = $this->getters->getSign();

            $review_rate_history = NewsRateHistory::where('news_id', $news_id)->where('sign', $rate_sign)->orderByDesc('id')->first();

            $prev_rate = false;
            if (!empty($review_rate_history)) {
                $prev_rate = $review_rate_history['type'];
            }

            if ($type == 'like') {
                if (!$prev_rate) {
                    $result['like']++;
                } elseif ($prev_rate == 'like') {
                    $result['like']--;
                } elseif ($prev_rate == 'dislike') {
                    $result['dislike']--;
                    $result['like']++;
                }
            } elseif ($type == 'dislike') {
                if (!$prev_rate) {
                    $result['dislike']++;
                } elseif ($prev_rate == 'dislike') {
                    $result['dislike']--;
                } elseif ($prev_rate == 'like') {
                    $result['dislike']++;
                    $result['like']--;
                }
            }

            if ($result['like'] <= 0) {
                $result['like'] = 0;
            }

            if ($result['dislike'] <= 0) {
                $result['dislike'] = 0;
            }

            if ($prev_rate != $type) {
                $result['active'] = $type;
            }

            NewsRateHistory::create(['news_id' => $news_id, 'type' => $result['active'], 'sign' => $rate_sign]);
            News::where('id', $news_id)->update([
                'like' => $result['like'],
                'dislike' => $result['dislike'],
            ]);
        }

        if (!empty($news_id) && !empty($type)) {
            return response()->json(['status' => 'success', 'message' => 'Установлен рейтинг', 'like' => $result['like'], 'dislike' => $result['dislike']]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Не установлен рейтинг']);
        }
    }
}
