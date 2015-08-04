<a href="javascript:void(0);" class="wrapper">
    @if(isset($news['third']))
        <div class="frame third">
            @if(!empty($news['third']['meta']['photo']) && File::exists(Config::get('site.galleries_photo_dir').'/'.$news['third']['meta']['photo']['name']))
                <img src="{{ asset(Config::get('site.galleries_photo_public_dir').'/'.$news['third']['meta']['photo']['name']) }}" class="visual" alt="{{ $news['third']['meta']['title'] }}">
            @endif
            <div class="title">
                {{ $news['third']['meta']['title'] }}
            </div>
            <div class="date">
                {{ (new myDateTime())->setDateString($news['third']['published_at'])->custom_format('d M Y') }}
            </div>
        </div>
    @endif
    @if(isset($news['second']))
        <div class="frame second">
            @if(!empty($news['second']['meta']['photo']) && File::exists(Config::get('site.galleries_photo_dir').'/'.$news['second']['meta']['photo']['name']))
                <img src="{{ asset(Config::get('site.galleries_photo_public_dir').'/'.$news['second']['meta']['photo']['name']) }}" class="visual" alt="{{ $news['second']['meta']['title'] }}">
            @endif
            <div class="title">
                {{ $news['second']['meta']['title'] }}
            </div>
            <div class="date">
                {{ (new myDateTime())->setDateString($news['second']['published_at'])->custom_format('d M Y') }}
            </div>
        </div>
    @endif
    @if(isset($news['first']))
        <div class="frame">
            @if(!empty($news['first']['meta']['photo']) && File::exists(Config::get('site.galleries_photo_dir').'/'.$news['first']['meta']['photo']['name']))
                <img src="{{ asset(Config::get('site.galleries_photo_public_dir').'/'.$news['first']['meta']['photo']['name']) }}" class="visual" alt="{{ $news['first']['meta']['title'] }}">
            @endif
            <div class="title">
                {{ $news['first']['meta']['title'] }}
            </div>
            <div class="date">
                {{ (new myDateTime())->setDateString($news['first']['published_at'])->custom_format('d M Y') }}
            </div>
        </div>
    @endif
</a>