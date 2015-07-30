<?
/**
* TITLE: Главная страница
* AVAILABLE_ONLY_IN_ADVANCED_MODE
*/
?>
@extends(Helper::layout())
@section('style')
@stop
@section('content')
    <section class="long color-blue" style="background-image: url('img/tmp-visual-1.jpg')">
        <div class="cover"></div>
        <div class="holder">
            <h2>Прими участие в творческом конкурсе
                и попади на гала-концерт</h2>

            <p>Если тебе менее 35 лет и&nbsp;ты&nbsp;чувствуешь в&nbsp;себе творческий потенциал, прими участие в&nbsp;творческом
                конкурсе. Создай свое шоу с&nbsp;помощью звездных наставников.</p>
            <a href="" class="play">
                <span class="icon-play"></span>
            </a>
            <center>
                <a href="" class="down">
                    <span class="icon-angle-down"></span>
                </a>
            </center>
        </div>
    </section>
    <section class="color-green">
        <div class="cover"></div>
        <div class="holder">
            <a href="" class="btn">Принять участие в конкурсе</a>

            <p>
                До окончания регистрации в конкурсе осталось 27 дней.
            </p>
        </div>
    </section>
    <section class="long color-yellow" style="background-image: url('img/tmp-visual-2.jpg')">
        <div class="cover"></div>
        <div class="holder">
            <h2>Что нужно сделать?</h2>

            <p>Если тебе менее 35 лет и ты чувствуешь в себе творческий потенциал, отложи дела и выходи в реальность.
                Докажи, что твоя свобода, твое мировоззрение, твоя команда, твоя жизнь – начало нового поколения.</p>

            <p>
                Можно петь, танцевать, поставить сценку!
                Для начала нужно зарегистрироваться на сайте и загрузить видео своего выступления.
            </p>
        </div>
    </section>
    <div class="competitors">
        <div class="holder">
            <h3>УЧАСТНИКИ КОНКУРСА</h3>

            <div class="unit">
                <div class="img"><img src="http://lorempixel.com/200/200/people/?5" alt=""></div>
                <div class="name">
                    Мылтыхян<br> Саша
                </div>
                <div class="location">
                    Ростов-на-Дону
                </div>
                <div class="rating">
                    <span class="icon2-star"></span>

                    <div class="count">34</div>
                    <div class="legend"></div>
                </div>
                <a href="" class="vote">Проголосовать</a>
            </div>
            <div class="unit">
                <div class="img"><img src="http://lorempixel.com/200/200/people/?1" alt=""></div>
                <div class="name">
                    Мылтыхян<br> Саша
                </div>
                <div class="location">
                    Ростов-на-Дону
                </div>
                <div class="rating">
                    <span class="icon2-star"></span>

                    <div class="count">31</div>
                    <div class="legend"></div>
                </div>
                <a href="" class="vote">Проголосовать</a>
            </div>
            <div class="unit">
                <div class="img"><img src="http://lorempixel.com/200/200/people/?2" alt=""></div>
                <div class="name">
                    Мылтыхян<br> Саша
                </div>
                <div class="location">
                    Ростов-на-Дону
                </div>
                <div class="rating">
                    <span class="icon2-star"></span>

                    <div class="count">35</div>
                    <div class="legend"></div>
                </div>
                <a href="" class="vote">Проголосовать</a>
            </div>
            <div class="unit">
                <div class="img"><img src="http://lorempixel.com/200/200/people/?3" alt=""></div>
                <div class="name">
                    Мылтыхян<br> Саша
                </div>
                <div class="location">
                    Ростов-на-Дону
                </div>
                <div class="rating">
                    <span class="icon2-star"></span>

                    <div class="count">34</div>
                    <div class="legend"></div>
                </div>
                <a href="" class="vote">Проголосовать</a>
            </div>
            <div class="unit">
                <div class="img"><img src="http://lorempixel.com/200/200/people/?4" alt=""></div>
                <div class="name">
                    ДУДНАКОВА <br>
                    ЕЛЕНА
                </div>
                <div class="location">
                    Ростов-на-Дону
                </div>
                <div class="rating">
                    <span class="icon2-star"></span>

                    <div class="count">34</div>
                    <div class="legend"></div>
                </div>
                <a href="" class="vote">Проголосовать</a>
            </div>
        </div>
    </div>
    <section class="long color-purple" style="background-image: url('img/tmp-visual-3.jpg')">
        <div class="cover"></div>
        <div class="holder">
            <h2>Галл-концер состоится 24 октября 2015
                в Вертол-Экспо (Ростов-на-Дону)</h2>

            <p>До гала-концерта остался 91 день.
                Зарегистрируйся на сайте и покажи свой талант всем!</p>
        </div>
    </section>
    <section class="color-red">
        <div class="cover"></div>
        <div class="holder">
            <a href="" class="btn">Принять участие в конкурсе</a>

            <p>
                До окончания регистрации в конкурсе осталось 27 дней.
            </p>
        </div>
    </section>
    <div class="main-news">
        <h3>Новости проекта</h3>

        <div class="holder">
            <div class="unit photo">
                <a href="#" class="wrapper">
                    <div class="frame third">
                        <img src="img/tmp-visual-6.jpg" class="visual" alt="">

                        <div class="title">
                            Фоторепортаж из Воронежа
                        </div>
                        <div class="date">
                            3 августа 2015
                        </div>
                    </div>
                    <div class="frame second">
                        <img src="img/tmp-visual-5.jpg" class="visual" alt="">

                        <div class="title">
                            Фоторепортаж из Воронежа
                        </div>
                        <div class="date">
                            3 августа 2015
                        </div>
                    </div>
                    <div class="frame">
                        <img src="img/tmp-visual-4.jpg" class="visual" alt="">

                        <div class="title">
                            Фоторепортаж из Воронежа
                        </div>
                        <div class="date">
                            3 августа 2015
                        </div>
                    </div>
                </a>
                <a href="a" class="all">Посмотреть все репортажи</a>
            </div>
            <div class="unit map">
                <a href="#" class="wrapper">
                    <div class="frame">
                        <img src="img/map.png" class="visual" alt="">

                        <div class="title">
                            График перемещение мобильной команды
                        </div>
                    </div>
                </a>
                <a href="a" class="all">БУДЬ ПЕРВЫМ В СВОЕМ ГОРОДЕ</a>
            </div>
            <div class="unit video best">
                <a href="#" class="wrapper">
                    <div class="frame">
                        <div class="play">
                            <span class="icon-play"></span>
                        </div>
                        <img src="img/tmp-video.jpg" alt="">

                        <div class="name">ДУДНАКОВА ЕЛЕНА</div>
                        <div class="location">Краснодар</div>
                        <div class="rating">
                            <span class="icon2-star"></span>

                            <div class="count">34</div>
                            <div class="legend"> голоса</div>
                        </div>
                    </div>
                </a>
                <a href="a" class="all">Посмотреть другие видео</a>
            </div>
        </div>
    </div>
@stop
@section('scripts')
@stop