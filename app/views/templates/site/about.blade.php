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
        : 'До окончания регистрации в конкурсе осталось ' . $created->diffInDays($now) . ' ' . Lang::choice('день|дня|дней', $created->diffInDays($now)) . '.';
?>
<?php
$steps = array();
if (isset($page['blocks']['etap_1'])):
    if (isset($page['blocks']['etap_1']['meta']['content']) && !empty($page['blocks']['etap_1']['meta']['content'])):
        $steps['etap_1'] = json_decode($page['blocks']['etap_1']['meta']['content'], TRUE);
    endif;
endif;
if (isset($page['blocks']['etap_2'])):
    if (isset($page['blocks']['etap_2']['meta']['content']) && !empty($page['blocks']['etap_2']['meta']['content'])):
        $steps['etap_2'] = json_decode($page['blocks']['etap_2']['meta']['content'], TRUE);
    endif;
endif;
if (isset($page['blocks']['etap_3'])):
    if (isset($page['blocks']['etap_3']['meta']['content']) && !empty($page['blocks']['etap_3']['meta']['content'])):
        $steps['etap_3'] = json_decode($page['blocks']['etap_3']['meta']['content'], TRUE);
    endif;
endif;
?>
<?php
$nominations = array();
if (isset($page['blocks']['nom_1'])):
    if (isset($page['blocks']['nom_1']['meta']['content']) && !empty($page['blocks']['nom_1']['meta']['content'])):
        $nominations['nom_1'] = json_decode($page['blocks']['nom_1']['meta']['content'], TRUE);
    endif;
endif;
if (isset($page['blocks']['nom_2'])):
    if (isset($page['blocks']['nom_2']['meta']['content']) && !empty($page['blocks']['nom_2']['meta']['content'])):
        $nominations['nom_2'] = json_decode($page['blocks']['nom_2']['meta']['content'], TRUE);
    endif;
endif;
if (isset($page['blocks']['nom_3'])):
    if (isset($page['blocks']['nom_3']['meta']['content']) && !empty($page['blocks']['nom_3']['meta']['content'])):
        $nominations['nom_3'] = json_decode($page['blocks']['nom_3']['meta']['content'], TRUE);
    endif;
endif;
?>
<?php
$map = array();
if (isset($page['blocks']['map'])):
    if (isset($page['blocks']['map']['meta']['content']) && !empty($page['blocks']['map']['meta']['content'])):
        $map = json_decode($page['blocks']['map']['meta']['content'], TRUE);
    endif;
endif;
?>
@extends(Helper::layout())
@section('style')
@stop
@section('content')
    <main>
        <section class="long video"
                 style="background-image: url('{{ asset(Config::get('site.theme_path')) }}/img/space-tmp.jpg')">
            <iframe data-src="https://player.vimeo.com/video/6382511?autoplay=1&loop=1&color=ffffff&title=0&byline=0&portrait=0"
                    frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
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
                    {{ $page->block('conditions') }}
                </div>
                <br>
                <br>
                <br>
            </div>
        </div>
        <div class="holder">
            <h3>{{ @$map['title'] }}</h3>
            <img src="{{ asset(@$map['file_path']) }}" style="width:100%;" alt="">
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
                                {{ @$nominations["nom_$i"]['italic'] }}
                            </div>
                            <div class="title">
                                {{ @$nominations["nom_$i"]['title'] }}
                            </div>
                            <p>{{ @$nominations["nom_$i"]['desc'] }}</p>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <div class="row">
            <div class="holder">
                <h3>Организатор конкурса</h3>

                <div class="center-text">
                    <img src="{{ asset(Config::get('site.theme_path')) }}/img/logo3.png" alt="">

                    <p>
                        Организацией конкурса является компания Amway, приоритетом которой всегда являлась внешняя и
                        внутренняя красота ее клиентов.
                    </p>

                    <p>
                        Компания занимается производством и продажей средств личной гигиены, бытовой химии,
                        косметических средств, биологически активных добавок к пище и др.
                    </p>
                </div>
                <br>

                <div class="note">
                    <a href="#" target="_blank">Официальный сайт компании Amway</a>
                </div>
                <br>
                <br>
                <br>
            </div>
            <br>
            <br>
            <br>

            <div class="col-2">
                <div>
                    <center>
                        <div class="img-holder">
                            <img src="{{ asset(Config::get('site.theme_path')) }}/img/nutrilite.png" alt="">
                        </div>
                        <br>
                        <br>

                        <p>
                            ARTISTRY&nbsp;&mdash; бренд &#8470;&nbsp;2 по&nbsp;объему продаж в&nbsp;категории косметики
                            по&nbsp;уходу за&nbsp;кожей
                        <nobr>премиум-класса</nobr>
                        в&nbsp;России в&nbsp;2012 году. ARTISTRY постоянно расширяет границы красоты. Мы&nbsp;черпаем
                        вдохновение из&nbsp;природы и&nbsp;постоянно подбираем новые растительные ингредиенты для своей
                        продукции.
                        </p>
                        <a href="#">Узнать больше</a>
                    </center>
                </div>
                <div>
                    <center>
                        <div class="img-holder">
                            <img src="{{ asset(Config::get('site.theme_path')) }}/img/artistry.png" alt="">
                        </div>
                        <br>
                        <br>

                        <p>
                            NUTRILITE&trade;&nbsp;&mdash; это единственный в&nbsp;мире бренд витаминов и&nbsp;минералов,
                            который выращивает, собирает и&nbsp;обрабатывает растения для своих продуктов на&nbsp;собственных
                            сертифицированных фермах органического земледелия.
                        </p>
                        <a href="#">Узнать больше</a>
                    </center>
                </div>
            </div>
        </div>
    </main>
@stop
@section('scripts')
@stop