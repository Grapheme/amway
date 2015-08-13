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

<script src="{{ Config::get('site.theme_path') }}/js/vendor/swfobject-2.2.min.js"></script>
<script src="{{ Config::get('site.theme_path') }}/js/vendor/evercookie.js"></script>
<script src="//cdn.jsdelivr.net/jquery.validation/1.14.0/jquery.validate.min.js"></script>
<script src="{{ Config::get('site.theme_path') }}/js/messages_ru.js"></script>
<script src="//cdn.jsdelivr.net/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"></script>

<script src="//ulogin.ru/js/ulogin.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/cropper/0.9.3/cropper.min.js"></script>
<script src="//cdn.jsdelivr.net/bxslider/4.2.5/jquery.bxslider.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/js-cookie/2.0.3/js.cookie.min.js"></script>
{{ HTML::script(Config::get('site.theme_path').'/js/main.js') }}

<!-- Yandex.Metrika counter -->
<script type="text/javascript"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter31932671 = new Ya.Metrika({ id:31932671, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true, trackHash:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="https://mc.yandex.ru/watch/31932671" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->