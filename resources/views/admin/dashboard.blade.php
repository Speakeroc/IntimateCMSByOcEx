@extends('admin/layout/layout')
@section('header', $data['elements']['header'])
@section('sidebar', $data['elements']['sidebar'])
@section('css_js_header')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection
@section('content')
    <main id="main-container">
        <div class="bg-body-light">
            <div class="content content-full">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                    <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">{{ __('admin/page_titles.dashboard') }}</h1>
                </div>
            </div>
        </div>

        <div class="content">

            <div class="row">
                <div class="col-sm-3 col-12">
                    <div class="block block-rounded block-link-pop">
                        <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                            <div><i class="fa fa-2x fa-users text-primary"></i></div>
                            <div class="ms-3 text-end"><p class="fs-3 fw-medium mb-0">{{ $data['count_users'] }}</p><p class="text-muted mb-0">{{ trans_choice('admin/dashboard.users_choice', $data['count_users']) }}</p></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 col-12">
                    <div class="block block-rounded block-link-pop">
                        <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                            <div><i class="fa fa-2x fa-photo-film text-primary"></i></div>
                            <div class="ms-3 text-end"><p class="fs-3 fw-medium mb-0">{{ $data['posts_content_size'] }}</p><p class="text-muted mb-0">{{ __('admin/dashboard.media') }}</p></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 col-12">
                    <div class="block block-rounded block-link-pop">
                        <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                            <div><i class="fa fa-2x fa-image-portrait text-primary"></i></div>
                            <div class="ms-3 text-end"><p class="fs-3 fw-medium mb-0">{{ $data['count_posts'] }}</p><p class="text-muted mb-0">{{ trans_choice('admin/dashboard.posts_choice', $data['count_posts']) }}</p></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 col-12">
                    <div class="block block-rounded block-link-pop">
                        <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                            <div><i class="fa fa-2x fa-users text-primary"></i></div>
                            <div class="ms-3 text-end"><p class="fs-3 fw-medium mb-0">{{ $data['online_users'] }}</p><p class="text-muted mb-0">{{ __('admin/dashboard.online_users') }}</p></div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-12">
                    <div class="block block-rounded">
                        <div class="block-header">
                            <h3 class="block-title">{{ __('admin/dashboard.registration_stats') }}</h3>
                            <div class="block-options">
                                <span class="badge bg-primary">{{ __('lang.today') }}: {{ $data['chart_users']['today'] }}</span>
                                <span class="badge bg-black-50">{{ __('lang.yesterday') }}: {{ $data['chart_users']['yesterday'] }}</span>
                            </div>
                        </div>
                        <div class="block-content">
                            <canvas id="chart_users" height="120px"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-12">
                    <div class="block block-rounded">
                        <div class="block-header">
                            <h3 class="block-title">{{ __('admin/dashboard.posts_stats') }}</h3>
                            <div class="block-options">
                                <span class="badge bg-primary">{{ __('lang.today') }}: {{ $data['chart_posts']['today'] }}</span>
                                <span class="badge bg-black-50">{{ __('lang.yesterday') }}: {{ $data['chart_posts']['yesterday'] }}</span>
                            </div>
                        </div>
                        <div class="block-content">
                            <canvas id="chart_posts" height="120px"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-12">
                    <div class="block block-rounded">
                        <div class="block-header">
                            <h3 class="block-title">{{ $data['realtime_chart']['title'] }}</h3>
                        </div>
                        <div class="block-content">
                            <canvas id="chart_realtime" height="120px"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-12">
                    <div class="block block-rounded">
                        <div class="block-header">
                            <h3 class="block-title">{{ $data['realtime_links']['title'] }}</h3>
                        </div>
                        <div class="block-content">

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-vcenter">
                                    <thead>
                                    <tr>
                                        <th>{{ __('admin/dashboard.link') }}</th>
                                        <th>{{ __('admin/dashboard.count') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody id="realtime_links">
                                    @foreach($data['realtime_links']['items'] as $link)
                                        <tr>
                                            <td><small>{{ $link['url'] }}</small></td>
                                            <td class="text-end">{{ $link['count'] }}</td>
                                        </tr>
                                    @endforeach
                                    @if(!$data['realtime_links']['items'])
                                        <tr><td class="text-center" colspan="2">{{ __('lang.list_is_empty') }}</td></tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-12">
                    <div class="block block-rounded">
                        <div class="block-header">
                            <h3 class="block-title">{{ __('admin/dashboard.last_post') }}</h3>
                        </div>
                        <div class="block-content">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-vcenter">
                                    <thead>
                                    <tr>
                                        <th class="text-center" style="width: 5%;"><i class="fa-solid fa-image" data-bs-toggle="tooltip" title="{{ __('admin/posts/post.col_image') }}"></i></th>
                                        <th style="width: 40%;">{{ __('admin/posts/post.name') }}</th>
                                        <th class="text-center" style="width: 5%;">{{ __('admin/posts/post.col_publish') }}</th>
                                        <th class="text-center" style="width: 5%;"><i class="fa-regular fa-calendar-days"></i></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data['last_post'] as $item)
                                        <tr>
                                            <td class="text-center">
                                                <img class="img-avatar ex_post_list_image" src="{{ $item['image'] }}" alt="{{ $item['name'] }}" style="object-fit: cover">
                                            </td>
                                            <td class="fw-semibold">
                                                <div>{{ $item['name'] }} ({{ $item['age'] }})</div>
                                                <div><small><span class="ex_copyText" data-copy-info="{{ $item['phone'] }}" data-bs-toggle="tooltip" title="{{ __('lang.copy_to_clipboard') }}">{{ $item['phone'] }} <i class="ex_copyIcon fa-solid fa-copy"></i> </span></small></div>
                                                <div><small><a href="{{ $item['user_link'] }}" target="_blank" class="fs-14" data-bs-toggle="tooltip" title="{{ __('admin/posts/post.user') }}">{{ $item['user'] }} <i class="fas fa-arrow-up-right-from-square"></i></a></small></div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge {{ $item['publish'] ? 'bg-success' : 'bg-black-50' }}">{{ $item['publish'] ? __('lang.status_on') : __('lang.status_off') }}</span>
                                            </td>
                                            <td class="text-center fs-6" style="white-space:nowrap;"><small>{{ $item['publish_date'] }}</small></td>
                                        </tr>
                                    @endforeach
                                    @if(!$data['last_post'])
                                        <tr><td class="text-center" colspan="10">{{ __('lang.list_is_empty') }}</td></tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-12">
                    <div class="block block-rounded">
                        <div class="block-header">
                            <h3 class="block-title">{{ __('admin/dashboard.transaction') }}</h3>
                        </div>
                        <div class="block-content">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-vcenter">
                                    <thead>
                                    <tr>
                                        <th>{{ __('admin/pay/transaction.type') }}</th>
                                        <th>{{ __('admin/pay/transaction.price') }}</th>
                                        <th>{{ __('admin/pay/transaction.user') }}</th>
                                        <th class="text-center"><i class="fa-regular fa-calendar-days"></i></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data['transaction'] as $transaction)
                                        <tr>
                                            <td class="fw-semibold"><small>{!! $transaction['type'] !!} {!! ($transaction['order_status']) ? '| '.$transaction['order_status'] : '' !!}</small></td>
                                            <td><small>{{ $transaction['price'] }}</small></td>
                                            <td><small><a href="{{ $transaction['user']['user_link'] }}" target="_blank">{{ $transaction['user']['user'] }} <i class="fas fa-arrow-up-right-from-square"></i></a></small></td>
                                            <td class="text-center fs-6" style="white-space:nowrap;"><small>{{ $transaction['date'] }}</small></td>
                                        </tr>
                                    @endforeach
                                    @if(!$data['transaction'])
                                        <tr><td class="text-center" colspan="4">{{ __('lang.list_is_empty') }}</td></tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </main>
