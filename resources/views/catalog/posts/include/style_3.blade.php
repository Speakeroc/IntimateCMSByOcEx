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
<div class="col-6 col-md-6 col-xl-3">
    <a href="{{ $data['link'] }}" class="ex_post_small_item">
        <div class="ex_post_small_item_block_image {{ ($data['color']) ? 'color' : '' }}">
            <div class="ex_post_small_item_block_image_info">
                @if($data['photo'])<div class="ex_post_small_item_block_image_info_item"><i class="fas fa-images"></i></div>@endif
                @if($data['selfie'])<div class="ex_post_small_item_block_image_info_item"><i class="fas fa-image-portrait"></i></div>@endif
                @if($data['video'])<div class="ex_post_small_item_block_image_info_item"><i class="fas fa-video"></i></div>@endif
                @if($data['reviews'])<div class="ex_post_small_item_block_image_info_item"><i class="fas fa-comments"></i> {{ $data['reviews'] }}</div>@endif
            </div>
            <img src="{{ $data['image'] }}" alt="{{ $data['name'] }} {{ $data['age'] }}" title="{{ $data['name'] }} {{ $data['age'] }}" class="ex_post_small_item_image">
            @if($data['verify'])
                <img src="{{ url('/images/icons/verify.svg') }}" alt="{{ __('lang.photo_verify') }}" title="{{ __('lang.photo_verify') }}" class="ex_post_small_item_image_verify">
            @endif
            @if($status_class == 'vip')
                <div class="ex_post_small_item_block_name">{{ $data['name'] }} {{ $data['age'] }}
                    <div class="ex_post_small_item_block_status vip">VIP</div>
                </div>
            @elseif($status_class == 'diamond')
                <div class="ex_post_small_item_block_name">{{ $data['name'] }} {{ $data['age'] }}
                    <div class="ex_post_small_item_block_status diamond">Diamond</div>
                </div>
            @else
                <div class="ex_post_small_item_block_name">{{ $data['name'] }} {{ $data['age'] }}</div>
            @endif
        </div>
    </a>
    @if(!empty( $data['microdata']))
        <script type="application/ld+json">{!! $data['microdata'] !!}</script>
    @endif
</div>
