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
                <div class="gallery">
                    @if(isset($news->meta->gallery->photos) && count($news->meta->gallery->photos))
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
                    @endif
                    <div class="text">
                        {{ $news->meta->preview }}
                        {{ $news->meta->content }}
                        <script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script>
                        <div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="link"
                             data-yashareQuickServices="vkontakte,facebook,odnoklassniki"></div>
                    </div>
                </div>

            </div>
        </div>
    </main>
@stop
@section('scripts')
@stop