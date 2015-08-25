<?
/**
* TITLE: Главная страница
* AVAILABLE_ONLY_IN_ADVANCED_MODE
*/
?>
<?php
$created = new Carbon(Config::get('site.date_over_action'));
$now = Carbon::now();
$difference = ($created->diff($now)->days < 1)
        ? 'Сегодня заканчивается регистрация в конкурсе.'
        : 'До окончания регистрации в конкурсе осталось '.$created->diffInDays($now).' '.Lang::choice('день|дня|дней', $created->diffInDays($now)).'.';
?>
<?php
$created = new Carbon(Config::get('site.date_final_action'));
$now = Carbon::now();
$difference_final = ($created->diff($now)->days < 1)
        ? 'Сегодня шоу-финал.'
        : 'До шоу-финала остается '.$created->diffInDays($now).' '.Lang::choice('день|дня|дней', $created->diffInDays($now)).'. Зарегистрируйся на сайте и покажи свой талант всем!'
?>
<?php
$map = array();
if (isset($page->blocks['map']['meta']['content']) && !empty($page->blocks['map']['meta']['content'])):
    $map = json_decode($page->blocks['map']['meta']['content'], TRUE);
endif;
?>
<?php
$participants = Accounts::where('group_id',4)->where('in_main_page', 1)->with('ulogin', 'likes')->take(15)->get();
foreach($participants as $index => $participant):
    $participants[$index]['like_disabled'] = FALSE;
endforeach;
if (isset($_COOKIE['votes_list'])):
    $users_ids = json_decode($_COOKIE['votes_list']);
    foreach($participants as $index => $participant):
        if (in_array($participant->id, $users_ids)):
            $participants[$index]['like_disabled'] = TRUE;
        endif;
    endforeach;
