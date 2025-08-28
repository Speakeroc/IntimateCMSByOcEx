@extends('admin/layout/layout')
@section('header', $data['elements']['header'])
@section('sidebar', $data['elements']['sidebar'])
@section('css_js_header')
@endsection
@section('content')
    <main id="main-container">
        <div class="bg-body-light">
            <div class="content content-full">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                    <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">{{ __('admin/page_titles.settings') }}</h1>
                </div>
            </div>
        </div>

        <div class="content">
            <form action="{{ route('admin.settings') }}" method="post" id="form-save" enctype="multipart/form-data">
                @csrf
                <div class="block block-rounded">
                    <ul class="nav nav-tabs nav-tabs-block align-items-center" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link px-2 active" data-bs-toggle="tab" href="#settings-main" role="tab" aria-controls="settings-main" aria-selected="true">{{ __('admin/system/settings.tab_main') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-2" data-bs-toggle="tab" href="#settings-post" role="tab" aria-controls="settings-post" aria-selected="false">{{ __('admin/system/settings.tab_post') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-2" data-bs-toggle="tab" href="#settings-salon" role="tab" aria-controls="settings-salon" aria-selected="false">{{ __('admin/system/settings.tab_salon') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-2" data-bs-toggle="tab" href="#settings-images" role="tab" aria-controls="settings-images" aria-selected="false">{{ __('admin/system/settings.tab_images') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-2" data-bs-toggle="tab" href="#settings-home" role="tab" aria-controls="settings-home" aria-selected="false">{{ __('admin/system/settings.tab_home') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-2" data-bs-toggle="tab" href="#settings-pages" role="tab" aria-controls="settings-pages" aria-selected="false">{{ __('admin/system/settings.tab_pages') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-2" data-bs-toggle="tab" href="#settings-user" role="tab" aria-controls="settings-user" aria-selected="false">{{ __('admin/system/settings.tab_user') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-2" data-bs-toggle="tab" href="#settings-prices" role="tab" aria-controls="settings-prices" aria-selected="false">{{ __('admin/system/settings.tab_prices') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-2" data-bs-toggle="tab" href="#settings-microdata" role="tab" aria-controls="settings-microdata" aria-selected="false">Microdata</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-2" data-bs-toggle="tab" href="#settings-js-script" role="tab" aria-controls="settings-js-script" aria-selected="false">{{ __('admin/system/settings.tab_js') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-2" data-bs-toggle="tab" href="#settings-robots" role="tab" aria-controls="settings-robots" aria-selected="false">Robots</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-2" data-bs-toggle="tab" href="#settings-modal" role="tab" aria-controls="settings-modal" aria-selected="false">{{ __('admin/system/settings.tab_modal') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-2" data-bs-toggle="tab" href="#settings-header" role="tab" aria-controls="settings-header" aria-selected="false">{{ __('admin/system/settings.tab_header') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-2" data-bs-toggle="tab" href="#settings-footer" role="tab" aria-controls="settings-footer" aria-selected="false">{{ __('admin/system/settings.tab_footer') }}</a>
                        </li>
                        <li class="nav-item">

                            <a class="nav-link px-2" data-bs-toggle="tab" href="#settings-new-year-mode" role="tab" aria-controls="settings-new-year-mode" aria-selected="false">NewYearMode</a>
                        </li>
                        <li class="nav-item ms-auto">
                            <div class="btn-group btn-group-sm pe-2">
                                <button type="submit" form="form-save" id="form-save-button" class="btn btn-primary">{{ __('buttons.save') }}</button>
                            </div>
                        </li>
                    </ul>
                    <div class="block-content tab-content">
                        <div class="tab-pane active" id="settings-main" role="tabpanel">
                            <h2 class="content-heading pt-0 mb-3">{{ __('admin/system/settings.heading_main') }}</h2>

                            <div class="block block-rounded">
                                <div class="mb-4">
                                    <label class="form-label" for="meta_title">{{ __('admin/system/settings.meta_title') }}</label>
                                    <input type="text" name="meta_title" value="{{ old('meta_title') ?? ($data['meta_title'] ?? '') }}" class="form-control" id="meta_title" placeholder="{{ __('admin/system/settings.meta_title') }}">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="meta_h1">{{ __('admin/system/settings.meta_h1') }}</label>
                                    <input type="text" name="meta_h1" value="{{ old('meta_h1') ?? ($data['meta_h1'] ?? '') }}" class="form-control" id="meta_h1" placeholder="{{ __('admin/system/settings.meta_h1') }}">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="meta_description">{{ __('admin/system/settings.meta_description') }}</label>
                                    <textarea name="meta_description" id="meta_description" rows="3" class="form-control" placeholder="{{ __('admin/system/settings.meta_description_p') }}">{{ old('meta_description') ?? ($data['meta_description'] ?? '') }}</textarea>
                                </div>

                                <h2 class="content-heading pt-0">{{ __('admin/system/settings.heading_contact') }}</h2>

                                <div class="mb-4">
                                    <label class="form-label" for="support_email">{{ __('admin/system/settings.support_email') }} </label>
                                    <input type="text" name="support_email" value="{{ old('support_email') ?? ($data['support_email'] ?? '') }}" class="form-control" id="support_email" placeholder="{{ __('admin/system/settings.support_email_p') }}">
                                </div>

                                <div class="mb-4">
                                    <label class="form-label" for="sitemap_url">{{ __('admin/system/settings.sitemap_url') . $data['app_url'] }}</label>
                                    <input type="text" name="sitemap_url" value="{{ old('sitemap_url') ?? ($data['sitemap_url'] ?? '') }}" class="form-control" id="sitemap_url" placeholder="{{ __('admin/system/settings.sitemap_url_p') }}">
                                </div>

                                <div class="mb-4">
                                    <label class="form-label" for="default_city_id">{{ __('admin/system/settings.default_city_id') }}</label>
                                    @php $default_city_id = old('default_city_id') ?? ($data['default_city_id'] ?? null); @endphp
                                    <select class="form-select" id="select-default-city-id" name="default_city_id">
                                        @foreach($data['main_data']['city'] as $city)
                                        <option value="{{ $city['id'] }}" @if($default_city_id == $city['id']) selected @endif>{{ $city['title'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <h2 class="content-heading pt-0">{{ __('admin/system/settings.heading_currency') }}</h2>

                                <div class="row">
                                    <div class="col-sm-4 col-12">
                                        <div class="mb-4">
                                            <label class="form-label" for="currency_symbol_right">{{ __('admin/system/settings.currency_symbol_right') }}</label>
                                            <input type="text" name="currency_symbol_right" value="{{ old('currency_symbol_right') ?? ($data['currency_symbol_right'] ?? '') }}" class="form-control" id="currency_symbol_right" placeholder="{{ __('admin/system/settings.currency_symbol_right_p') }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-12">
                                        <div class="mb-4">
                                            <label class="form-label" for="currency_symbol_left">{{ __('admin/system/settings.currency_symbol_left') }}</label>
                                            <input type="text" name="currency_symbol_left" value="{{ old('currency_symbol_left') ?? ($data['currency_symbol_left'] ?? '') }}" class="form-control" id="currency_symbol_left" placeholder="{{ __('admin/system/settings.currency_symbol_left_p') }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-12">
                                        <div class="mb-4">
                                            <label class="form-label" for="currency_symbol_code">{{ __('admin/system/settings.currency_symbol_code') }}</label>
                                            <input type="text" name="currency_symbol_code" value="{{ old('currency_symbol_code') ?? ($data['currency_symbol_code'] ?? '') }}" class="form-control" id="currency_symbol_code" placeholder="{{ __('admin/system/settings.currency_symbol_code_p') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="settings-post" role="tabpanel">
                            <h2 class="content-heading pt-0 mb-3">{{ __('admin/system/settings.sections_post') }}</h2>
                            <div class="row">
                                <div class="col-12 col-md-3 col-xl-3">
                                    <div class="mb-2">
                                        <label class="form-label fs-14 d-block" for="input-post-display-cloth">{{ __('admin/system/settings.object_p_display_cloth') }}</label>
                                        @php $post_display_cloth = old('post_display_cloth') ?? $data['post_display_cloth']; @endphp
                                        <div class="btn-group bg-white" role="group">
                                            <input type="radio" class="btn-check" name="post_display_cloth" value="1" id="post_display_cloth-1" {{ ($post_display_cloth == 1) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary" for="post_display_cloth-1">{{ __('lang.status_on') }}</label>
                                            <input type="radio" class="btn-check" name="post_display_cloth" value="0" id="post_display_cloth-0" {{ ($post_display_cloth == 0) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-danger" for="post_display_cloth-0">{{ __('lang.status_off') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 col-xl-3">
                                    <div class="mb-2">
                                        <label class="form-label fs-14 d-block" for="input-post-display-shoes">{{ __('admin/system/settings.object_p_display_shoes') }}</label>
                                        @php $post_display_shoes = old('post_display_shoes') ?? $data['post_display_shoes']; @endphp
                                        <div class="btn-group bg-white" role="group">
                                            <input type="radio" class="btn-check" name="post_display_shoes" value="1" id="post_display_shoes-1" {{ ($post_display_shoes == 1) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary" for="post_display_shoes-1">{{ __('lang.status_on') }}</label>
                                            <input type="radio" class="btn-check" name="post_display_shoes" value="0" id="post_display_shoes-0" {{ ($post_display_shoes == 0) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-danger" for="post_display_shoes-0">{{ __('lang.status_off') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 col-xl-3">
                                    <div class="mb-2">
                                        <label class="form-label fs-14 d-block" for="input-post-display-zone">{{ __('admin/system/settings.object_p_display_zone') }}</label>
                                        @php $post_display_zone = old('post_display_zone') ?? $data['post_display_zone']; @endphp
                                        <div class="btn-group bg-white" role="group">
                                            <input type="radio" class="btn-check" name="post_display_zone" value="1" id="post_display_zone-1" {{ ($post_display_zone == 1) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary" for="post_display_zone-1">{{ __('lang.status_on') }}</label>
                                            <input type="radio" class="btn-check" name="post_display_zone" value="0" id="post_display_zone-0" {{ ($post_display_zone == 0) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-danger" for="post_display_zone-0">{{ __('lang.status_off') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 col-xl-3">
                                    <div class="mb-2">
                                        <label class="form-label fs-14 d-block" for="input-post-display-metro">{{ __('admin/system/settings.object_p_display_metro') }}</label>
                                        @php $post_display_metro = old('post_display_metro') ?? $data['post_display_metro']; @endphp
                                        <div class="btn-group bg-white" role="group">
                                            <input type="radio" class="btn-check" name="post_display_metro" value="1" id="post_display_metro-1" {{ ($post_display_metro == 1) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary" for="post_display_metro-1">{{ __('lang.status_on') }}</label>
                                            <input type="radio" class="btn-check" name="post_display_metro" value="0" id="post_display_metro-0" {{ ($post_display_metro == 0) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-danger" for="post_display_metro-0">{{ __('lang.status_off') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h2 class="content-heading pt-0 mb-3">{{ __('admin/system/settings.object_p_section_title') }}</h2>
                            <div class="row">
                                @foreach($data['post_section_status'] as $key => $value)
                                    <div class="col-12 col-md-3 col-xl-3">
                                        <div class="mb-2">
                                            <label class="form-label fs-14 d-block" for="input-post-publish-status">{{ $value }}</label>
                                            @php $post_section_status = old($key) ?? $data[$key]; @endphp
                                            <div class="btn-group bg-white" role="group">
                                                <input type="radio" class="btn-check" name="{{ $key }}" value="1" id="{{ $key }}-1" {{ ($post_section_status == 1) ? 'checked' : '' }}>
                                                <label class="btn btn-outline-primary" for="{{ $key }}-1">{{ __('lang.status_on') }}</label>
                                                <input type="radio" class="btn-check" name="{{ $key }}" value="0" id="{{ $key }}-0" {{ ($post_section_status == 0) ? 'checked' : '' }}>
                                                <label class="btn btn-outline-danger" for="{{ $key }}-0">{{ __('lang.status_off') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <h2 class="content-heading pt-0 mb-3">{{ __('admin/system/settings.sections_post_block') }}</h2>
                            <div class="row">
                                <div class="col-12 col-md-3 col-xl-3">
                                    <div class="mb-2">
                                        <label class="form-label fs-14 d-block" for="input-post-publish-status">{{ __('admin/system/settings.post_block_city') }}</label>
                                        @php $post_block_city_status = old('post_block_city_status') ?? $data['post_block_city_status']; @endphp
                                        <div class="btn-group bg-white" role="group">
                                            <input type="radio" class="btn-check" name="post_block_city_status" value="1" id="post-block-city-status-1" {{ ($post_block_city_status == 1) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary" for="post-block-city-status-1">{{ __('lang.status_on') }}</label>
                                            <input type="radio" class="btn-check" name="post_block_city_status" value="0" id="post-block-city-status-0" {{ ($post_block_city_status == 0) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-danger" for="post-block-city-status-0">{{ __('lang.status_off') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 col-xl-3">
                                    <div class="mb-2">
                                        <label class="form-label fs-14 d-block" for="input-post-publish-status">{{ __('admin/system/settings.post_block_zone') }}</label>
                                        @php $post_block_zone_status = old('post_block_zone_status') ?? $data['post_block_zone_status']; @endphp
                                        <div class="btn-group bg-white" role="group">
                                            <input type="radio" class="btn-check" name="post_block_zone_status" value="1" id="post-block-zone-status-1" {{ ($post_block_zone_status == 1) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary" for="post-block-zone-status-1">{{ __('lang.status_on') }}</label>
                                            <input type="radio" class="btn-check" name="post_block_zone_status" value="0" id="post-block-zone-status-0" {{ ($post_block_zone_status == 0) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-danger" for="post-block-zone-status-0">{{ __('lang.status_off') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 col-xl-3">
                                    <div class="mb-2">
                                        <label class="form-label fs-14 d-block" for="input-post-block-date-status">{{ __('admin/system/settings.post_block_date') }}</label>
                                        @php $post_block_date_status = old('post_block_date_status') ?? $data['post_block_date_status']; @endphp
                                        <div class="btn-group bg-white" role="group">
                                            <input type="radio" class="btn-check" name="post_block_date_status" value="1" id="post-block-date-status-1" {{ ($post_block_date_status == 1) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary" for="post-block-date-status-1">{{ __('lang.status_on') }}</label>
                                            <input type="radio" class="btn-check" name="post_block_date_status" value="0" id="post-block-date-status-0" {{ ($post_block_date_status == 0) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-danger" for="post-block-date-status-0">{{ __('lang.status_off') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h2 class="content-heading pt-0 mb-3">{{ __('admin/system/settings.sections_activation') }}</h2>
                            <p class="m-0 text-gray-dark fs-14">{{ __('admin/system/settings.sections_activation_t') }}</p>
                            <div class="row">
                                <div class="col-sm-3 col-12">
                                    <div class="mb-2">
                                        <label class="form-label fs-14 d-block" for="input-post-publish-status">{{ __('admin/system/settings.status') }}</label>
                                        @php $post_activation_status = old('post_activation_status') ?? $data['post_activation_status']; @endphp
                                        <div class="btn-group bg-white" role="group">
                                            <input type="radio" class="btn-check" name="post_activation_status" value="1" id="post-activation-status-1" {{ ($post_activation_status == 1) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary" for="post-activation-status-1">{{ __('lang.status_on') }}</label>
                                            <input type="radio" class="btn-check" name="post_activation_status" value="0" id="post-activation-status-0" {{ ($post_activation_status == 0) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-danger" for="post-activation-status-0">{{ __('lang.status_off') }}</label>
                                        </div>

                                        <script>
                                            function togglePublishVariables() {
                                                const isActive = document.querySelector('input[name="post_activation_status"]:checked').value;
                                                const publishVariables = document.getElementById('publish_variables');
                                                if (isActive === '1') {
                                                    publishVariables.style.display = 'block';
                                                } else {
                                                    publishVariables.style.display = 'none';
                                                }
                                            }

                                            document.addEventListener('DOMContentLoaded', () => {
                                                togglePublishVariables();
                                                document.querySelectorAll('input[name="post_activation_status"]').forEach(radio => {
                                                    radio.addEventListener('change', togglePublishVariables);
                                                });
                                            });
                                        </script>
                                    </div>
                                </div>
                                <div id="publish_variables" class="col-12">
                                    <hr>
                                    <h2 class="content-heading pt-0 mb-3">{{ __('admin/system/settings.activation_variable') }}</h2>
                                    <table id="post-activation-variable-table" class="table table-vcenter">
                                        <thead>
                                        <tr>
                                            <th>{{ __('admin/system/settings.activation_var_days') }}</th>
                                            <th>{{ __('admin/system/settings.activation_var_price') }}</th>
                                            <th class="text-center"><i class="fa-solid fa-solar-panel"></i></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $var_row = 1; @endphp
                                        @foreach($data['post_publish_variable'] as $act_varialbe)
                                            <tr>
                                                <td class="fw-semibold"><input type="number" class="form-control" name="post_publish_variable[{{ $var_row }}][days]" value="{{ $act_varialbe['days'] }}" placeholder="{{ __('admin/system/settings.activation_var_days') }}"></td>
                                                <td class="d-none d-sm-table-cell"><input type="number" class="form-control" name="post_publish_variable[{{ $var_row }}][price]" value="{{ $act_varialbe['price'] }}" placeholder="{{ $data['currency_symbol'] }}"></td>
                                                <td class="text-center"><button type="button" onclick="$(this).parent().parent().remove()" class="btn btn-sm btn-alt-danger"><i class="fa fa-times"></i></button></td>
                                            </tr>
                                            @php $var_row = $var_row + 1; @endphp
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td class="text-center">
                                                <button type="button" onclick="addPostVariable();" class="btn btn-sm btn-alt-primary"><i class="fa fa-plus"></i></button>
                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            @foreach($data['post_image_settings'] as $image_item => $image_data)
                                <h2 class="content-heading pt-0 mb-3">{{ $image_data['title'] }}</h2>
                                <div class="row">
                                    @foreach($image_data['data'] as $data_item => $data_item_setting)
                                        <div class="col-sm-3 col-12">
                                            <div class="mb-2">
                                                <label class="form-label fs-14" for="input-{{ $image_item }}-{{ $data_item }}">{{ $data_item_setting['title'] }}</label>
                                                <input type="{{ $data_item_setting['type'] }}" class="form-control" id="input-{{ $image_item }}-{{ $data_item }}" name="{{ $image_item }}[{{ $data_item }}]" value="{{ old($image_item.'.'.$data_item) ?? ($data[$image_item][$data_item] ?? null) }}" placeholder="{{ $data_item_setting['title'] }}">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach

                            <div class="mb-2">
                                @php $post_verify_text = old('post_verify_text') ?? ($data['post_verify_text'] ?? null); @endphp
                                <label class="form-label fs-14" for="post_verify_text">{!! __('admin/system/settings.verify_text') !!}</label>
                                <textarea name="post_verify_text" class="post_verify_text">{{ $post_verify_text }}</textarea>
                            </div>
                            <div class="mb-2">
                                <label class="form-label fs-14" for="input-sections-watermark-status">{{ __('admin/system/settings.sections_watermark_status') }}</label>
                                @php $watermark_status = old('watermark_status') ?? $data['watermark_status']; @endphp
                                <select class="form-select" id="select-sections-watermark-status" name="watermark_status">
                                    <option value="1" @if($watermark_status == 1) selected @endif>{{ __('lang.status_on') }}</option>
                                    <option value="0" @if($watermark_status == 0) selected @endif>{{ __('lang.status_off') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="tab-pane" id="settings-salon" role="tabpanel">
                            <h2 class="content-heading pt-0 mb-3">{{ __('admin/system/settings.sections_s_activation') }}</h2>
                            <p class="m-0 text-gray-dark fs-14">{{ __('admin/system/settings.sections_s_activation_t') }}</p>
                            <div class="row">
                                <div class="col-sm-3 col-12">
                                    <div class="mb-2">
                                        <label class="form-label fs-14 d-block" for="input-salon-publish-status">{{ __('admin/system/settings.status') }}</label>
                                        @php $salon_activation_status = old('salon_activation_status') ?? $data['salon_activation_status']; @endphp
                                        <div class="btn-group bg-white" role="group">
                                            <input type="radio" class="btn-check" name="salon_activation_status" value="1" id="salon-activation-status-1" {{ ($salon_activation_status == 1) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary" for="salon-activation-status-1">{{ __('lang.status_on') }}</label>
                                            <input type="radio" class="btn-check" name="salon_activation_status" value="0" id="salon-activation-status-0" {{ ($salon_activation_status == 0) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-danger" for="salon-activation-status-0">{{ __('lang.status_off') }}</label>
                                        </div>

                                        <script>
                                            function toggleSalonPublishVariables() {
                                                const isActive = document.querySelector('input[name="salon_activation_status"]:checked').value;
                                                const publishVariables = document.getElementById('salon_publish_variables');
                                                if (isActive === '1') {
                                                    publishVariables.style.display = 'block';
                                                } else {
                                                    publishVariables.style.display = 'none';
                                                }
                                            }

                                            document.addEventListener('DOMContentLoaded', () => {
                                                toggleSalonPublishVariables();
                                                document.querySelectorAll('input[name="salon_activation_status"]').forEach(radio => {
                                                    radio.addEventListener('change', toggleSalonPublishVariables);
                                                });
                                            });
                                        </script>
                                    </div>
                                </div>
                                <div id="salon_publish_variables" class="col-12">
                                    <hr>
                                    <h2 class="content-heading pt-0 mb-3">{{ __('admin/system/settings.activation_variable') }}</h2>
                                    <table id="salon-activation-variable-table" class="table table-vcenter">
                                        <thead>
                                        <tr>
                                            <th>{{ __('admin/system/settings.activation_var_days') }}</th>
                                            <th>{{ __('admin/system/settings.activation_var_price') }}</th>
                                            <th class="text-center"><i class="fa-solid fa-solar-panel"></i></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $salon_var_row = 1; @endphp
                                        @foreach($data['salon_publish_variable'] as $salon_act_varialbe)
                                            <tr>
                                                <td class="fw-semibold"><input type="number" class="form-control" name="salon_publish_variable[{{ $salon_var_row }}][days]" value="{{ $salon_act_varialbe['days'] }}" placeholder="{{ __('admin/system/settings.activation_var_days') }}"></td>
                                                <td class="d-none d-sm-table-cell"><input type="number" class="form-control" name="salon_publish_variable[{{ $salon_var_row }}][price]" value="{{ $salon_act_varialbe['price'] }}" placeholder="{{ $data['currency_symbol'] }}"></td>
                                                <td class="text-center"><button type="button" onclick="$(this).parent().parent().remove()" class="btn btn-sm btn-alt-danger"><i class="fa fa-times"></i></button></td>
                                            </tr>
                                            @php $salon_var_row = $salon_var_row + 1; @endphp
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td class="text-center">
                                                <button type="button" onclick="addSalonVariable();" class="btn btn-sm btn-alt-primary"><i class="fa fa-plus"></i></button>
                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            @foreach($data['salon_image_settings'] as $image_item => $image_data)
                                <h2 class="content-heading pt-0 mb-3">{{ $image_data['title'] }}</h2>
                                <div class="row">
                                    @foreach($image_data['data'] as $data_item => $data_item_setting)
                                        <div class="col-sm-3 col-12">
                                            <div class="mb-2">
                                                <label class="form-label fs-14" for="input-{{ $image_item }}-{{ $data_item }}">{{ $data_item_setting['title'] }}</label>
                                                <input type="{{ $data_item_setting['type'] }}" class="form-control" id="input-{{ $image_item }}-{{ $data_item }}" name="{{ $image_item }}[{{ $data_item }}]" value="{{ old($image_item.'.'.$data_item) ?? ($data[$image_item][$data_item] ?? null) }}" placeholder="{{ $data_item_setting['title'] }}">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                        <div class="tab-pane" id="settings-images" role="tabpanel">
                            <div class="row">
                                <div class="col-sm-3 col-12">
                                    <h2 class="content-heading pt-0 mb-3">{{ __('admin/system/settings.sections_logo') }}</h2>
                                    <div class="d-flex justify-content-center position-relative mb-2">
                                        <div id="upload-container-logo" class="setting-upload-container" style="display: {{ $data['image_logo'] ? 'none' : 'flex' }}">
                                            <label for="image-upload-logo" class="setting-upload-placeholder"><i class="fas fa-upload"></i> {{ __('buttons.upload_image') }}</label>
                                            <svg class="ex_upload_preview" style="width:50%;height:50%;"><use xlink:href="#icon-preview-image"></use></svg>
                                            <input type="file" id="image-upload-logo" name="image_logo" accept=".png,.jpeg,.jpg" style="display:none;" onchange="handleUploadImage(event, 'logo')">
                                        </div>
                                        <div id="preview-container-logo" class="setting-preview-container" style="display: {{ $data['image_logo'] ? 'flex' : 'none' }};">
                                            <img id="preview-image-logo" src="{{ $data['image_logo'] }}" alt="Preview" class="setting-preview-img"/>
                                            <button type="button" class="setting-delete-preview" onclick="deletePreviewImage('logo')"><i class="fa fa-trash"></i> {{ __('buttons.delete') }}</button>
                                        </div>
                                        <div class="preload-container-logo">
                                            <svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="40px" height="40px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                                                <path d="M25.251,6.461c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615V6.461z"><animateTransform attributeType="xml" attributeName="transform" type="rotate" from="0 25 25" to="360 25 25" dur="0.6s" repeatCount="indefinite"/></path>
                                            </svg>
                                        </div>
                                        <input type="hidden" name="image_logo" id="image-path-logo" value="{{ $data['image_logo'] }}">
                                    </div>
                                </div>
                                <div class="col-sm-3 col-12">
                                    <h2 class="content-heading pt-0 mb-3">{{ __('admin/system/settings.sections_watermark') }}</h2>
                                    <div class="d-flex justify-content-center position-relative mb-2">
                                        <div id="upload-container-watermark" class="setting-upload-container" style="display: {{ $data['image_watermark'] ? 'none' : 'flex' }}">
                                            <label for="image-upload-watermark" class="setting-upload-placeholder"><i class="fas fa-upload"></i> {{ __('buttons.upload_image') }}</label>
                                            <svg class="ex_upload_preview" style="width:50%;height:50%;"><use xlink:href="#icon-preview-image"></use></svg>
                                            <input type="file" id="image-upload-watermark" name="image_watermark" accept=".png,.jpeg,.jpg" style="display:none;" onchange="handleUploadImage(event, 'watermark')">
                                        </div>
                                        <div id="preview-container-watermark" class="setting-preview-container" style="display: {{ $data['image_watermark'] ? 'flex' : 'none' }};">
                                            <img id="preview-image-watermark" src="{{ $data['image_watermark'] }}" alt="Preview" class="setting-preview-img"/>
                                            <button type="button" class="setting-delete-preview" onclick="deletePreviewImage('watermark')"><i class="fa fa-trash"></i> {{ __('buttons.delete') }}</button>
                                        </div>
                                        <div class="preload-container-watermark">
                                            <svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="40px" height="40px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                                                <path d="M25.251,6.461c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615V6.461z"><animateTransform attributeType="xml" attributeName="transform" type="rotate" from="0 25 25" to="360 25 25" dur="0.6s" repeatCount="indefinite"/></path>
                                            </svg>
                                        </div>
                                        <input type="hidden" name="image_watermark" id="image-path-watermark" value="{{ $data['image_watermark'] }}">
                                    </div>
                                </div>
                                <div class="col-sm-3 col-12 d-none">
                                    <h2 class="content-heading pt-0 mb-3">{{ __('admin/system/settings.sections_watermark_pos') }}</h2>
                                    <div class="mb-2">
                                        @php $watermark_position = old('watermark_position') ?? ($data['watermark_position'] ?? null); @endphp
                                        <select class="form-select" id="select-watermark-position" name="watermark_position">
                                            <option value="1" @if($watermark_position == 1) selected @endif>{{ __('admin/system/settings.water_pos_top_left') }}</option>
                                            <option value="2" @if($watermark_position == 2) selected @endif>{{ __('admin/system/settings.water_pos_top_center') }}</option>
                                            <option value="3" @if($watermark_position == 3) selected @endif>{{ __('admin/system/settings.water_pos_top_right') }}</option>
                                            <option value="4" @if($watermark_position == 4) selected @endif>{{ __('admin/system/settings.water_pos_center_left') }}</option>
                                            <option value="5" @if($watermark_position == 5) selected @endif>{{ __('admin/system/settings.water_pos_center') }}</option>
                                            <option value="6" @if($watermark_position == 6) selected @endif>{{ __('admin/system/settings.water_pos_center_right') }}</option>
                                            <option value="7" @if($watermark_position == 7) selected @endif>{{ __('admin/system/settings.water_pos_bottom_left') }}</option>
                                            <option value="8" @if($watermark_position == 8) selected @endif>{{ __('admin/system/settings.water_pos_bottom_center') }}</option>
                                            <option value="9" @if($watermark_position == 9) selected @endif>{{ __('admin/system/settings.water_pos_bottom_right') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="settings-home" role="tabpanel">
                            <h2 class="content-heading pt-0 mb-3">{{ __('admin/system/settings.sections_news') }}</h2>
                            <div class="row">
                                <div class="col-sm-2 col-12">
                                    <div class="mb-2">
                                        <label class="form-label fs-14" for="input-home-news-status">{{ __('admin/system/settings.status') }}</label>
                                        @php $home_news_status = old('home_news.status') ?? ($data['home_news']['status'] ?? null); @endphp
                                        <select class="form-select" id="select-home-news-status" name="home_news[status]">
                                            <option value="1" @if($home_news_status == 1) selected @endif>{{ __('lang.status_on') }}</option>
                                            <option value="0" @if($home_news_status == 0) selected @endif>{{ __('lang.status_off') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2 col-12">
                                    <div class="mb-2">
                                        <label class="form-label fs-14" for="input-home-news-count">{{ __('admin/system/settings.count') }}</label>
                                        <input type="number" class="form-control" id="input-home-news-count" name="home_news[count]" value="{{ old('home_news.count') ?? ($data['home_news']['count'] ?? null) }}" placeholder="{{ __('admin/system/settings.count') }}">
                                    </div>
                                </div>
                                <div class="col-sm-2 col-12">
                                    <div class="mb-2">
                                        <label class="form-label fs-14" for="input-home-news-sort_order">{{ __('admin/system/settings.sort_order') }}</label>
                                        <input type="number" class="form-control" id="input-home-news-sort_order" name="home_news[sort_order]" value="{{ old('home_news.sort_order') ?? ($data['home_news']['sort_order'] ?? null) }}" placeholder="{{ __('admin/system/settings.sort_order') }}">
                                    </div>
                                </div>
                            </div>
                            <h2 class="content-heading pt-0 mb-3">{{ __('admin/system/settings.sections_banners') }}</h2>
                            <div class="row">
                                <div class="col-sm-2 col-12">
                                    <div class="mb-2">
                                        <label class="form-label fs-14" for="input-home-banners-status">{{ __('admin/system/settings.status') }}</label>
                                        @php $home_banners_status = old('home_banners.status') ?? ($data['home_banners']['status'] ?? null); @endphp
                                        <select class="form-select" id="select-home-banners-status" name="home_banners[status]">
                                            <option value="1" @if($home_banners_status == 1) selected @endif>{{ __('lang.status_on') }}</option>
                                            <option value="0" @if($home_banners_status == 0) selected @endif>{{ __('lang.status_off') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2 col-12">
                                    <div class="mb-2">
                                        <label class="form-label fs-14" for="input-home-banners-count">{{ __('admin/system/settings.count') }}</label>
                                        <input type="number" class="form-control" id="input-home-banners-count" name="home_banners[count]" value="{{ old('home_banners.count') ?? ($data['home_banners']['count'] ?? null) }}" placeholder="{{ __('admin/system/settings.count') }}">
                                    </div>
                                </div>
                                <div class="col-sm-2 col-12">
                                    <div class="mb-2">
                                        <label class="form-label fs-14" for="input-home-banners-sort_order">{{ __('admin/system/settings.sort_order') }}</label>
                                        <input type="number" class="form-control" id="input-home-banners-sort_order" name="home_banners[sort_order]" value="{{ old('home_banners.sort_order') ?? ($data['home_banners']['sort_order'] ?? null) }}" placeholder="{{ __('admin/system/settings.sort_order') }}">
                                    </div>
                                </div>
                            </div>
                            <h2 class="content-heading pt-0 mb-3">{{ __('admin/system/settings.sections_post_banner') }}</h2>
                            <div class="row">
                                <div class="col-sm-2 col-12">
                                    <div class="mb-2">
                                        <label class="form-label fs-14" for="input-home-post-banner-status">{{ __('admin/system/settings.status') }}</label>
                                        @php $home_post_banner_status = old('home_post_banner.status') ?? ($data['home_post_banner']['status'] ?? null); @endphp
                                        <select class="form-select" id="select-home-post-banner-status" name="home_post_banner[status]">
                                            <option value="1" @if($home_post_banner_status == 1) selected @endif>{{ __('lang.status_on') }}</option>
                                            <option value="0" @if($home_post_banner_status == 0) selected @endif>{{ __('lang.status_off') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2 col-12">
                                    <div class="mb-2">
                                        <label class="form-label fs-14" for="input-home-post-banner-count">{{ __('admin/system/settings.count') }}</label>
                                        <input type="number" class="form-control" id="input-home-post-banner-count" name="home_post_banner[count]" value="{{ old('home_post_banner.count') ?? ($data['home_post_banner']['count'] ?? null) }}" placeholder="{{ __('admin/system/settings.count') }}">
                                    </div>
                                </div>
                                <div class="col-sm-2 col-12">
                                    <div class="mb-2">
                                        <label class="form-label fs-14" for="input-home-post-banner-sort_order">{{ __('admin/system/settings.sort_order') }}</label>
                                        <input type="number" class="form-control" id="input-home-post-banner-sort_order" name="home_post_banner[sort_order]" value="{{ old('home_post_banner.sort_order') ?? ($data['home_post_banner']['sort_order'] ?? null) }}" placeholder="{{ __('admin/system/settings.sort_order') }}">
                                    </div>
                                </div>
                            </div>
                            @foreach($data['home_settings'] as $home_setting)
                                <h2 class="content-heading pt-0 mb-3">{{ __('admin/system/settings.sections_'.$home_setting['key']) }}</h2>
                                @php $home_status = old('home_'.$home_setting['key'].'.status') ?? ($data['home_'.$home_setting['key']]['status'] ?? null); @endphp
                                @php $home_watermark = old('home_'.$home_setting['key'].'.watermark') ?? ($data['home_'.$home_setting['key']]['watermark'] ?? null); @endphp
                                @php $home_t_h = old('home_'.$home_setting['key'].'.template') ?? ($data['home_'.$home_setting['key']]['template'] ?? null); @endphp
                                <div class="row">
                                    <div class="col-sm-2 col-12">
                                        <div class="mb-2">
                                            <label class="form-label fs-14 d-block" for="input-home-{{ $home_setting['key'] }}-status">{{ __('admin/system/settings.status') }}</label>
                                            <div class="btn-group bg-white" role="group">
                                                <input type="radio" class="btn-check" name="home_{{ $home_setting['key'] }}[status]" value="1" id="radio-{{ $home_setting['key'] }}-status-1" {{ ($home_status == 1) ? 'checked' : '' }}>
                                                <label class="btn btn-outline-primary" for="radio-{{ $home_setting['key'] }}-status-1">{{ __('lang.status_on') }}</label>
                                                <input type="radio" class="btn-check" name="home_{{ $home_setting['key'] }}[status]" value="0" id="radio-{{ $home_setting['key'] }}-status-0" {{ ($home_status == 0) ? 'checked' : '' }}>
                                                <label class="btn btn-outline-danger" for="radio-{{ $home_setting['key'] }}-status-0">{{ __('lang.status_off') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 col-12">
                                        <div class="mb-2">
                                            <label class="form-label fs-14" for="input-home-{{ $home_setting['key'] }}-count">{{ __('admin/system/settings.count') }}</label>
                                            <input type="number" class="form-control" id="input-home-{{ $home_setting['key'] }}-count" name="home_{{ $home_setting['key'] }}[count]" value="{{ old('home_'.$home_setting['key'].'.count') ?? ($data['home_'.$home_setting['key']]['count'] ?? null) }}" placeholder="{{ __('admin/system/settings.count') }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-2 col-12">
                                        <div class="mb-2">
                                            <label class="form-label fs-14 d-block" for="input-home-{{ $home_setting['key'] }}-watermark">{{ __('admin/system/settings.sections_watermark') }}</label>
                                            <div class="btn-group bg-white" role="group">
                                                <input type="radio" class="btn-check" name="home_{{ $home_setting['key'] }}[watermark]" value="1" id="radio-{{ $home_setting['key'] }}-watermark-1" {{ ($home_watermark == 1) ? 'checked' : '' }}>
                                                <label class="btn btn-outline-primary" for="radio-{{ $home_setting['key'] }}-watermark-1">{{ __('lang.yes') }}</label>
                                                <input type="radio" class="btn-check" name="home_{{ $home_setting['key'] }}[watermark]" value="0" id="radio-{{ $home_setting['key'] }}-watermark-0" {{ ($home_watermark == 0) ? 'checked' : '' }}>
                                                <label class="btn btn-outline-danger" for="radio-{{ $home_setting['key'] }}-watermark-0">{{ __('lang.no') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 col-12">
                                        <div class="mb-2">
                                            <label class="form-label fs-14" for="input-home-{{ $home_setting['key'] }}-template">{{ __('admin/system/settings.template') }}</label>
                                            <select class="form-select" id="select-home-{{ $home_setting['key'] }}-template" name="home_{{ $home_setting['key'] }}[template]">
                                                @foreach($data[$home_setting['template']] as $template)
                                                    <option value="{{ $template['id'] }}" {{ ($template['id'] == $home_t_h) ? 'selected' : '' }}>{{ $template['title'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 col-12">
                                        <div class="mb-2">
                                            <label class="form-label fs-14" for="input-home-{{ $home_setting['key'] }}-sort-order">{{ __('admin/system/settings.sort_order') }}</label>
                                            <input type="number" class="form-control" id="input-home-{{ $home_setting['key'] }}-sort-order" name="home_{{ $home_setting['key'] }}[sort_order]" value="{{ old('home_'.$home_setting['key'].'.sort_order') ?? ($data['home_'.$home_setting['key']]['sort_order'] ?? null) }}" placeholder="{{ __('admin/system/settings.sort_order') }}">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="tab-pane" id="settings-pages" role="tabpanel">
                            @foreach($data['page_settings'] as $page_setting)
                                <h2 class="content-heading pt-0 mb-3">{{ __('admin/system/settings.sections_'.$page_setting['key']) }}</h2>
                                @php $page_watermark = old('page_'.$page_setting['key'].'.watermark') ?? ($data['page_'.$page_setting['key']]['watermark'] ?? null); @endphp
                                @php $page_template = old('page_'.$page_setting['key'].'.template') ?? ($data['page_'.$page_setting['key']]['template'] ?? null); @endphp
                                <div class="row">
                                    <div class="col-sm-4 col-12">
                                        <div class="mb-2">
                                            <label class="form-label fs-14" for="input-page-{{ $page_setting['key'] }}-count-per-page">{{ __('admin/system/settings.count_per_page') }}</label>
                                            <input type="number" class="form-control" id="input-page-{{ $page_setting['key'] }}-count-per-page" name="page_{{ $page_setting['key'] }}[count_per_page]" value="{{ old('page_'.$page_setting['key'].'.count_per_page') ?? ($data['page_'.$page_setting['key']]['count_per_page'] ?? null) }}" placeholder="{{ __('admin/system/settings.count_per_page') }}">
                                        </div>
                                    </div>
                                    @if($page_setting['watermark'])
                                        <div class="col-sm-4 col-12">
                                            <div class="mb-2">
                                                <label class="form-label fs-14 d-block" for="input-page-{{ $page_setting['key'] }}-watermark">{{ __('admin/system/settings.sections_watermark') }}</label>
                                                <div class="btn-group bg-white" role="group">
                                                    <input type="radio" class="btn-check" name="page_{{ $page_setting['key'] }}[watermark]" value="1" id="radio-page-{{ $page_setting['key'] }}-watermark-1" {{ ($page_watermark == 1) ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-primary" for="radio-page-{{ $page_setting['key'] }}-watermark-1">{{ __('lang.yes') }}</label>
                                                    <input type="radio" class="btn-check" name="page_{{ $page_setting['key'] }}[watermark]" value="0" id="radio-page-{{ $page_setting['key'] }}-watermark-0" {{ ($page_watermark == 0) ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-danger" for="radio-page-{{ $page_setting['key'] }}-watermark-0">{{ __('lang.no') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($page_setting['template'])
                                        <div class="col-sm-4 col-12">
                                            <div class="mb-2">
                                                <label class="form-label fs-14" for="input-page-{{ $page_setting['key'] }}-template">{{ __('admin/system/settings.template') }}</label>
                                                <select class="form-select" id="select-page-{{ $page_setting['key'] }}-template" name="page_{{ $page_setting['key'] }}[template]">
                                                    @foreach($data[$page_setting['template']] as $template)
                                                        <option value="{{ $template['id'] }}" {{ ($template['id'] == $page_template) ? 'selected' : '' }}>{{ $template['title'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <div class="tab-pane" id="settings-user" role="tabpanel">
                            <div class="block block-rounded">
                                <h2 class="content-heading pt-0 mb-3">{{ __('admin/system/settings.sections_register') }}</h2>
                                <div class="mb-2">
                                    <label class="form-label fs-14 d-block m-0" for="input-post-publish-status">{{ __('admin/system/settings.auth_email_verify') }}</label>
                                    <p class="m-0 text-gray-dark fs-14">{{ __('admin/system/settings.auth_email_verify_p') }}</p>
                                    @php $auth_email_verify = old('auth_email_verify') ?? $data['auth_email_verify']; @endphp
                                    <div class="btn-group bg-white" role="group">
                                        <input type="radio" class="btn-check" name="auth_email_verify" value="1" id="auth-email-verify-1" {{ ($auth_email_verify == 1) ? 'checked' : '' }}>
                                        <label class="btn btn-outline-primary" for="auth-email-verify-1">{{ __('lang.status_on') }}</label>
                                        <input type="radio" class="btn-check" name="auth_email_verify" value="0" id="auth-email-verify-0" {{ ($auth_email_verify == 0) ? 'checked' : '' }}>
                                        <label class="btn btn-outline-danger" for="auth-email-verify-0">{{ __('lang.status_off') }}</label>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fs-14" for="input-reg-start-balance">{{ __('admin/system/settings.reg_start_balance') }}</label>
                                    <input type="number" class="form-control" id="input-reg-start-balance" name="reg_start_balance" value="{{ old('reg_start_balance') ?? ($data['reg_start_balance'] ?? null) }}" placeholder="{{ __('admin/system/settings.reg_start_balance') }}">
                                </div>

                                <div class="mb-2">
                                    <label class="form-label fs-14" for="input-reg-privacy">{{ __('admin/system/settings.reg_privacy') }}</label>
                                    <select class="form-select" id="select-reg-privacy" name="reg_privacy">
                                        @foreach($data['information'] as $info)
                                            <option value="{{ $info['id'] }}" {{ (isset($data['reg_privacy']) && $data['reg_privacy'] == $info['id']) ? 'selected' : '' }}>{{ $info['title'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="settings-prices" role="tabpanel">
                            <div class="block block-rounded">
                                <h2 class="content-heading pt-0 mb-3">{{ __('admin/system/settings.tab_post') }}</h2>
                                <div class="row">
                                    @foreach($data['post_prices_settings'] as $group => $price_data)
                                        <div class="col-sm-4 col-12">
                                            <h2 class="content-heading fs-6 pt-0 mb-3">{{ $price_data['title'] }}</h2>
                                            @foreach($price_data['data'] as $key => $item)
                                                <div class="mb-2">
                                                    <label class="form-label fs-14" for="input-{{ $group }}-{{ $key }}">{{ $item['title'] }}</label>
                                                    <input type="{{ $item['type'] }}" class="form-control form-control-sm" id="input-{{ $group }}-{{ $key }}" name="post_prices[{{ $key }}]" value="{{ old("post_prices.$key") ?? ($data['post_prices'][$key] ?? null) }}" placeholder="{{ $item['title'] }}">
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                                <h2 class="content-heading pt-0 mb-3">{{ __('admin/system/settings.tab_salon') }}</h2>
                                <div class="row">
                                    @foreach($data['salon_prices_settings'] as $group => $price_data)
                                        <div class="col-sm-4 col-12">
                                            <h2 class="content-heading fs-6 pt-0 mb-3">{{ $price_data['title'] }}</h2>
                                            @foreach($price_data['data'] as $key => $item)
                                                <div class="mb-2">
                                                    <label class="form-label fs-14" for="input-{{ $group }}-{{ $key }}">{{ $item['title'] }}</label>
                                                    <input type="{{ $item['type'] }}" class="form-control form-control-sm" id="input-{{ $group }}-{{ $key }}" name="salon_prices[{{ $key }}]" value="{{ old("salon_prices.$key") ?? ($data['salon_prices'][$key] ?? null) }}" placeholder="{{ $item['title'] }}">
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="settings-microdata" role="tabpanel">
                            <p class="m-0 text-gray-dark fs-14">{{ __('admin/system/settings.microdata') }}</p>
                            <div class="block block-rounded">
                                <div class="mb-4">
                                    <label class="form-label" for="micro-site-name">{{ __('admin/system/settings.micro_site_name') }}</label>
                                    <input type="text" name="micro_site_name" value="{{ old('micro_site_name') ?? ($data['micro_site_name'] ?? '') }}" class="form-control" id="micro-site-name" placeholder="{{ __('admin/system/settings.micro_site_name_p') }}">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="settings-js-script" role="tabpanel">
                            <h2 class="content-heading pt-0 mb-3">{{ __('admin/system/settings.sections_yandex') }}</h2>
                            <textarea id="custom_js" name="custom_js" rows="15" class="form-control">{{ $data['custom_js'] }}</textarea>
                        </div>
                        <div class="tab-pane" id="settings-robots" role="tabpanel">
                            <h2 class="content-heading pt-0 mb-3">{{ __('admin/system/settings.sections_yandex') }}</h2>
                            <textarea id="robots" name="robots" rows="15" class="form-control">{{ $data['robots'] }}</textarea>
                        </div>
                        <div class="tab-pane" id="settings-modal" role="tabpanel">
                            <h2 class="content-heading pt-0 mb-3">{{ __('admin/system/settings.sections_age_detect') }}</h2>
                            <p class="m-0 text-gray-dark fs-14">{{ __('admin/system/settings.sections_age_detect_t') }}</p>
                            <label class="form-label fs-14 d-block" for="input-post-publish-status">{{ __('admin/system/settings.status') }}</label>
                            @php $age_detect = old('age_detect') ?? ($data['age_detect'] ?? null); @endphp
                            <div class="btn-group bg-white" role="group">
                                <input type="radio" class="btn-check" name="age_detect" value="1" id="age-detect-1" {{ ($age_detect == 1) ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary" for="age-detect-1">{{ __('lang.status_on') }}</label>
                                <input type="radio" class="btn-check" name="age_detect" value="0" id="age-detect-0" {{ ($age_detect == 0) ? 'checked' : '' }}>
                                <label class="btn btn-outline-danger" for="age-detect-0">{{ __('lang.status_off') }}</label>
                            </div>
                            <h2 class="content-heading pt-0 mb-3 mt-3">{{ __('admin/system/settings.sections_subscribe') }}</h2>
                            <p class="m-0 text-gray-dark fs-14">{{ __('admin/system/settings.sections_subscribe_t') }}</p>
                            <label class="form-label fs-14 d-block" for="input-post-publish-status">{{ __('admin/system/settings.status') }}</label>
                            @php $subscribe_status = old('subscribe_status') ?? ($data['subscribe_status'] ?? null); @endphp
                            <div class="btn-group bg-white mb-4" role="group">
                                <input type="radio" class="btn-check" name="subscribe_status" value="1" id="subscribe-status-1" {{ ($subscribe_status == 1) ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary" for="subscribe-status-1">{{ __('lang.status_on') }}</label>
                                <input type="radio" class="btn-check" name="subscribe_status" value="0" id="subscribe-status-0" {{ ($subscribe_status == 0) ? 'checked' : '' }}>
                                <label class="btn btn-outline-danger" for="subscribe-status-0">{{ __('lang.status_off') }}</label>
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="subscribe_title">{{ __('admin/system/settings.object_subsc_title') }}</label>
                                <input type="text" name="subscribe_title" value="{{ old('subscribe_title') ?? ($data['subscribe_title'] ?? '') }}" class="form-control" id="subscribe_title" placeholder="{{ __('admin/system/settings.object_subsc_title') }}">
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="subscribe_text">{{ __('admin/system/settings.object_subsc_text') }}</label>
                                <textarea class="form-control text-content-area" name="subscribe_text" id="subscribe_text" rows="4" placeholder="{{ __('admin/system/settings.object_subsc_title') }}">{{ old('subscribe_text') ?? ($data['subscribe_text'] ?? '') }}</textarea>
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="subscribe_btn_title">{{ __('admin/system/settings.object_subsc_btn_title') }}</label>
                                <input type="text" name="subscribe_btn_title" value="{{ old('subscribe_btn_title') ?? ($data['subscribe_btn_title'] ?? '') }}" class="form-control" id="subscribe_btn_title" placeholder="{{ __('admin/system/settings.object_subsc_title') }}">
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="subscribe_btn_link">{{ __('admin/system/settings.object_subsc_btn_link') }}</label>
                                <input type="text" name="subscribe_btn_link" value="{{ old('subscribe_btn_link') ?? ($data['subscribe_btn_link'] ?? '') }}" class="form-control" id="subscribe_btn_link" placeholder="{{ __('admin/system/settings.object_subsc_title') }}">
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="subscribe_btn_color">{{ __('admin/system/settings.object_subsc_btn_color') }}</label>
                                <input type="color" name="subscribe_btn_color" value="{{ old('subscribe_btn_color') ?? ($data['subscribe_btn_color'] ?? '') }}" class="form-control" id="subscribe_btn_color" placeholder="{{ __('admin/system/settings.object_subsc_title') }}" style="max-width:50px;padding:0;">
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="subscribe_btn_color_t">{{ __('admin/system/settings.object_subsc_btn_color_t') }}</label>
                                <input type="color" name="subscribe_btn_color_t" value="{{ old('subscribe_btn_color_t') ?? ($data['subscribe_btn_color_t'] ?? '') }}" class="form-control" id="subscribe_btn_color_t" placeholder="{{ __('admin/system/settings.object_subsc_title') }}" style="max-width:50px;padding:0;">
                            </div>
                        </div>
                        <div class="tab-pane" id="settings-header" role="tabpanel">
                            <div class="row">
                                <div class="col-12 col-md-3 col-xl-3">
                                    <div class="mb-2">
                                        <label class="form-label fs-14 d-block" for="input-post-publish-status">{{ __('admin/system/settings.object_header_zone') }}</label>
                                        @php $header_display_zone = old('header_display_zone') ?? $data['header_display_zone']; @endphp
                                        <div class="btn-group bg-white" role="group">
                                            <input type="radio" class="btn-check" name="header_display_zone" value="1" id="header-display-zone-1" {{ ($header_display_zone == 1) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary" for="header-display-zone-1">{{ __('lang.status_on') }}</label>
                                            <input type="radio" class="btn-check" name="header_display_zone" value="0" id="header-display-zone-0" {{ ($header_display_zone == 0) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-danger" for="header-display-zone-0">{{ __('lang.status_off') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 col-xl-3">
                                    <div class="mb-2">
                                        <label class="form-label fs-14 d-block" for="input-post-publish-status">{{ __('admin/system/settings.object_header_city') }}</label>
                                        @php $header_display_city = old('header_display_city') ?? $data['header_display_city']; @endphp
                                        <div class="btn-group bg-white" role="group">
                                            <input type="radio" class="btn-check" name="header_display_city" value="1" id="header-display-city-1" {{ ($header_display_city == 1) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary" for="header-display-city-1">{{ __('lang.status_on') }}</label>
                                            <input type="radio" class="btn-check" name="header_display_city" value="0" id="header-display-city-0" {{ ($header_display_city == 0) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-danger" for="header-display-city-0">{{ __('lang.status_off') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 col-xl-3">
                                    <div class="mb-2">
                                        <label class="form-label fs-14 d-block" for="input-post-publish-status">{{ __('admin/system/settings.object_header_map') }}</label>
                                        @php $header_display_map = old('header_display_map') ?? $data['header_display_map']; @endphp
                                        <div class="btn-group bg-white" role="group">
                                            <input type="radio" class="btn-check" name="header_display_map" value="1" id="header-display-map-1" {{ ($header_display_map == 1) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary" for="header-display-map-1">{{ __('lang.status_on') }}</label>
                                            <input type="radio" class="btn-check" name="header_display_map" value="0" id="header-display-map-0" {{ ($header_display_map == 0) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-danger" for="header-display-map-0">{{ __('lang.status_off') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="settings-footer" role="tabpanel">
                            <div class="mb-4">
                                <label class="form-label" for="footer_text">{{ __('admin/system/settings.object_footer_text') }}</label>
                                <textarea class="form-control text-content-footer-text" name="footer_text" id="footer_text" rows="4" placeholder="{{ __('admin/system/settings.object_footer_text') }}">{{ old('footer_text') ?? ($data['footer_text'] ?? '') }}</textarea>
                            </div>

                            <div class="mb-4">
                                <h2 class="content-heading pt-0 mb-3">{{ __('admin/system/settings.social_vars') }}</h2>
                                <table id="social-links-table" class="table table-vcenter">
                                    <thead>
                                    <tr>
                                        <th>{{ __('admin/system/settings.social_var') }}</th>
                                        <th>{{ __('admin/system/settings.social_link') }}</th>
                                        <th class="text-center"><i class="fa-solid fa-solar-panel"></i></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $var_row = 1; @endphp
                                    @php $social_links = old('social_links') ?? ($data['social_links'] ?? null); @endphp
                                    @foreach($social_links as $solial_link)
                                        <tr>
                                            <td style="width: 300px">
                                                <select class="form-select form-select-sm" name="social_links[{{ $var_row }}][social]">
                                                    @foreach($data['social_vars'] as $key => $value)
                                                        <option value="{{ $key }}" {{ ($solial_link['social'] == $key) ? 'selected' : '' }}>{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm" name="social_links[{{ $var_row }}][link]" value="{{ $solial_link['link'] }}" placeholder="{{ __('admin/system/settings.social_link') }}">
                                            </td>
                                            <td class="text-center"><button type="button" onclick="$(this).parent().parent().remove()" class="btn btn-sm btn-alt-danger"><i class="fa fa-times"></i></button></td>
                                        </tr>
                                        @php $var_row = $var_row + 1; @endphp
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td class="text-center">
                                            <button type="button" onclick="addSocialLink();" class="btn btn-sm btn-alt-primary"><i class="fa fa-plus"></i></button>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="settings-new-year-mode" role="tabpanel">
                            <div class="row">
                                <div class="col-12 col-md-3 col-xl-3">
                                    <div class="mb-2">
                                        <label class="form-label fs-14 d-block" for="input-NewYearMode">NewYearMode</label>
                                        @php $new_year_mode = old('new_year_mode') ?? $data['new_year_mode']; @endphp
                                        <div class="btn-group bg-white" role="group">
                                            <input type="radio" class="btn-check" name="new_year_mode" value="1" id="header-new-year-mode-1" {{ ($new_year_mode == 1) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary" for="header-new-year-mode-1">{{ __('lang.status_on') }}</label>
                                            <input type="radio" class="btn-check" name="new_year_mode" value="0" id="header-new-year-mode-0" {{ ($new_year_mode == 0) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-danger" for="header-new-year-mode-0">{{ __('lang.status_off') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="block block-themed">
                <div class="block-content bg-warning text-white">
                    {{ $data['postMaxSize'] }}
                </div>
            </div>
        </div>
    </main>

    <!-- Errors -->
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <script>
                kbNotify('danger', '{{ $error }}');
            </script>
        @endforeach
    @endif
    <!-- //Errors -->

@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
    <script src="{{ url('builder/admin/js/ckeditor5-classic/build/ckeditor.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            ClassicEditor.create(document.querySelector('.post_verify_text'), {removePlugins: ['ImageUpload', 'EasyImage']}).catch(error => {console.error(error);});
            ClassicEditor.create(document.querySelector('.text-content-area'), {
                removePlugins: ['ImageUpload', 'EasyImage', 'Indent', 'MediaEmbed', 'Table', 'Heading']
            }).then(editor => {
                editorInstance = editor;
            }).catch(error => {
                console.error(error);
            });
            ClassicEditor.create(document.querySelector('.text-content-footer-text'), {
                removePlugins: ['ImageUpload', 'EasyImage', 'Indent', 'MediaEmbed', 'Table', 'Heading']
            }).then(editor => {
                editorInstance = editor;
            }).catch(error => {
                console.error(error);
            });
        });

        //Add Activation Variable
        var activation_variable_row = {{ $var_row }};

        function addPostVariable() {
            html =  '<tr>';
            html += '    <td class="fw-semibold"><input type="number" class="form-control" name="post_publish_variable[' + activation_variable_row + '][days]" placeholder="{{ __('admin/system/settings.activation_var_days') }}"></td>';
            html += '    <td class="d-none d-sm-table-cell"><input type="number" class="form-control" name="post_publish_variable[' + activation_variable_row + '][price]" placeholder="{{ $data['currency_symbol'] }}"></td>';
            html += '    <td class="text-center"><button type="button" onclick="$(this).parent().parent().remove()" class="btn btn-sm btn-alt-danger"><i class="fa fa-times"></i></button></td>';
            html += '</tr>';
            $('#post-activation-variable-table tbody').append(html);
            activation_variable_row++;
        }

        //Add Salon Activation Variable
        var salon_activation_variable_row = {{ $salon_var_row }};

        function addSalonVariable() {
            html =  '<tr>';
            html += '    <td class="fw-semibold"><input type="number" class="form-control" name="salon_publish_variable[' + salon_activation_variable_row + '][days]" placeholder="{{ __('admin/system/settings.activation_var_days') }}"></td>';
            html += '    <td class="d-none d-sm-table-cell"><input type="number" class="form-control" name="salon_publish_variable[' + salon_activation_variable_row + '][price]" placeholder="{{ $data['currency_symbol'] }}"></td>';
            html += '    <td class="text-center"><button type="button" onclick="$(this).parent().parent().remove()" class="btn btn-sm btn-alt-danger"><i class="fa fa-times"></i></button></td>';
            html += '</tr>';
            $('#salon-activation-variable-table tbody').append(html);
            salon_activation_variable_row++;
        }

        //Add Salon Activation Variable
        var social_links_row = {{ $salon_var_row }};

        function addSocialLink() {
            html =  '<tr>';
            html += '    <td style="width: 300px">';
            html += '        <select class="form-select form-select-sm" name="social_links[' + social_links_row + '][social]">';
            @foreach($data['social_vars'] as $key => $value)
                html += '            <option value="{{ $key }}">{{ $value }}</option>';
            @endforeach
                html += '        </select>';
            html += '    </td>';
            html += '    <td>';
            html += '        <input type="text" class="form-control form-control-sm" name="social_links[' + social_links_row + '][link]" placeholder="{{ __('admin/system/settings.social_link') }}">';
            html += '    </td>';
            html += '    <td class="text-center"><button type="button" onclick="$(this).parent().parent().remove()" class="btn btn-sm btn-alt-danger"><i class="fa fa-times"></i></button></td>';
            html += '</tr>';
            $('#social-links-table tbody').append(html);
            social_links_row++;
        }
    </script>
    <script>
        function handleUploadImage(event, type) {
            const file = event.target.files[0];
            if (!file) return;

            $('.preload-container-'+type).addClass('active');

            const formData = new FormData();
            const path = `/images/temp/settings/${type}`;
            formData.append('image', file);
            formData.append('path', path);
            fetch('/services/upload-image', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`preview-image-${type}`).src = `${path}/${data.filename}`;
                        document.getElementById(`image-path-${type}`).value = `${path}/${data.filename}`;
                        document.getElementById(`preview-container-${type}`).style.display = 'flex';
                        document.getElementById(`upload-container-${type}`).style.display = 'none';

                        setTimeout(function () {
                            $('.preload-container-'+type).removeClass('active');
                        }, 1000);
                    }
                });
        }

        function deletePreviewImage(type) {
            document.getElementById(`preview-container-${type}`).style.display = 'none';
            document.getElementById(`upload-container-${type}`).style.display = 'flex';
            document.getElementById(`image-path-${type}`).value = '';
            document.getElementById(`image-upload-${type}`).value = '';
        }
    </script>
    <style>
        .ck-editor__editable_inline {
            min-height: 200px;
        }

        .preload-container-logo, .preload-container-watermark {
            width: 326px;
            height: 326px;
        }
    </style>
@endsection
