<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
<?php
$profile = Accounts::where('id', Auth::user()->id)->with('ulogin', 'likes')->first();
?>
@extends(Helper::layout())
@section('style')
@stop
@section('page_class') profile @stop
@section('content')
    <main>
        <div class="holder">
            <div class="photo">
            @if(!empty($profile->photo) && File::exists(public_path($profile->photo)))
                <img src="{{ asset($profile->photo) }}"
                     alt="{{ $profile->name }}" class="{{ $profile->name }}">
            @elseif(!empty($profile->ulogin) && !empty($profile->ulogin->photo_big))
                <img src="{{ $profile->ulogin->photo_big }}" alt="{{ $profile->name }}"
                     class="{{ $profile->name }}">
            @endif
            </div>
            <div class="info">
                <div class="row">
                    <h3 class="name">{{ $profile->name }}</h3>
                    <a href="{{ URL::route('profile.edit') }}" class="edit">Редактировать профиль</a>
                    <a href="{{ URL::route('logout') }}" class="exit btn-white">Выйти</a>
                </div>
                <div class="row">
                    <p class="location">{{ $profile->location }}</p>

                    <p class="age">{{ $profile->age }} {{ Lang::choice('год|года|лет', (int)$profile->age ) }}</p>

                    <div class="rating">
                        <span class="icon2-star"></span>
                        <div class="count">{{ $profile->likes->count() }}</div>
                        <div class="legend"></div>
                    </div>
                </div>
                @if(!Auth::user()->load_video)
                    <div class="row">
                        <a href="#" class="btn-big-red add-video">Добавить видео</a>
                        <input class="videoupload" type="file" name="video"
                               data-url="{{ URL::route('profile.video.upload') }}"
                               accept="video/*, video/x-flv, video/mp4, application/x-mpegURL, video/MP2T, video/3gpp, video/quicktime, video/x-msvideo, video/x-ms-wmv">
                    </div>
                    <div class="row" style="display:none;">
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width:0%">
                                0%
                            </div>
                        </div>
                    </div>
                @else
                    @if(Auth::user()->video != '')
                        <div class="row video">
                            <div class="video-holder">
                                <a href="#" data-video="{{{ Auth::user()->video }}}" class="video-preview" @if(!empty(Auth::user()->video_thumb))) style="background-image:url('{{  Auth::user()->video_thumb }}');" @endif>
                                    <div class="play">
                                        <span class="icon-play"></span>
                                    </div>
                                </a>
                                <div class="about">
                                    Добавлено {{ (new myDateTime())->setDateString(Auth::user()->local_video_date)->custom_format('d M Y') }}.
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <a href="#" class="btn-big-red add-video">Заменить видео</a>
                            <input class="videoupload" type="file" name="video"
                                   data-url="{{ URL::route('profile.video.upload') }}"
                                   accept="video/*, video/x-flv, video/mp4, application/x-mpegURL, video/MP2T, video/3gpp, video/quicktime, video/x-msvideo, video/x-ms-wmv">
                            <div class="note">
                                <p>
                                    Если вы недовольны своим видео, вы всегда можете заменить его на новое. Желаем вам
                                    победы!
                                </p>
                            </div>
                        </div>
                        <div class="row" style="display:none;">
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width:0%">
                                    0%
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row video loading">
                            <div class="video-holder">
                                <div class="video-preview"
                                     style="background-image:url('{{ asset(Config::get('site.theme_path')).'/img/video-loading.png' }}');">
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </main>
@stop
@section('scripts')
@stop