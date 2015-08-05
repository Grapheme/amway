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
            <div id="uLogin_aef33ddd" data-uloginid="aef33ddd"
                 data-ulogin="mobilebuttons=0;display=buttons;fields=first_name,last_name,email,city,photo,photo_big;redirect_uri={{ URL::route('signin.ulogin') }}">
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
            {{ Form::text('name', trim(Session::get('first_name').' '.Session::get('last_name'))) }}
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
    <div class="popup" id="photo-edit">
        <a href="" class="close">
            <!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24"
                 height="24" viewBox="0 0 24 24">
                <path d="M18.984 6.422l-5.578 5.578 5.578 5.578-1.406 1.406-5.578-5.578-5.578 5.578-1.406-1.406 5.578-5.578-5.578-5.578 1.406-1.406 5.578 5.578 5.578-5.578z"></path>
            </svg>
        </a>

        <div class="holder">
            <img src="" alt="">
        </div>
        <br>
        <center>
            <a href="" class="btn-big-red photo-edit-final">Готово</a>
        </center>
        <br>
    </div>

    <div class="popup" id="video">
        <div class="holder">
            <a href="" class="close">
                <!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                     width="24" height="24" viewBox="0 0 24 24">
                    <path d="M18.984 6.422l-5.578 5.578 5.578 5.578-1.406 1.406-5.578-5.578-5.578 5.578-1.406-1.406 5.578-5.578-5.578-5.578 1.406-1.406 5.578 5.578 5.578-5.578z"></path>
                </svg>
            </a>
            <iframe width="840" height="480" src="" frameborder="0" allowfullscreen></iframe>
            <div class="col">
                <div class="name"></div>
                <div class="city">

                </div>
            </div>
            <div class="col">
                <div class="rating">
                    <span class="icon2-star"></span>

                    <div class="count"></div>
                    <div class="legend"></div>
                </div>
            </div>
            <div class="col">
                <a href="" class="vote">Проголосовать</a>
            </div>
            <div class="social">
                <div class="label">
                    Расскажи
                    друзьям
                </div>
                <a href="http://vk.com/share.php?title=заголовок&description=описание&url=" target="_blank" class="vk">
                    <?='<?xml version = "1.0" encoding = "utf-8"?>' ?>
                    <!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                         xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                         viewBox="231.4 13.5 18.4 24" enable-background="new 231.4 13.5 18.4 24" xml:space="preserve">
                    <g>
                        <path d="M242.6,27.5c-0.8-0.5-1.9-0.5-2.7-0.5H237v6h2.7c1,0,2.2,0.1,3.1-0.5c0.8-0.5,1.2-1.6,1.2-2.5
                        C244,29,243.4,28,242.6,27.5z M241.5,22.4c0.6-0.5,1-1.4,1-2.2c0-0.9-0.4-1.7-1.2-2.2c-0.8-0.5-2.2-0.3-3.1-0.3H237V23h1.7
                        C239.6,23,240.7,23,241.5,22.4z M241.3,37.5h-9.9v-24h10.9c3.1,0,6.1,2,6.1,5.5c0,2.7-1.5,4.6-3.5,5.2v0.1c2.9,0.6,4.9,2.2,4.9,5.9
                        C249.8,33.7,247.4,37.5,241.3,37.5z"/>
                    </g>
                    </svg>
                </a>
                <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?t=заголовок&u=" class="fb">
                    <?='<?xml version = "1.0" encoding = "utf-8"?>' ?>
                    <!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                         xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                         viewBox="-149.4 12.9 13 24" enable-background="new -149.4 12.9 13 24" xml:space="preserve">
                    <g>
                        <path d="M-136.9,12.9l-3.1,0c-3.5,0-5.8,2.3-5.8,5.9v2.7h-3.1c-0.3,0-0.5,0.2-0.5,0.5V26c0,0.3,0.2,0.5,0.5,0.5h3.1
                        v10c0,0.3,0.2,0.5,0.5,0.5h4.1c0.3,0,0.5-0.2,0.5-0.5v-10h3.7c0.3,0,0.5-0.2,0.5-0.5l0-3.9c0-0.1-0.1-0.3-0.1-0.3
                        c-0.1-0.1-0.2-0.1-0.3-0.1h-3.7v-2.3c0-1.1,0.3-1.7,1.7-1.7l2.1,0c0.3,0,0.5-0.2,0.5-0.5v-3.7C-136.5,13.2-136.7,12.9-136.9,12.9z"
                              />
                    </g>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
