<?php namespace Sngrl\Routing;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\RoutingServiceProvider as RoutingServiceProvider;

/**
 * Custom RoutingServiceProvider Class - for overloading router facade
 * @package Illuminate\Routing
 */
class CustomRoutingServiceProvider extends RoutingServiceProvider {

    /**
     * Register the router instance.
     *
     * @return void
     */
    protected function registerRouter()
    {

        /**
         * Call parent registerRouter() method
         */
        parent::registerRouter();

        /**
         * Overload router facade - for the sngrl custom router
         */
        $this->app['router'] = $this->app->share(function($app)
        {
            $router = new \Sngrl\Routing\Router($app['events'], $app);
            // If the current application environment is "testing", we will disable the
            // routing filters, since they can be tested independently of the routes
            // and just get in the way of our typical controller testing concerns.
            if ($app['env'] == 'testing')
            {
                $router->disableFilters();
            }

            return $router;
        });
    }

}
