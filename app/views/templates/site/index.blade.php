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
            <a href="" class="btn-popup btn" data-href="enter">Принять участие в конкурсе</a>
            {{ $page->block('second_section') }}
            <p>До окончания регистрации в конкурсе осталось 27 дней.</p>
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
            @foreach(User::all() as $user)
                <div class="unit">
                    <div class="img"><img src="http://lorempixel.com/200/200/people/?5" alt=""></div>
                    <div class="name">
                        Мылтыхян<br> Саша
                    </div>
                    <div class="location">
                        Ростов-на-Дону
                    </div>
                    <div class="rating">
                        <span class="icon2-star"></span>

                        <div class="count">34</div>
                        <div class="legend"></div>
                    </div>
                    <a href="" class="vote">Проголосовать</a>
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
            <a href="" class="btn-popup btn" data-href="enter">Принять участие в конкурсе</a>
            {{ $page->block('six_section') }}
            <p>
                До окончания регистрации в конкурсе осталось 27 дней.
            </p>
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
            <div class="unit video best">
                <a href="#" class="wrapper">
                    <div class="frame">
                        <div class="play">
                            <span class="icon-play"></span>
                        </div>
                        <img src="{{ asset(Config::get('site.theme_path')) }}/img/tmp-video.jpg" alt="">

                        <div class="name">ДУДНАКОВА ЕЛЕНА</div>
                        <div class="location">Краснодар</div>
                        <div class="rating">
                            <span class="icon2-star"></span>

                            <div class="count">34</div>
                            <div class="legend"> голоса</div>
                        </div>
                    </div>
                </a>
                <a href="a" class="all">Посмотреть другие видео</a>
            </div>
        </div>
    </div>
@stop
@section('scripts')
@stop