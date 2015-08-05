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
        <ul class="pagination">
          <li class="disabled">
            «
          </li>
          <li class="active">
            1
          </li>
          <li>
            <a href="#?page=2">
              2
            </a>
          </li>
          <li>
            <a href="#?page=3">
              3
            </a>
          </li>
          <li>
            <a href="#?page=4">
              4
            </a>
          </li>
          <li>
            <a href="#?page=5">
              5
            </a>
          </li>
          <li>
            <a href="#?page=6">
              6
            </a>
          </li>
          <li class="disabled">
            ...
          </li>
          <li>
            <a href="#?page=40">
              40
            </a>
          </li>
          <li>
            <a href="#?page=41">
              41
            </a>
          </li>
          <li>
            <a href="#?page=2" rel="next">
              »
            </a>
          </li>
        </ul>
    </div>
</main>
@stop
@section('scripts')
@stop