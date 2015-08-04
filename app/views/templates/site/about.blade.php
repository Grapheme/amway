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
<?php
$steps = array();
if (isset($page->blocks['etap_1']['meta']['content']) && !empty($page->blocks['etap_1']['meta']['content'])):
    $steps['etap_1'] = json_decode($page->blocks['etap_1']['meta']['content'], TRUE);
endif;
if (isset($page->blocks['etap_2']['meta']['content']) && !empty($page->blocks['etap_2']['meta']['content'])):
    $steps['etap_2'] = json_decode($page->blocks['etap_2']['meta']['content'], TRUE);
endif;
if (isset($page->blocks['etap_3']['meta']['content']) && !empty($page->blocks['etap_3']['meta']['content'])):
    $steps['etap_3'] = json_decode($page->blocks['etap_3']['meta']['content'], TRUE);
endif;
?>
<?php
$nominations = array();
if (isset($page->blocks['nom_1']['meta']['content']) && !empty($page->blocks['nom_1']['meta']['content'])):
    $nominations['nom_1'] = json_decode($page->blocks['nom_1']['meta']['content'], TRUE);
endif;
if (isset($page->blocks['nom_2']['meta']['content']) && !empty($page->blocks['nom_2']['meta']['content'])):
    $nominations['nom_2'] = json_decode($page->blocks['nom_2']['meta']['content'], TRUE);
endif;
if (isset($page->blocks['nom_3']['meta']['content']) && !empty($page->blocks['nom_3']['meta']['content'])):
    $nominations['nom_3'] = json_decode($page->blocks['nom_3']['meta']['content'], TRUE);
endif;
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
                @for($i = 1; $i <= 3; $i++)
                <div class="unit">
                    <img src="{{ asset(Config::get('site.theme_path')) }}/img/stage-{{ $i }}.png" alt="">
                    <div class="title">
                        {{ @$steps["etap_$i"]['period'] }}
                    </div>
                    <p>{{ @$steps["etap_$i"]['desc'] }}</p>
                </div>
                @endfor
            </div>
        </div>
        <div class="row grey">
            <div class="holder">
                <h3>Номинации</h3>
                <div class="units-3">
                @for($i = 1; $i <= 3; $i++)
                    <div class="unit">
                        <img src="{{ asset(Config::get('site.theme_path')) }}/img/award-{{ $i }}.png" alt="">
                        <div class="italic">
                            {{ @$steps["nom_$i"]['italic'] }}
                        </div>
                        <div class="title">
                            {{ @$steps["nom_$i"]['title'] }}
                        </div>
                        <p>{{ @$steps["nom_$i"]['desc'] }}</p>
                    </div>
                @endfor
                </div>
            </div>
        </div>
    </main>
@stop
@section('scripts')
@stop