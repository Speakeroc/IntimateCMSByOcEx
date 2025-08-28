<?php

namespace App\Http\Controllers\admin\system;

use App\Http\Controllers\Controller;
use App\Models\Info\Information;
use App\Models\system\Getters;
use App\Models\system\Settings;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class settingsController extends Controller
{
    private Getters $getters;

    public function __construct()
    {
        $this->getters = new Getters;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/app_settings')) return $redirect;
        SEOMeta::setTitle(__('admin/page_titles.settings'));
        $data['postMaxSize'] = __('admin/system/settings.postMaxSize', ['size' => ini_get('post_max_size')]);

        $data['currency_symbol'] = $this->getters->getCurrencySymbol();
        $data['app_url'] = config('app.url');

        $data_settings = [
            'meta_title' => 'txt',
            'meta_h1' => 'txt',
            'meta_description' => 'txt',
            'support_email' => 'txt',
            'sitemap_url' => 'txt',
            'default_city_id' => 'int',
            'currency_symbol_right' => 'txt',
            'currency_symbol_left' => 'txt',
            'currency_symbol_code' => 'txt',

            //Post Settings
            'post_block_city_status' => 'int',
            'post_block_zone_status' => 'int',
            'post_block_date_status' => 'int',
            'post_main_photo' => 'array',
            'post_photo' => 'array',
            'post_selfie' => 'array',
            'post_video' => 'array',
            'post_verify' => 'array',

            //Salon Settings
            'salon_main_photo' => 'array',
            'salon_photo' => 'array',

            'post_verify_text' => 'txt_convert',
            'post_activation_status' => 'int',
            'post_publish_variable' => 'array',

            //Salon Settings
            'salon_activation_status' => 'int',
            'salon_publish_variable' => 'array',

            //Post Settings
            'image_logo' => 'txt',
            'image_watermark' => 'txt',
            'watermark_status' => 'int',
            'watermark_position' => 'int',
            'post_display_cloth' => 'int',
            'post_display_shoes' => 'int',
            'post_display_zone' => 'int',
            'post_display_metro' => 'int',

            'post_section_individuals_status' => 'int',
            'post_section_premium_status' => 'int',
            'post_section_health_status' => 'int',
            'post_section_elite_status' => 'int',
            'post_section_bdsm_status' => 'int',
            'post_section_masseuse_status' => 'int',

            //Home Settings
            'home_post_banner' => 'array',
            'home_news' => 'array',
            'home_banners' => 'array',

            'home_all' => 'array',
            'home_popular' => 'array',
            'home_individual' => 'array',
            'home_premium' => 'array',
            'home_health' => 'array',
            'home_latest' => 'array',
            'home_elite' => 'array',
            'home_bdsm' => 'array',
            'home_masseuse' => 'array',
            'home_salon' => 'array',

            //Pages
            'page_all' => 'array',
            'page_popular' => 'array',
            'page_individual' => 'array',
            'page_premium' => 'array',
            'page_health' => 'array',
            'page_latest' => 'array',
            'page_elite' => 'array',
            'page_bdsm' => 'array',
            'page_masseuse' => 'array',
            'page_salon' => 'array',
            'page_search' => 'array',
            'page_services' => 'array',
            'page_tags' => 'array',
            'page_city' => 'array',
            'page_zone' => 'array',
            'page_metro' => 'array',
            'page_news' => 'array',

            //Microdata
            'micro_site_name' => 'txt',

            //User
            'auth_email_verify' => 'int',
            'reg_start_balance' => 'int',
            'reg_privacy' => 'int',

            //Pries
            'post_prices' => 'array',
            'salon_prices' => 'array',

            //Social Links
            'social_links' => 'array',

            'custom_js' => 'txt_convert',
            'robots' => 'txt_convert',
            'age_detect' => 'int',

            'subscribe_status' => 'int',
            'subscribe_title' => 'txt',
            'subscribe_text' => 'txt_convert',
            'subscribe_btn_title' => 'txt',
            'subscribe_btn_link' => 'txt',
            'subscribe_btn_color' => 'txt',
            'subscribe_btn_color_t' => 'txt',

            //Footer
            'footer_text' => 'txt_convert',
            'header_display_zone' => 'int',
            'header_display_city' => 'int',
            'header_display_map' => 'int',

            //NewYear Mode
            'new_year_mode' => 'int',
        ];

        $information = Information::where('status', 1)->get();
        $data['information'] = [];
        foreach ($information as $info) {
            $data['information'][] = [
                'id' => $info['id'],
                'title' => $info['title'],
            ];
        }

        $data['main_data'] = $this->getters->getCityData();

        $data['post_section_status'] = [
            'post_section_individuals_status' => __('admin/system/settings.object_p_section_indiv'),
            'post_section_premium_status' => __('admin/system/settings.object_p_section_premium'),
            'post_section_health_status' => __('admin/system/settings.object_p_section_health'),
            'post_section_elite_status' => __('admin/system/settings.object_p_section_elite'),
            'post_section_bdsm_status' => __('admin/system/settings.object_p_section_bdsm'),
            'post_section_masseuse_status' => __('admin/system/settings.object_p_section_masseuse'),
        ];

        $data['home_settings'] = [
            ['key' => 'all', 'template' => 'post_templates'],
            ['key' => 'popular', 'template' => 'post_templates'],
            ['key' => 'individual', 'template' => 'post_templates'],
            ['key' => 'premium', 'template' => 'post_templates'],
            ['key' => 'health', 'template' => 'post_templates'],
            ['key' => 'latest', 'template' => 'post_templates'],
            ['key' => 'elite', 'template' => 'post_templates'],
            ['key' => 'bdsm', 'template' => 'post_templates'],
            ['key' => 'masseuse', 'template' => 'post_templates'],
            ['key' => 'salon', 'template' => 'salon_templates'],
        ];

        $data['page_settings'] = [
            ['key' => 'all', 'watermark' => true, 'template' => 'post_templates'],
            ['key' => 'popular', 'watermark' => true, 'template' => 'post_templates'],
            ['key' => 'individual', 'watermark' => true, 'template' => 'post_templates'],
            ['key' => 'premium', 'watermark' => true, 'template' => 'post_templates'],
            ['key' => 'health', 'watermark' => true, 'template' => 'post_templates'],
            ['key' => 'latest', 'watermark' => true, 'template' => 'post_templates'],
            ['key' => 'elite', 'watermark' => true, 'template' => 'post_templates'],
            ['key' => 'bdsm', 'watermark' => true, 'template' => 'post_templates'],
            ['key' => 'masseuse', 'watermark' => true, 'template' => 'post_templates'],
            ['key' => 'salon', 'watermark' => true, 'template' => 'salon_templates'],
            ['key' => 'search', 'watermark' => true, 'template' => 'post_templates'],
            ['key' => 'services', 'watermark' => true, 'template' => 'post_templates'],
            ['key' => 'tags', 'watermark' => true, 'template' => 'post_templates'],
            ['key' => 'city', 'watermark' => true, 'template' => 'post_templates'],
            ['key' => 'zone', 'watermark' => true, 'template' => 'post_templates'],
            ['key' => 'metro', 'watermark' => true, 'template' => 'post_templates'],
            ['key' => 'news', 'watermark' => false, 'template' => null],
        ];

        $data['post_image_settings'] = [
            'post_main_photo' => [
                'title' => __('admin/system/settings.sections_main_photo'),
                'data' => [
                    'max_width' => ['title' => __('admin/system/settings.photo_max_width').', px', 'type' => 'number'],
                    'max_height' => ['title' => __('admin/system/settings.photo_max_height').', px', 'type' => 'number'],
                    'access_format' => ['title' => __('admin/system/settings.photo_access_format'), 'type' => 'text'],
                    'size' => ['title' => __('admin/system/settings.photo_size'), 'type' => 'number'],
                ],
            ],
            'post_photo' => [
                'title' => __('admin/system/settings.sections_photo'),
                'data' => [
                    'max_width' => ['title' => __('admin/system/settings.photo_max_width').', px', 'type' => 'number'],
                    'max_height' => ['title' => __('admin/system/settings.photo_max_height').', px', 'type' => 'number'],
                    'access_format' => ['title' => __('admin/system/settings.photo_access_format'), 'type' => 'text'],
                    'count' => ['title' => __('admin/system/settings.photo_count'), 'type' => 'number'],
                    'size' => ['title' => __('admin/system/settings.photo_size'), 'type' => 'number'],
                ],
            ],
            'post_selfie' => [
                'title' => __('admin/system/settings.sections_selfie'),
                'data' => [
                    'max_width' => ['title' => __('admin/system/settings.selfie_max_width').', px', 'type' => 'number'],
                    'max_height' => ['title' => __('admin/system/settings.selfie_max_height').', px', 'type' => 'number'],
                    'access_format' => ['title' => __('admin/system/settings.selfie_access_format'), 'type' => 'text'],
                    'count' => ['title' => __('admin/system/settings.selfie_count'), 'type' => 'number'],
                    'size' => ['title' => __('admin/system/settings.selfie_size'), 'type' => 'number'],
                ],
            ],
            'post_video' => [
                'title' => __('admin/system/settings.sections_video'),
                'data' => [
                    'access_format' => ['title' => __('admin/system/settings.video_access_format'), 'type' => 'text'],
                    'count' => ['title' => __('admin/system/settings.video_size'), 'type' => 'number'],
                    'size' => ['title' => __('admin/system/settings.video_count'), 'type' => 'number'],
                ],
            ],
            'post_verify' => [
                'title' => __('admin/system/settings.sections_verify'),
                'data' => [
                    'max_width' => ['title' => __('admin/system/settings.verify_max_width').', px', 'type' => 'number'],
                    'max_height' => ['title' => __('admin/system/settings.verify_max_height').', px', 'type' => 'number'],
                    'access_format' => ['title' => __('admin/system/settings.verify_access_format'), 'type' => 'text'],
                    'size' => ['title' => __('admin/system/settings.verify_size'), 'type' => 'number'],
                ],
            ]
        ];

        $data['salon_image_settings'] = [
            'salon_main_photo' => [
                'title' => __('admin/system/settings.sections_main_photo'),
                'data' => [
                    'max_width' => ['title' => __('admin/system/settings.photo_max_width').', px', 'type' => 'number'],
                    'max_height' => ['title' => __('admin/system/settings.photo_max_height').', px', 'type' => 'number'],
                    'access_format' => ['title' => __('admin/system/settings.photo_access_format'), 'type' => 'text'],
                    'size' => ['title' => __('admin/system/settings.photo_size'), 'type' => 'number'],
                ],
            ],
            'salon_photo' => [
                'title' => __('admin/system/settings.sections_photo'),
                'data' => [
                    'max_width' => ['title' => __('admin/system/settings.photo_max_width').', px', 'type' => 'number'],
                    'max_height' => ['title' => __('admin/system/settings.photo_max_height').', px', 'type' => 'number'],
                    'access_format' => ['title' => __('admin/system/settings.photo_access_format'), 'type' => 'text'],
                    'count' => ['title' => __('admin/system/settings.photo_count'), 'type' => 'number'],
                    'size' => ['title' => __('admin/system/settings.photo_size'), 'type' => 'number'],
                ],
            ],
        ];

        $data['post_prices_settings'] = [
            'diamond' => [
                'title' => __('admin/system/settings.price_diamond'),
                'data' => [
                    'diamond_act' => ['title' => __('admin/system/settings.price_act', ['currency' => $data['currency_symbol']]), 'type' => 'number'],
                    'diamond_ext' => ['title' => __('admin/system/settings.price_ext', ['currency' => $data['currency_symbol']]), 'type' => 'number']
                ]
            ],
            'vip' => [
                'title' => __('admin/system/settings.price_vip'),
                'data' => [
                    'vip_act' => ['title' => __('admin/system/settings.price_act', ['currency' => $data['currency_symbol']]), 'type' => 'number'],
                    'vip_ext' => ['title' => __('admin/system/settings.price_ext', ['currency' => $data['currency_symbol']]), 'type' => 'number']
                ]
            ],
            'color' => [
                'title' => __('admin/system/settings.price_color'),
                'data' => [
                    'color_act' => ['title' => __('admin/system/settings.price_act', ['currency' => $data['currency_symbol']]), 'type' => 'number'],
                    'color_ext' => ['title' => __('admin/system/settings.price_ext', ['currency' => $data['currency_symbol']]), 'type' => 'number'],
                ]
            ],
            'up' => [
                'title' => __('admin/system/settings.price_up_to_top'),
                'data' => [
                    'up_to_top' => ['title' => __('admin/system/settings.price_up_to_top').' '.$data['currency_symbol'], 'type' => 'number'],
                ]
            ],
        ];

        $data['salon_prices_settings'] = [
            'up' => [
                'title' => __('admin/system/settings.price_salon_up_to_top'),
                'data' => [
                    'up_to_top' => ['title' => __('admin/system/settings.price_salon_up_to_top').' '.$data['currency_symbol'], 'type' => 'number'],
                ]
            ],
        ];

        $data['social_vars'] = [
            'telegram' => 'Telegram',
            'whatsapp' => 'WhatsApp',
            'youtube' => 'Youtube',
            'facebook' => 'Facebook',
            'instagram' => 'Instagram',
            'pinterest' => 'Pinterest',
            'linkedin' => 'LinkedIn',
            'snapchat' => 'Snapchat',
            'x' => 'X (Twitter)',
            'tiktok' => 'TikTok',
            'wechat' => 'WeChat',
        ];

        foreach ($data_settings as $key => $value) {
            if ($value == 'txt') {
                $data[$key] = Settings::where('code', 'setting')->where('key', $key)->value('value') ?? "";
            }
            if ($value == 'array') {
                $value = Settings::where('code', 'setting')->where('key', $key)->value('value');
                if (is_string($value) && !empty($value)) {
                    $data[$key] = json_decode($value, true) ?? [];
                } else {
                    $data[$key] = [];
                }
            }
            if ($value == 'int') {
                $data[$key] = Settings::where('code', 'setting')->where('key', $key)->value('value') ?? 0;
            }
            if ($value == 'txt_convert') {
                $get_text_data = Settings::where('code', 'setting')->where('key', $key)->value('value') ?? null;
                $data[$key] = $this->getters->reverseTextData($get_text_data);
            }
        }

        $data['post_templates'] = app('post_template');

        $data['salon_templates'] = app('salon_template');

        $data['elements'] = $this->getters->getAdminHeaderFooter();

        return view('admin/system/settings', ['data' => $data]);
    }

    public function saveSetting(Request $request): RedirectResponse
    {
        if ($redirect = $this->getters->getAdminAccess(type: 'access', key: 'view/admin_panel', route: 'client')) return $redirect;
        if ($redirect = $this->getters->getAdminAccess(type: 'modify', key: 'edit/app_settings_save')) return $redirect;
        //Validation
        $this->validate($request, [
            'meta_title' => 'required|min:3',
            'meta_h1' => 'required|min:3',
            'support_email' => 'email|required',
            'currency_symbol_code' => 'required',

            'post_block_city_status' => 'required|integer',
            'post_block_zone_status' => 'required|integer',
            'post_block_date_status' => 'required|integer',
            'post_main_photo.max_width' => 'required|integer',
            'post_main_photo.max_height' => 'required|integer',
            'post_main_photo.access_format' => 'required',
            'post_main_photo.size' => 'required|integer',

            'post_photo.max_width' => 'required|integer',
            'post_photo.max_height' => 'required|integer',
            'post_photo.access_format' => 'required',
            'post_photo.count' => 'required|integer',
            'post_photo.size' => 'required|integer',

            'salon_main_photo.max_width' => 'required|integer',
            'salon_main_photo.max_height' => 'required|integer',
            'salon_main_photo.access_format' => 'required',
            'salon_main_photo.size' => 'required|integer',

            'salon_photo.max_width' => 'required|integer',
            'salon_photo.max_height' => 'required|integer',
            'salon_photo.access_format' => 'required',
            'salon_photo.count' => 'required|integer',
            'salon_photo.size' => 'required|integer',

            'post_selfie.max_width' => 'required|integer',
            'post_selfie.max_height' => 'required|integer',
            'post_selfie.access_format' => 'required',
            'post_selfie.count' => 'required|integer',
            'post_selfie.size' => 'required|integer',

            'post_verify.max_width' => 'required|integer',
            'post_verify.max_height' => 'required|integer',
            'post_verify.access_format' => 'required',
            'post_verify.size' => 'required|integer',

            'auth_email_verify' => 'required|integer',
            'reg_start_balance' => 'required|integer',
            'reg_privacy' => 'required|integer',

            'image_logo' => 'required',
            'post_video.access_format' => 'required',
            'post_video.size' => 'required|integer',
            'post_video.count' => 'required|integer',
            'post_verify_text' => 'required',
            'post_activation_status' => 'required|integer',

            'salon_activation_status' => 'required|integer',

            'post_prices.diamond_act' => 'required|integer',
            'post_prices.diamond_ext' => 'required|integer',
            'post_prices.vip_act' => 'required|integer',
            'post_prices.vip_ext' => 'required|integer',
            'post_prices.color_act' => 'required|integer',
            'post_prices.color_ext' => 'required|integer',
            'post_prices.up_to_top' => 'required|integer',

            'salon_prices.up_to_top' => 'required|integer',

            'header_display_zone' => 'required|integer',
            'header_display_city' => 'required|integer',
            'header_display_map' => 'required|integer',
        ]);
        //Validation

        $data_settings = [
            'meta_title',
            'meta_h1',
            'meta_description',
            'support_email',
            'sitemap_url',
            'default_city_id',
            'currency_symbol_right',
            'currency_symbol_left',
            'currency_symbol_code',
            'post_block_city_status',
            'post_block_zone_status',
            'post_block_date_status',
            'post_main_photo',
            'post_photo',
            'post_selfie',
            'post_video',
            'post_verify',
            'post_activation_status',
            'watermark_status',
            'watermark_position',
            'post_display_cloth',
            'post_display_shoes',
            'post_display_zone',
            'post_display_metro',

            'post_section_individuals_status',
            'post_section_premium_status',
            'post_section_health_status',
            'post_section_elite_status',
            'post_section_bdsm_status',
            'post_section_masseuse_status',

            'salon_activation_status',

            'salon_main_photo',
            'salon_photo',

            //Home
            'home_post_banner',
            'home_news',
            'home_banners',

            'home_all',
            'home_popular',
            'home_individual',
            'home_premium',
            'home_health',
            'home_latest',
            'home_elite',
            'home_bdsm',
            'home_masseuse',
            'home_salon',

            //Pages
            'page_all',
            'page_popular',
            'page_individual',
            'page_premium',
            'page_health',
            'page_latest',
            'page_elite',
            'page_bdsm',
            'page_masseuse',
            'page_salon',
            'page_search',
            'page_services',
            'page_tags',
            'page_city',
            'page_zone',
            'page_metro',
            'page_news',

            //Microdata
            'micro_site_name',

            //User
            'auth_email_verify',
            'reg_start_balance',
            'reg_privacy',

            //Prices
            'post_prices',
            'salon_prices',
            'social_links',

            //Modal
            'age_detect',
            'subscribe_status',
            'subscribe_title',
            'subscribe_btn_title',
            'subscribe_btn_link',
            'subscribe_btn_color',
            'subscribe_btn_color_t',

            'header_display_zone',
            'header_display_city',
            'header_display_map',

            //NewYear Mode
            'new_year_mode',
        ];
        foreach ($data_settings as $data_setting) {
            if (is_array($request->input($data_setting))) {
                Settings::updateOrCreate(['code' => 'setting', 'key' => $data_setting], ['value' => json_encode($request->input($data_setting))]);
            } else {
                Settings::updateOrCreate(['code' => 'setting', 'key' => $data_setting], ['value' => strval($request->input($data_setting))]);
            }
        }

        //Logo
        $image_logo = $request->input('image_logo');
        if (!empty($image_logo) && str_contains($image_logo, 'temp')) {
            $image_path = $this->getters->moveTempToFolder($image_logo, 'images/settings', 'logo');
            Settings::updateOrCreate(['code' => 'setting', 'key' => 'image_logo'], ['value' => $image_path]);
        }

        //Watermark
        $image_watermark = $request->input('image_watermark');
        if (!empty($image_watermark) && str_contains($image_watermark, 'temp')) {
            $image_path = $this->getters->moveTempToFolder($image_watermark, 'images/settings', 'watermark');
            Settings::updateOrCreate(['code' => 'setting', 'key' => 'image_watermark'], ['value' => $image_path]);
        }

        $robots = $request->input('robots');

        $converter_text_data = [
            'post_verify_text',
            'custom_js',
            'subscribe_text',
            'robots',
            'footer_text',
        ];

        foreach ($converter_text_data as $item) {
            Settings::updateOrCreate(['code' => 'setting', 'key' => $item], ['value' => $this->getters->convertTextData($request->input($item))]);
        }

        Settings::updateOrCreate(['code' => 'setting', 'key' => 'post_publish_variable'], ['value' => json_encode($request->input('post_publish_variable'))]);
        Settings::updateOrCreate(['code' => 'setting', 'key' => 'salon_publish_variable'], ['value' => json_encode($request->input('salon_publish_variable'))]);
        //Saved

        $robotsPath = public_path('robots.txt');

        if ($robots && $robotsPath) {
            File::put($robotsPath, $robots);
        }

        return redirect()->route('admin.settings');
    }
}
