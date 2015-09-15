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
                <span class="red">Выходи <nobr>в реальность</nobr></span>
                <span class="ast">* Эй Джен — Поколение-А</span>
            </div>
        @else
            <a href="{{ URL::route('mainpage') }}" class="logo">
                <img src="{{ asset(Config::get('site.theme_path')) }}/img/logo.png" alt="">
                <span class="red">Выходи <nobr>в реальность</nobr></span>
                <span class="ast">* Эй Джен — Поколение-А</span>
            </a>
        @endif
        <nav>
            <ul>
                {{ Menu::placement('main_menu') }}
                <li class="enter">
                    <img src="{{ asset(Config::get('site.theme_path')) }}/img/ico-user.png" class="user-ico" alt="">
                    @if(Auth::guest())
                        <a href="" class="btn-popup" data-href="enter"
                           onclick="yaCounter31932671.reachGoal('reg_top'); return true;">Вход/Регистрация</a>
                    @else
                        <a href="{{ URL::to(AuthAccount::getGroupStartUrl()) }}"
                           onclick="yaCounter31932671.reachGoal('enter_lk'); return true;">Личный кабинет</a>
                    @endif
                </li>
                <!--<li class="casting-btn">
                	<a href="" class="btn-popup" data-href="casting">Запись на кастинг</a>
                </li>-->
            </ul>
        </nav>
        <div class="burger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</header>