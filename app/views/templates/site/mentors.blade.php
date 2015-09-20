<?
/**
 * TITLE: Наставники
 * AVAILABLE_ONLY_IN_ADVANCED_MODE
 */
?>
@extends(Helper::layout())
@section('style')
@stop
@section('page_class')sticky
@stop
@section('content')
    <main class="mentors-list">
        <section class="color-green mid">
            <div class="cover"></div>
            <div class="holder">
                <h2>Будь лучшим. Учись у лучших.</h2>

                <p>
                    Наставники проекта #agenproject помогут раскрыть ваш талант и подготовить лучшее выступление на шоу-финале в Вертол-Экспо 24 октября.
                </p>
            </div>
        </section>
        <div class="holder">
            <h3>{{ $page->seo->h1 }}</h3>

            <div class="units-3 units-4">
                <div class="unit"><img src="{{ asset(Config::get('site.theme_path').'/img/mentor-1.jpg') }}" alt=""> </div>
                <div class="unit"><img src="{{ asset(Config::get('site.theme_path').'/img/mentor-3.jpg') }}" alt=""> </div>                
                <div class="unit"><img src="{{ asset(Config::get('site.theme_path').'/img/mentor-4.jpg') }}" alt=""> </div>
                <div class="unit"><img src="{{ asset(Config::get('site.theme_path').'/img/mentor-6.jpeg') }}" alt=""> </div>                
                <div class="unit"><img src="{{ asset(Config::get('site.theme_path').'/img/mentor-2.jpg') }}" alt=""> </div>  
                <div class="unit"><img src="{{ asset(Config::get('site.theme_path').'/img/mentor-5.jpg') }}" alt=""> </div>
                <!-- <div class="unit"><img src="{{ asset(Config::get('site.theme_path').'/img/mentor-7.jpeg') }}" alt=""> </div> -->
            </div>
            <h3>Видео-обращения наставников</h3>

            <div class="units-3">
                <div class="unit">
                    <iframe src="https://player.vimeo.com/video/138072183?title=0&byline=0&portrait=0" width="320"
                            height="180" frameborder="0" webkitallowfullscreen mozallowfullscreen
                            allowfullscreen></iframe>
                </div>
                <div class="unit">
                    <iframe src="https://player.vimeo.com/video/137256707?title=0&byline=0&portrait=0" width="320"
                            height="180" frameborder="0" webkitallowfullscreen mozallowfullscreen
                            allowfullscreen></iframe>
                </div>
                <div class="unit">
                    <iframe src="https://player.vimeo.com/video/137017831?title=0&byline=0&portrait=0" width="320"
                            height="180" frameborder="0" webkitallowfullscreen mozallowfullscreen
                            allowfullscreen></iframe>
                </div>
                <div class="unit">
                    <iframe src="https://player.vimeo.com/video/137017821?title=0&byline=0&portrait=0" width="320"
                            height="180" frameborder="0" webkitallowfullscreen mozallowfullscreen
                            allowfullscreen></iframe>
                </div>
                <div class="unit">
                    <iframe src="https://player.vimeo.com/video/137017809?title=0&byline=0&portrait=0" width="320"
                            height="180" frameborder="0" webkitallowfullscreen mozallowfullscreen
                            allowfullscreen></iframe>
                </div>
            </div>
        </div>

    </main>
@stop
@section('scripts')
@stop