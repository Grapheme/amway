<?
/**
* TITLE: Новости проекта
* AVAILABLE_ONLY_IN_ADVANCED_MODE
*/
?>
@extends(Helper::layout())
@section('style')
@stop
@section('content')
    <main>
        <section class="color-red mid">
            <div class="cover"></div>
            <div class="holder">
                {{ $page->block('first_section') }}
            </div>
        </section>
    @if($news_list = News::where('publication' ,1)->orderBy('published_at','DESC')->with('meta.photo')->get())
        <div class="news-grid">
        @if($news_list->count())
            <h3>ФОТОРЕПОРТАЖИ</h3>
            <div class="holder">
            @foreach($news_list as $news)
                <div class="unit photo">
                    @include(Helper::layout('blocks.news'), compact('news'))
                </div>
            @endforeach
            </div>
        @endif
        </div>
    @endif
    @if($top_video = Accounts::where('group_id' ,4)->where('load_video', 1)->orderBy('top_week_video','DESC')->where('video', '!=', '')->where('top_video', 1)->with('likes')->get())
         <div class="row grey">
            <div class="holder">
                <h3>ВИДЕО УЧАСТНИКОВ ПРОЕКТА</h3>

                <div class="center-text">
                    <p>Нашел себя в «Лучших видео» — расскажи друзьям, набирай голоса и приходи на кастинг! Следи за новостями проекта.</p>
                </div>
            </div>
        </div>
        <div class="news-grid grey">
        @if($top_video->count())
            <div class="holder">
                @foreach($top_video as $video)
                <div class="unit video{{ $video->top_week_video ? ' best' : '' }}">
                    @include(Helper::layout('blocks.video'), compact('video'))
                </div>
                @endforeach
            </div>
        @endif
        </div>
    @endif
        <div class="holder">
            <h3>ГРАФИК ПЕРЕМЕЩЕНИЯ МОБИЛЬНОЙ КОМАНДЫ</h3>
            <img src="/uploads/files/1438690660_1094242.jpg" style="width:100%;" alt="">
            <p>&nbsp;</p>
        </div>
    </main>
@stop
@section('scripts')
@stop