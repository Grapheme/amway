<?php namespace Sngrl\Support\Facades;

use Illuminate\Support\Facades\Route as DefaultRoute;

/**
 * Custom Route Facade - for overloading custom Router class
 *
 * @see \Illuminate\Routing\Router
 */
class CustomRoute extends DefaultRoute {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'router'; }

}
