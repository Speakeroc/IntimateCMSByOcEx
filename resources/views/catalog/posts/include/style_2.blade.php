@php
    if ($data['vip']) {
        $status_class = 'vip';
    }
    if ($data['diamond']) {
        $status_class = 'diamond';
    }
    if (!$data['diamond'] && !$data['vip']) {
        $status_class = '';
    }
@endphp
<div class="col-12 col-md-6 col-xl-4">
    <div class="ex_post_middle_item">
        <div class="row">
            <div class="col-6">
                <a href="{{ $data['link'] }}" class="ex_post_middle_item_block_image {{ ($data['color']) ? 'color' : '' }}">
                    <div class="ex_post_middle_item_block_image_info">
                        @if($data['photo'])
                            <div class="ex_post_middle_item_block_image_info_item"><i class="fas fa-images"></i></div>
                        @endif
                        @if($data['selfie'])
                            <div class="ex_post_middle_item_block_image_info_item"><i class="fas fa-image-portrait"></i>
                            </div>
                        @endif
                        @if($data['video'])
                            <div class="ex_post_middle_item_block_image_info_item"><i class="fas fa-video"></i></div>
                        @endif
                        @if($data['reviews'])
                            <div class="ex_post_middle_item_block_image_info_item"><i class="fas fa-comments"></i> {{ $data['reviews'] }}</div>
                        @endif
                    </div>
                    <img src="{{ $data['image'] }}" alt="{{ $data['name'] }} {{ $data['age'] }}" title="{{ $data['name'] }} {{ $data['age'] }}" class="ex_post_middle_item_image">
                    <div class="ex_post_middle_item_block_statuses">
                        @if($status_class == 'vip')
                            <div class="ex_post_middle_item_block_status vip">VIP</div>
                        @elseif($status_class == 'diamond')
                            <div class="ex_post_middle_item_block_status diamond">Diamond</div>
                        @endif
                    </div>
                </a>
            </div>
            <div class="col-6">
                <div class="ex_post_middle_item_info_block">
                    <div class="ex_post_middle_item_info_block_top">
                        @if(!empty($data['date_added']))
                            <div class="ex_post_middle_item_micro_data">
                                <span class="ex_post_middle_item_micro_data_item">{{ $data['date_added'] }}</span>
                            </div>
                        @endif
                        <div class="ex_post_middle_item_post_name_age">
                            <a href="{{ $data['link'] }}">
                                <div class="ex_post_big_item_post_name {{ $status_class }}">{{ $data['name'] }}
                                    @if($data['verify'])
                                        <img src="{{ url('/images/icons/verify.svg') }}" alt="{{ __('lang.photo_verify') }}" title="{{ __('lang.photo_verify') }}" class="ex_post_middle_item_image_verify">
                                    @endif
                                </div>
                            </a>
                            <div class="ex_post_middle_item_post_age">{{ $data['age'] }}</div>
                        </div>
                        @if(!empty($data['city']) || !empty($data['zone']))
                            <div class="d-flex flex-row flex-wrap align-items-center justify-content-start gap-1">
                                @if(!empty($data['city']))
                                    <div class="ex_post_middle_item_post_location">
                                        <svg class="ex_post_middle_item_post_icon_location"><use xlink:href="#icon-menu-city"></use></svg>
                                        <span><a href="{{ $data['city']['link'] }}">{{ $data['city']['title'] }}</a></span>
                                    </div>
                                @endif
                                @if(!empty($data['zone']))
                                    <div class="ex_post_middle_item_post_location">
                                        <svg class="ex_post_middle_item_post_icon_location"><use xlink:href="#icon-menu-location"></use></svg>
                                        <span><a href="{{ $data['zone']['link'] }}">{{ $data['zone']['title'] }}</a></span>
                                    </div>
                                @endif
                            </div>
                        @endif
                        <div class="row ex_post_middle_item_post_params">
                            <div class="col-4 ex_post_middle_item_post_param_item">
                                <div class="ex_post_middle_item_post_param_item_title">{{ __('catalog/posts/post.item_breast') }}</div>
                                <div class="ex_post_middle_item_post_param_item_text">{{ $data['breast'] }}</div>
                            </div>
                            <div class="col-4 ex_post_middle_item_post_param_item">
                                <div class="ex_post_middle_item_post_param_item_title">{{ __('catalog/posts/post.item_weight') }}</div>
                                <div class="ex_post_middle_item_post_param_item_text">{{ $data['weight'] }}</div>
                            </div>
                            <div class="col-4 ex_post_middle_item_post_param_item">
                                <div class="ex_post_middle_item_post_param_item_title">{{ __('catalog/posts/post.item_height') }}</div>
                                <div class="ex_post_middle_item_post_param_item_text">{{ $data['height'] }}</div>
                            </div>
                        </div>
                        <div class="ex_post_middle_item_post_prices">
                            <div class="ex_post_middle_item_post_price">
                                <span class="ex_post_middle_item_post_price_time">
                                    <svg class="ex_post_middle_item_post_price_icon">
                                        <use xlink:href="#icon-time-hour"></use>
                                    </svg> {{ __('catalog/posts/post.item_hour_one') }}
                                </span>
                                <span class="ex_post_middle_item_post_price_summ">{{ $data['price_hour'] }}</span>
                            </div>
                            <div class="ex_post_middle_item_post_price">
                                <span class="ex_post_middle_item_post_price_time">
                                    <svg class="ex_post_middle_item_post_price_icon">
                                        <use xlink:href="#icon-time-hour"></use>
                                    </svg> {{ __('catalog/posts/post.item_hour_two') }}
                                </span>
                                <span class="ex_post_middle_item_post_price_summ">{{ $data['price_hours'] }}</span>
                            </div>
                        </div>
                        @if(!empty($data['tags']))
                            <div class="ex_post_middle_item_post_tags">
                                @foreach($data['tags'] as $tag)
                                    <a href="{{ $tag['link'] }}" class="ex_post_middle_item_post_tags_item">{{ $tag['tag'] }}</a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="ex_post_middle_item_info_block_bottom">
                        <a href="{{ $data['link'] }}" class="ex_post_middle_item_button_contact">{{ __('catalog/posts/post.item_contact') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(!empty( $data['microdata']))
        <script type="application/ld+json">{!! $data['microdata'] !!}</script>
    @endif
</div>
