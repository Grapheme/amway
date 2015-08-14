<?
/**
* TITLE: Участники
* AVAILABLE_ONLY_IN_ADVANCED_MODE
*/
?>
<?php
$participants = Accounts::where('group_id', 4)->orderBy('created_at', 'DESC')->with('ulogin', 'likes')->paginate(25);
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
@section('page_class')sticky participants
@stop
@section('content')
<main>
    <section class="long color-blue video"
             style="background-image: url('{{ asset(Config::get('site.theme_path')) }}/img/tmp-visual-12.jpg')">
        <iframe data-src="https://player.vimeo.com/video/135698166?autoplay=1&loop=1&color=ffffff&title=0&byline=0&portrait=0"
                frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        <div class="cover"></div>
        <div class="holder">
            Пример видео
        </div>
    </section>
    <section class="color-purple-dark mid">
        <div class="cover"></div>
        <div class="holder">
            {{ $page->block('content') }}
        </div>
    </section>
    <div class="holder">
        <h3>УЧАСТНИКИ КОНКУРСА</h3>
        <div class="note">
            ВСЕГО {{ Accounts::where('group_id', 4)->count() }} {{ Lang::choice('УЧАСТНИК|УЧАСТНИКА|УЧАСТНИКОВ', Accounts::where('group_id', 4)->count()) }}
        </div>
        <br>
        <br>
        <br>
        @if($participants->count())
        <div class="competitors">
            <div class="holder">
                @foreach($participants as $user)
                    @include(Helper::layout('blocks.user'), compact('user'))
                @endforeach
            </div>
        </div>
        @endif
        {{ $participants->links() }}
    </div>
</main>
@stop
@section('scripts')
@stop