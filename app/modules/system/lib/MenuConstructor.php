<?
class MenuConstructor {

    private $menu, $items, $order, $tpl, $pages_ids, $pages, $pages_by_sysname, $temp_items = [];

    public function __construct($slug) {

        $menu_item = Storage::where('module', 'menu')->where('name', $slug);

        if (NULL != ($db_remember_timeout = Config::get('app.settings.main.db_remember_timeout')) && $db_remember_timeout > 0)
            $menu_item->remember($db_remember_timeout);

        $menu_item = $menu_item->first();
        if (!is_object($menu_item) || !$menu_item->value)
            return false;

        $this->menu = json_decode($menu_item->value, 1);
        $this->items = @(array)$this->menu['items'];
        #dd($this->items);
        $this->order = @(array)json_decode($this->menu['order'], 1);
        /*
        $this->tpl = array(
            'container' => '<ul>%elements%</ul>',
            'element_container' => '<li%attr%>%element%%children%</li>',
            'element' => '<a href="%url%"%attr%>%text%</a>',
            'active_class' => 'active',
        );
        */
        $this->tpl = array(
            'container' => $this->menu['container'],
            'element_container' => $this->menu['element_container'],
            'element' => $this->menu['element'],
            'active_class' => $this->menu['active_class'],
        );
        #Helper::d($this->tpl);
        #Helper::d($this->menu);
        #Helper::d($this->items);
        #Helper::d($this->order);

        $this->pages_ids = array();
        $this->pages = array();

        /**
         * Предзагружаем нужные данные, чтобы избежать множественных однотипных запросов к БД
         */
        $this->prepare_data();
    }


    /**
     * Предзагружаем нужные данные, чтобы избежать множественных однотипных запросов к БД
     */
    private function prepare_data() {

        if (count($this->items)) {

            foreach ($this->items as $item) {
                /**
                 * Находим все Страницы в меню
                 */
                if ($item['type'] == 'page') {
                    #Helper::d($item);
                    $this->pages_ids[] = $item['page_id'];
                }
            }

            /**
             * Если в меню присутствуют страницы - загрузим их все сразу, одним запросом
             */
            if (count($this->pages_ids)) {

                $pages = Page::whereIn('id', $this->pages_ids);

                if (NULL != ($db_remember_timeout = Config::get('app.settings.main.db_remember_timeout')) && $db_remember_timeout > 0)
                    $pages->remember($db_remember_timeout);

                $pages = $pages->get();
                #Helper::tad($pages);
                $pages_by_sysname = new Collection();
                if (count($pages)) {
                    $array = new Collection();
                    foreach ($pages as $p => $page) {
                        $page->extract(true);
                        $array[$page->id] = $page;
                        $pages_by_sysname[$page->sysname] = $page;
                    }
                    #Helper::tad($array);
                    $this->pages = $array;
                    $this->pages_by_sysname = $pages_by_sysname;
                    unset($pages, $array, $pages_by_sysname);
                }
                #Helper::tad($this->pages);
                #Helper::tad($this->pages_by_sysname);
            }
        }
    }


    /**
     * Отрисовываем меню
     *
     * @return bool|mixed|string
     */
    public function draw() {

        if (
            !isset($this->tpl['container']) || !isset($this->tpl['element_container']) || !isset($this->tpl['element'])
            #|| !@is_array($menu['elements']) || !@count($menu['elements'])
        )
            return false;

        /**
         * Отрисовываем меню, начиная с самого верхнего уровня
         */
        $menu = $this->get_level($this->order);

        #echo $menu; die;

        if (is_string($menu) && $menu != '')
            $menu = StringView::force($menu);

        return $menu;
    }


