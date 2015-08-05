<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
<footer>
    <div class="holder">
        <div class="left">
            @if (Request::is('/'))
            <div class="logo"><img src="{{ asset(Config::get('site.theme_path')) }}/img/logo2.png" alt=""></div>
            @else
            <a href="{{ URL::route('mainpage') }}" class="logo"><img src="{{ asset(Config::get('site.theme_path')) }}/img/logo2.png" alt=""></a>
            @endif
            <div class="copy">
                © 2015, Amway, A-Gen, 2015<? if (date('Y') > 2015) { echo '-' . date('Y'); } ?><br>
                Все права защищены.
            </div>
            <a class="mail" href="mailto:info@agen-project.ru">info@agen-project.ru</a>
        </div>
        <div class="right">
<<<<<<< HEAD
            <a href="http://myxa-digital.com/" class="dev">MYXA Digital</a>
=======
            <a href="http://myxa-digital.com/" target="_blank" class="dev">MYXA digital</a>
>>>>>>> af06ef7a460c9da02a4be3e05fd5c5d7315aa240
        </div>
    </div>
</footer>
