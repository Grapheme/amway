<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
<header>
    <div class="holder">
        @if (Request::is('/'))
        <div class="logo">
            <img src="{{ asset(Config::get('site.theme_path')) }}/img/logo.png" alt="">
            <span class="red">Выходи<br> в реальность</span>
            <span class="ast">* Эй Джен — Поколение-А</span>
        </div>
        @else
            <a href="{{ URL::route('mainpage') }}" class="logo">
                <img src="{{ asset(Config::get('site.theme_path')) }}/img/logo.png" alt="">
                <span class="red">Выходи<br> в реальность</span>
                <span class="ast">* Эй Джен — Поколение-А</span>
            </a>
        @endif
        <nav>
            <ul>
                <li><a href="">О проекте</a></li>
                <li><a href="">Участники</a></li>
                <li><a href="">Новости проекта</a></li>
                <li class="enter">
                    <img src="img/ico-user.png" class="user-ico" alt="">
                    <a href="" class="btn-popup" data-href="enter">Вход для участников</a>
                </li>
            </ul>
        </nav>
    </div>
</header>