    /**
     * Отрисовываем уровень меню
     *
     * @param $order
     * @return mixed|string
     */
    private function get_level($order) {

        $level = array();

        #Helper::d($this->items);

        /**
         * Перебираем все элементы меню текущего уровня
         */
        foreach ($order as $element_array) {
            $id = $element_array['id'];

            /**
             * Отрисовываем элемент меню
             */
            $element = $this->get_check_element($id);
            #Helper::d($element);
            #dd($element);

            if (!$element)
                continue;

            /**
             * Одиночный элемент или массив элементов
             */
            #Helper::d(' [ ' . $element .' ] ');
            if (is_string($element)) {
                $items = $this->items;
                $element = array($id => $element);
            } else {
                $items = $this->temp_items;
            }

            #echo "\n\n<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<\n\n";
            #Helper::d($items);
            #echo "\n\n>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>\n\n";


            $elements = $element;
            unset($element);

            #Helper::d($elements);

            foreach ($elements as $eid => $element) {

                if (!$element)
                    continue;

                #Helper::d($element);

                $attr = [];

                /**
                 * Небольшой костылек - определим, активен ли элемент или нет
                 */
                $data = @$items[$eid];
                #Helper::d($data);
                if (isset($data['li_class']) && $data['li_class'] != '')
                    $attr['class'] = @trim(@trim($attr['class']) . ' ' . $data['li_class']);
                $is_active = $this->get_active($data);
                #Helper::d($is_active);
                #dd($is_active);
                if ($is_active)
                    $attr['class'] = @trim(@trim($attr['class']) . ' ' . $this->tpl['active_class']);

                /*
                echo '$eid = ' . $eid . "\n";
                echo '$element';
                Helper::d($element);
                echo '$data';
                Helper::d($data);
                echo '$attr';
                Helper::d($attr);
                echo "\n\n======================================================================\n\n";
                */

                /**
                 * Отрисовываем дочерние элементы текущего пункта меню, если они есть
                 */
                $child_level = '';
                if (isset($element_array['children'])) {
                    $children = $element_array['children'];
                    $child_level = $this->get_level($element_array['children']);
                }

                /**
                 * Отрисовываем текущий элемент меню
                 */
                $attributes = trim(Helper::arrayToAttributes($attr));
                $element = strtr(
                    $this->tpl['element_container'],
                    array(
                        '%element%' => $element,
                        '%children%' => @$child_level ?: '',
                        '%attr%' => $attributes ? ' ' . $attributes : '', #$is_active
                    )
                );

                /**
                 * Добавляем отрисованный элемент меню в текущий уровень
                 */
                $level[] = $element;
            }

        }
        #Helper::dd($level);

        /**
         * Отрисовываем текущий уровень меню
         */
        $return = implode('', $level);
        $return = strtr(
            $this->tpl['container'],
            array(
                '%elements%' => $return,
            )
        );
        $return = preg_replace("~\%[^\%]+?\%~is", '', $return);
        /*
        ## !!! http:// => http:/ !!!
        $return = strtr($return, [
            '//' => '/',
        ]);
        */

        /**
         * Возвращаем текущий уровень меню
         */
        return $return;
    }


    /**
     * Проверяем элемент меню
     */
    private function get_check_element($element_id) {

        if (!isset($this->items[$element_id]))
            return false;

        /**
         * Получаем данные об элементе меню
         */
        $data = $this->items[$element_id];
        #Helper::d($data);

        if (@$data['type'] == 'function') {

            $this->temp_items = [];

            #Helper::d($data);

            $function = Config::get('menu.functions.' . $data['function_name']);
            if (isset($function) && is_callable($function)) {
                $result = $function();
                #Helper::d($result);

                if (isset($result['url']))
                    $result = array($result);

                $return = array();
                foreach ($result as $res) {

                    #Helper::d($res);

                    if (!@$data['use_function_data']) {
                        $res['text'] = @StringView::force($data['text']);
                        $res['title'] = @StringView::force($data['title']);
                    }

                    #@$return .=
                    #return $this->get_element_info_by_data($res);
                    #Helper::d( $this->get_element_info_by_data($res) );
                    #Helper::d($res);

                    $this->temp_items[] = $res;

                    $tmp = $this->get_element_info_by_data($res);

                    if ($tmp)
                        $return[] = $tmp;
                }

                #Helper::d($return);

                return $return;

            }

            return NULL;

        } else if (@$data['type'] == 'page' && !isset($this->pages[$data['page_id']])) {

            return null;

        } else {

            return $this->get_element($element_id);
        }
    }


