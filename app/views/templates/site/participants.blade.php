<?
/**
* TITLE: Участники
* AVAILABLE_ONLY_IN_ADVANCED_MODE
*/
?>
<?php
$participants = Accounts::where('group_id', 4)->with('ulogin', 'likes')->paginate(25);
?>
@extends(Helper::layout())
@section('style')
@stop
@section('page_class')sticky participants
@stop
@section('content')
<main>
    <section class="color-purple-dark mid">
        <div class="cover"></div>
        <div class="holder">
            {{ $page->block('content') }}
        </div>
    </section>
    <div class="holder">
        <h3>УЧАСТНИКИ КОНКУРСА</h3>
        <div class="note">
            ВСЕГО {{ $participants->count() }} {{ Lang::choice('УЧАСТНИК|УЧАСТНИКА|УЧАСТНИКОВ', $participants->count()) }}
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