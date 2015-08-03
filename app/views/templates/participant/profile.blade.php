<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
@extends(Helper::layout())
@section('style')
@stop
@section('page_class') profile @stop
@section('content')
    <main>
        <div class="holder">
            <div class="photo">
            @if(!empty($profile->ulogin) && !empty($profile->ulogin->photo_big))
                <img src="{{ $profile->ulogin->photo_big }}" alt="{{ $profile->name }}" class="{{ $profile->name }}">
            @elseif(!empty($profile->photo) && File::exists(Config::get('site.galleries_photo_public_dir').'/'.$profile->photo))
                <img src="{{ asset(Config::get('site.galleries_photo_public_dir').'/'.$profile->photo) }}" alt="{{ $profile->name }}" class="{{ $profile->name }}">
            @endif
            </div>
            <div class="info">
                <div class="row">
                    <h3 class="name">{{ $profile->name }}</h3>
                    <a href="{{ URL::route('profile.edit') }}" class="edit">Редактировать профиль</a>
                    <a href="" class="exit btn-white">Выйти</a>
                </div>
                <div class="row">
                    <p class="location">Ростов-на-Дону</p>
                    <p class="age">29 лет</p>
                    <div class="rating">
                        <span class="icon2-star"></span>
                        <div class="count">34</div>
                        <div class="legend"></div>
                    </div>
                </div>

                <div class="row">
                    <a href="#" class="btn-big-red add-video">Добавить видео</a>
                    <input class="videoupload" type="file" name="video" data-url="server/php/" accept="video/*, video/x-flv, video/mp4, application/x-mpegURL, video/MP2T, video/3gpp, video/quicktime, video/x-msvideo, video/x-ms-wmv">
                </div>
                <div class="row" style="display:none;">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width:0%">
                            0%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@stop
@section('scripts')
@stop