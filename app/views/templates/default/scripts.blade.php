<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
@if(Config::get('app.use_scripts_local'))
	{{HTML::script('js/vendor/jquery.min.js');}}
@else
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="{{asset('js/vendor/jquery.min.js');}}"><\/script>')</script>
@endif