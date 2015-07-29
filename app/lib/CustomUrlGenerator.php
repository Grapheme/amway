<?php namespace Illuminate\Routing;

use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;

/**
 * Custom UrlGenerator Class - for redeclare route() method
 *
 * @package Illuminate\Routing
 *
 * @see \vendor\laravel\framework\src\Illuminate\Routing\UrlGenerator.php
 */
class CustomUrlGenerator extends UrlGenerator {

    private $url_modifiers = [];

	##
    ## Custom URL::route() method
    ##
	public function route($name, $parameters = array(), $absolute = true, $route = null) {
        ##
        ## Call route modifier closure
        ##
        if (isset($this->url_modifiers[$name]) && is_callable($this->url_modifiers[$name])) {
            #\Helper::dd($parameters);
            $this->url_modifiers[$name]($name, $parameters);
            #print_r($parameters);
        }


        ##
        ## !!! IMPORTANT FOR MULTILANGUAGE!!!
        ## Apply the route default parameters
        ## Need CustomRouter, CustomRoute & him custom facades
        ##
        $route = $route ?: $this->routes->getByName($name);

        if (NULL !== $route) {
            $parameters = (array)$parameters;
            $defaults = (array)$route->getDefaults();
        }

        #if ($name == 'mainpage') {
        #    var_dump($route);
        #}

        if ($name == 'page' && 0) {
            var_dump($route);
            var_dump((array)$route->parameterNames());
            var_dump($parameters);
            var_dump($defaults);
            #die;
        }

        if ($name == 'page' && 0) {
            var_dump($route);
            var_dump((array)$route->parameterNames());
            var_dump($parameters);
            var_dump($defaults);
            #die;
        }

        ## URL::route('page', 'news') => parameters: [0 => 'news'] => ['slug' => 'news']
        ## URL::route('page', ['news', 'lang' => 'en']) => parameters: [0 => 'news', 'lang' => 'en'] => ['slug' => 'news', 'lang' => 'en']
        if (is_object($route) && $route->getName() == 'page' && !isset($parameters['slug']) && isset($parameters[0])) {
            #\Helper::ta($parameters);
            $parameters['slug'] = $parameters[0];
            unset($parameters[0]);
        }

        if (NULL !== $route) {
            foreach ((array)$route->parameterNames() as $value) {
                #echo $value . '<br/>';

                /*
                ## Route parameter - required or not?
                $required = TRUE;
                preg_match('~\{' . $value . '(\?|)\}~is', $route->getPath(), $matches);
                #print_r($matches);
                if (isset($matches[1]) && $matches[1] === '?')
                    $required = FALSE;
                */

                #print_r(\Config::get('app.locale'));

                if (
                    $route->getName() == 'mainpage'
                    && $value == 'lang'
                    && isset($defaults[$value])
                    && count(\Config::get('app.locales')) > 1
                    && $defaults[$value] == \Config::get('app.locale')
                )
                    continue;

                if (
                    $route->getName() == 'page'
                    && $value == 'lang'
                    && !@$parameters['slug']
                    && count(\Config::get('app.locales')) > 1
                    && \Config::get('app.locale') == \Config::get('app.default_locale')
                )
                    continue;

                if (!isset($parameters[$value]) && isset($defaults[$value])) {
                    $parameters[$value] = $defaults[$value];
                }
            }
        }
        #print_r($parameters);


        ##
        ## Call original URL::route() with 100% right $parameters
        ##
        return parent::route($name, $parameters, $absolute, $route);
	}


    /**
     * Custom action method
     * Get the URL to a controller action.
     *
     * @param  string  $action
     * @param  mixed   $parameters
     * @param  bool    $absolute
     * @return string
     */
    public function action($action, $parameters = array(), $absolute = true)
    {
        ##
        ## Call url link modifier closure
        ##
        if (isset($action) && $action != '' && isset($this->url_modifiers[$action]) && is_callable($this->url_modifiers[$action])) {
            #\Helper::dd($parameters);
            $this->url_modifiers[$action]($action, $parameters);
        }

        return parent::route($action, $parameters, $absolute, $this->routes->getByAction($action));
    }


    ##
    ## Add route modifier closure
    ##
    public function add_url_modifier($route_name = false, $closure) {

        if (!is_string($route_name) || !is_callable($closure))
            return false;

        #\Helper::dd($route_name);

        if (!isset($this->url_modifiers[$route_name]))
            $this->url_modifiers[$route_name] = $closure;
        else
            throw new \Exception('Route "' . $route_name . '" also have modifier.');
    }


    public function get_modified_parameters($route_name, $params = array()) {
        if (isset($route_name) && $route_name != '' && isset($this->url_modifiers[$route_name]) && is_callable($this->url_modifiers[$route_name])) {
            #\Helper::d('=== START URL::get_modified_parameters() ===');
            #\Helper::d($route_name);
            #\Helper::d($params);
            $this->url_modifiers[$route_name]($route_name, $params);
            #\Helper::d($params);
            #\Helper::d('=== END URL::get_modified_parameters() ===');
        }
        return $params;
    }
}
