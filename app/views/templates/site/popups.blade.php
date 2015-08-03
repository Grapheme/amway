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
            <div id="uLogin_aef33ddd" data-uloginid="aef33ddd" data-ulogin="mobilebuttons=0;display=buttons;fields=first_name,last_name,email,city,photo,photo_big;redirect_uri={{ URL::route('signin.ulogin') }}">
                <a href="javascript:void(0);" data-uloginbutton="facebook" class="fb">Войти с Facebook</a>
                <br>
                <a href="javascript:void(0);" data-uloginbutton="vkontakte" class="vk">Войти с Vkontakte</a>
            </div>
        </center>
        <hr>
        <div class="note">или с помощью эл.почты</div>
        {{ Form::open(array('route'=>'signin')) }}
            {{ Form::email('email',NULL,array('placeholder'=>'Эл. почта')) }}
            {{ Form::password('password',NULL,array('placeholder'=>'Пароль')) }}
            {{ Form::button('Войти',array('type'=>'submit')) }}
        {{ Form::close() }}
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
        {{ Form::open(array('route'=>'signup-participant')) }}
            {{ Form::hidden('token', Session::get('token')) }}
            {{ Form::hidden('identity', Session::get('identity')) }}
            {{ Form::hidden('profile', Session::get('profile')) }}
            {{ Form::hidden('uid', Session::get('uid')) }}
            {{ Form::hidden('photo_big', Session::get('photo_big')) }}
            {{ Form::hidden('photo', Session::get('photo')) }}
            {{ Form::hidden('network', Session::get('network')) }}
            {{ Form::hidden('verified_email', Session::has('verified_email') ? Session::get('verified_email') : 0) }}
            <label>
                <span class="label">Ваше имя и фамилия</span>
                {{ Form::text('name', Session::get('first_name').' '.Session::get('last_name')) }}
            </label>
            <label>
                <span class="label">Ваш город</span>
                {{ Form::text('location', Session::get('city')) }}
            </label>
            <label>
                <span class="label">Укажите ваш возраст</span>
                {{ Form::text('age') }}
            </label>
            <label>
                <span class="label">Электронная почта</span>
                {{ Form::email('email', Session::get('email')) }}
            </label>
            <label>
                <span class="label">Телефон</span>
                {{ Form::text('phone') }}
            </label>
            <label>
                <span class="label">Профиль в соцсети</span>
                {{ Form::text('social[]', Session::get('profile')) }}
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
                {{ Form::checkbox('agree1', 1, TRUE) }}
                <span class="label">Я согласен на обработку предоставленных мною персональных данных, в соответствии с законодательством РФ.</span>
            </label>
            <label class="small">
                {{ Form::checkbox('agree2', 1, TRUE) }}
                <span class="label">Я ознакомлен с правилами участия в проекте A-gen.</span>
            </label>
            <hr>
            <center>
                {{ Form::button('ЗАРЕГИСТРИРОВАТЬСЯ',array('type'=>'submit')) }}
            </center>
        {{ Form::close() }}
        <hr>
        <center>
            <a href="" data-href="enter" class="btn-popup">У меня уже есть регистрация</a>
        </center>
    </div>
</div>
