<?
/**
 * MENU_PLACEMENTS: main_menu=Основное меню|footer_menu=Меню в подвале
 */
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
    	@include(Helper::layout('head'))
	    @yield('style')
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        @include(Helper::layout('header'))

        @section('content')
            {{ @$content }}
        @show

        @section('footer')
            @include(Helper::layout('footer'))
        @show

        @include(Helper::layout('scripts'))

        @section('overlays')
        @show

        @section('scripts')
        @show

        {{ Config::get('app.settings.main.tpl_footer_counters') }}

    </body>
</html>