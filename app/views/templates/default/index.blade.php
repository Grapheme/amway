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

    Slug of the index page (by slug "index"): {{ pageurl('index') }}<br/>

    {{ Helper::ta(Config::get('app.pages')) }}

    {{ Helper::ta(Page::all_by_slug()) }}

    {{ Helper::ta(Page::all_by_sysname()) }}

    {{ Helper::ta(Page::by_slug('pervaya-stranica')) }}

    {{ Helper::ta(Page::by_sysname('index')) }}

@stop


@section('scripts')
@stop