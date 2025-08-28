<div class="col-12 col-md-6 col-xl-4">
    <div class="ex_news_big">
        <div class="ex_news_big_block_image">
            @if($data['pinned']) <svg class="ex_news_big_block_pinned"><use xlink:href="#icon-pinned"></use></svg> @endif
            <a href="{{ $data['link'] }}">
                <img src="{{ $data['image'] }}" alt="{{ $data['title'] }}" class="ex_news_big_image">
            </a>
        </div>
        <div class="ex_news_big_info_block">
            <div class="ex_news_big_info_block_top">
                <div class="ex_news_big_micro_data">
                    <span class="ex_news_big_micro_data_item">{{ $data['date_added'] }}</span>
                </div>
                <div class="ex_news_big_name_age">
                    <div class="ex_news_big_name">
                        <a href="{{ $data['link'] }}">{{ $data['title'] }}</a>
                    </div>
                </div>
                <div class="ex_news_big_description">{!! $data['desc'] !!}</div>
            </div>
            <div class="ex_news_big_info_block_bottom">
                <a href="{{ $data['link'] }}" class="ex_news_big_button_contact">{{ __('catalog/posts/post.item_details') }}</a>
            </div>
        </div>
    </div>
</div>
