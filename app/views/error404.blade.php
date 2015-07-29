@extends(Helper::layout())


@section('style')
@stop


@section('content')

    <main>
        <div class="slideshow">
            <div class="slide">
                <div class="slide-bg" style="background-image: url({{asset('theme/img/404bg.jpg')}});"></div>
                <section class="slide-cont" style="margin-top: 15.1%;">
                    <header>
                        <div class="slide-logo" style="background: url({{asset('theme/img/404.png')}}); width: 215px; height: 96px;">
                            
                        </div>
                        <h2 class="slide-head" style="margin-bottom: 0;">
                            ОШИБКА
                        </h2>
                        <div class="desc"></div>
                    </header>
                    <div class="slide-desc">
                        Запрашиваемая вами страница не существует.<br>
                        Вернитесь на <a href="/">главную</a>
                    </div>
                </section>
            </div>
            <div class="arrow arrow-left"><span class="icon icon-angle-left"></span></div>
            <div class="arrow arrow-right"><span class="icon icon-angle-right"></span></div>
        </div>
    </main>

@stop


@section('scripts')
@stop