    /**
     * Отрисовываем элемент меню
     *
     * @param $element_id
     * @return mixed|string
     */
    private function get_element($element_id) {


        if (!isset($this->items[$element_id]))
            return false;

        /**
         * Получаем данные об элементе меню
         */
        $data = $this->items[$element_id];
        #Helper::d($data);

        if (@$data['hidden'])
            return false;

        if (@$data['use_display_logic'] && @$data['display_logic'] != '') {
            #Helper::ta($data['display_logic']);
            $result = NULL;
            try {

                $code = '$result = @(bool)(' . $data['display_logic'] . ');';
                @eval($code);
                #die;

            } catch (Exception $e) {
                #
            }
            if (!$result)
                return false;
        }

        return $this->get_element_info_by_data($data);
    }


    private function get_element_info_by_data($data) {

        #Helper::dd($data);

        if (!is_array($data))
            return false;

        /**
         * Определяем атрибуты ссылки (class, title, target и т.д.)
         */
        $attr = array();
        if (isset($data['title']) && $data['title'] != '')
            $attr['title'] = StringView::force($data['title']);
        if (isset($data['target']))
            $attr['target'] = $data['target'];

        /**
         * Определяем, активна ли ссылка или нет
         */
        if ($this->get_active($data))
            $attr['class'] = @trim(@trim($attr['class']) . ' ' . $this->tpl['active_class']);

        #var_dump($this->get_active($data));
        #Helper::d($attr);

        /**
         * Получаем URL ссылки
         */
        $url = $this->get_url($data);

        /**
         * Отрисовываем элемент меню
         */
        $attributes = trim(Helper::arrayToAttributes($attr));
        $return = strtr(
            $this->tpl['element'],
            array(
                '%url%' => $url,
                '%attr%' => $attributes ? ' ' . $attributes : '',
                '%text%' => @StringView::force($data['text']),
            )
        );
        $return = preg_replace("~\%[^\%]+?\%~is", '', $return);
        #Helper::d($return);

        /**
         * Возвращаем элемент меню
         */
        return $return;
    }

    /**
     * Возвращаем URL элемента меню
     *
     * @param $element
     * @return bool
     */
    private function get_url($element) {
        #return '#';
        #Helper::d($element);

        /**
         * Возвращаем URL элемента меню, в зависимости от типа элемента меню
         */
        switch(@$element['type']) {

            case 'page':
                if (isset($this->pages[$element['page_id']]) && is_object($this->pages[$element['page_id']]))
                    return URL::route('page', ['slug' => $this->pages[$element['page_id']]->slug]);
                return false;
                break;

            case 'link':
                $url = @$element['url'] ?: false;
                /**
                 * Сделаем замену паттернов
                 */
                $url = $this->replaces($url);
                #var_dump($element['active_regexp']);
                return $url;
                break;

            case 'route':
                $route_params = array();
                if ('' != ($element['route_params'] = trim($element['route_params']))) {
                    $temp = explode("\n", $element['route_params']);
                    if (@count($temp)) {
                        foreach ($temp as $tmp) {
                            $tmp = trim($tmp);
                            if (!$tmp)
                                continue;
                            if (strpos($tmp, '=')) {
                                $tmp_params = explode('=', $tmp, 2);
                                $route_params[trim($tmp_params[0])] = trim($tmp_params[1]);
                            } else {
                                $route_params[] = $tmp;
                            }
                        }
                    }
                }
                $url = URL::route($element['route_name'], $route_params);
                $url = $this->replaces($url);
                return $url;
                break;

            case 'function':
                #Helper::dd($element);
                $function = Config::get('menu.functions.' . $element['function_name']);
                if (isset($function) && is_callable($function)) {
                    $result = $function();
                    $url = @$result['url'] ?: '';
                    $url = $this->replaces($url);
                    return $url;
                }
                return false;
                break;

            default:
                return false;
                break;
        }
    }


