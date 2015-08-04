<a href="javascript:void(0);" class="wrapper">
    <div class="frame">
        <div class="play">
            <span class="icon-play"></span>
        </div>
        {{-- $video->video --}}
        <img src="{{ asset(Config::get('site.theme_path')) }}/img/tmp-video.jpg" alt="">
        <div class="name">{{ $video->name }}</div>
        <div class="location">{{ $video->location }}</div>
        <div class="rating">
            <span class="icon2-star"></span>
            <div class="count">{{ count($video->likes) }}</div>
            <div class="legend">{{ Lang::choice('голос|голоса|голосов', (int)count($video->likes) ) }}</div>
        </div>
    </div>
</a>