@if($news)
    <a href="#" class="wrapper">
        <div class="frame third">
            <img src="{{  asset(Config::get('site.theme_path')) }}/img/tmp-visual-6.jpg" class="visual" alt="">
            <div class="title">Фоторепортаж из Воронежа</div>
            <div class="date">3 августа 2015</div>
        </div>
        <div class="frame second">
            <img src="{{  asset(Config::get('site.theme_path')) }}/img/tmp-visual-5.jpg" class="visual" alt="">
            <div class="title">Фоторепортаж из Воронежа</div>
            <div class="date">3 августа 2015</div>
        </div>
        <div class="frame">
        @if(!empty($news['meta']['photo']) && File::exists(Config::get('site.galleries_photo_dir').'/'.$news['meta']['photo']['name']))
            <img src="{{ asset(Config::get('site.galleries_photo_public_dir').'/'.$news['meta']['photo']['name']) }}"
                 class="visual" alt="{{ $news['meta']['title'] }}">
        @endif
            <div class="title">{{ $news['meta']['title'] }}</div>
            <div class="date">{{ (new myDateTime())->setDateString($news['published_at'])->custom_format('d M Y') }}</div>
        </div>
    </a>
@endif