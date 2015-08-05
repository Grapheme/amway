<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
@extends(Helper::layout())
@section('style')
    <link rel="stylesheet" href="//cdn.jsdelivr.net/bxslider/4.2.5/jquery.bxslider.css">
@stop
@section('content')
    <main>
        <div class="row grey">
            <div class="holder">
                <h1>{{ $news->meta->title }}</h1>
                <div class="note">
                    {{ (new myDateTime())->setDateString($news->published_at)->custom_format('d M Y') }}
                </div>
                @if($news->meta->gallery->photos->count())
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
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </main>
@stop
@section('scripts')
    <script src="//cdn.jsdelivr.net/bxslider/4.2.5/jquery.bxslider.min.js"></script>
@stop