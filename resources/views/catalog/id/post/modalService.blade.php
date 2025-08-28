<div class="ex_post_modal_statuses">
    @if($data['post']['publish'] && $data['post_activation_status'])
        <div class="ex_post_modal_status activation">{{ $data['post']['publish_date'] }}</div>
    @endif
    @if($data['post']['publish'])
        <div class="ex_post_modal_status top">TOP: {{ __('catalog/id/post.position', ['num' => $data['post']['position']]) }}</div>
    @endif
    <div class="ex_post_modal_status diamond">Diamond: {{ ($data['post']['diamond']) ? $data['post']['diamond_date'] : __('catalog/id/post.status_service_off') }}</div>
    <div class="ex_post_modal_status vip">VIP: {{ ($data['post']['vip']) ? $data['post']['vip_date'] : __('catalog/id/post.status_service_off') }}</div>
    <div class="ex_post_modal_status color">Color: {{ ($data['post']['color']) ? $data['post']['color_date'] : __('catalog/id/post.status_service_off') }}</div>
</div>
@if($data['post_activation_status'])
<div class="ex_post_modal_action_title">{{ __('catalog/id/post.service_action_btns') }}</div>
<div class="ex_post_modal_actiones">
    @foreach($data['activation_variable'] as $variable)
        <button type="button" class="ex_post_modal_action_btn" onclick="postActivation({{ $variable['day'] }}, {{ $data['post']['id'] }});"><span></span><span>{{ $data['btn_activation_prefix'].$variable['price'] }}</span></button>
    @endforeach
</div>
@endif
@if($data['post']['publish'])
<div class="ex_post_modal_action_title">{{ __('catalog/id/post.service_services_btns') }}</div>
<div class="ex_post_modal_actiones">
    @if($data['post']['publish'] && $data['post']['position'] >= 2 && $data['up_to_top_btn'])
        <button type="button" class="ex_post_modal_action_btn top" onclick="postUpToTop({{ $data['post']['id'] }});"><span></span><span>{{ $data['up_to_top_btn'] }}</span></button>
    @endif
        <button type="button" class="ex_post_modal_action_btn diamond" onclick="postServiceDiamond({{ $data['post']['id'] }});"><span></span><span>{{ $data['diamond_btn'] }}</span></button>
        <button type="button" class="ex_post_modal_action_btn vip" onclick="postServiceVip({{ $data['post']['id'] }});"><span></span><span>{{ $data['vip_btn'] }}</span></button>
        <button type="button" class="ex_post_modal_action_btn color" onclick="postServiceColor({{ $data['post']['id'] }});"><span></span><span>{{ $data['color_btn'] }}</span></button>
</div>
@endif
