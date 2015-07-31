<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
<div class="popup-wrapper">
    <div class="popup" id="enter">
        <a href="" class="close">
            <!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24"
                 height="24" viewBox="0 0 24 24">
                <path d="M18.984 6.422l-5.578 5.578 5.578 5.578-1.406 1.406-5.578-5.578-5.578 5.578-1.406-1.406 5.578-5.578-5.578-5.578 1.406-1.406 5.578 5.578 5.578-5.578z"></path>
            </svg>
        </a>

        <div class="header">Войти</div>
        <center>
            <a href="" class="fb">Войти с Facebook</a>
            <br>
            <a href="" class="vk">Войти с Vkontakte</a>
        </center>
        <hr>
        <div class="note">или с помощью эл.почты</div>
        <form action="">
            <input type="email" name="email" placeholder="Эл. почта">
            <input type="password" name="password" placeholder="Пароль">
            <button type="submit">Войти</button>
        </form>
        <hr>
        <center>
            <a href="" data-href="reg" class="btn-popup">У меня ещё нет регистрации</a>
        </center>
    </div>
    <div class="popup" id="reg">
        <a href="" class="close">
            <!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24"
                 height="24" viewBox="0 0 24 24">
                <path d="M18.984 6.422l-5.578 5.578 5.578 5.578-1.406 1.406-5.578-5.578-5.578 5.578-1.406-1.406 5.578-5.578-5.578-5.578 1.406-1.406 5.578 5.578 5.578-5.578z"></path>
            </svg>
        </a>

        <div class="header">Регистрация</div>
        <form action="">
            <label>
                <span class="label">Ваше имя и фамилия</span>
                <input type="text" name="name">
            </label>
            <label>
                <span class="label">Ваш город</span>
                <input type="text" name="location">
            </label>
            <label>
                <span class="label">Укажите ваш возраст</span>
                <input type="text" name="age">
            </label>
            <label>
                <span class="label">Электронная почта</span>
                <input type="email" name="email">
            </label>
            <label>
                <span class="label">Телефон</span>
                <input type="phone" name="phone">
            </label>
            <label>
                <span class="label">Профиль в соцсети</span>
                <input type="text" name="social">
                <a href="" class="social-plus">
                    <?='<?xml version = "1.0" encoding = "utf-8"?>' ?>
                    {{ '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">' }}
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                         width="24" height="24" viewBox="0 0 24 24">
                        <path d="M18.984 12.984h-6v6h-1.969v-6h-6v-1.969h6v-6h1.969v6h6v1.969z"></path>
                    </svg>
                </a>
            </label>

            <div class="note">
                Если вы хотите добавить несколько соцсетей, нажмите плюс.
            </div>
            <label class="small">
                <input type="checkbox" name="agree1">
                <span class="label">Я согласен на обработку предоставленных мною персональных данных, в соответствии с законодательством РФ.</span>
            </label>
            <label class="small">
                <input type="checkbox" name="agree2">
                <span class="label">Я ознакомлен с правилами участия в проекте A-gen.</span>
            </label>
            <hr>
            <center>
                <button type="submit">ЗАРЕГИСТРИРОВАТЬСЯ</button>
            </center>
        </form>
        <hr>
        <center>
            <a href="" data-href="enter" class="btn-popup">У меня уже есть регистрация</a>
        </center>
    </div>
</div>
