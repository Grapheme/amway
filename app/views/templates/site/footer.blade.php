<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
<footer>
    <div class="holder">
        <div class="left">
            <a href="#" class="logo"><img src="{{ asset(Config::get('site.theme_path')) }}/img/logo2.png" alt=""></a>
            <div class="copy">
                © 2015, Amway, A-Gen, 2015<? if (date('Y') > 2015) { echo '-' . date('Y'); } ?><br>
                Все права защищены.
            </div>
            <a class="mail" href="mailto:info@agen-project.ru">info@agen-project.ru</a>
        </div>
        <div class="right">
            <a href="http://myxa-digital.com/" class="dev">MYXA digital</a>
        </div>
    </div>
</footer>
