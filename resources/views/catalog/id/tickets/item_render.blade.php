@if($data['exists'])
<div class="ex_ticket_item">
    <div class="ex_ticket_item_header">
        <div class="ex_ticket_item_name">{{ $data['name'] }}</div>
        <div class="ex_ticket_item_date">{{ $data['created_at'] }}</div>
    </div>
    <div class="ex_ticket_item_content">{!! $data['content'] !!}</div>
</div>
@endif
