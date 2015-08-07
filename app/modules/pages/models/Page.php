<?php

class Page extends BaseModel {

	protected $guarded = array();

    protected $table = 'pages';

    protected $orderBy = 'order ASC, created_at DESC';
    public static $cache_key = 'app.pages';

    public static $rules = array(
	    'name' => 'required',
		#'seo_url' => 'alpha_dash'
	);

    protected $fillable = array(
        'version_of',
        'name',
        'slug',
        'sysname',
        'template',
        'type_id',
        'publication',
        'start_page',
        'parametrized',
        'order',
        'settings',
    );


    public function blocks() {
        return $this->hasMany('PageBlock', 'page_id', 'id')->orderBy('order');
    }

    public function metas() {
        return $this->hasMany('PageMeta', 'page_id', 'id');
    }

    public function meta() {
        return $this->hasOne('PageMeta', 'page_id', 'id')->where('language', Config::get('app.locale', 'ru'));
    }

    public function seos() {
        return $this->hasMany('Seo', 'unit_id', 'id')->where('module', 'Page');
    }

    public function seo() {
        return $this->hasOne('Seo', 'unit_id', 'id')->where('module', 'Page')->where('language', Config::get('app.locale', 'ru'));
    }

    public function versions() {
        return $this->hasMany('Page', 'version_of', 'id')->orderBy('updated_at', 'DESC');
    }

    public function original_version() {
        return $this->hasOne('Page', 'id', 'version_of');
    }

    public static function startPage() {
        $page = Page::firstOrNew(['start_page' => '1']);
        $page->load('meta', 'blocks.meta', 'seo');
        $page->extract(true);
        return $page;
    }

    /**
     * Depricated - use $page->extract(true);
     *
     * @return $this
     */
    /*
    public function blocksBySlug() {
        #$return = $this;
        if (@count($this->blocks)) {
            $temp = array();
            foreach ($this->blocks as $b => $block) {
                $this->blocks[$block->slug] = $block;
                unset($this->blocks[$b]);
            }
        }
        return $this;
    }
    */


    public function draw_blocks() {
        $page = $this;
        $result = [];
        if (count($page->blocks)) {
            foreach ($page->blocks as $block) {
                $result[] = $page->block($block->slug);
            }
        }
        return implode("\n", $result);
    }

    public function block($slug = false, $field = 'content', $variables = array(), $force_compile = true) {

        if (
            !$slug || !@count($this->blocks) || !@is_object($this->blocks[$slug])
            #|| (!@isset($this->blocks[$slug]->content) && !@is_object($this->blocks[$slug]->meta))
        )
            return false;

        #Helper::tad($this);

        $block = null;
        $content_container = false;
        if (isset($this->blocks[$slug]->metas[Config::get('app.locale')])) {

            $content_container = $this->blocks[$slug]->metas[Config::get('app.locale')];
            $block = $this->blocks[$slug];

        } elseif (isset($this->blocks[$slug]->content)) {

            $content_container = $this->blocks[$slug];

        } elseif (isset($this->blocks[$slug]->meta) && !is_null($this->blocks[$slug]->meta)) {

            $content_container = $this->blocks[$slug]->meta;
        }

        if (!$content_container)
            return '';

        #dd($content_container);

        #Helper::dd($this->blocks[$slug]->meta->content);
        ## Without blade syntax compile
        #return $this->blocks[$slug]->meta->content;

        ## Force template compile
        if ($force_compile)
            $content_container->updated_at = date('Y-m-d H:i:s');

        ## Without updated_at - COMPILE ONLY ONCE!
        #unset($this->blocks[$slug]->meta->updated_at);

        #Helper::ta($content_container);
        #Helper::ta($block);

        if (isset($block) && is_object($block) && isset($block->template)) {

            if ($block->template == '') {

                ## Return compiled field of the model
                return DbView::make($content_container)->field($field)->with($variables)->render();

            } else if (View::exists(Helper::layout('blocks.' . $block->template))) {

                $content = json_decode($content_container->content, true);
                $content_container->content = $content;
                #Helper::ta($content);
                #dd(extract($content));

                return View::make(Helper::layout('blocks.' . $block->template), $content);
            }

        }

        return null;
    }

    public function extract($unset = false) {

        #Helper::ta($this);

        ## Extract SEO
        if (isset($this->seos)) {
            #Helper::tad($this->seos);
            if (count($this->seos) == 1 && count(Config::get('app.locales')) == 1) {
                $app_locales = Config::get('app.locales');
                foreach ($app_locales as $locale_sign => $locale_name)
                    break;
                foreach ($this->seos as $s => $seo) {
                    $this->seos[$locale_sign] = $seo;
                    break;
                }
                unset($this->seos[0]);
                #Helper::tad($this->seos);
            } else {
                foreach ($this->seos as $s => $seo) {
                    $this->seos[$seo->language] = $seo;
                    #Helper::d($s . " != " . $seo->language);
                    if ($s != $seo->language || $s === 0)
                        unset($this->seos[$s]);
                }
            }
        }

        ## Extract metas
        if (isset($this->metas)) {
            foreach ($this->metas as $m => $meta) {
                $this->metas[$meta->language] = $meta;
                if ($m != $meta->language || $m === 0)
                    unset($this->metas[$m]);
            }
        }

        ## Extract meta
        if (isset($this->meta)) {
            if ($this->meta->template)
                $this->template = $this->meta->template;
            $this->language = $this->meta->language;
            if ($unset)
                unset($this->meta);
        }

        ## Extract blocks
        if (isset($this->blocks)) {
            #$all_blocks = [];
            $blocks = new Collection;
            foreach ($this->blocks as $b => $block) {

                #Helper::tad($block);

                $metas = new Collection;

                if (isset($block->metas)) {
                    foreach ($block->metas as $block_meta) {
                        $metas[$block_meta->language] = $block_meta;
                    }
                }

                #Helper::tad($metas);

                /*
                if (isset($block->meta)) {
                    if ($block->meta->name)
                        $block->name = $block->meta->name;
                    if ($block->meta->template)
                        $block->template = $block->meta->template;
                    $block->content = $block->meta->content;
                    if ($unset)
                        unset($block->meta);
                }
                #$this->blocks[$block->slug] = $block;
                */

                #unset($block->relations['metas']);
                #$block->metas = $metas;
                $block->setRelation('metas', $metas);

                $blocks[$block->slug] = $block;
                #$all_blocks[$block->language][$block->slug] = $block;

                #unset($this->relations['blocks']);
                #$this->relations['blocks'] = $blocks;
            }
            #unset($this->relations['blocks']);
            #$this->blocks = $blocks;
            $this->setRelation('blocks', $blocks);
        }

        #Helper::tad($this);

        return $this;
    }


