<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="{{ Config::get('site.theme_path') }}/js/vendor/jquery-1.11.2.min.js"><\/script>')</script>
<script src="{{ Config::get('site.theme_path') }}/js/vendor/jquery.ui.widget.js"></script>
<script src="{{ Config::get('site.theme_path') }}/js/vendor/jquery.iframe-transport.js"></script>
<script src="{{ Config::get('site.theme_path') }}/js/vendor/jquery.fileupload.js"></script>
<script src="//cdn.jsdelivr.net/jquery.validation/1.14.0/jquery.validate.min.js"></script>
<script src="{{ Config::get('site.theme_path') }}/js/messages_ru.js"></script>
<script src="//cdn.jsdelivr.net/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"></script>

<script src="//ulogin.ru/js/ulogin.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/cropper/0.9.3/cropper.min.js"></script>
<script src="//cdn.jsdelivr.net/bxslider/4.2.5/jquery.bxslider.min.js"></script>
{{ HTML::script(Config::get('site.theme_path').'/js/main.js') }}