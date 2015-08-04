<?
/**
 * TITLE: О проекте
 * AVAILABLE_ONLY_IN_ADVANCED_MODE
 */
?>
<?php
$created = new Carbon(Config::get('site.date_over_action'));
$now = Carbon::now();
$difference = ($created->diff($now)->days < 1)
        ? 'Сегодня заканчивается регистраця в конкурсе.'
        : 'До окончания регистрации в конкурсе осталось '.$created->diffInDays($now).' '.Lang::choice('день|дня|дней', $created->diffInDays($now)).'.';
?>
@extends(Helper::layout())
@section('style')
@stop
@section('content')
    <main>
        <section class="long" style="background-image: url('{{ asset(Config::get('site.theme_path')) }}/img/space-tmp.jpg')">
            <div class="cover"></div>
            <div class="holder">
                {{ $page->block('first_section') }}
            </div>
        </section>
        @if(Auth::guest())
        <section class="color-green">
            <div class="cover"></div>
            <div class="holder">
                <a href="" class="btn-popup btn" data-href="enter">Принять участие в конкурсе</a>
                <p>{{ $difference }}</p>
            </div>
        </section>
        @endif
        <div class="holder">
            <h3>{{ $page->seo->h1 }}</h3>
            <div class="col-2">
                {{ $page->block('second_section') }}
            </div>
        </div>
        <div class="row grey">
            <div class="holder">
                <h3>КАК ПРИНЯТЬ УЧАСТИЕ</h3>
                <div class="center-text">
                    {{ $page->block('third_section') }}
                </div>
                <br>
                <div class="note">
                    <a href="#">Полные условия проведения конкурса.</a> (pdf, 234 кб)
                </div>
                <br>
                <br>
                <br>
            </div>
        </div>
        <div class="holder">
            <h3>ГОРОДА УЧАСТНИКИ</h3>
            <img src="{{ asset(Config::get('site.theme_path')) }}/img/map.jpg" style="width:100%;" alt="">
        </div>
        <div class="holder">
            <h3>Этапы конкурса</h3>

            <div class="units-3">
                <div class="unit">
                    <img src="{{ asset(Config::get('site.theme_path')) }}/img/stage-1.png" alt="">

                    <div class="title">
                        8 августа — 31 августа
                    </div>
                    <p>Регистрация участников конкурса</p>
                </div>
                <div class="unit">
                    <img src="{{ asset(Config::get('site.theme_path')) }}/img/stage-2.png" alt="">

                    <div class="title">
                        1 сентября — 18 октября
                    </div>
                    <p>
                        Формирование команд
                        и&nbsp;подготовка к шоу
                    </p>
                </div>
                <div class="unit">
                    <img src="{{ asset(Config::get('site.theme_path')) }}/img/stage-3.png" alt="">

                    <div class="title">
                        24 октября
                    </div>
                    <p>
                        Гала-концерт
                        на большой сцене
                    </p>
                </div>
            </div>
        </div>
        <div class="row grey">
            <div class="holder">
                <h3>Номинации</h3>

                <div class="units-3">
                    <div class="unit">
                        <img src="{{ asset(Config::get('site.theme_path')) }}/img/award-1.png" alt="">

                        <div class="italic">
                            Artistry
                        </div>
                        <div class="title">
                            КРАСОТА СНАРЖИ
                        </div>
                        <p>
                            Вручается за активное участие в конкурсе
                        </p>
                    </div>
                    <div class="unit">
                        <img src="{{ asset(Config::get('site.theme_path')) }}/img/award-2.png" alt="">

                        <div class="italic">
                            Nutrilite
                        </div>
                        <div class="title">
                            КРАСОТА ВНУТРИ
                        </div>
                        <p>
                            Вручается за активное участие в конкурсе
                        </p>
                    </div>
                    <div class="unit">
                        <img src="{{ asset(Config::get('site.theme_path')) }}/img/award-3.png" alt="">

                        <div class="italic">
                            Artistry&Nutrilite
                        </div>
                        <div class="title">
                            КРАСОТА СНАРУЖИ<br>
                            И ВНУТРИ
                        </div>
                        <p>
                            Вручается за активное участие в конкурсе
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>
@stop
@section('scripts')
@stop