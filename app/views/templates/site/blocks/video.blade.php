<a href="javascript:void(0);" data-href="video" data-name="{{ $video->name }}" data-location="{{ $video->location }}"
   data-user-id="{{ $video->id }}" data-vote-count="{{ count($video->likes) }}"
   data-vote-url="{{ URL::route('participant.public.set.like', $video->id) }}" data-src="{{{ $video->video }}}"
   class="wrapper btn-popup">
    <div class="frame">
        <div class="play">
            <span class="icon-play"></span>
        </div>
        @if(!empty($video->video_thumb))
            <img src="{{ $video->video_thumb }}" alt="">
        @endif
        <div class="name">{{ $video->name }}</div>
        <div class="location">{{ $video->location }}</div>
        <div class="rating">
            <span class="icon2-star"></span>

            <div class="count">{{ count($video->likes) }}</div>
            <div class="legend">{{ Lang::choice('голос|голоса|голосов', (int)count($video->likes) ) }}</div>
        </div>
    </div>
</a>