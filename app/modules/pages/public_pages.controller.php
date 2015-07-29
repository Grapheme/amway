<?php

class PublicPagesController extends BaseController {

    public static $name = 'pages_public';
    public static $group = 'pages';

    /****************************************************************************/
    /**
     * @author Alexander Zelensky
     * @todo Возможно, в будущем здесь нужно будет добавить проверку параметра, использовать ли вообще мультиязычность по сегментам урла, или нет. Например, удобно будет отключить мультиязычность по сегментам при использовании разных доменных имен для каждой языковой версии.
     */
    public static function returnRoutes($prefix = null) {

        #Helper::dd(I18nPage::count());

        $class = __CLASS__;

        /**
         * Laravel не дает возможности переписывать пути (роуты), если они были зарегистрированы ранее.
         * А это значит, что если данный модуль активен и в нем создана хоть одна страница, то будет переписан корневой путь: /
         * Это не даст в будущем добавлять роуты от корня с помощью метода Route::controller('', '..@..'), придется все прописывать руками.
         * Надо бы подключать модуль страниц последним
         * [
         *     '/login' => Route object to /login,
         *     '{_missing}' => Fallthrough route object,
         *     '/' => Route object to /,
         * ]
         * Описание данной ситуации здесь:
         * http://stackoverflow.com/questions/20617244/laravel-4-1-controller-method-not-found?rq=1
         * https://github.com/laravel/framework/issues/2848
         * https://github.com/laravel/framework/pull/2850
         * https://github.com/laravel/framework/issues/2863
         * https://github.com/bencorlett/framework/pull/1
         * https://github.com/bencorlett/framework/commit/ac091a25465d070f8925a80b46eb237ef21ea912
         */
        if (
            !Allow::module(self::$group)
            #|| !Page::count()
            || Config::get('site.pages.disabled')
        )
            return false;

        Page::preload();

        ##
        ## Add URL modifier for check SEO url of the page (custom UrlGenerator functionality)
        ##
        /*
        URL::add_url_modifier('page', function(&$name, &$parameters) use ($class) {
            #var_dump($class); die;
            #Helper::dd('Page url modifier!');
            #Helper::dd($parameters);
            #return;
            if (
                #is_string($parameters)
                #&&
                count(Config::get('app.locales')) > 1
                && !Config::get('pages.disable_url_modification')
                && Allow::module('seo')
            ) {
                $pages = new $class;
                $right_url = $pages->getRightPageUrl($parameters);
                #Helper::d("Change page URL: " . $parameters . " -> " . $right_url);
                #Helper::dd("Change page URL: " . $parameters . " -> " . $right_url);
                if (@$right_url)
                    $parameters = $right_url;
                #$parameters = '111';
            }
        });
        */
        #/*
        if (
            count(Config::get('app.locales')) > 1
            && !Config::get('pages.disable_url_modification')
        ) {
            ## Mainpage route modifier
            URL::add_url_modifier('mainpage', function(&$name, &$parameters) use ($class) {

                #print_r($parameters);

                if (isset($parameters['lang']) && $parameters['lang'] == Config::get('app.default_locale'))
                    unset($parameters['lang']);

                #print_r($parameters);
            });
        }
        #*/

        ## Если в конфиге прописано несколько языковых версий - генерим роуты с языковым префиксом
        if (is_array(Config::get('app.locales')) && count(Config::get('app.locales')) > 1) {

            $default_locale_mainpage = ( Config::get('app.default_locale') == Config::get('app.locale') );
            #$locale_sign = Config::get('app.locale');

            ## Генерим роуты для всех языков с префиксом (первый сегмент), который будет указывать на текущую локаль
            Route::group(array('before' => 'i18n_url', 'prefix' => '{lang}'), function() use ($class, $default_locale_mainpage) {

                ## Regular page
                Route::any('/{url}', array('as' => 'page', 'uses' => $class . '@showPage'));

                ## Main page for non-default locale
                #if (!Config::get('pages.disable_mainpage_route') && !$default_locale_mainpage)
                #    Route::any('/', array('as' => 'mainpage', 'uses' => $class.'@showPage'));

                ## Main page for current locale (non-default)
                #Route::any('/', array('as' => 'mainpage_i18n', 'uses' => $class.'@showPage'));

            });

            Route::any('{lang?}', array('as' => 'mainpage', 'uses' => $class.'@showPage'));

            ## Main page for default locale
            #if (!Config::get('pages.disable_mainpage_route') && $default_locale_mainpage)
            #    Route::any('/', array('as' => 'mainpage', 'uses' => $class.'@showPage'));

            ## Main page for default locale
            #Route::any('/', array('as' => 'mainpage_default', 'uses' => $class.'@showPage'));

        } else {

            ## Генерим роуты без языкового префикса
            Route::group(array('before' => 'pages_right_url'), function() use ($class) {

                ## Regular page
                Route::any('/{url}', array(
                    'as' => 'page',
                    'uses' => $class.'@showPageSingle'
                ));

                ## Main page
                if (!Config::get('pages.disable_mainpage_route')) {

                    Route::any('/', array(
                        'as' => 'mainpage',
                        'uses' => $class.'@showPageSingle'
                    ));
                }
            });

        }

    }

