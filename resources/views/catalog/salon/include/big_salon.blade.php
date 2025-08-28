<div class="col-12 col-md-6 col-xl-4">
    <div class="ex_salon_big_item">
        <div class="ex_salon_big_item_block_image">
            <a href="{{ $data['link'] }}">
                <img src="{{ $data['image'] }}" alt="{{ $data['title'] }}" title="{{ $data['title'] }}" class="ex_salon_big_item_image">
            </a>
        </div>
        <div class="ex_salon_big_item_info_block">
            <div class="ex_salon_big_item_info_block_top">
                <div class="ex_salon_big_item_micro_data">
                    <span class="ex_salon_big_item_micro_data_item">{{ $data['date_added'] }}</span>
                </div>
                <div class="ex_salon_big_item_name_age">
                    <div class="ex_salon_big_item_name">
                        <a href="{{ $data['link'] }}">{{ $data['title'] }}</a>
                    </div>
                </div>
                <div class="ex_salon_big_item_location">
                    <svg class="ex_salon_big_item_icon_location">
                        <use xlink:href="#icon-menu-location"></use>
                    </svg>
                    <span><a href="{{ $data['city_link'] }}">{{ $data['city'] }}</a>, {{ $data['address'] }}</span>
                </div>

                <div class="ex_salon_big_item_prc_double">
                    <div class="ex_salon_big_item_prc_light">
                        <div class="ex_salon_big_item_prc_light_header">
                            <img src="{{ url('/images/icons/time/day.svg') }}" alt="{{ __('catalog/posts/post.day') }}" title="{{ __('catalog/posts/post.day') }}" class="ex_salon_big_item_prc_light_image">
                            <div class="ex_salon_big_item_prc_hour">{{ __('catalog/posts/post.item_hour_one_s') }}</div>
                        </div>
                        <div class="ex_salon_big_item_prc_light_bottom"><span>{{ __('catalog/posts/post.item_hour_we_have') }}</span><strong>{{ $data['price_day_in_one'] ?? '---' }}</strong></div>
                        <div class="ex_salon_big_item_prc_light_bottom"><span>{{ __('catalog/posts/post.item_hour_you_have') }}</span><strong>{{ $data['price_day_out_one'] ?? '---' }}</strong></div>
                    </div>
                    <div class="ex_salon_big_item_prc_light">
                        <div class="ex_salon_big_item_prc_light_header">
                            <img src="{{ url('/images/icons/time/day.svg') }}" alt="{{ __('catalog/posts/post.day') }}" title="{{ __('catalog/posts/post.day') }}" class="ex_salon_big_item_prc_light_image">
                            <div class="ex_salon_big_item_prc_hour">{{ __('catalog/posts/post.item_hour_two_s') }}</div>
                        </div>
                        <div class="ex_salon_big_item_prc_light_bottom"><span>{{ __('catalog/posts/post.item_hour_we_have') }}</span><strong>{{ $data['price_day_in_two'] ?? '---' }}</strong></div>
                        <div class="ex_salon_big_item_prc_light_bottom"><span>{{ __('catalog/posts/post.item_hour_you_have') }}</span><strong>{{ $data['price_day_out_two'] ?? '---' }}</strong></div>
                    </div>
                </div>
                <div class="ex_salon_big_item_prc_double">
                    <div class="ex_salon_big_item_prc_night">
                        <div class="ex_salon_big_item_prc_night_header">
                            <img src="{{ url('/images/icons/time/night_one.svg') }}" alt="{{ __('catalog/posts/post.item_hour_one_s') }}" title="{{ __('catalog/posts/post.item_hour_one_s') }}" class="ex_salon_big_item_prc_night_image">
                            <div class="ex_salon_big_item_prc_hour">{{ __('catalog/posts/post.item_hour_one_s') }}</div>
                        </div>
                        <div class="ex_salon_big_item_prc_night_bottom"><span>{{ __('catalog/posts/post.item_hour_we_have') }}</span><strong>{{ $data['price_night_in_one'] ?? '---' }}</strong></div>
                        <div class="ex_salon_big_item_prc_night_bottom"><span>{{ __('catalog/posts/post.item_hour_you_have') }}</span><strong>{{ $data['price_night_out_one'] ?? '---' }}</strong></div>
                    </div>
                    <div class="ex_salon_big_item_prc_night">
                        <div class="ex_salon_big_item_prc_night_header">
                            <img src="{{ url('/images/icons/time/night_two.svg') }}" alt="{{ __('catalog/posts/post.item_hour_night') }}" title="{{ __('catalog/posts/post.item_hour_night') }}" class="ex_salon_big_item_prc_night_image">
                            <div class="ex_salon_big_item_prc_hour">{{ __('catalog/posts/post.item_hour_night') }}</div>
                        </div>
                        <div class="ex_salon_big_item_prc_night_bottom"><span>{{ __('catalog/posts/post.item_hour_we_have') }}</span><strong>{{ $data['price_night_in_night'] ?? '---' }}</strong></div>
                        <div class="ex_salon_big_item_prc_night_bottom"><span>{{ __('catalog/posts/post.item_hour_you_have') }}</span><strong>{{ $data['price_night_out_night'] ?? '---' }}</strong></div>
                    </div>
                </div>
                <div class="ex_salon_big_item_description">{{ $data['desc'] }}</div>
            </div>
            <div class="ex_salon_big_item_info_block_bottom">
                <a href="{{ $data['link'] }}" class="ex_salon_big_item_button_contact">{{ __('catalog/posts/post.item_details') }}</a>
            </div>
        </div>
    </div>
</div>
