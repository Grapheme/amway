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
        ? 'Сегодня заканчивается регистраця в конкурсе.'
        : 'До окончания регистрации в конкурсе осталось '.$created->diffInDays($now).' '.Lang::choice('день|дня|дней', $created->diffInDays($now)).'.';
?>
<?php
$map = array();
if (isset($page->blocks['map']['meta']['content']) && !empty($page->blocks['map']['meta']['content'])):
    $map = json_decode($page->blocks['map']['meta']['content'], TRUE);
endif;
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
    @if(Auth::guest())
    <section class="color-green">
        <div class="cover"></div>
        <div class="holder">
            <a href="" class="btn-popup btn" data-href="enter">Принять участие в конкурсе</a>
            {{ $page->block('second_section') }}
            <p>{{ $difference }}</p>
        </div>
    </section>
    @endif
    <section class="long color-yellow" style="background-image: url('{{ asset(Config::get('site.theme_path')) }}/img/tmp-visual-2.jpg')">
        <div class="cover"></div>
        <div class="holder">
            {{ $page->block('third_section') }}
        </div>
    </section>
    <div class="competitors">
        <div class="holder">
            {{ $page->block('four_section') }}
            @foreach(Accounts::where('group_id',4)->where('in_main_page', 1)->with('ulogin', 'likes')->take(6)->get() as $user)
                @include(Helper::layout('blocks.user'), compact('user'))
            @endforeach
        </div>
    </div>
    <section class="long color-purple" style="background-image: url('{{ asset(Config::get('site.theme_path')) }}/img/tmp-visual-3.jpg')">
        <div class="cover"></div>
        <div class="holder">
            {{ $page->block('five_section') }}
        </div>
    </section>
    @if(Auth::guest())
    <section class="color-red">
        <div class="cover"></div>
        <div class="holder">
            <a href="" class="btn-popup btn" data-href="enter">Принять участие в конкурсе</a>
            {{ $page->block('six_section') }}
            <p>{{ $difference }}</p>
        </div>
    </section>
    @endif
    <div class="news-grid">
        {{ $page->block('seven_section') }}
        <div class="holder">
            @if($news = News::where('publication' ,1)->orderBy('published_at','DESC')->with('meta.photo')->first())
            <div class="unit photo">
                @include(Helper::layout('blocks.news'),compact('news'))
                <a href="{{ pageurl('news') }}" class="all">Посмотреть все репортажи</a>
            </div>
            @endif
            <div class="unit map">
                <a href="#" class="wrapper">
                    <div class="frame">
                        <img src="{{ asset(@$map['file_path']) }}" class="visual" alt="{{ @$map['title'] }}">
                        <div class="title">
                            {{ @$map['title'] }}
                        </div>
                    </div>
                </a>
                <a href="javascript:void(0);" class="all">БУДЬ ПЕРВЫМ В СВОЕМ ГОРОДЕ</a>
            </div>
            @if($video = Accounts::where('group_id' ,4)->where('load_video', 1)->where('video', '!=', '')->where('top_week_video', 1)->with('likes')->first())
            <div class="unit video best">
                @include(Helper::layout('blocks.video'), compact('video'))
                <a href="javascript:void(0);" class="all">Посмотреть другие видео</a>
            </div>
            @endif
        </div>
    </div>
@stop
@section('scripts')
@stop