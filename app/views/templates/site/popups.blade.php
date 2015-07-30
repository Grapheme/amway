<?
/**
 * TEMPLATE_IS_NOT_SETTABLE
 */
?>
<div class="popup-wrapper">
    <div class="popup" id="enter">
        <a href="" class="close">Х</a>

        <div class="header">Войти</div>
        <a href="" class="fb">Войти с Facebook</a>
        <br>
        <a href="" class="vk">Войти с Vkontakte</a>
        <hr>
        <div class="note">или с помощью эл.почты</div>
        <form action="">
            <input type="email" name="email" placeholder="Эл. почта">
            <input type="password" name="password" placeholder="Пароль">
            <button type="submit">Войти</button>
        </form>
        <hr>
        <a href="" data-href="reg" class="btn-popup">У меня ещё нет регистрации</a>
    </div>
    <div class="popup" id="reg">
        <a href="" class="close">Х</a>

        <div class="header">Регистрация</div>
        <form action="">
            <label>
                <span class="label">Ваше имя и фамилия</span>
                <input type="text" name="name">
            </label>
            <br>
            <label>
                <span class="label">Ваш город</span>
                <input type="text" name="location">
            </label>
            <br>
            <label>
                <span class="label">Укажите ваш возраст</span>
                <input type="text" name="age">
            </label>
            <br>
            <label>
                <span class="label">Электронная почта</span>
                <input type="email" name="email">
            </label>
            <br>
            <label>
                <span class="label">Пароль</span>
                <input type="password" name="password">
            </label>
            <br>
            <label>
                <span class="label">Пароль ещё раз</span>
                <input type="password" name="password_again">
            </label>
            <br>
            <label>
                <span class="label">Профиль в соцсети</span>
                <input type="text" name="social">
                <a href="" class="social-plus">+</a>
            </label>

            <div class="note">
                Если вы хотите добавить несколько соцсетей, нажмите плюс.
            </div>
            <br>
            <label>
                <input type="checkbox" name="agree1">
                <span class="label">Я согласен на обработку предоставленных мною персональных данных, в соответствии с законодательством РФ.</span>
            </label>
            <br>
            <label>
                <input type="checkbox" name="agree2">
                <span class="label">Я ознакомлен с правилами участия в проекте A-gen.</span>
            </label>
            <br>
            <button type="submit">ЗАРЕГИСТРИРОВАТЬСЯ</button>
        </form>
        <hr>
        <a href="" data-href="enter" class="btn-popup">У меня уже есть регистрация</a>
    </div>
</div>