    /**
     * Замены паттернов
     *
     * @param $text
     *
     * @return string
     */
    private function replaces($text) {
        $text = strtr($text, [
            '%locale%' => Config::get('app.locale'),
            '%default_locale%' => Config::get('app.default_locale'),
        ]);
        return $text;
    }


    /**
     * Возвращаем пометку об активности текущего пункта меню
     *
     * @param $element
     * @return bool
     */
    private function get_active($element) {
        #return false;

        #Helper::tad($element);

        $is_active = false;

        /**
         * Собственное правило для определения активности пункта меню
         * Проверка текущего URL на соответствие шаблону регулярного выражения
         */
        if (isset($element) && is_array($element)) {

            if (
                (@$element['use_active_regexp'] && @$element['active_regexp'])
            ) {

                #Helper::ta($element);

                if (@$element['use_active_regexp'] && @$element['active_regexp']) {

                    /**
                     * Сделаем замену в регулярке, если у нас обычная страница
                     */
                    if (isset($element['type']) && $element['type'] == 'page') {
                        $page = isset($this->pages[$element['page_id']]) ? $this->pages[$element['page_id']] : NULL;
                        if ($page && is_object($page) && $page->slug) {
                            $element['active_regexp'] = strtr($element['active_regexp'], [
                                '%slug%' => $page->slug, '%url%' => $page->slug,
                            ]);
                        }
                        #var_dump($element['active_regexp']);
                    }

                    try {
                        $element['active_regexp'] = $this->replaces($element['active_regexp']);
                    } catch (Exception $e) {
                        echo 'Error: ', $e->getMessage(), "\n";
                        Helper::tad($element);
                        #die;
                    }

                    /**
                     * Замена конструкций вида %_page_sysname_>url%
                     */
                    preg_match_all('~\%([A-Za-z0-9\-\_]+)\>url\%~is', $element['active_regexp'], $matches);

                    if (isset($matches[1]) && count($matches[1])) {

                        #var_dump($matches);
                        #Helper::ta($this->pages_by_sysname);
                        $pages = new Collection();
                        $page_sysnames = [];
                        ## Все найденные конструкции
                        foreach ($matches[1] as $page_sysname) {
                            if (isset($this->pages_by_sysname[$page_sysname])) {
                                ## Ищем текущую страницу среди страниц текущего меню
                                $pages[$page_sysname] = $this->pages_by_sysname[$page_sysname];
                            } elseif (NULL !== Config::get('pages.preload_pages_limit') && NULL !== ($tmp = Page::by_sysname($page_sysname))) {
                                ## Ищем текущую страницу в кеше страниц
                                $pages[$page_sysname] = $tmp;
                            } else {
                                ## Если страница уж совсем нигде не нашлась - придется ее подгружать из БД. Делать это будем позже одним запросом.
                                $page_sysnames[] = $page_sysname;
                            }
                        }
                        ## Если есть список страниц для их подгрузки из БД - сделаем это!
                        if (count($page_sysnames)) {
                            $temp = Page::whereIn('sysname', $page_sysnames)->where('version_of', NULL)->get();
                            if (count($temp)) {
                                ## Если что-то нашлось - разложим по sysname
                                $pages_by_sysnames = new Collection();
                                foreach ($temp as $tmp) {
                                    if (!$tmp->sysname)
                                        continue;
                                    $pages_by_sysnames[$tmp->sysname] = $tmp;
                                }
                                if (count($pages_by_sysnames)) {
                                    ## Найдем недостающие страницы и добавим их в список
                                    foreach ($page_sysnames as $psn) {
                                        if (isset($pages_by_sysnames[$psn]))
                                            $pages[$psn] = $pages_by_sysnames[$psn];
                                    }
                                }
                            }
                            unset($temp, $tmp);
                        }
                        #Helper::tad($pages_by_sysnames);
                        #Helper::tad($pages);

                        $replaces = [];
                        ## Еще раз пройдемся по списку найденных паттернов и сгенерируем список для замены
                        foreach ($matches[1] as $page_sysname) {
                            if (isset($pages[$page_sysname]) && NULL !== ($page = $pages[$page_sysname])) {
                                $replaces['%' . $page->sysname . '>url%'] = $page->slug;
                            }
                        }
                        #dd($replaces);

                        ## Производим замену паттернов
                        $element['active_regexp'] = strtr($element['active_regexp'], $replaces);
                        ## Если остались ненайденные паттерны - удалим их
                        $element['active_regexp'] = preg_replace('~\%([A-Za-z0-9\-\_]+)\>url\%~is', '', $element['active_regexp']);
                    }

                    #Helper::dd(Request::path());
                    #Helper::dd($element['active_regexp']);
                    #Helper::dd(preg_match($element['active_regexp'], Request::path()));

                    $is_active = @(bool)preg_match($element['active_regexp'], Request::path());
                    #return $is_active;
                }

                if ($is_active)
                    return true;
            }
        }

        /**
         * Возвращаем пометку об активности ссылки, в зависимости от типа элемента меню
         */
        switch(@$element['type']) {

            case 'page':
                #Helper::ta($this->pages);
                #Helper::ta($this->pages[$element['page_id']]);
                $page = isset($this->pages[$element['page_id']]) ? $this->pages[$element['page_id']] : NULL;
                #Helper::ta($page);
                if (!$page)
                    return NULL;

                #$return = $this->isRoute('page', ['url' => $page->slug]);
                #$return = $this->isRoute('page', $page->slug);

                $is_active = $this->isRoute($page->start_page ? 'mainpage' : 'page', ['url' => $page->slug]);
                #Helper::tad($is_active);

                if ($is_active)
                    return $is_active;

                ## Если активна опция определения активности по дочерним страницам (из структуры страниц)
                if (@$element['use_active_hierarchy']) {

                    #Helper::ta($element);
                    #Helper::ta($page);

                    ## Получим структуру страниц из Storage
                    $current_hierarchy = Storage::where('module', 'pages')->where('name', 'hierarchy')->pluck('value');
                    $elements = json_decode($current_hierarchy, false);
                    #Helper::d($elements);
                    #Helper::d($elements[$page->id]);

                    ## Сформируем id_left_right
                    $id_left_right = [];
                    if (count($elements)) {

                        foreach($elements as $element_id => $element) {
                            #dd($element);
                            $id_left_right[$element_id] = array();
                            $id_left_right[$element_id]['left'] = $element->left;
                            $id_left_right[$element_id]['right'] = $element->right;
                        }
                    }
                    #Helper::tad($id_left_right);

                    ## Получим IDs страниц, для которых текущая страница (пункт меню) является родителем
                    $pages_ids = (new NestedSetModel())->get_children_ids_by_id_from_id_left_right($id_left_right, $page->id);
                    #Helper::ta('PAGES IDS:');
                    #Helper::ta($pages_ids);

                    ## Если у текущей страницы (пункта меню) в иерархии есть потомки
                    if (isset($pages_ids) && is_array($pages_ids) && count($pages_ids)) {

                        ## Получение страниц-потомков по их IDs
                        ## Как будем искать страницы - в кеше или в БД?
                        if (Config::get('pages.not_cached')) {

                            #Helper::tad('PAGES CACHE DISABLED! MenuConstructor:709');
                            ## Кеширование отключено (или не найдено ни одной страницы) - ищем в БД
                            $pages = (new Page())
                                ->where('publication', 1)
                                ->where('version_of', NULL)
                                ->whereIn('id', $pages_ids)
                                ->get()
                            ;

                        } else {

                            ## Если страницы есть в кеше
                            if (count(Page::all_by_id())) {

                                ## Ищем все нужные страницы в кеше по их IDs
                                foreach ($pages_ids as $page_id) {

                                    $pages[] = Page::by_id($page_id);
                                }
                            }
                        }

                        ## Если получены объекты страниц
                        if (isset($pages) && count($pages)) {

                            #Helper::dd($pages);

                            ## Перебираем их
                            foreach ($pages as $page) {

                                if (!is_object($page)) {
                                    #dd($page);
                                    continue;
                                }

                                ## Проверка на активность
                                $is_active = $this->isRoute($page->start_page ? 'mainpage' : 'page', ['url' => $page->slug]);
                                if ($is_active)
                                    return true;
                            }
                        }

                    }

                    $is_active = false;
                }

                return $is_active;

                break;

            case 'link':
                return (bool)preg_match('~' . $element['url'] . '$~s', Request::fullUrl());
                break;

            case 'route':
                $route_params = array();
                if ('' != ($element['route_params'] = trim($element['route_params']))) {
                    $temp = explode("\n", $element['route_params']);
                    if (@count($temp)) {
                        foreach ($temp as $tmp) {
                            $tmp = trim($tmp);
                            if (!$tmp) {
                                continue;
                            }
                            if (strpos($tmp, '=')) {
                                $tmp_params = explode('=', $tmp, 2);
                                $route_params[trim($tmp_params[0])] = trim($tmp_params[1]);
                            } else {
                                $route_params[] = $tmp;
                            }
                        }
                    }
                }
                return $this->isRoute($element['route_name'], $route_params);
                break;

            case 'function':
                #Helper::dd($element);
                $function = Config::get('menu.functions.' . $element['function_name']);
                if (isset($function) && is_callable($function)) {
                    $result = $function();
                    #return $result['url'];


                    /**
                     * Одиночная ссылка
                     */
                    #return (bool)preg_match('~' . $result['url'] . '$~s', Request::fullUrl());
                    if (isset($result['url']))
                        $result = array($result);

                    /**
                     * Перебираем весь массив ссылок
                     */
                    foreach ($result as $res)
                        if (isset($res['url']) && (bool)preg_match('~' . $res['url'] . '$~s', Request::fullUrl()) == true)
                            return true;

                }
                return false;
                break;

            default:
                return false;
                break;
        }
    }


