<?
/**
* TITLE: Главная страница
* AVAILABLE_ONLY_IN_ADVANCED_MODE
*/
?>
@extends(Helper::layout())
@section('style')
@stop
@section('content')
    <section class="long color-blue" style="background-image: url('{{ asset(Config::get('site.theme_path')) }}/img/tmp-visual-1.jpg')"  >
        <div class="cover"></div>
        <div class="holder">
            {{ $page->block('first_section') }}
        </div>
    </section>
    <section class="color-green">
        <div class="cover"></div>
        <div class="holder">
            @if(Auth::guest())
            <a href="" class="btn-popup btn" data-href="enter">Принять участие в конкурсе</a>
            @endif
            {{ $page->block('second_section') }}
            <?php
            $created = new Carbon(Config::get('site.date_over_action'));
            $now = Carbon::now();
            $difference = ($created->diff($now)->days < 1)
                    ? 'Сегодня заканчивается регистраця в конкурсе.'
                    : 'До окончания регистрации в конкурсе осталось '.$created->diffInDays($now).' '.Lang::choice('день|дня|дней', $created->diffInDays($now)).'.';
            ?>
            <p>{{ $difference }}</p>
        </div>
    </section>
    <section class="long color-yellow" style="background-image: url('{{ asset(Config::get('site.theme_path')) }}/img/tmp-visual-2.jpg')">
        <div class="cover"></div>
        <div class="holder">
            {{ $page->block('third_section') }}
        </div>
    </section>
    <div class="competitors">
        <div class="holder">
            {{ $page->block('four_section') }}
            @foreach(Accounts::where('group_id',4)->where('in_main_page', 1)->with('ulogin', 'likes')->get() as $user)
                <div class="unit">
                    <div class="img">
                    @if(!empty($user->ulogin) && !empty($user->ulogin->photo_big))
                        <img src="{{ $user->ulogin->photo_big }}" alt="{{ $user->name }}"
                             class="{{ $user->name }}">
                    @elseif(!empty($profile->photo) && File::exists(Config::get('site.galleries_photo_public_dir').'/'.$user->photo))
                        <img src="{{ asset(Config::get('site.galleries_photo_public_dir').'/'.$user->photo) }}"
                             alt="{{ $user->name }}" class="{{ $user->name }}">
                    @endif
                    </div>
                    <div class="name">
                        {{ $user->name }}
                    </div>
                    <div class="location">
                        {{ $user->location }}
                    </div>
                    <div class="rating">
                        <span class="icon2-star"></span>
                        <div class="count">{{ count($user->likes) }}</div>
                        <div class="legend">{{ Lang::choice('голос|голоса|голосов', (int)count($user->likes) ) }}</div>
                    </div>
                    <a href="{{ URL::route('participant.public.set.like',$user->id) }}" class="vote">Проголосовать</a>
                </div>
            @endforeach
        </div>
    </div>
    <section class="long color-purple" style="background-image: url('{{ asset(Config::get('site.theme_path')) }}/img/tmp-visual-3.jpg')">
        <div class="cover"></div>
        <div class="holder">
            {{ $page->block('five_section') }}
        </div>
    </section>
    <section class="color-red">
        <div class="cover"></div>
        <div class="holder">
            @if(Auth::guest())
            <a href="" class="btn-popup btn" data-href="enter">Принять участие в конкурсе</a>
            @endif
            {{ $page->block('six_section') }}
            <p>{{ $difference }}</p>
        </div>
    </section>
    <div class="main-news">
        {{ $page->block('seven_section') }}
        <div class="holder">
            <div class="unit photo">
                <a href="#" class="wrapper">
                    <div class="frame third">
                        <img src="{{ asset(Config::get('site.theme_path')) }}/img/tmp-visual-6.jpg" class="visual" alt="">

                        <div class="title">
                            Фоторепортаж из Воронежа
                        </div>
                        <div class="date">
                            3 августа 2015
                        </div>
                    </div>
                    <div class="frame second">
                        <img src="{{ asset(Config::get('site.theme_path')) }}/img/tmp-visual-5.jpg" class="visual" alt="">

                        <div class="title">
                            Фоторепортаж из Воронежа
                        </div>
                        <div class="date">
                            3 августа 2015
                        </div>
                    </div>
                    <div class="frame">
                        <img src="{{ asset(Config::get('site.theme_path')) }}/img/tmp-visual-4.jpg" class="visual" alt="">

                        <div class="title">
                            Фоторепортаж из Воронежа
                        </div>
                        <div class="date">
                            3 августа 2015
                        </div>
                    </div>
                </a>
                <a href="a" class="all">Посмотреть все репортажи</a>
            </div>
            <div class="unit map">
                <a href="#" class="wrapper">
                    <div class="frame">
                        <img src="{{ asset(Config::get('site.theme_path')) }}/img/map.png" class="visual" alt="">

                        <div class="title">
                            График перемещение мобильной команды
                        </div>
                    </div>
                </a>
                <a href="a" class="all">БУДЬ ПЕРВЫМ В СВОЕМ ГОРОДЕ</a>
            </div>
            @if($week_video = Accounts::where('group_id' ,4)->where('load_video', 1)->where('video', '!=', '')->where('top_week_video', 1)->with('likes')->first())
            <div class="unit video best">
                <a href="#" class="wrapper">
                    <div class="frame">
                        <div class="play">
                            <span class="icon-play"></span>
                        </div>
                        {{ $week_video->video }}
{{--                        <img src="{{ asset(Config::get('site.theme_path')) }}/img/tmp-video.jpg" alt="">--}}
                        <div class="name">{{ $week_video->name }}</div>
                        <div class="location">{{ $week_video->location }}</div>
                        <div class="rating">
                            <span class="icon2-star"></span>
                            <div class="count">{{ count($week_video->likes) }}</div>
                            <div class="legend">{{ Lang::choice('голос|голоса|голосов', (int)count($week_video->likes) ) }}</div>
                        </div>
                    </div>
                </a>
                <a href="javascript:void(0);" class="all">Посмотреть другие видео</a>
            </div>
            @endif
        </div>
    </div>
@stop
@section('scripts')
@stop