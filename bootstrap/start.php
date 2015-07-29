<?php

##
## Fix SCRIPT_NAME from /public/.htaccess
##
if (isset($_SERVER['REDIRECT_SCRIPT_NAME']))
    $_SERVER['SCRIPT_NAME'] = $_SERVER['REDIRECT_SCRIPT_NAME'];

##
## Custom Request object initialization
## http://laravel.ru/articles/odd_bod/extending-request-and-response#наследование_класса_phprequest-12
##
########################################################################
$app = new Illuminate\Foundation\Application;
########################################################################
//$request = Fideloper\Example\Http\Request::createFromGlobals();
//$app = new Illuminate\Foundation\Application( $request );
########################################################################

$env = $app->detectEnvironment(array(
	'az' => array('Acer_5742G'),
	'vkharseev' => array('DNS'),
	'kd' => array('DobriyMac.local'),
	'kf' => array('Mac-mini-Admin.local', 'mac-mini-admin'),
	'at' => array('MacBook-Pro-Tommy.local'),
));
$app->bindInstallPaths(require __DIR__.'/paths.php');
$framework = $app['path.base'].'/vendor/laravel/framework/src';
require $framework.'/Illuminate/Foundation/start.php';
return $app;