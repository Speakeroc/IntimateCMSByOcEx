<a href="{{ (!empty($data['link'])) ? $data['link'] : $data['post'] }}" target="_blank" class="ex_post_banner_link">
    <img src="{{ $data['banner'] }}" alt="{{ (!empty($data['link'])) ? 'link' : $data['name'].' '.$data['age'] }}" title="{{ (!empty($data['link'])) ? 'link' : $data['name'].' '.$data['age'] }}" class="ex_post_banner_image lazy">
</a>