endif;
?>
@extends(Helper::layout())
@section('style')
@stop
@section('content')
    <section class="long color-blue video">
        <iframe data-src="https://player.vimeo.com/video/135698166?autoplay=1&loop=1&color=ffffff&title=0&byline=0&portrait=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        <div class="slides">
          <!-- <div class="slide" style="background-image: url('{{ asset(Config::get('site.theme_path')) }}/img/tmp-visual-1.jpg')"></div>
          <div class="slide" style="background-image: url('{{ asset(Config::get('site.theme_path')) }}/img/tmp-visual-2.jpg')"></div>
          <div class="slide" style="background-image: url('{{ asset(Config::get('site.theme_path')) }}/img/tmp-visual-3.jpg')"></div> -->
          <div class="slide" style="background-image: url('{{ asset(Config::get('site.theme_path')) }}/img/tmp-visual-11.jpg')"></div>
        </div>
        <div class="cover"></div>
        <div class="holder">
          {{ $page->block('first_section') }}
        </div>
    </section>
    @if(Auth::guest())
    <section class="color-green">
        <div class="cover"></div>
        <div class="holder">
            <a href="" class="btn-popup btn" data-href="enter" onclick="yaCounter31932671.reachGoal('reg_main_1'); return true;">Принять участие в конкурсе</a>
            {{ $page->block('second_section') }}
            <p>{{ $difference }}</p>
        </div>
    </section>
    @endif
    <div class="row grey">
        <div class="holder">
            <h3>Что нужно сделать?</h3>
            <h4>У тебя есть идеи и есть команда?</h4>
            <div class="units-3">
                <div class="unit">
                    <img src="http://agen-project.ru/theme/img/stage-1.png" alt="">
                    <div class="title">
                       &nbsp;
                    </div>
                    <p>Регистрируйся на сайте</p>
                </div>
                <div class="unit">
                    <img src="http://agen-project.ru/theme/img/stage-main-2.png" alt="">
                    <div class="title">
                        &nbsp;
                    </div>
                    <p>Заливай креативное видео с участниками твоей команды</p>
                </div>
                <div class="unit">
                    <img src="http://agen-project.ru/theme/img/stage-main-3.png" alt="">
                    <div class="title">
                        &nbsp;
                    </div>
                    <p>Набирай «голоса» и следи за новостями проекта</p>
                </div>
            </div>
            <h4 class="yellow">Твоя команда в проекте!</h4>

            <h4>У тебя есть идея и ты — творческая личность?</h4>
            <div class="units-3">
                <div class="unit">
                    <img src="http://agen-project.ru/theme/img/stage-main-4.png" alt="">
                    <div class="title">
                       &nbsp;
                    </div>
                    <p>Регистрируйся на сайте</p>
                </div>
                <div class="unit">
                    <img src="http://agen-project.ru/theme/img/stage-main-5.png" alt="">
                    <div class="title">
                        &nbsp;
                    </div>
                    <p>Заливай креативное видео, в котором покажи <br>свою идею</p>
                </div>
                <div class="unit">
                    <img src="http://agen-project.ru/theme/img/stage-main-6.png" alt="">
                    <div class="title">
                        &nbsp;
                    </div>
                    <p>Набирай «голоса»</p>
                </div>
            </div>
            <h4 class="orange">Приходи на кастинг!<sup>*</sup>
                <span><sup>*</sup>Следи за датой и временем кастинга в разделе «<a href="{{ pageurl('news') }}">Новости проекта</a>»</span>
            </h4>
        </div>
    </div>
    
    <!-- <section class="long color-red" style="background-image: url('{{ asset(Config::get('site.theme_path')) }}/img/tmp-visual-2.jpg')">
        <div class="cover"></div>
        <div class="holder">
            {{ $page->block('third_section') }}
        </div>
    </section> -->

    <!-- <div class="row grey">
      <div class="mentors">
        <div class="holder">
        <h3>Наставники</h3>
        <center>
          <div class="unit">
            <div class="img">
              <img src="{{ asset(Config::get('site.theme_path')) }}/img/pojarskaya.png" alt="">
            </div>
            <div class="name">
              Соня Пожарская
            </div>
            <div class="about">
              режиссер, продюссер, <nobr>шеф-редактор</nobr> Первого Канала, <nobr>экс-программный</nobr> директор Love Radio
            </div>
          </div>
          <div class="unit">
            <div class="img">
              <img src="{{ asset(Config::get('site.theme_path')) }}/img/balkov.png" alt="">
            </div>
            <div class="name">
              Антон Балков
            </div>
            <div class="about">
              Певец, полуфиналист фестиваля &laquo;Новая <nobr>Волна-2012</nobr>&raquo;, финалист телепроекта &laquo;СТС зажигает суперзвезду&raquo;
            </div>
          </div>
          <div class="unit">
            <div class="img">
              <img src="{{ asset(Config::get('site.theme_path')) }}/img/veda.png" alt="">
            </div>
            <div class="name">
              Натали Веда
            </div>
            <div class="about">
              ведущая, модель, <nobr>экс-ведущая</nobr> MTV, REN TV, ТНТ
            </div>
          </div>
          <div class="unit">
            <div class="img">
              <img src="{{ asset(Config::get('site.theme_path')) }}/img/polyakov.png" alt="">
            </div>
            <div class="name">
              Александр Поляков
            </div>
            <div class="about">
              <nobr>вице-чемпион</nobr> России, полуфиналист чемпионатов Мира и&nbsp;Европы, финалист кубков Мира и&nbsp;Европы, основатель школы танцев &laquo;Dance School&raquo;
            </div>
          </div>
          <div class="unit">
            <div class="img">
              <img src="{{ asset(Config::get('site.theme_path')) }}/img/krey.png" alt="">
            </div>
            <div class="name">
              Алекс Крэй
            </div>
            <div class="about">
              иллюзионист, финалист шоу &laquo;Феномен&raquo; на&nbsp;телеканале &laquo;Россия&raquo;
            </div>
          </div>
          <div class="unit">
            <div class="img">
              <img src="{{ asset(Config::get('site.theme_path')) }}/img/malahov.png" alt="">
            </div>
            <div class="name">
              Алексей Малахов
            </div>
            <div class="about">
              певец, победитель проекта &laquo;Битва хоров&raquo; в&nbsp;составе хора Краснодарского края
            </div>
          </div>
          <div class="unit">
            <div class="img">
              <img src="{{ asset(Config::get('site.theme_path')) }}/img/lepskaya.png" alt="">
            </div>
            <div class="name">
              Мирослава Лепская
            </div>
            <div class="about">
              стилист проекта, имиджмейкер
            </div>
          </div>
        </center>
      </div>
      </div>
    </div> -->

    <div class="competitors">
        <div class="holder">
            {{ $page->block('four_section') }}
            @foreach($participants as $user)
                @include(Helper::layout('blocks.user'), compact('user'))
            @endforeach
        </div>
    </div>
    <section class="long color-purple" style="background-image: url('{{ asset(Config::get('site.theme_path')) }}/img/tmp-visual-3.jpg')">
        <div class="cover"></div>
        <div class="holder">
            {{ $page->block('five_section') }}
            <p>{{ $difference_final }}</p>
        </div>
    </section>
    @if(Auth::guest())
    <section class="color-red">
        <div class="cover"></div>
        <div class="holder">
            <a href="" class="btn-popup btn" data-href="enter" onclick="yaCounter31932671.reachGoal('reg_main_2'); return true;">Принять участие в конкурсе</a>
            {{ $page->block('six_section') }}
            <p>{{ $difference }}</p>
        </div>
    </section>
    @endif
    <div class="news-grid">
        {{ $page->block('seven_section') }}
        <div class="holder">
            @if($news = News::where('publication' ,1)->orderBy('published_at','DESC')->with('meta.photo')->take(1)->skip(0)->first())
            <div class="unit photo">
                @include(Helper::layout('blocks.news'),compact('news'))
                <a href="{{ pageurl('news') }}" class="all">Посмотреть все репортажи</a>
            </div>
            @endif
            @if($news = News::where('publication' ,1)->orderBy('published_at','DESC')->with('meta.photo')->take(1)->skip(1)->first())
            <div class="unit photo">
                @include(Helper::layout('blocks.news'),compact('news'))
                <a href="{{ pageurl('news') }}" class="all">Посмотреть все репортажи</a>
            </div>
            @endif
            <!--<div class="unit map">
                <a href="{{ pageurl('news') }}" class="wrapper">
                    <div class="frame">
                        <img src="{{ asset(@$map['file_path']) }}" class="visual" alt="{{ @$map['title'] }}">
                        <div class="title">
                            {{ @$map['title'] }}
                        </div>
                    </div>
                </a>
                <a href="{{ pageurl('news') }}" class="all">БУДЬ ПЕРВЫМ В СВОЕМ ГОРОДЕ</a>
            </div> -->
            @if($video = Accounts::where('group_id' ,4)->where('load_video', 1)->where('video', '!=', '')->where('top_week_video', 1)->with('likes')->first())
            <div class="unit video best">
                @include(Helper::layout('blocks.video'), compact('video'))
                <a href="{{ pageurl('news') }}" class="all">Посмотреть другие видео</a>
            </div>
            @endif
        </div>
    </div>
@stop
@section('scripts')
@stop