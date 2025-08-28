<div class="ex_post_page_block">
    @if($data['allow_post_help'] == 1)
        @if($data['post_activation_status'])
            <div class="ex_post_inline_title mb-2 ex-text-main-color">{{ __('catalog/posts/post.service_action_btns') }}</div>
            <div class="ex_all_tags_list mb-3">
                @foreach($data['activation_variable'] as $variable)
                    <button type="button" class="ex_post_services_action_btn" onclick="postActivation({{ $variable['day'] }}, {{ $data['post']['id'] }});"><span></span><span>{{ $data['btn_activation_prefix'].$variable['price'] }}</span></button>
                @endforeach
            </div>
        @endif
        <div class="ex_post_inline_title mb-2 ex-text-main-color">{{ __('catalog/posts/post.service_services_btns') }}</div>
        <div class="ex_all_tags_list">
            @if($data['post']['position'] >= 2 && $data['up_to_top_btn'])
                <button type="button" class="ex_post_services_action_btn top" onclick="postUpToTop({{ $data['post']['id'] }});"><span></span><span>{{ $data['up_to_top_btn'] }}</span></button>
            @endif
            <button type="button" class="ex_post_services_action_btn diamond" onclick="postServiceDiamond({{ $data['post']['id'] }});"><span></span><span>{{ $data['diamond_btn'] }}</span></button>
            <button type="button" class="ex_post_services_action_btn vip" onclick="postServiceVip({{ $data['post']['id'] }});"><span></span><span>{{ $data['vip_btn'] }}</span></button>
            <button type="button" class="ex_post_services_action_btn color" onclick="postServiceColor({{ $data['post']['id'] }});"><span></span><span>{{ $data['color_btn'] }}</span></button>
        </div>
    @endif
    <div class="ex_all_tags_list my-2">
        <button type="button" class="ex_post_services_action_btn delete" data-bs-toggle="modal" data-bs-target="#delete_post"><span></span><span>{{ __('catalog/posts/post.post_delete') }}</span></button>
    </div>
</div>
