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
                    <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">{{ __('admin/page_titles.analytics') }}</h1>
                </div>
            </div>
        </div>


        <div class="content">
            <div class="row">
                <div class="col-sm-6 col-12">
                    <h2 class="content-heading pt-1 mb-2">{{ __('admin/system/analytics.aaio_payment') }}</h2>
                    <div class="block block-rounded block-link-pop mb-2">
                        <div class="block-content">
                            <div class="table-responsive">
                                <table class="table table-bordered table-vcenter" style="margin: 0">
                                    <tbody>
                                    @foreach($data['aaio'] as $aaio_key => $aaio_value)
                                        <tr>
                                            <td class="fw-semibold text-left" style="padding: 5px;">{{ __('admin/system/analytics.aaio_'.$aaio_key) }}</td>
                                            <td class="text-end" style="padding: 5px;">{{ $aaio_value }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-12">
                    <h2 class="content-heading pt-1 mb-2">{{ __('admin/system/analytics.service_payment') }}</h2>
                    <div class="block block-rounded block-link-pop mb-2">
                        <div class="block-content">
                            <div class="table-responsive">
                                <table class="table table-bordered table-vcenter" style="margin: 0">
                                    <tbody>
                                    @foreach($data['services'] as $service_key => $service_value)
                                        <tr>
                                            <td class="fw-semibold text-left" style="padding: 5px;">{{ __('admin/system/analytics.aaio_'.$service_key) }}</td>
                                            <td class="text-end" style="padding: 5px;">{{ $service_value }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-12">
                    <h2 class="content-heading pt-1 mb-2">{{ __('admin/system/analytics.service_visitors') }}</h2>
                    <div class="block block-rounded block-link-pop mb-2">
                        <div class="block-content">
                            <div class="table-responsive">
                                <table class="table table-bordered table-vcenter" style="margin: 0">
                                    <tbody>
                                    @foreach($data['visitors'] as $visitor_key => $visitor_value)
                                        <tr>
                                            <td class="fw-semibold text-left" style="padding: 5px;">{{ __('admin/system/analytics.aaio_'.$visitor_key) }}</td>
                                            <td class="text-end" style="padding: 5px;">{{ $visitor_value }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-12">
                    <h2 class="content-heading pt-1 mb-2">{{ __('admin/system/analytics.user_register') }}</h2>
                    <div class="block block-rounded block-link-pop mb-2">
                        <div class="block-content">
                            <div class="table-responsive">
                                <table class="table table-bordered table-vcenter" style="margin: 0">
                                    <tbody>
                                    @foreach($data['registers'] as $register_key => $register_value)
                                        <tr>
                                            <td class="fw-semibold text-left" style="padding: 5px;">{{ __('admin/system/analytics.aaio_'.$register_key) }}</td>
                                            <td class="text-end" style="padding: 5px;">{{ $register_value }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-12">
                    <h2 class="content-heading pt-1 mb-2">{{ __('admin/system/analytics.device_types') }}</h2>
                    <div class="block block-rounded">
                        <ul class="nav nav-tabs nav-tabs-block" role="tablist">
                            @foreach($data['visitors_device'] as $visitors_device_key => $visitors_device_value)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link @if($loop->first) active @endif" id="device-types-{{ $visitors_device_key }}-tab" data-bs-toggle="tab" data-bs-target="#device-types-{{ $visitors_device_key }}" role="tab" aria-controls="device-types-{{ $visitors_device_key }}" aria-selected="false" tabindex="-1">{{ __('admin/system/analytics.aaio_' . $visitors_device_key) }}</button>
                                </li>
                            @endforeach
                        </ul>
                        <div class="block-content tab-content">
                            @foreach($data['visitors_device'] as $visitors_device_key => $visitors_device_value)
                                <div class="tab-pane @if($loop->first) active show @endif" id="device-types-{{ $visitors_device_key }}" role="tabpanel" aria-labelledby="device-types-{{ $visitors_device_key }}-tab" tabindex="0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-vcenter" style="margin: 0">
                                            <tbody>
                                            @if(!empty($visitors_device_value))
                                                @foreach($visitors_device_value as $device_value_key => $device_value_value)
                                                    <tr>
                                                        <td class="fw-semibold text-left" style="padding: 5px;">{{ $device_value_value['title'] }}</td>
                                                        <td class="text-end" style="padding: 5px;">{{ $device_value_value['count'] }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr><td class="text-center" colspan="2">{{ __('lang.list_is_empty') }}</td></tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-12">
                    <h2 class="content-heading pt-1 mb-2">{{ __('admin/system/analytics.operation_system') }}</h2>
                    <div class="block block-rounded">
                        <ul class="nav nav-tabs nav-tabs-block" role="tablist">
                            @foreach($data['visitors_operating_system'] as $visitors_os_key => $visitors_os_value)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link @if($loop->first) active @endif" id="os-{{ $visitors_os_key }}-tab" data-bs-toggle="tab" data-bs-target="#os-{{ $visitors_os_key }}" role="tab" aria-controls="os-{{ $visitors_os_key }}" aria-selected="false" tabindex="-1">{{ __('admin/system/analytics.aaio_' . $visitors_os_key) }}</button>
                                </li>
                            @endforeach
                        </ul>
                        <div class="block-content tab-content">
                            @foreach($data['visitors_operating_system'] as $visitors_os_key => $visitors_os_value)
                                <div class="tab-pane @if($loop->first) active show @endif" id="os-{{ $visitors_os_key }}" role="tabpanel" aria-labelledby="os-{{ $visitors_os_key }}-tab" tabindex="0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-vcenter" style="margin: 0">
                                            <tbody>
                                            @if(!empty($visitors_os_value))
                                                @foreach($visitors_os_value as $os_value_key => $os_value_value)
                                                    <tr>
                                                        <td class="fw-semibold text-left" style="padding: 5px;">{{ $os_value_value['title'] }}</td>
                                                        <td class="text-end" style="padding: 5px;">{{ $os_value_value['count'] }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr><td class="text-center" colspan="2">{{ __('lang.list_is_empty') }}</td></tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-12">
                    <h2 class="content-heading pt-1 mb-2">{{ __('admin/system/analytics.language') }}</h2>
                    <div class="block block-rounded">
                        <ul class="nav nav-tabs nav-tabs-block" role="tablist">
                            @foreach($data['visitors_language'] as $visitors_language_key => $visitors_language_value)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link @if($loop->first) active @endif" id="language-{{ $visitors_language_key }}-tab" data-bs-toggle="tab" data-bs-target="#language-{{ $visitors_language_key }}" role="tab" aria-controls="language-{{ $visitors_language_key }}" aria-selected="false" tabindex="-1">{{ __('admin/system/analytics.aaio_' . $visitors_language_key) }}</button>
                                </li>
                            @endforeach
                        </ul>
                        <div class="block-content tab-content">
                            @foreach($data['visitors_language'] as $visitors_language_key => $visitors_language_value)
                                <div class="tab-pane @if($loop->first) active show @endif" id="language-{{ $visitors_language_key }}" role="tabpanel" aria-labelledby="language-{{ $visitors_language_key }}-tab" tabindex="0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-vcenter" style="margin: 0">
                                            <tbody>
                                            @if(!empty($visitors_language_value))
                                                @foreach($visitors_language_value as $language_value_key => $language_value_value)
                                                    <tr>
                                                        <td class="fw-semibold text-left" style="padding: 5px;">{{ $language_value_value['title'] }}</td>
                                                        <td class="text-end" style="padding: 5px;">{{ $language_value_value['count'] }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr><td class="text-center" colspan="2">{{ __('lang.list_is_empty') }}</td></tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-12">
                    <h2 class="content-heading pt-1 mb-2">{{ __('admin/system/analytics.browser') }}</h2>
                    <div class="block block-rounded">
                        <ul class="nav nav-tabs nav-tabs-block" role="tablist">
                            @foreach($data['visitors_browser'] as $visitors_browser_key => $visitors_browser_value)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link @if($loop->first) active @endif" id="browser-{{ $visitors_browser_key }}-tab" data-bs-toggle="tab" data-bs-target="#browser-{{ $visitors_browser_key }}" role="tab" aria-controls="browser-{{ $visitors_browser_key }}" aria-selected="false" tabindex="-1">{{ __('admin/system/analytics.aaio_' . $visitors_browser_key) }}</button>
                                </li>
                            @endforeach
                        </ul>
                        <div class="block-content tab-content">
                            @foreach($data['visitors_browser'] as $visitors_browser_key => $visitors_browser_value)
                                <div class="tab-pane @if($loop->first) active show @endif" id="browser-{{ $visitors_browser_key }}" role="tabpanel" aria-labelledby="browser-{{ $visitors_browser_key }}-tab" tabindex="0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-vcenter" style="margin: 0">
                                            <tbody>
                                            @if(!empty($visitors_browser_value))
                                                @foreach($visitors_browser_value as $browser_value_key => $browser_value_value)
                                                    <tr>
                                                        <td class="fw-semibold text-left" style="padding: 5px;">{{ $browser_value_value['title'] }}</td>
                                                        <td class="text-end" style="padding: 5px;">{{ $browser_value_value['count'] }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr><td class="text-center" colspan="2">{{ __('lang.list_is_empty') }}</td></tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-12">
                    <h2 class="content-heading pt-1 mb-2">{{ __('admin/system/analytics.country') }}</h2>
                    <div class="block block-rounded">
                        <ul class="nav nav-tabs nav-tabs-block" role="tablist">
                            @foreach($data['visitors_country'] as $visitors_country_key => $visitors_country_value)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link @if($loop->first) active @endif" id="country-{{ $visitors_country_key }}-tab" data-bs-toggle="tab" data-bs-target="#country-{{ $visitors_country_key }}" role="tab" aria-controls="country-{{ $visitors_country_key }}" aria-selected="false" tabindex="-1">{{ __('admin/system/analytics.aaio_' . $visitors_country_key) }}</button>
                                </li>
                            @endforeach
                        </ul>
                        <div class="block-content tab-content">
                            @foreach($data['visitors_country'] as $visitors_country_key => $visitors_country_value)
                                <div class="tab-pane @if($loop->first) active show @endif" id="country-{{ $visitors_country_key }}" role="tabpanel" aria-labelledby="country-{{ $visitors_country_key }}-tab" tabindex="0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-vcenter" style="margin: 0">
                                            <tbody>
                                            @if(!empty($visitors_country_value))
                                                @foreach($visitors_country_value as $country_value_key => $country_value_value)
                                                    <tr>
                                                        <td class="fw-semibold text-left" style="padding: 5px;">{{ $country_value_value['title'] }}</td>
                                                        <td class="text-end" style="padding: 5px;">{{ $country_value_value['count'] }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr><td class="text-center" colspan="2">{{ __('lang.list_is_empty') }}</td></tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
@endsection
