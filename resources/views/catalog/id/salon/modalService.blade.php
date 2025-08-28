<div class="ex_post_modal_statuses">
    @if($data['salon']['publish'] && $data['salon_activation_status'])
        <div class="ex_post_modal_status activation">{{ $data['salon']['publish_date'] }}</div>
    @endif
    @if($data['salon']['publish'])
        <div class="ex_post_modal_status top">TOP: {{ __('catalog/id/post.position', ['num' => $data['salon']['position']]) }}</div>
    @endif
</div>
@if($data['salon_activation_status'])
<div class="ex_post_modal_action_title">{{ __('catalog/id/post.service_action_btns') }}</div>
<div class="ex_post_modal_actiones">
    @foreach($data['activation_variable'] as $variable)
        <button type="button" class="ex_post_modal_action_btn" onclick="salonActivation({{ $variable['day'] }}, {{ $data['salon']['id'] }});"><span></span><span>{{ $data['btn_activation_prefix'].$variable['price'] }}</span></button>
    @endforeach
</div>
@endif
@if($data['salon']['publish'])
    @if($data['salon']['publish'] && $data['salon']['position'] >= 2)
        <div class="ex_post_modal_action_title">{{ __('catalog/id/post.service_services_btns') }}</div>
        <div class="ex_post_modal_actiones">
            <button type="button" class="ex_post_modal_action_btn top" onclick="salonUpToTop({{ $data['salon']['id'] }});"><span></span><span>{{ $data['up_to_top_btn'] }}</span></button>
        </div>
    @endif
@endif
