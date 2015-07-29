<?php

App::before(function($request){
	//
});

App::after(function($request, $response){
	//
});

App::error(function(Exception $exception, $code){

	switch($code):
		case 403:
            return 'Access denied!';
        /*
		case 404:
			#if(Page::where('seo_url','404')->exists()):
			#	return spage::show('404',array('message'=>$exception->getMessage()));
			#else:
			#	return View::make('error404', array('message'=>$exception->getMessage()), 404);
			#endif;
        */
	endswitch;

    if (View::exists(Helper::layout($code)))
        return Response::view(Helper::layout($code), array('message' => $exception->getMessage()), $code);

});

App::missing(function ($exception) {

    #Helper::classInfo('Route');
    #Helper::dd(get_declared_classes());

    $tpl = View::exists(Helper::layout('404')) ? Helper::layout('404') : 'error404';
    return Response::view($tpl, array('message' => $exception->getMessage()), 404);
});

Route::filter('auth', function(){
	if(Auth::guest()):
		App::abort(404);
	endif;
});

Route::filter('login', function(){
	if(Auth::check()):
		return Redirect::to(AuthAccount::getStartPage());
	endif;
});

Route::filter('auth.basic', function(){
	return Auth::basic();
});

Route::filter('admin.auth', function(){

	if(!AuthAccount::isAdminLoggined()):
		return Redirect::to('/');
	endif;
});

Route::filter('user.auth', function(){
	if(!AuthAccount::isUserLoggined()):
		return Redirect::to('/');
	endif;
});

/*
|--------------------------------------------------------------------------
| Permission Filter
|--------------------------------------------------------------------------
*/
if(Auth::check()):
	#Allow::modulesFilters();
endif;

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
*/

Route::filter('guest', function(){
	if(Auth::check()):
		return Redirect::to('/');
	endif;
});

Route::filter('auth2login', function(){
    if(Auth::check()) {
        #Helper::dd(Request::path() . ' != ' . AuthAccount::getStartPage());
        if (Request::path() != AuthAccount::getStartPage())
            return Redirect::to(AuthAccount::getStartPage());
    } else {
        return Redirect::route('login');
    }
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
*/

Route::filter('csrf', function(){
	if (Session::token() != Input::get('_token')):
		throw new Illuminate\Session\TokenMismatchException;
	endif;
});

/*
|--------------------------------------------------------------------------
| Internationalization-in-url filter (I18N)
|--------------------------------------------------------------------------
*/

/*
 * Фильтр используется для переадресации мультиязычных страниц на урл, первым сегментом которого идет указатель на текущий язык, например /ru/{url}.
 * Работает в паре с кодом из /app/start/global.php
 */
Route::filter('i18n_url', function(){

    $locales = Config::get('app.locales');
    if ( @!$locales[Request::segment(1)] ) {
        if (Request::path() != '/') {
            ## Если не главная страница - подставим дефолтную локаль и сделаем редирект
            #Helper::dd(Config::get('app.locale') . '/' . Request::path());
            Redirect(URL::to(Config::get('app.locale') . '/' . Request::path()));
        }
    }
});

function Redirect($url = '', $code = '301 Moved Permanently') {
	header("HTTP/1.1 {$code}");
    header("Location: {$url}");
    die;
}


###############################################################################
## MOBILE VERSION
## Template changing by mobile subdomain
###############################################################################

$host = explode('.', Request::getHost());
if (
    count($host) > 2
    && Config::get('site.mobile.enabled')
    && NULL !== ($mobile_domain = Config::get('site.mobile.domain'))
    && NULL !== ($mobile_template = Config::get('site.mobile.template'))
    && $host[0] == $mobile_domain
    && is_dir(app_path('views/templates/' . $mobile_template))
) {
    Config::set('site.mobile.active', TRUE);
    Config::set('app.template', $mobile_template);

    if (NULL !== ($mobile_theme_path = Config::get('site.mobile.theme_path')))
        Config::set('site.theme_path', $mobile_theme_path);
    elseif (NULL !== ($mobile_theme_path = Config::get('site.mobile_theme_path')))
        Config::set('site.theme_path', $mobile_theme_path);
}


## Выводит на экран все SQL-запросы
#Event::listen('illuminate.query',function($query){ echo "<pre>" . print_r($query, 1) . "</pre>\n"; });
