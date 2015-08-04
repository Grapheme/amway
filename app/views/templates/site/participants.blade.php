<?
/**
* TITLE: Участники
* AVAILABLE_ONLY_IN_ADVANCED_MODE
*/
?>
<?php
$participants = Accounts::where('group_id', 4)->with('ulogin', 'likes')->get();
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
        @if($participants->count())
        <div class="competitors">
            <div class="holder">
                @foreach($participants as $user)
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
                    <a href="{{ URL::route('participant.public.set.like', $user->id) }}" class="vote">Проголосовать</a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</main>
@stop
@section('scripts')
@stop