@endsection
@section('footer', $data['elements']['footer'])
@section('css_js_footer')
    <script>
        //Charts
        //Users
        const ctxUsers = document.getElementById('chart_users').getContext('2d');
        const usersChart = new Chart(ctxUsers, {
            type: 'bar',
            data: {
                labels: [{{ $data['chart_users']['dates'] }}],
                datasets: [
                    {label: '{{ __('lang.this_month') }}', data: [{!! $data['chart_users']['month'] !!}], backgroundColor: 'rgba(6,101,208)', borderColor: 'rgb(6,101,208)', borderRadius: 5, borderWidth: 2, fill: false, tension: 0.4,},
                ],
            },
            options: {elements: {line: {tension: 0.4, borderCapStyle: 'round'}}, plugins: {legend: {display: true}}, responsive: true, interaction: {mode: 'index', intersect: false,}, stacked: false,},
        });

        //Posts
        const ctxPosts = document.getElementById('chart_posts').getContext('2d');
        const anketsChart = new Chart(ctxPosts, {
            type: 'bar',
            data: {
                labels: [{{ $data['chart_posts']['dates'] }}],
                datasets: [
                    {label: '{{ __('lang.this_month') }}', data: [{!! $data['chart_posts']['month'] !!}], backgroundColor: 'rgba(6,101,208)', borderColor: 'rgb(6,101,208)', borderRadius: 5, borderWidth: 2, fill: false, tension: 0.4,},
                ],
            },
            options: {elements: {line: {tension: 0.4, borderCapStyle: 'round'}}, plugins: {legend: {display: true}}, responsive: true, interaction: {mode: 'index', intersect: false,}, stacked: false,},
        });

        //RealTime
        const ctxRealTime = document.getElementById('chart_realtime').getContext('2d');
        const realTimeChart = new Chart(ctxRealTime, {
            type: 'bar',
            data: {
                labels: @json($data['realtime_chart']['labels']),
                datasets: [
                    {label: '{{ __('admin/dashboard.realtime_chart_t') }}', data: @json($data['realtime_chart']['data']), backgroundColor: 'rgba(6,101,208)', borderColor: 'rgb(6,101,208)', borderRadius: 5, borderWidth: 2, fill: false, tension: 0.4,},
                ],
            },
            options: {elements: {line: {tension: 0.4, borderCapStyle: 'round'}}, plugins: {legend: {display: true}}, responsive: true, interaction: {mode: 'index', intersect: false,}, stacked: false,},
        });

        function updateDataRealtime() {
            $('#blacklist_modal_body').html('');
            $.ajax({
                url: '{{ route('services.getRealtimeToAjax') }}',
                type: 'POST',
                data: {_token: '{{ csrf_token() }}'},
                success: function(response) {
                    if (response.chart && response.chart.labels && response.chart.data) {
                        realTimeChart.data.labels = response.chart.labels;
                        realTimeChart.data.datasets[0].data = response.chart.data;
                        realTimeChart.update();
                    } else {
                        console.error('Invalid response structure:', response);
                    }

                    let realtime_table = '';
                    if (response.link && response.link.items && response.link.items.length > 0) {
                        response.link.items.forEach(link => {
                            realtime_table += `<tr><td><small>${link.url}</small></td><td class="text-end">${link.count}</td></tr>`;
                        });
                    } else {
                        realtime_table = `<tr><td class="text-center" colspan="2">{{ __('lang.list_is_empty') }}</td></tr>`;
                    }

                    // Вставка новых данных в блок #realtime_links
                    $('#realtime_links').html(realtime_table);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        }

        setInterval(function () {updateDataRealtime()}, 5000);
    </script>
@endsection
