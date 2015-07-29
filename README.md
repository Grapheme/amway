## Мультиязычность

Для реализации мультиязычности достаточно добавить к роуту или группе роутов префикс {lang}, например так:

```php
Route::group(array('prefix' => '{lang}'), function() {
    Route::get('/article/{id}', array('as' => 'article', 'uses' => 'ArticleController@getArticle'));
});
```

Или так:

```php
Route::get('/{lang}/article/{id}', array('as' => 'article', 'uses' => 'ArticleController@getArticle'));
```

Система сама будет заменять данный сегмент на текущую активную локаль (Config::get('app.locale')):

```php
URL::route('article', 123) => /en/article/123
```

Для генерации ссылки на страницу с другим языком достаточно просто передать нужную локаль в параметрах маршрута:

```php
URL::route('article', ['lang' => 'ru', 'id' => 123]) => /ru/article/123
## или так:
URL::route('article', ['lang' => 'ru', 123]) => /ru/article/123
```

Следует отметить, что доступны будут только те локали, которые определены в конфиге (Config::get('app.locales')):

```php
'locales' => array(
    'ru' => 'Русский',
    'en' => 'English',
),
```

Все методы контроллеров, которые будут обрабатывать мультиязычные роуты, первым параметром должны принимать параметр $lang:

```php
public function getArticle($lang, $id) {
    ...
}
```

## Locale switcher

Пример реализации кнопок переключения между языковыми версиями - с поддержкой главной страницы и с сохранением урл-адреса текущей страницы (при условии, что роут текущей страницы имеет префикс {lang}):

```php
@if (NULL !== ($route = Route::getCurrentRoute()) && is_object($route))
    <?
    $route_name = $route->getName();
    $route_lang = $route->getParameter('lang');
    $langs = [
        'ru' => 'rus',
        'en' => 'eng',
    ];
    ?>
    <div class="lang-sw">

        @foreach ($langs as $lang_sign => $lang_text)

            <?
            if (in_array($route_name, ['mainpage'])) {

                ## Если мы на главной странице (основной или языковой)
                $route_name = 'mainpage';
                $route_params = ['lang' => $lang_sign] + $route->parameters();
                $class = (
                        $route_lang == $lang_sign
                        || (
                                is_null($route_lang)
                                && Config::get('app.locale') == Config::get('app.default_locale')
                                && $lang_sign == Config::get('app.locale')
                        )
                ) ? 'active' : '';

            } else {

                ## Для всех остальных роутов, кроме главной страницы (основной или языковой)
                $route_params = ['lang' => $lang_sign] + $route->parameters();
                $class = (NULL !== ($route_lang = $route->getParameter('lang')) && $route_lang == $lang_sign) ? 'active' : '';
            }
            ?>

            <a href="{{ URL::route($route_name, $route_params) }}" class="{{ $class }}">{{ $lang_text }}</a>

        @endforeach
        
    </div>
@endif
```

## Принцип работы мультиязычности

Для реализации мультиязычности используются модифицированные классы движка Laravel 4.2 с добавлением необходимых методов и свойств. Все они находятся в /app/lib/:
CustomRoute, CustomRouteFacade, CustomRouter, CustomRoutingServiceProvider, CustomURL, CustomUrlGenerator, CustomUrlServiceProvider
В файле /app/config/app.php добавлены необходимые сервис-провайдеры:

```php
        'Sngrl\Routing\CustomUrlServiceProvider',
        'Sngrl\Routing\CustomRoutingServiceProvider',
```

Там же переопределены некоторые алиасы:

```php
		#'Route'           => 'Illuminate\Support\Facades\Route',
		'Route'           => 'Sngrl\Support\Facades\CustomRoute',
        #'URL'             => 'Illuminate\Support\Facades\URL',
        'URL'             => 'Sngrl\Support\Facades\CustomURL',
```

