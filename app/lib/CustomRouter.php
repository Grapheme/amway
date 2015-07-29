<?php namespace Sngrl\Routing;

use Illuminate\Routing\Router as DefaultRouter;

/**
 * Custom Router Class with modified newRoute() method - for return custom Route class
 * @package Sngrl\Routing
 */
class Router extends DefaultRouter {

    private $patterns_defaults = [];

    /**
     * Return custom Route class
     *
     * @param array|string $methods
     * @param string       $uri
     * @param mixed        $action
     *
     * @return Route
     */
    protected function newRoute($methods, $uri, $action)
    {
        return new CustomRoute($methods, $uri, $action);
    }


    ###########################################################################


    /**
     * Return Router instance after Route::pattern() - for them set the default value
     *
     * @param string $key
     * @param string $pattern
     *
     * @return $this
     */
    public function pattern($key, $pattern)
    {
        parent::pattern($key, $pattern);
        return $this;
    }


    /**
     * Set default value for Route pattern (after declaring via Route::pattern())
     *
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function defaults($key, $value) {
        $this->patterns_defaults[$key] = $value;
        #dd( $this );
        return $this;
    }


    /**
     * Return default values for patterns
     *
     * @return array
     */
    public function get_patterns_defaults() {
        return $this->patterns_defaults;
    }


    /**
     * Add the necessary where clauses to the route based on its initial registration.
     *
     * @param  \Illuminate\Routing\Route  $route
     * @return \Illuminate\Routing\Route
     */
    protected function addWhereClausesToRoute($route)
    {
        foreach ($this->get_patterns_defaults() as $key => $value) {
            $route->defaults($key, $value);
        }
        if ($route->getName() == 'page') {
            #dd($route);
        }
        parent::addWhereClausesToRoute($route);
    }

}