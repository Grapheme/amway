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
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>@yield('title')</title>
<meta name="description" content="@yield('description')">
<meta name="keywords" content="@yield('keywords')">
<meta name="viewport" content="width=device-width, initial-scale=1, minimal-ui">

<link rel="icon" type="image/png" href="{{ Config::get('site.theme_path') }}/images/favicon-32x32.png" sizes="32x32">
<link rel="icon" type="image/png" href="{{ Config::get('site.theme_path') }}/images/favicon-16x16.png" sizes="16x16">

{{ HTML::style(Config::get('site.theme_path').'/styles/vendor.css') }}
{{ HTML::style(Config::get('site.theme_path').'/styles/main.css') }}