Также в файле /app/routes.php объявлен следующий паттерн для переменной {lang}:

```php
Route::pattern('lang', implode('|', array_keys(Config::get('app.locales'))))
    ->defaults('lang', Config::get('app.locale'))
;
```

Он делает возможными для использования только те локали, которые указаны в файле /app/config/app.php в секции locales.
Также устанавливается значение для паттерна по умолчанию, которое используется в том случае, если значение не передано явно (данный функционал не является стандартным для Laravel, и был реализован самостоятельно).

## Конструктор меню

При использовании конструктора меню в Шаблоне активности есть возможность указать следующие конструкции:

%url% - будет заменено на URL текущей страницы (к которой относится пункт меню)
%news>url% - будет заменено на URL страницы с системным именем news (поиск происходит в: 1) предзагруженных модулем страницах; 2) кеше; 3) БД.)
%locale% - текущая локаль сайта
%default_locale% - локаль сайта по-умолчанию

Примеры использования:

```php
~/(%url%)~is
```
Данный пункт меню будет активен, если в адресной строке будет встречаться URL текущей страницы. Удобно использовать, например, для раздела Новости или Статьи, когда список записей располагается по адресу /news, а конкретная запись имеет адрес /news/first-news

```php
~/(%news>url%|%publications>url%)~is
```
Данную конструкцию удобно использовать, если текущий пункт меню должен быть активен сразу для нескольких разделов сайта.
В данном случае пункт меню будет активен для всех адресов, в которых будет найдены URL-адреса страниц с системным именем news или publications. В частности, он будет активен на странице списка новостей, на странице одной новости и на странице со списком публикаций в СМИ.

Данные конструкции будут работать как для простых, так и для многоязычных роутов.

Макросы %locale% и %default_locale% также можно использовать в пункте меню типа "Ссылка" в поле URL.

## Капча

Вывести картинку с капчей:

```php
<img src="{{ URL::route('kcaptcha_image', [session_name() => session_id()]) }}" />
```

Проверка капчи из веб-формы:

AJAX POST-запрос по адресу:
```php
{{ URL::route('kcaptcha_check') }}
```
с передачей двух параметров:
keystring - введенный пользователем код капчи
clear - очищать ли код капчи в сессии. Для ajax-запроса обязательно нужно передавать FALSE!

Проверка капчи программно из PHP:

```php
$valid = CaptchaController::checkKcaptcha($keystring, $clear);
```
$keystring - введенный пользователем код капчи
$clear - очищать ли код капчи в сессии. По умолчанию - TRUE. Отключать очистку не рекомендуется!

## Конфигурация

После каждого сохранения Настроек из Админки все переменные конфигурации сохраняются в БД, а также кешируются стандартными возможностями Laravel (Cache::set()). При инициации любого запроса конфигурация загружается из кеша, и добраться до нее можно таким образом:

```php
$settings = Config::get('app.settings'); // Получение всего массива настроек
$main = Config::get('app.settings.main'); // Получение списка настроек из секции MAIN
$sitename = Config::get('app.settings.main.sitename'); // Получение значения настройки SITENAME из секции MAIN
```

## Работа с GIT

Система умеет работать с GIT (github).
Подготовка к работе:

Репозиторий должен иметь имя вида: git@github.com:_USER_/_PROJECT_.git

Даем права на запись в папку .git:
```php
chmod -R 0777 .git
```

Идем в настройки репозитория: Settings > Webhooks & Services > Add webhook
Payload URL: адрес вашего проекта
Content type: application/x-www-form-urlencoded
Secret: секретный ключ репозитория

Сохраняем, заходим в созданный вебхук и смотрим последний ответ в секции Recent Deliveries.
Смотрим на текст запроса, и берем оттуда repository id и repository name:

```php
...
"repository": {
     "id": 123456789,
     "name": "git_project",
...
```

Сохраняем эти данные в app/config/github.php (а также Secret).