    /**
     * Предзагрузка всех страниц и кеширование
     */
    public static function preload() {

        $cache_key = self::$cache_key;
        $cache_pages_limit = Config::get('pages.preload_pages_limit');

        if ($cache_pages_limit === NULL) {
            Config::set('pages.not_cached', TRUE);
            return;
        }

        if (Cache::has($cache_key) && !Input::get('drop_pages_cache')) {

            ## From cache
            $pages = Cache::get($cache_key);

        } elseif ($cache_pages_limit === 0 || ($cache_pages_limit > 0 && Page::count() <= $cache_pages_limit)) {

            #echo "LOAD PAGES FROM DB!";

            ## From DB
            $pages = (new Page())
                ->where('publication', 1)
                ->where('version_of', NULL)
                ->with(['metas', 'blocks.metas', 'seos', 'blocks.meta', 'meta', 'seo'])
                ->get()
            ;

            if (isset($pages) && is_object($pages) && count($pages)) {
                $pages_by_slug = new Collection();
                $pages_by_sysname = new Collection();
                $pages_by_id = new Collection();
                foreach ($pages as $p => $page) {

                    #Helper::ta($page);
                    $page->extract(1);
                    #Helper::tad($page);

                    $pages_by_slug[$page->start_page ? '/' : $page->slug] = $page;
                    $pages_by_sysname[$page->sysname] = $page;
                    $pages_by_id[$page->id] = $page;
                }
                $pages = ['by_slug' => $pages_by_slug, 'by_sysname' => $pages_by_sysname, 'by_id' => $pages_by_id];
            }
        }

        ## Save cache
        $cache_lifetime = Config::get('pages.preload_cache_lifetime') ?: NULL;
        if ($cache_lifetime) {
            $expiresAt = Carbon::now()->addMinutes($cache_lifetime);
            Cache::put($cache_key, $pages, $expiresAt);
        }

        Config::set($cache_key, $pages);

        #Helper::tad($pages);
    }

    public static function drop_cache() {

        $cache_key = self::$cache_key;
        Config::set($cache_key, NULL);
        Cache::forget($cache_key);
    }


    public static function all_by_slug() {
        $pages = Config::get('app.pages');
        $pages = @$pages['by_slug'];
        return $pages ?: NULL;
    }

    public static function all_by_sysname() {
        $pages = Config::get('app.pages');
        $pages = @$pages['by_sysname'];
        return $pages ?: NULL;
    }

    public static function all_by_id() {
        $pages = Config::get('app.pages');
        $pages = @$pages['by_id'];
        return $pages ?: NULL;
    }


    public static function by_slug($slug) {
        $pages = Config::get('app.pages');
        $page = @$pages['by_slug'][$slug];
        return $page ?: NULL;
    }

    public static function by_sysname($sysname) {
        $pages = Config::get('app.pages');
        $page = @$pages['by_sysname'][$sysname];
        return $page ?: NULL;
    }

    public static function by_id($id) {
        $pages = Config::get('app.pages');
        $page = @$pages['by_id'][$id];
        return $page ?: NULL;
    }


    public static function slug_by_sysname($sysname) {
        $pages = Config::get('app.pages');
        $page = @$pages['by_sysname'][$sysname];
        $slug = NULL;
        if (isset($page) && is_object($page)) {
            if ($page->start_page)
                $slug = '/';
            elseif ($page->slug)
                $slug = $page->slug;
        }
        return $slug;
    }


    public static function name_by_sysname($sysname) {
        $pages = Config::get('app.pages');
        $page = @$pages['by_sysname'][$sysname];
        $name = NULL;
        if (isset($page) && is_object($page)) {
            $name = $page->name;
        }
        return $name;
    }


    public function h1_or_name($before = '<h1>', $after = '</h1>') {
        $locale = Config::get('app.locale');
        return $before .
           (
               (
                    isset($this->seos)
                    && isset($this->seos[$locale]) && is_object($this->seos[$locale])
                    && isset($this->seos[$locale]->h1) && $this->seos[$locale]->h1
                )
            ? $this->seos[$locale]->h1
            : $this->name
           ) . $after;
    }

    public function field($name = '') {

        return @$this->page_meta_settings['fields'][$name] ?: null;
    }

}

if (!function_exists('pageslug')) {
    function pageslug($sysname) {
        return Page::slug_by_sysname($sysname);
    }
}

if (!function_exists('pageurl')) {
    function pageurl($sysname, $params = []) {
        return URL::route('page', [pageslug($sysname)] + $params);
    }
}

if (!function_exists('pagename')) {
    function pagename($sysname) {
        return Page::name_by_sysname($sysname);
    }
}

