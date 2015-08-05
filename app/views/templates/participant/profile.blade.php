<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
<?php
$profile = Accounts::where('id', Auth::user()->id)->with('ulogin', 'likes')->first();
?>
@extends(Helper::layout())
@section('style')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/cropper/0.9.3/cropper.min.css">
@stop
@section('page_class') profile @stop
@section('content')
    <main>
        <div class="holder">
            <div class="photo">
            @if(!empty($profile->photo) && File::exists(public_path($profile->photo)))
                <img src="{{ asset($profile->photo) }}"
                     alt="{{ $profile->name }}" class="{{ $profile->name }}">
            @elseif(!empty($profile->ulogin) && !empty($profile->ulogin->photo_big))
                <img src="{{ $profile->ulogin->photo_big }}" alt="{{ $profile->name }}"
                     class="{{ $profile->name }}">
            @endif
                <br>
                <br>
                <center>
                    <a href="#" class="edit-photo">Изменить фотографию</a>
                </center>
                <input class="photoupload" type="file" name="photo" data-url="server/php/" accept="image/*">
            </div>
            <div class="info">
                <div class="row">
                    <a href="{{ URL::route('logout') }}" class="exit btn-white">Выйти</a>
                </div>
                <div class="row">
                    {{ Form::model($profile,array('route'=>'profile.save','class'=>'edit-profile','novalidate'=>'novalidate','files'=>TRUE)) }}
                    <label>
                        <span class="label">Ваше имя и фамилия</span>
                        {{ Form::text('name') }}
                    </label>
                    <label>
                        <span class="label">Ваш город</span>
                        {{ Form::text('location') }}
                    </label>
                    <label>
                        <span class="label">Укажите ваш возраст</span>
                        {{ Form::text('age') }}
                    </label>
                    <label>
                        <span class="label">Электронная почта</span>
                        {{ Form::email('email') }}
                    </label>
                    <label>
                        <span class="label">Телефон</span>
                        {{ Form::text('phone') }}
                    </label>

                    <label>
                        <span class="label">Почему именно я должен принять участие в шоу-финале?</span>
                        {{ Form::textarea('way') }}
                    </label>
                    {{ Form::hidden('photo','') }}
                    <br>
                    <br>
                    {{ Form::button('Сохранить данные',array('type'=>'submit','class'=>'btn-big-red')) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </main>
@stop
@section('scripts')

@stop