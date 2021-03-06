<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
<?
/**
 * META TITLE
 */
if (isset($page) && is_object($page)) {
    if (isset($page->seos) && is_object($page->seos) && isset($page->seos[Config::get('app.locale')]) && is_object($page->seos[Config::get('app.locale')]) && $page->seos[Config::get('app.locale')]->title) {
        $page_title = $page->seos[Config::get('app.locale')]->title;
    } else {
        $page_title = $page->name;
    }
} elseif (isset($seo) && is_object($seo) && $seo->title) {
    $page_title = $seo->title;
} elseif (!isset($page_title)) {
    $page_title = Config::get('site.seo.default_title');
}
/**
 * META DESCRIPTION
 */
if (isset($page->seos) && is_object($page->seos) && isset($page->seos[Config::get('app.locale')]) && is_object($page->seos[Config::get('app.locale')]) && $page->seos[Config::get('app.locale')]->description) {
    $page_description = $page->seos[Config::get('app.locale')]->description;
} elseif (isset($seo) && is_object($seo) && $seo->description) {
    $page_description = $seo->description;
} elseif (!isset($page_description)) {
    $page_description = Config::get('site.seo.default_description');
}
/**
 * META KEYWORDS
 */
if (isset($page->seos) && is_object($page->seos) && isset($page->seos[Config::get('app.locale')]) && is_object($page->seos[Config::get('app.locale')]) && $page->seos[Config::get('app.locale')]->keywords) {
    $page_keywords = $page->seos[Config::get('app.locale')]->keywords;
} elseif (isset($seo) && is_object($seo) && $seo->keywords) {
    $page_keywords = $seo->keywords;
} elseif (!isset($page_keywords)) {
    $page_keywords = Config::get('site.seo.default_keywords');
}
/**
 * SEO H1
 */
if (isset($page->seos) && is_object($page->seos) && isset($page->seos[Config::get('app.locale')]) && is_object($page->seos[Config::get('app.locale')]) && $page->seos[Config::get('app.locale')]->h1) {
    $page_h1 = $page->seos[Config::get('app.locale')]->h1;
} elseif (isset($seo) && is_object($seo) && $seo->h1) {
    $page_h1 = $seo->h1;
} elseif (!isset($page_h1) && isset($page) && is_object($page)) {
    $page_h1 = $page->name;
} else {
    $page_h1 = NULL;
}
?>
@section('title'){{{ $page_title }}}@stop
@section('description'){{{ $page_description }}}@stop
@section('keywords'){{{ $page_keywords }}}@stop
@section('h1'){{{ $page_h1 }}}@stop
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>@yield('title')</title>
<meta name="description" content="@yield('description')">
<meta name="keywords" content="@yield('keywords')">
<meta name="_token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1">

<meta property="og:title" content="@yield('title')" />
<meta property="og:description" content="@yield('description')" />
<meta property="og:url" content="{{ URL::to(Request::path()) }}" />
<meta property="og:image" content="{{ asset(Config::get('site.theme_path').'/img/logo.png') }}" />

<link rel="stylesheet" href="//cdn.jsdelivr.net/bxslider/4.2.5/jquery.bxslider.css">
{{ HTML::style(Config::get('site.theme_path').'/css/normalize.min.css') }}
{{ HTML::style(Config::get('site.theme_path').'/css/main.css') }}

{{ HTML::script(Config::get('site.theme_path').'/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js') }}