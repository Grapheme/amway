<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
@extends(Helper::layout())
@section('style')
@stop
@section('content')
    <main>
        <div class="row grey">
            <div class="holder">
                <h1>{{ $news->meta->title }}</h1>
                <div class="note">
                    {{ (new myDateTime())->setDateString($news->published_at)->custom_format('d M Y') }}
                </div>
                @if(isset($news->meta->gallery->photos) && count($news->meta->gallery->photos))
                    <div class="gallery">
                        <ul class="bxslider">
                            @foreach($news->meta->gallery->photos as $photo)
                                @if(!empty($photo) && File::exists(Config::get('site.galleries_photo_dir').'/'.$photo->name))
                                    <li>
                                        <img src="{{ asset(Config::get('site.galleries_photo_public_dir').'/'.$photo->name) }}"
                                            alt="{{ $news->meta->title }}">
                                        <div class="desc">{{ $photo->title }}</div>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                        <div id="bx-pager">
                        @foreach($news->meta->gallery->photos as $index => $photo)
                            <a data-slide-index="{{ $index }}" href="">
                                <img src="{{ asset(Config::get('site.galleries_thumb_public_dir').'/'.$photo->name) }}"
                                     alt="{{ $news->meta->title }}">
                            </a>
                        @endforeach
                        </div>
                        <div class="text">
                            {{ $news->meta->content }}
                            <p>Поделись с друзьями:</p>
                            <script type="text/javascript">(function() {
                              if (window.pluso)if (typeof window.pluso.start == "function") return;
                              if (window.ifpluso==undefined) { window.ifpluso = 1;
                                var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
                                s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
                                s.src = ('https:' == window.location.protocol ? 'https' : 'http')  + '://share.pluso.ru/pluso-like.js';
                                var h=d[g]('body')[0];
                                h.appendChild(s);
                              }})();
                            </script>
                            <div class="pluso" data-background="transparent" data-options="big,round,line,horizontal,nocounter,theme=04" data-services="vkontakte,odnoklassniki,facebook,google,email"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </main>
@stop
@section('scripts')
@stop