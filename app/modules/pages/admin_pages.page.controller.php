<?php

class AdminPagesPageController extends BaseController {

    public static $name = 'pages_page';
    public static $group = 'pages';
    public static $entity = 'page';
    public static $entity_name = 'страница';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = NULL) {
        $class = __CLASS__;
        Route::group(array('before' => 'auth', 'prefix' => $prefix), function () use ($class) {
            $entity = $class::$entity;

            Route::get($class::$group . '/{id}/restore', array('as' => $entity . '.restore', 'uses' => $class . "@restore"));

            Route::get($class::$group . '/hierarchy', array('as' => $entity . '.hierarchy', 'uses' => $class . "@getHierarchy"));
            Route::post($class::$group . '/nestedsetmodel', array('as' => $class::$group . '.nestedsetmodel', 'uses' => $class . "@postAjaxNestedSetModel"));

            Route::resource($class::$group /* . "/" . $entity */, $class, array(
                'except' => array('show'), 'names' => array(
                    'index' => $entity . '.index', 'create' => $entity . '.create', 'store' => $entity . '.store', 'edit' => $entity . '.edit', 'update' => $entity . '.update', 'destroy' => $entity . '.destroy',
                )
            ));
        });

        Route::post('ajax-pages-get-page-blocks', $class . '@postAjaxPagesGetPageBlocks');
        Route::post('ajax-pages-get-block', $class . '@postAjaxPagesGetBlock');
        Route::post('ajax-pages-delete-block', $class . '@postAjaxPagesDeleteBlock');
        Route::post('ajax-pages-save-block', $class . '@postAjaxPagesSaveBlock');
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

    ## Menu elements of the module
    public static function returnMenu() {
    }

    /****************************************************************************/

    public function __construct(Page $essence, PageMeta $pages_meta, PageBlock $pages_blocks, PageBlockMeta $pages_blocks_meta) {

        $this->essence = $essence;
        $this->pages_meta = $pages_meta;
        $this->pages_blocks = $pages_blocks;
        $this->pages_blocks_meta = $pages_blocks_meta;

        $this->locales = Config::get('app.locales');

        $this->module = array(
            'name' => self::$name, 'group' => self::$group, 'rest' => self::$group, 'tpl' => static::returnTpl('admin'), 'gtpl' => static::returnTpl(), 'class' => __CLASS__,

            'entity' => self::$entity, 'entity_name' => self::$entity_name,
        );
        View::share('module', $this->module);
    }


    public function index() {

        Allow::permission($this->module['group'], 'view');

        $pages = $this->essence
            ->where('version_of', NULL)
            ->orderBy('start_page', 'DESC')
            ->with('blocks')
        ;

        $order_bys = ['date' => 'created_at', 'name' => 'name', 'url' => 'slug'];
        $order_types = ['asc', 'desc'];

        $order_by = Input::get('order_by');
        $order_type = $order_type_tmp = Input::get('order_type');

        $order_by_tmp = @$order_bys[$order_by];

        if (!$order_by_tmp) {
            $order_by_tmp = 'name';
        }

        if (!$order_type_tmp || !in_array($order_type_tmp, $order_types)) {
            $order_type_tmp = 'asc';
        }

        setcookie('admin__pages__order_by', $order_by, time()+60*60*24*30);
        setcookie('admin__pages__order_type', $order_type, time()+60*60*24*30);

        $pages = $pages->orderBy($order_by_tmp, $order_type_tmp);

        $pages = $pages->paginate(30);
        #$pages = $pages->get();

        #Helper::tad($pages);

        $locales = $this->locales;

        $list_mode = 1;

        return View::make($this->module['tpl'] . 'index', compact('pages', 'locales', 'order_by', 'order_type', 'list_mode'));
    }


    public function create() {

        Allow::permission($this->module['group'], 'create');

        $element = new $this->essence;

        $locales = $this->locales;
        #Helper::dd($locales);

        $templates = array();

        $template_exists = FALSE;

        foreach ($this->templates(Helper::theme_dir(), '') as $key => $template)
            @$templates_theme[$key] = $template;
        if (@count($templates_theme)) {

            if (@$templates_theme[$element->template])
                $template_exists = TRUE;

            natsort($templates_theme);
            $templates['Тема оформления'] = $templates_theme;
        }

        if (Allow::action('pages', 'advanced')) {
            foreach ($this->templates(__DIR__) as $template)
                @$templates_module[$template] = $template;
            if (@count($templates_module)) {

                if (@$templates_theme[$element->template])
                    $template_exists = TRUE;

                natsort($templates_module);
                $templates['Модуль'] = $templates_module;
            }
        }
        #Helper::dd($templates);

        $show_template_select = FALSE;
        if (Allow::action('pages', 'advanced')
            || !isset($element->template)
            || $template_exists
        ) {
            $show_template_select = TRUE;
        }

        return View::make($this->module['tpl'] . 'edit', compact('element', 'locales', 'templates', 'show_template_select'));
    }


    public function edit($id) {

        Allow::permission($this->module['group'], 'edit');

        $element = $this->essence->where('id', $id)#->with('metas.seo')
        ->with(['metas', 'seos'])->with('blocks')->with(['versions', 'original_version.versions'])->first();

        if (!isset($element) || !is_object($element) || !$element->id) {
            #App::abort(404);
            $class = __CLASS__;

            return Redirect::route($class::$entity . '.index');
        }

        $element->extract(FALSE);

        ##
        ## Получение страницы, с языковой МЕТА, и блоками (с языковой МЕТА) со SLUG-ключами
        ##
        #$element = Page::where('id', $id)->with('meta', 'blocks.meta', 'seo')->first()->extract(true);

        #Helper::tad($element);

        if (!is_object($element))
            return Redirect::route($this->module['entity'] . '.index');

        $locales = $this->locales;
        #Helper::dd($locales);

        $templates = array();

        $template_exists = FALSE;

        foreach ($this->templates(Helper::theme_dir(), '') as $key => $template)
            @$templates_theme[$key] = $template;
        if (@count($templates_theme)) {

            if (@$templates_theme[$element->template])
                $template_exists = TRUE;

            #die;
            #Helper::dd($templates_theme);

            natsort($templates_theme);
            $templates['Тема оформления'] = $templates_theme;
        }

        if (Allow::action('pages', 'advanced')) {
            foreach ($this->templates(__DIR__) as $template)
                @$templates_module[$template] = $template;
            if (@count($templates_module)) {

                if (@$templates_theme[$element->template])
                    $template_exists = TRUE;

                natsort($templates_module);
                $templates['Модуль'] = $templates_module;
            }
        }
        #Helper::dd($templates);

        $show_template_select = FALSE;
        if (Allow::action('pages', 'advanced')
            || !isset($element->template)
            || $template_exists
        ) {
            $show_template_select = TRUE;
        }

        ## Шаблоны блока
        $block_templates = $this->block_tpls();

        return View::make($this->module['tpl'] . 'edit', compact('element', 'locales', 'templates', 'show_template_select', 'block_templates'));
    }


    public function store() {

        return $this->postSave();
    }


    public function update($id) {

        return $this->postSave($id);
    }


    public function postSave($id = FALSE) {

        Allow::permission($this->module['group'], 'create');

        if (!Request::ajax())
            App::abort(404);

        $json_request = array('status' => FALSE, 'responseText' => '', 'responseErrorText' => '', 'redirect' => FALSE);

        $input = Input::all();

        #Helper::tad($input);

        $locales = Helper::withdraw($input, 'locales');
        $blocks = Helper::withdraw($input, 'blocks');
        $blocks_new = Helper::withdraw($input, 'blocks_new');
        #$seo = Helper::withdraw($input, 'seo');

        $fields_all = @$input['fields'];
        $element_fields = Config::get('pages.fields');
        $element_fields = is_callable($element_fields) ? $element_fields() : [];

        $input['template'] = @$input['template'] ? $input['template'] : NULL;
        $input['start_page'] = @$input['start_page'] ? 1 : NULL;

        $input['slug'] = @$input['slug'] ? $input['slug'] : ($input['start_page'] ? '' : $input['name']);
        $input['slug'] = $this->getPageSlug($input['slug']);

        $input['parametrized'] = is_int(strpos($input['slug'], '{')) ? 1 : 0;

        $input['sysname'] = @$input['sysname'] ? $input['sysname'] : ($input['start_page'] ? '' : $input['name']);
        $input['sysname'] = Helper::translit($input['sysname']);

        #Helper::tad($input);

        $json_request = array('status' => FALSE, 'responseText' => '', 'responseErrorText' => '', 'redirect' => FALSE, 'pageSlug' => $input['slug']);
        $validator = Validator::make($input, $this->essence->rules());
        if ($validator->passes()) {

            $redirect = FALSE;

            if (Allow::action('pages', 'advanced', true, false)) {
                $input['settings']['new_block'] = isset($input['settings']['new_block']) ? 1 : 0;
            }

            ## PAGES
            if ($id != FALSE && $id > 0 && NULL != ($element = $this->essence->find($id))) {

                /**
                 * Создаем резервную копию страницы со всеми данными
                 */
                $this->create_backup($id);

                if (@$input['settings'])
                    $input['settings'] = json_encode(array_merge(@(array)json_decode($element['settings']), $input['settings']));

                #$element = $this->essence->find($id);
                $element->update($input);

                ## PAGES_BLOCKS - update
                if (count($blocks)) {
                    foreach ($blocks as $block_id => $block_data) {

                        if (Allow::action('pages', 'advanced', true, false)) {

                            if (isset($block_data['slug'])) {

                                $block_data['slug'] = trim($block_data['slug']) != '' ? $block_data['slug'] : $block_data['name'];
                                $block_data['slug'] = Helper::translit($block_data['slug']);
                            }
                        }

                        #$block_data['settings'] = json_encode($block_data['settings']);
                        $block = $this->pages_blocks->find($block_id);
                        if (is_object($block)) {
                            $block->update($block_data);
                        }
                    }
                }

            } else {

                if (@$input['settings'])
                    $input['settings'] = json_encode($input['settings']);

                $element = $this->essence->create($input);
                $id = $element->id;

                $redirect = URL::route($this->module['entity'] . '.edit', array('page_id' => $id));
            }

            if (!is_null($element->start_page))
                $this->essence->where('start_page', 1)->where('id', '!=', $element->id)->update(array('start_page' => NULL));

            ## PAGES_META
            if (count($locales)) {
                foreach ($locales as $locale_sign => $locale_settings) {

                    $seo = Helper::withdraw($locale_settings, 'seo');

                    $page_meta = $this->pages_meta->where('page_id', $element->id)->where('language', $locale_sign)->first();

                    ## Сохраняем доп. поля
                    ## Сыровато, требует доработки - нужно сделать по аналогии со словарями
                    #Helper::tad($fields_all);
                    $fields = (array)$fields_all[$locale_sign];
                    if (count($fields)) {
                        foreach ($fields as $field_name => $field) {

                            if (is_callable($handler = @$element_fields[$field_name]['handler'])) {
                                #Helper::d($handler);
                                $field = $handler($field, $element);
                                $fields[$field_name] = $field;
                            }
                        }
                    }
                    $settings = is_object($page_meta) ? @json_decode($page_meta->settings, true) : [];
                    $settings['fields'] = $fields;
                    $locale_settings['settings'] = json_encode($settings);

                    $locale_settings['template'] = @$locale_settings['template'] ? $locale_settings['template'] : NULL;

                    if (is_object($page_meta)) {
                        $page_meta->update($locale_settings);
                    } else {
                        $locale_settings['page_id'] = $id;
                        $locale_settings['language'] = $locale_sign;
                        $page_meta = $this->pages_meta->create($locale_settings);
                    }

                    ## PAGES META SEO
                    #if (isset($seo[$locale_sign])) {
                    if (isset($seo)) {

                        ###############################
                        ## Process SEO
                        ###############################
                        $seo_result = ExtForm::process('seo', array(
                            'module' => 'Page', 'unit_id' => $element->id, 'locale' => $locale_sign, 'data' => $seo,
                        ));
                        #Helper::tad($seo_result);
                        ###############################
                    }
                }
            }

            ## PAGES_BLOCKS - create
            if (count($blocks_new)) {
                foreach ($blocks_new as $null => $block_data) {

                    $block_data['page_id'] = $id;

                    if (Allow::action('pages', 'advanced', true, false)) {

                        if (isset($block_data['slug'])) {

                            $block_data['slug'] = trim($block_data['slug']) != '' ? $block_data['slug'] : $block_data['name'];
                            $block_data['slug'] = Helper::translit($block_data['slug']);
                        }
                    }

                    $this->pages_blocks->create($block_data);
                }
            }

            #$json_request['responseText'] = Helper::d($redirect);
            #return Response::json($json_request,200);

            ## Clear & reload pages cache
            Page::drop_cache();
            Page::preload();

            $json_request['responseText'] = 'Сохранено';
            if ($redirect)
                $json_request['redirect'] = $redirect;
            $json_request['status'] = TRUE;

        } else {

            $json_request['responseText'] = 'Неверно заполнены поля';
            $json_request['responseErrorText'] = $validator->messages()->all();
        }

        return Response::json($json_request, 200);
    }


    public function destroy($id) {

        if (!Request::ajax())
            App::abort(404);

        Allow::permission($this->module['group'], 'delete');

        $json_request = array('status' => FALSE, 'responseText' => '');

        $page = $this->essence->find($id);
        if (is_object($page)) {

            $metas = $this->pages_meta->where('page_id', $id)->get();
            if (count($metas)) {

                /**
                 * Delete SEO data of the PageMeta
                 * DEPRICATED
                 */
                if (class_exists('Seo')) {
                    $metas_list = $metas->lists('id');
                    Seo::where('module', 'page_meta')->whereIn('unit_id', $metas_list)->delete();
                }

                foreach ($metas as $meta) {
                    $meta->delete();
                }
            }

            $blocks = $this->pages_blocks->where('page_id', $id)->get();
            if (count($blocks)) {
                foreach ($blocks as $block) {
                    $block_metas = $this->pages_blocks_meta->where('block_id', $block->id)->get();
                    foreach ($block_metas as $block_meta) {
                        $block_meta->delete();
                    }
                    $block->delete();
                }
            }

            /**
             * Delete SEO data of the Page
             */
            if (class_exists('Seo')) {
                Seo::where('module', 'Page')->where('unit_id', $page->id)->delete();
            }
        }
        $page->delete();

        ## Clear & reload pages cache
        Page::drop_cache();
        Page::preload();

        $json_request['responseText'] = 'Страница удалена';
        $json_request['status'] = TRUE;

        return Response::json($json_request, 200);
    }



    private function block_tpls($slug = null) {

        ## Шаблоны блока
        $block_templates = Config::get('pages.block_templates');
        if (is_callable($block_templates)) {

            $block_templates = $block_templates();

            if (is_array($block_templates) && count($block_templates)) {

                $temp = [];
                foreach ($block_templates as $block_name => $block_template) {

                    $temp[$block_name] = (isset($block_template['title']) ? $block_template['title'] : $block_name);
                }

                $block_templates = $temp;
            }
        }
        #Helper::tad($block_templates);

        #if (isset($slug) && $slug && isset($block_templates[$slug]) && is_array($block_templates[$slug])) {
        #    return $block_templates[$slug];
        #}

        return $block_templates;
    }


    public function postAjaxPagesDeleteBlock() {

        if (!Request::ajax())
            App::abort(404);

        $id = Input::get('id');
        #Helper::d($id);
        $block = PageBlock::where('id', $id)->with('metas')->first();
        #Helper::tad($block);
        if (is_object($block)) {
            if (count($block->metas)) {
                foreach ($block->metas as $meta) {
                    $meta->delete();
                }
            }
            $block->delete();

            ## Clear & reload pages cache
            Page::drop_cache();
            Page::preload();

            return 1;
        }

        return 0;
    }


    public function postAjaxPagesGetPageBlocks() {

        if (!Request::ajax())
            App::abort(404);

        $block_templates = $this->block_tpls();

        $id = Input::get('id');
        $blocks = PageBlock::where('page_id', $id)->with('metas')->orderBy('order')->get();
        #return $blocks->toJson();

        $return = '';
        if (count($blocks)) {
            foreach ($blocks as $block) {
                $return .= View::make($this->module['tpl'] . '_block', compact('block', 'block_templates'));
            }
        }

        return $return;
    }


    public function postAjaxPagesBlocksOrderSave() {

        if (!Request::ajax())
            App::abort(404);

        $poss = Input::get('poss');

        $pls = PageBlock::whereIn('id', $poss)->get();

        if ($pls) {
            foreach ($pls as $pl) {
                $pl->order = array_search($pl->id, $poss);
                $pl->save();
            }

            ## Clear & reload pages cache
            Page::drop_cache();
            Page::preload();
        }

        return Response::make('1');
    }


    public function postAjaxPagesGetBlock() {

        if (!Request::ajax())
            App::abort(404);

        $element = PageBlock::where('id', Input::get('id'))->with('metas')->orderBy('order')->first()->metasByLang();
        #return $block->toJson();

        $locales = $this->locales;

        #Helper::dd($this->templates(__DIR__, '/views/tpl_block'));

        $templates = [];
        #foreach ($this->templates(__DIR__, '/views/tpl_block') as $template)
        #    @$templates[$template] = $template;

        $block_templates = $this->block_tpls();
        #Helper::tad($block_template);

        return View::make($this->module['tpl'] . '_block_edit', compact('element', 'locales', 'templates', 'block_templates'));

    }


    public function postAjaxPagesSaveBlock() {

        #if(!Request::ajax())
        #    App::abort(404);

        /*
        if (Input::get('id'))
            $block = PageBlock::where('id', Input::get('id'))->first();
        else
            $block = new PageBlock;
        */

        #Helper::tad(Input::all());

        $id = Input::get('id');

        $block = $id != FALSE && $id > 0 && $this->pages_blocks->find($id)->exists() ? $this->pages_blocks->find($id) : new PageBlock;

        $input = Input::all();
        $locales = Helper::withdraw($input, 'locales');

        if (Allow::action('pages', 'advanced', true, false)) {

            if (isset($input['template'])) {

                $input['template'] = trim($input['template']) != '' ? $input['template'] : NULL;
            }

            if (isset($input['slug'])) {

                $input['slug'] = trim($input['slug']) != '' ? $input['slug'] : $input['name'];
                $input['slug'] = Helper::translit($input['slug']);
            }

            $input['settings']['system_block'] = isset($input['settings']['system_block']) ? 1 : 0;
        }

        if (@$input['settings'])
            $input['settings'] = json_encode(array_merge(@(array)json_decode($block['settings']), $input['settings']));

        $validator = Validator::make($input, $this->pages_blocks->rules());
        if ($validator->passes()) {

            $redirect = FALSE;

            ## BLOCK
            /*
            if ($id != FALSE && $id > 0 && $this->pages_blocks->find($id)->exists()) {

                $element = $this->pages_blocks->find($id);
                $element->update($input);

            } else {

                $element = $this->pages_blocks->create($input);
                $id = $element->id;
            }
            */
            $block->save();
            $block->update($input);

            ## BLOCK_META
            if (count($locales)) {
                foreach ($locales as $locale_sign => $locale_settings) {

                    $locale_settings['template'] = @$locale_settings['template'] ? $locale_settings['template'] : NULL;

                    #dd($locale_settings);
                    if (isset($locale_settings['content']) && is_array($locale_settings['content'])) {
                        $locale_settings['content'] = json_encode($locale_settings['content'], JSON_UNESCAPED_UNICODE);
                    }

                    $block_meta = $this->pages_blocks_meta->where('block_id', $block->id)->where('language', $locale_sign)->first();
                    if (is_object($block_meta)) {
                        $block_meta->update($locale_settings);
                    } else {
                        $locale_settings['block_id'] = $id;
                        $locale_settings['language'] = $locale_sign;
                        $this->pages_blocks_meta->create($locale_settings);
                    }
                }
            }

            ## Clear & reload pages cache
            Page::drop_cache();
            Page::preload();

            $json_request['responseText'] = 'Сохранено';
            if (@$redirect)
                $json_request['redirect'] = $redirect;
            $json_request['status'] = TRUE;
        } else {
            $json_request['responseText'] = 'Неверно заполнены поля';
            $json_request['responseErrorText'] = $validator->messages()->all();
        }


        return Response::json($json_request, 200);
        #return '';
    }



    public function getHierarchy() {

        $pages = $this->essence
            ->where('version_of', NULL)
            ->orderBy('start_page', 'DESC')
            ->get()
        ;

        #Helper::tad($pages);

        if (count($pages)) {

            $temp = new Collection();
            foreach ($pages as $page) {
                $temp[$page->id] = $page;
            }
            $pages = $temp;
        }

        $pages2 = clone $pages;

        /*
        $temp = [];
        $lft = 0;
        foreach ($pages as $page) {
            ++$lft;
            $rgt = $lft+1;
            $temp[$page->id] = [
                'id' => $page->id,
                'lft' => $lft,
                'rgt' => $rgt,
            ];
            ++$lft;
        }
        Helper::tad(json_encode($temp));
        #*/

        $current_hierarchy = Storage::where('module', 'pages')->where('name', 'hierarchy')->pluck('value');
        #Helper::tad($current_hierarchy);

        /*
        $temp = [
            1 => [
                'lft' => 1,
                'rgt' => 2,
            ],
        ];
        Helper::tad(json_encode($temp));
        */

        $elements = json_decode($current_hierarchy, false);
        #Helper::tad($elements);
        #dd($elements);

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

        $hierarchy = (new NestedSetModel())->get_hierarchy_from_id_left_right($id_left_right);
        #Helper::tad($hierarchy);

        $total_elements = count($pages);

        $sortable = 9;

        return View::make($this->module['tpl'] . 'hierarchy', compact('pages', 'pages2', 'hierarchy', 'total_elements', 'sortable'));
    }


    public function postAjaxNestedSetModel() {

        #$input = Input::all();

        $data = Input::get('data');
        $data = json_decode($data, 1);
        #Helper::dd($data);
        $dic_id = NULL;
        $dic = NULL;

        if (count($data)) {

            $id_left_right = (new NestedSetModel())->get_id_left_right($data);

            #Helper::tad($id_left_right);

            Storage::where('module', 'pages')->where('name', 'hierarchy')->update(['value' => json_encode($id_left_right)]);

            #if (count($id_left_right)) {

                /*
                $dicvals = DicVal::whereIn('id', array_keys($id_left_right))->get();

                if (count($dicvals)) {
                    foreach ($dicvals as $dicval) {
                        if (!$dic_id)
                            $dic_id = $dicval->dic_id;
                        $dicval->lft = $id_left_right[$dicval->id]['left'];
                        $dicval->rgt = $id_left_right[$dicval->id]['right'];
                        $dicval->save();
                    }
                    if ($dic_id) {
                        $dic = Dic::by_id($dic_id);
                    }
                }
                */
            #}
        }

        return Response::make('1');
    }


    /**
     * Функция сохраняет текущее состояние записи,
     * восстанавливает состояние записи из резервной копии
     * и удаляет все резервные копии, превысившие лимит
     *
     * @param $id
     *
     * @return string
     * @throws Exception
     */
    public function restore($id) {

        /**
         * Находим запись резервной копии для восстановления
         */
        $version = $this->essence->where('id', $id)->with(['metas', 'blocks.metas', 'seos'])->first();

        if (!isset($version) || !is_object($version) || $version->version_of == NULL)
            return Redirect::to(URL::previous());

        #Helper::tad($version);

        /**
         * Находим запись оригинала
         */
        $element = $this->essence->where('id', $version->version_of)->with(['metas', 'blocks.metas', 'seos', 'versions'])->first();

        if (!isset($element) || !is_object($element) || $element->version_of != NULL)
            return Redirect::to(URL::previous());

        #Helper::ta($version);
        #Helper::ta($element);
        #dd();

        #Helper::ta($element->versions);

        /**
         * Создаем резервную копию оригинальной записи
         */
        $create_backup_result = $this->create_backup($version->version_of, FALSE);

        if (!$create_backup_result) {
            throw new Exception("Can't create backup of original record");
        }

        #Helper::tad($element->versions);

        /**
         * Восстанавливаем содержимое записи из резервной копии
         */
        $restore_backup_result = $this->restore_backup($version->id);

        if (!$restore_backup_result) {
            throw new Exception("Can't restore backup of original record");
        }

        /**
         * Удаляем старые резервные копии (если их больше лимита)
         */
        $delete_backup_result = $this->delete_backups($element->id);

        if (!$delete_backup_result) {
            throw new Exception("Can't delete over backups of original record");
        }

        #Helper::dd((int)$create_backup_result . ' / ' . (int)$restore_backup_result . ' / ' . (int)$delete_backup_result);

        $url = action('page.edit', array('id' => $element->id)) . (Request::getQueryString() ? '?' . Request::getQueryString() : '');

        #Helper::d($element);
        #Helper::dd($url);

        ## Clear & reload pages cache
        Page::drop_cache();
        Page::preload();

        #return Redirect::to($url);
        Redirect($url);

        return '';
    }


    /**
     * Функция создания бэкапа из текущей версии, с возможностью удаления превысивших лимит резервных копий
     *
     * @param int  $page_id
     * @param bool $delete_over_backups
     *
     * @return bool
     */
    private function create_backup($page_id = 0, $delete_over_backups = TRUE) {

        /**
         * Находим запись для создания ее бэкапа
         * Запись должна быть оригиналом, т.е. иметь version_of = NULL
         */
        $element = $this->essence->where('id', $page_id)->with(['metas', 'blocks.metas', 'seos', 'versions'])->first();
        if (!isset($element) || !is_object($element) || $element->version_of != NULL)
            return FALSE;

        #Helper::tad($element);
        #Helper::tad($element->seos);

        $versions = Config::get('pages.versions');
        $element_versions = $element->versions;
        #Helper::tad($element_versions);

        /**
         * Создадим резервную копию записи
         */
        $version_array = $element->toArray();
        unset($version_array['versions'], $version_array['id'], $version_array['created_at'], $version_array['updated_at']);
        $version_array['version_of'] = $element->id;
        #Helper::tad($version_array);
        $new_version = $this->essence->create($version_array);
        #Helper::d($version_array);
        #Helper::tad($new_version);

        /**
         * Создадим резервные копии всех META данных текущей записи
         */
        $element_metas = $element->metas;
        #Helper::tad($element_metas);
        if (count($element_metas)) {
            foreach ($element_metas as $a => $element_meta) {
                $element_meta = $element_meta->toArray();
                #Helper::dd($element_meta);
                unset($element_meta['id'], $element_meta['created_at'], $element_meta['updated_at']);
                $element_meta['page_id'] = $new_version->id;
                $temp = $this->pages_meta->firstOrCreate($element_meta);
            }
        }

        /**
         * Создадим резервные копии всех блоков (и их META данных) текущей записи
         */
        $element_blocks = $element->blocks;
        #Helper::tad($element_blocks);
        if (count($element_blocks)) {
            foreach ($element_blocks as $e => $element_block) {
                if (isset($element_block) && is_object($element_block) && $element_block->id) {
                    #Helper::ta($element_block);
                    /**
                     * Создаем резервную копию блока
                     */
                    $block_metas = $element_block->metas;
                    $element_block_backup = $element_block->toArray();
                    unset($element_block_backup['id'], $element_block_backup['created_at'], $element_block_backup['updated_at'], $element_block_backup['metas']);
                    $element_block_backup['page_id'] = $new_version->id;
                    $new_block = $this->pages_blocks->firstOrCreate($element_block_backup);
                    if (is_object($new_block) && $new_block->id && isset($block_metas) && count($block_metas)) {
                        /**
                         * Создаем резервную копию всех META данных блока
                         */
                        foreach ($block_metas as $b => $block_meta) {
                            $block_meta_backup = $block_meta->toArray();
                            unset($block_meta_backup['id'], $block_meta_backup['created_at'], $block_meta_backup['updated_at']);
                            $block_meta_backup['block_id'] = $new_block->id;
                            $new_block_meta = $this->pages_blocks_meta->firstOrCreate($block_meta_backup);
                        }
                    }
                }
            }
        }

        /**
         * Создадим резервные копии SEO данных текущей записи
         */
        $element_seos = $element->seos;
        #Helper::tad($element_seos);
        if (count($element_seos)) {
            foreach ($element_seos as $e => $element_seo) {
                if (isset($element_seo) && is_object($element_seo) && $element_seo->id) {
                    $seo_backup = $element_seo->toArray();
                    unset($seo_backup['id'], $seo_backup['created_at'], $seo_backup['updated_at']);
                    $seo_backup['unit_id'] = $new_version->id;
                    if (!$seo_backup['language'])
                        $seo_backup['language'] = Config::get('app.locale');
                    #Helper::ta($seo_backup);
                    if (class_exists('Seo'))
                        $temp = Seo::firstOrCreate($seo_backup);
                }
            }
        }

        /**
         * Если кол-во существующих версий > заданного в конфиге лимита - удалим все старые версии, оставив (LIMIT-1) самых свежих
         * В данный момент count($element_versions) уже реально на 1 больше, т.к. мы только что создали свежую резервную копию!
         */
        #Helper::dd((int)$delete_over_backups);
        if (count($element_versions) >= $versions && $delete_over_backups) {
            $this->delete_backups($element->id);
        }

        return TRUE;
    }


    /**
     * Функция восстанавливает состояние записи из резервной копии
     *
     * @param int $page_id
     *
     * @return bool
     */
    private function restore_backup($page_id = 0) {

        /**
         * Находим резервную копию записи для восстановления
         * Она должна быть резервной копией, т.е. иметь version_of != NULL
         */
        $version = $this->essence->where('id', $page_id)->with(['metas', 'blocks.meta', 'seos'])->first();
        if (!isset($version) || !is_object($version)) {
            return FALSE;
        }
        #Helper::tad($version);

        /**
         * Находим запись оригинала
         */
        $element = $this->essence->where('id', $version->version_of)->with(['metas', 'blocks.meta', 'seos'])->first();
        if (!isset($element) || !is_object($element)) {
            return FALSE;
        }

        /**
         * Временно меняем id оригинала. Если восстановление не удастся - придется его восстановить
         */
        $original_id = $element->id;
        #$element->id = 0;

        /**
         * Открываем транзакцию
         */
        DB::transaction(function () use ($element, $version, $original_id) {

            /**
             * Удаляем все META данные оригинала, и ставим на их место META данные резервной копии
             */
            if (count($element->metas)) {
                foreach ($element->metas as $e => $meta) {
                    $meta->delete();
                }
                foreach ($version->metas as $v => $meta) {
                    $meta->page_id = $original_id;
                    $meta->save();
                }
            }


            /**
             * Удаляем все блоки оригинала (и их META данные), и ставим на их место блоки (и их META данные) резервной копии
             */
            if (count($element->blocks)) {
                /**
                 * Удаляем все текущие блоки и их META данные
                 */
                $element_blocks_ids = $element->blocks->lists('id');
                $this->pages_blocks_meta->whereIn('block_id', $element_blocks_ids)->delete();
                $this->pages_blocks->whereIn('id', $element_blocks_ids)->delete();

                /**
                 * Обновляем блоки из резервной копии
                 */
                if (count($version->blocks)) {
                    $version_blocks_ids = $version->blocks->lists('id');
                    $this->pages_blocks->whereIn('id', $version_blocks_ids)->update(array('page_id' => $original_id));
                    /*
                    foreach ($version->blocks as $v => $version_block) {
                        $version_block->page_id = $original_id;
                        $version_block->save();
                    }
                    */
                }
            }

            /**
             * Удаляем все SEO данные оригинала, и ставим на их место SEO данные резервной копии
             */
            if (count($element->seos) && class_exists('Seo')) {
                foreach ($element->seos as $e => $seo) {
                    $seo->delete();
                }
                foreach ($version->seos as $v => $seo) {
                    $seo->unit_id = $original_id;
                    $seo->save();
                }
            }

            /**
             * Удаляем оригинал
             */
            $element->delete();

            /**
             * Ставим бекап на место оригинала
             */
            $version->id = $original_id;
            $version->version_of = NULL;
            $version->save();
        });

        /**
         * Проверяем, успешно ли выполнились все запросы внутри транзакции, и возвращаем результат
         */

        return ($version->id == $original_id);
    }


    /**
     * Функция удаляет резервные копии, превысившие лимит
     *
     * @param int $page_id
     *
     * @return bool
     */
    private function delete_backups($page_id = 0) {

        /**
         * Находим запись для удаления ее бэкапов
         * Запись должна быть оригиналом, т.е. иметь version_of = NULL
         */
        $element = $this->essence->where('id', $page_id)->with(['metas', 'blocks.metas', 'seos', 'versions'])->first();
        if (!isset($element) || !is_object($element) || $element->version_of != NULL)
            return FALSE;

        #Helper::tad($element);

        $versions = Config::get('pages.versions');
        $element_versions = $element->versions;

        $result = TRUE;

        if (count($element_versions) > 0 && count($element_versions) >= $versions) {
            /**
             * Вычисляем ID записей, подлежащих удалению
             */
            $for_delete = $element_versions->lists('id');
            krsort($for_delete);
            $for_delete = array_slice($for_delete, 0, count($element_versions) - $versions);
            #Helper::dd($for_delete);

            if (count($for_delete)) {
                $result = FALSE;
                /**
                 * Открываем транзакцию
                 */
                DB::transaction(function () use ($element, $for_delete, $result) {
                    /**
                     * Удаляем старые резервные копии и META данные, блоки (и их META данные) и SEO-данные
                     */
                    $this->pages_meta->whereIn('page_id', $for_delete)->delete();
                    $element_blocks = $this->pages_blocks->whereIn('page_id', $for_delete)->get();
                    if (isset($element_blocks) && count($element_blocks)) {
                        $element_blocks_ids = $element_blocks->lists('id');
                        $element_blocks_metas = $this->pages_blocks_meta->whereIn('block_id', $element_blocks_ids)->delete();
                    }
                    $this->pages_blocks->whereIn('page_id', $for_delete)->delete();
                    if (Allow::module('seo')) {
                        Seo::where('module', 'Page')->whereIn('unit_id', $for_delete)->delete();
                    }
                    $deleted = $this->essence->where('version_of', $element->id)->whereIn('id', $for_delete)->delete();
                    #Helper::d($deleted);
                    #Helper::dd($for_delete);
                    if ($deleted)
                        $result = TRUE;
                });
            }
        }

        return $result;
    }

    /**
     * @param $input
     *
     * @return bool|mixed|string
     */
    public function getPageSlug($s) {

        $space = '-';
        $s = (string)$s; // преобразуем в строковое значение
        $s = strip_tags($s); // убираем HTML-теги
        $s = str_replace(["\n", "\r"], " ", $s); // убираем перевод каретки
        $s = preg_replace('/ +/', ' ', $s); // удаляем повторяющие пробелы
        $s = trim($s); // убираем пробелы в начале и конце строки
        ## Переводим строку в нижний регистр (иногда надо задать локаль)
        $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s);
        $s = strtr($s, array(
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'e', 'ж' => 'j', 'з' => 'z',
            'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
            'ы' => 'y', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya', 'ъ' => '', 'ь' => '',

            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'E', 'Ж' => 'J', 'З' => 'Z',
            'И' => 'I', 'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R',
            'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C', 'Ч' => 'CH', 'Ш' => 'SH', 'Щ' => 'SCH',
            'Ы' => 'Y', 'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA', 'Ъ' => '', 'Ь' => '',
        ));
        $s = preg_replace("~[^0-9A-Za-z-_/{} ]~i", "", $s); // очищаем строку от недопустимых символов
        $s = str_replace(" ", $space, $s); // заменяем пробелы знаком минус

        $s = trim($s, ' /'); ## удаляем с начала косую черту

        return $s; // возвращаем результат

        #return Helper::translit($input['slug']);
    }

}
