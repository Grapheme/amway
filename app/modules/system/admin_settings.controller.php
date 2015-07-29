<?php

class AdminSettingsController extends BaseController {

    public static $name = 'settings';
    public static $group = 'system';
    public static $entity = 'settings';
    public static $entity_name = 'настройки';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        $entity = self::$entity;

        Route::group(array('before' => 'auth', 'prefix' => $prefix . "/" . $class::$group), function() use ($class, $entity) {

            Route::post('cache/drop', array('as' => 'drop_cache', 'uses' => $class.'@dropCache'));

            Route::post('update', array('as' => $class::$group . '.' . $class::$name . '.update', 'uses' => $class.'@update'));

            Route::resource($class::$name, $class,
                array(
                    'except' => array('show', 'create', 'store', 'edit', 'update', 'destroy'),
                    'names' => array(
                        'index'   => $class::$group . '.' . $class::$name . '.index',
                        #'create'  => $class::$group . '.' . $class::$name . '.create',
                        #'store'   => $class::$group . '.' . $class::$name . '.store',
                        #'edit'    => $class::$group . '.' . $class::$name . '.edit',
                        #'update'  => $class::$group . '.' . $class::$name . '.update',
                        #'destroy' => $class::$group . '.' . $class::$name . '.destroy',
                    )
                )
            );
        });

        if (Cache::has('cms.settings')) {
            $settings = Cache::get('cms.settings');
        } else {
            $settings = Storage::where('module', 'system')->where('name', 'settings')->pluck('value');
            $settings = json_decode($settings, true);
        }
        Config::set('app.settings', $settings);

        #$temp = Config::get('app.settings.main');
        #dd($temp);
    }

    ## Shortcodes of module
    public static function returnShortCodes() {
        ##
    }
    
    ## Actions of module (for distribution rights of users)
    public static function returnActions() {
        ##return array();
    }

    ## Info about module (now only for admin dashboard & menu)
    public static function returnInfo() {
        ##
    }
        
    /****************************************************************************/
    
	public function __construct() {

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group,
            'tpl' => static::returnTpl('admin/' . self::$name),
            'gtpl' => static::returnTpl(),

            'entity' => self::$entity,
            'entity_name' => self::$entity_name,

            'class' => __CLASS__,
        );

        View::share('module', $this->module);
	}


	public function index() {

        Allow::permission($this->module['group'], 'settings');

        ## Скелет настроек из конфига
        $settings = Config::get('settings');
        $settings_sections = isset($settings['sections']) && is_callable($settings['sections'])? $settings['sections']() : NULL;
        #Helper::ta($settings_sections);

        ## Текущие значения настроек из БД
        $settings_values = Storage::firstOrNew([
            'module' => 'system',
            'name'   => 'settings'
        ]);
        $settings_values = json_decode($settings_values->value, true);
        #Helper::tad($settings_values);

        ## Будем показывать только эту секцию
        $only_section = Input::get('section');

        return View::make($this->module['tpl'].'index', compact('settings', 'settings_sections', 'settings_values', 'only_section'));
	}

    /************************************************************************************/

	public function update() {

        Allow::permission($this->module['group'], 'settings');

		if(!Request::ajax())
            App::abort(404);

        $json_request = array('status' => FALSE, 'responseText' => '', 'responseErrorText' => '', 'redirect' => FALSE);

        ## Скелет настроек из конфига
        $settings = Config::get('settings');
        $settings_sections = isset($settings['sections']) && is_callable($settings['sections'])? $settings['sections']() : NULL;
        #Helper::ta($settings_sections);

        ## Текущие значения настроек из БД
        $settings_values = Storage::firstOrNew([
            'module' => 'system',
            'name'   => 'settings'
        ]);
        $settings_values = json_decode($settings_values->value, true);
        #Helper::tad($settings_values);

        ## Новые значения настроек - из формы
        $settings_values_new = @Input::all()['settings'];
        #Helper::tad($settings_values);

        ## Проходим все проверки, и сохраняем настройки
        ## Если в конфиге объявлены секции настроек...
        if (!is_null($settings_sections) && is_array($settings_sections) && count($settings_sections)) {

            ## Если из формы переданы новые значения настроек
            if (is_array($settings_values_new) && count($settings_values_new)) {

                ## Перебираем все секции настроек, переданных из формы
                foreach ($settings_values_new as $section_slug => $options) {

                    ## Если передана неизвестная секция - пропускаем ее
                    if (!isset($settings_sections[$section_slug]) || !is_array($settings_sections[$section_slug]) || !count($settings_sections[$section_slug]))
                        continue;

                    #Helper::ta($settings_sections[$section_slug]);

                    ## Перебираем все опции в текущей известной секции (из конфига)
                    foreach ($settings_sections[$section_slug]['options'] as $option_slug => $option_data) {

                        #Helper::ta($section_slug . ' / ' . $option_slug . ' => ');

                        $value = isset($options[$option_slug]) ? $options[$option_slug] : NULL;

                        ## If handler of field is defined
                        if (is_callable($handler = @$option_data['handler'])) {
                            #Helper::d($handler);
                            #var_dump($value);
                            $value = $handler($value);
                        }

                        if ($value === false)
                            continue;

                        #var_dump($value);

                        $settings_values[$section_slug][$option_slug] = $value;
                    }
                }
            }
        }

        ## СОХРАНЕНИЕ
        #Helper::tad($settings_values);
        Storage::updateOrCreate([
            'module' => 'system',
            'name'   => 'settings',
        ], [
            'value'  => json_encode($settings_values),
        ]);

        ## КЕШИРОВАНИЕ
        Cache::forever('cms.settings', $settings_values);

        $json_request['responseText'] = 'Сохранено';
        $json_request['status'] = TRUE;
		return Response::json($json_request, 200);
	}


    public function dropCache() {

        Cache::flush();
        echo "Cache flushed.";
    }
}