    /**
     * Функция проверяет, сопадает ли проверяемый маршрут (и его параметры) с текущим положением пользователя на сайте
     *
     * @param bool $route_name
     * @param array $route_params
     * @return bool
     */
    private function isRoute($route_name = false, $route_params = array()) {

        #var_dump($route_name);
        #var_dump($route_params);
        #dd(Route::currentRouteName());

        if (Route::currentRouteName() != $route_name)
            return false;

        $match = true;
        $route = Route::getCurrentRoute();

        #var_dump($route->getPath());

        /**
         * Парсим параметры текущего маршрута
         */
        if (is_string($route_params)) {

            /*
            if (@$matches[1] != '') {
                $route_params = array($matches[1] => $route_params);
            } else {
                $route_params = array();
            }
            */
            $tmp_route_params = array();
            preg_match_all("~\{([^\}]+?)\}~is", $route->getPath(), $matches);
            #Helper::dd($matches);

            ## Поддержка мультиязычных роутов
            if (count($matches[1])) {
                foreach ($matches[1] as $match) {
                    if ($match == 'lang')
                        continue;
                    else {
                        $tmp_route_params = [$match => $route_params];
                        break;
                    }
                }
            }
            $route_params = $tmp_route_params;
        }
        #Helper::d($route_params);

        /**
         * Если есть параметры у текущего роута - проверяем их
         */
        if (count($route_params)) {

            /**
             * Если объявлен модификатор url-адреса для текущего роута - пробуем его применить
             */
            $route_params = URL::get_modified_parameters($route_name, $route_params);
            #Helper::dd($route_params);

            if (count($route_params)) {
                foreach ($route_params as $key => $value) {
                    #Helper::d("[" . $key . "] => " . $route->getParameter($key) . " ?= " . $value . ' => ' . (int)($route->getParameter($key) == $value));
                    /**
                     * Если хотя бы один из параметров текущего маршрута не совпадает с проверяемым - возвращаем FALSE
                     */
                    if ($route->getParameter($key) != $value) {
                        $match = false;
                        break;
                    }
                }
            }
        }
        #Helper::d((int)$match);

        /**
         * Возвращаем результат
         */
        return (bool)$match;
    }
}
