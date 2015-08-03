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
                {{ Menu::placement('main_menu') }}
                <li class="enter">
                    <img src="{{ asset(Config::get('site.theme_path')) }}/img/ico-user.png" class="user-ico" alt="">
                    @if(Auth::guest())
                    <a href="" class="btn-popup" data-href="enter">Вход для участников</a>
                    @else
                    <a href="{{ URL::to(AuthAccount::getGroupStartUrl()) }}">Личный кабинет</a>
                    @endif
                </li>
            </ul>
        </nav>
    </div>
</header>