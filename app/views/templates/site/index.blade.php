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
    <?php
    $created = new Carbon(Config::get('site.date_over_action'));
    $now = Carbon::now();
    $difference = ($created->diff($now)->days < 1)
            ? 'Сегодня заканчивается регистраця в конкурсе.'
            : 'До окончания регистрации в конкурсе осталось '.$created->diffInDays($now).' '.Lang::choice('день|дня|дней', $created->diffInDays($now)).'.';
    ?>
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
            @foreach(Accounts::where('group_id',4)->where('in_main_page', 1)->with('ulogin', 'likes')->get() as $user)
                <div class="unit">
                    <div class="img">
                    @if(!empty($user->photo) && File::exists(public_path($user->photo)))
                        <img src="{{ asset($user->photo) }}"
                             alt="{{ $user->name }}" class="{{ $user->name }}">
                    @elseif(!empty($user->ulogin) && !empty($user->ulogin->photo_big))
                        <img src="{{ $user->ulogin->photo_big }}" alt="{{ $user->name }}"
                             class="{{ $user->name }}">
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
            @if($news = News::where('publication' ,1)->orderBy('published_at','DESC')->with('meta.photo')->take(3)->get())
            <?php
                $tmp_news = $news;
                $news = array(
                    'first' => isset($tmp_news[0]) ? $tmp_news[0] : NULL,
                    'second' => isset($tmp_news[1]) ? $tmp_news[1] : NULL,
                    'third' => isset($tmp_news[2]) ? $tmp_news[2] : NULL
                );
            ?>
            <div class="unit photo">
                <a href="javascript:void(0);" class="wrapper">
                    @if($news['third'])
                    <div class="frame third">
                        @if(!empty($news['third']['meta']['photo']) && File::exists(Config::get('site.galleries_photo_dir').'/'.$news['third']['meta']['photo']['name']))
                        <img src="{{ asset(Config::get('site.galleries_photo_public_dir').'/'.$news['third']['meta']['photo']['name']) }}" class="visual" alt="{{ $news['third']['meta']['title'] }}">
                        @endif
                        <div class="title">
                            {{ $news['third']['meta']['title'] }}
                        </div>
                        <div class="date">
                            {{ (new myDateTime())->setDateString($news['third']['published_at'])->custom_format('d M Y') }}
                        </div>
                    </div>
                    @endif
                    @if($news['second'])
                    <div class="frame second">
                        @if(!empty($news['second']['meta']['photo']) && File::exists(Config::get('site.galleries_photo_dir').'/'.$news['second']['meta']['photo']['name']))
                            <img src="{{ asset(Config::get('site.galleries_photo_public_dir').'/'.$news['second']['meta']['photo']['name']) }}" class="visual" alt="{{ $news['second']['meta']['title'] }}">
                        @endif
                        <div class="title">
                            {{ $news['second']['meta']['title'] }}
                        </div>
                        <div class="date">
                            {{ (new myDateTime())->setDateString($news['second']['published_at'])->custom_format('d M Y') }}
                        </div>
                    </div>
                    @endif
                    @if($news['first'])
                    <div class="frame">
                        @if(!empty($news['first']['meta']['photo']) && File::exists(Config::get('site.galleries_photo_dir').'/'.$news['first']['meta']['photo']['name']))
                        <img src="{{ asset(Config::get('site.galleries_photo_public_dir').'/'.$news['first']['meta']['photo']['name']) }}" class="visual" alt="{{ $news['first']['meta']['title'] }}">
                        @endif
                        <div class="title">
                            {{ $news['first']['meta']['title'] }}
                        </div>
                        <div class="date">
                            {{ (new myDateTime())->setDateString($news['first']['published_at'])->custom_format('d M Y') }}
                        </div>
                    </div>
                    @endif
                </a>
                <a href="{{ pageurl('news') }}" class="all">Посмотреть все репортажи</a>
            </div>
            @endif
            <div class="unit map">
                <a href="#" class="wrapper">
                    <div class="frame">
                        <img src="{{ asset(Config::get('site.theme_path')) }}/img/map.png" class="visual" alt="">

                        <div class="title">
                            График перемещение мобильной команды
                        </div>
                    </div>
                </a>
                <a href="javascript:void(0);" class="all">БУДЬ ПЕРВЫМ В СВОЕМ ГОРОДЕ</a>
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