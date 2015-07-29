@if(Config::get('app.use_scripts_local'))
	{{ HTML::script('private/js/vendor/jquery.min.js') }}
	{{ HTML::script('private/js/vendor/jquery-ui.min.js') }}
@else
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="{{ asset('private/js/vendor/jquery.min.js') }}"><\/script>');</script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
	<script>if(!window.jQuery.ui){document.write('<script src="{{ asset('private/js/vendor/jquery-ui.min.js') }}"><\/script>');}</script>
@endif
	{{ HTML::script('private/js/vendor/bootstrap.min.js') }}
	{{ HTML::script('private/js/vendor/SmartNotification.min.js') }}
	{{ HTML::script('private/js/vendor/jquery.validate.min.js') }}
	{{ HTML::script('private/js/vendor/jquery.maskedinput.min.js') }}
	{{ HTML::script('private/js/vendor/bootstrap-slider.min.js') }}
	{{ HTML::script('private/js/vendor/smartclick.js') }}
	{{ HTML::script('private/js/vendor/dropzone.min.js') }}
	{{ HTML::script('private/js/plugin/jquery-nestable/jquery.nestable.js') }}

    {{ HTML::script('private/js/plugin/pace/pace.min.js', array('data-pace-options' => '{ "restartOnRequestAfter": true }')) }}

	{{ HTML::script('private/js/system/messages.js') }}
	{{ HTML::script('private/js/system/main.js') }}
	{{ HTML::script('private/js/system/app.js') }}

    {{ HTML::scriptmod(URL::route('collector-js')) }}
