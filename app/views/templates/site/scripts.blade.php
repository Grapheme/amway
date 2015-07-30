<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="{{ Config::get('site.theme_path') }}/js/vendor/jquery-1.11.2.min.js"><\/script>')</script>

{{ HTML::script(Config::get('site.theme_path').'/js/main.js') }}