    ## Shortcodes of module
    public static function returnShortCodes() {
    }

    ## Actions of module (for distribution rights of users)
    public static function returnActions() {
    }

    ## Info about module (now only for admin dashboard & menu)
    public static function returnInfo() {
    }

    /****************************************************************************/

	public function __construct(){

        $this->page = new Page;
        $this->page_meta = new PageMeta;
        $this->page_block = new PageBlock;
        $this->page_block_meta = new PageBlockMeta;
        $this->locales = Config::get('app.locales');

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            #'rest' => self::$group,
            'tpl' => static::returnTpl('admin'),
            'gtpl' => static::returnTpl(),
            'class' => __CLASS__,

            #'entity' => self::$entity,
            #'entity_name' => self::$entity_name,
        );
        View::share('module', $this->module);
	}


    ## Функция для просмотра одноязычной страницы
    public function showPageSingle($slug = false) {

        #dd($slug);
        return $this->showPage(Config::get('app.locale'), $slug);
    }

    ## Функция для просмотра мультиязычной страницы
    public function showPage($lang = NULL, $slug = false) {

        #dd($lang);

        if (!$lang)
            $lang = Config::get('app.locale');

        ## Как будем искать страницы - в кеше или в БД?
        if (Config::get('pages.not_cached')) {

            ## Кеширование отключено (или не найдено ни одной страницы) - ищем в БД
            $page = (new Page())->where('publication', 1)->where('version_of', NULL);

            if ($slug) {
                $page = $page->where('slug', $slug);
            } else {
                $page = $page->where('start_page', 1);
            }

            $page->with(['blocks.meta', 'seo']);

            $page = $page->first();

        } else {

            ## Если страниц нет в кеше - показываем 404
            if (!count(Page::all_by_slug()))
                App::abort(404);

            ## Кеширование включено - ищем страницу в кеше
            $page = Page::by_slug($slug ?: '/');
        }

        #Helper::smartQueries(1); #die;
        #Helper::tad($page);

        ## Если страница не найдена...
        if (!isset($page) || !is_object($page)) {

            ## ...пробуем в теме найти шаблон с таким же именем, как запрошенный slug...
            if (
                $slug != ''
                && !Config::get('pages.disable_slug_to_template')
                && preg_match("~^[A-Za-z0-9\-\_]+?$~is", $slug)
                && View::exists(Helper::layout($slug))
            ) {
                $page = new Page;
                return View::make(Helper::layout($slug), compact('slug', 'page'));
            }

            ## ...иначе показываем 404
            App::abort(404);
        }

        ## 301 редирект в том случае, если страница является главной, но ее запросили по slug.
        ## В случае в кешированием такого не должно произойти.
        if ($page->start_page && $slug != '') {
            $redirect = URL::route('mainpage');
            return Redirect::to($redirect, 301);
        }

        ## Если у страницы не задан шаблон - устанавливаем simple-page
        if (!$page->template)
            $page->template = 'simple-page';

        ## Ищем шаблон
        if(
            empty($page->template)
            || (
                !View::exists($this->module['gtpl'].$page->template)
                && !View::exists(Helper::layout($page->template))
            )
        )
            throw new Exception('Template [' . $this->module['gtpl'].$page->template . '] not found.');

        $template = NULL;
        if (View::exists($this->module['gtpl'].$page->template))
            $template = $this->module['gtpl'].$page->template;
        elseif (View::exists(Helper::layout($page->template)))
            $template = Helper::layout($page->template);

        ## Экстрактим страницу
        $page->extract(true);

        ## Настройки page_meta
        $page_meta_settings =
            isset($page->metas) && is_object($page->metas[Config::get('app.locale')])
                ? (array)json_decode($page->metas[Config::get('app.locale')]->settings, true)
                : []
        ;

        $page->page_meta_settings = $page_meta_settings;

        #Helper::tad($page);
        #Helper::dd($page->blocks['pervyy_blok']->meta->content);

        ## Рендерим контент всех блоков - обрабатываем шорткоды
        if (isset($page->blocks) && is_object($page->blocks) && $page->blocks->count()) {

            foreach ($page->blocks as $b => $block) {
                #if (is_object($block) && is_object($block->meta)) {
                if (is_object($block)) {
                    #Helper::dd($block->meta);
                    if ($block->content != '') {
                        #$block->meta->content = self::content_render($meta->content);
                        $page->blocks[$b]->content = self::content_render($block->content);
                    }
                }
            }
        }

        #Helper::tad($page);

        return View::make($template, compact('page', 'lang', 'page_meta_settings'))->render();
	}
    

	public static function content_render($page_content, $page_data = NULL){

		$regs = $change = $to = array();
		preg_match_all('~\[([^\]]+?)\]~', $page_content, $matches);

        #dd($page_content);
        #dd($matches);

		for($j=0; $j<count($matches[0]); $j++) {
			$regs[trim($matches[0][$j])] = trim($matches[1][$j]);
		}
        
        #dd($regs);
        
		if(!empty($regs)) {
			foreach($regs as $tochange => $clear):
                
                #echo "$tochange => $clear"; die;
                
				if(!empty($clear)):
					$change[] = $tochange;
					$tag = explode(' ', $clear);

                    #dd($tag);
                    
					if(isset($tag[0]) && $tag[0] == 'view') {
						$to[] = self::shortcode($clear, $page_data);
					} else {
						$to[] = self::shortcode($clear);
					}
				endif;
			endforeach;
		}
        
        #dd($change);
        
		return str_replace($change, $to, $page_content);
	}

	private static function shortcode($clear, $data = NULL){

        ## $clear - строка шорткода без квадратных скобок []
        #dd($clear);

		$str = explode(" ", $clear);
		#$type = $str[0]; ## name of shortcode
        $type = array_shift($str);
		$options = NULL;
		if(count($str)) {
			#for($i=1; $i<count($str); $i++) {
            foreach ($str as $expr) {
                if (!strpos($expr, "="))
                    continue;
				#preg_match_all("~([^\=]+?)=['\"]([^'\"\s\t\r\n]+?)['\"]~", $str[$i], $rendered);
                #dd($rendered);
                list($key, $value) = explode("=", $expr);
                $key = trim($key);
                $value = trim($value, "'\"");
				if($key != '' && $value != '') {
					$options[$key] = $value;
				}
			}
		}

        #dd($type);
        #dd($options);

		return shortcode::run($type, $options);
	}

    ## Get right page url
    private function getRightPageUrl($parameters = array()) {

        if (!$parameters)
            return false;

        $return_mode = 1;

        ## Get page slug...
        if (is_string($parameters)) {
            $return_mode = 1;
            $url = $parameters;
        } elseif (is_array($parameters)) {
            if (isset($parameters['url'])) {
                $return_mode = 2;
                $url = $parameters['url'];
            } else {
                $return_mode = 3;
                ## First param is slug
                foreach ($parameters as $p => $param) {
                    $url = $param;
                    break;
                }
            }
        }
        #Helper::dd("Page->getRightPageUrl() -> url = " . $url);

        $return = $parameters;

        $locales = Config::get('locales');
        $locale = Config::get('locale');
        $default_locale = Config::get('default_locale');

        $page = $this->page->where('publication', 1);

        ## Search slug in SEO URL
        $page_seo = Seo::where('module', 'Page')->where('url', $url)->first();
        #Helper::tad($page_meta_seo);

        ## If page not found by slug in SEO URL...
        if (is_object($page_seo) && is_numeric($page_seo->unit_id)) {

            $page = $this->page
                ->where('id', $page_seo->unit_id)
                ->with(array('meta' => function($query) use ($locales, $default_locale) {
                    #if (@is_array($locales) && count($locales) > 1) {
                        $query->where('language', $default_locale);
                    #}
                }))
                ->with('seo');

                /*
                ->with(array('page' => function($query) use ($locales, $default_locale) {
                        $query->with(array('meta' => function($query) use ($locales, $default_locale) {
                                if (@is_array($locales) && count($locales) > 1) {
                                    $query->where('language', $default_locale);
                                }
                                $query->with('seo');
                            }));
                    }));
                */

            $page = $page->first();

            #Helper::ta($page);

        } else {

            ## Search slug in PAGE SLUG
            $page = $this->page
                ->where('slug', $url)
                ->with(array('meta' => function($query) use ($locales, $default_locale) {
                        #if (@is_array($locales) && count($locales) > 1) {
                            #$query->where('language', $default_locale);
                        #}
                        #$query->with('seo');
                    }))
                ->with('seo')
                ->first();

            #Helper::ta($page);
        }

        ## Compare SEO url & gettin' $url
        if (@is_object($page) && @is_object($page->seo) && $page->seo->url != '' && $page->seo->url != $url) {

            $return_url = $page->seo->url;

            switch ($return_mode) {
                case 1:
                    $return = $return_url;
                    break;
                case 2:
                    $return['url'] = $return_url;
                    break;
                case 3:
                    foreach ($return as $r => $ret) {
                        $return[$r] = $return_url;
                        break;
                    }
                    break;
            }

            #$redirect = URL::route('page', array('url' => $page->meta->seo->url));
            #Helper::dd($redirect);
            #return Redirect::to($redirect, 301);
        }

        #Helper::dd($url . ' -> ' . $return);

        return $return;
    }

}