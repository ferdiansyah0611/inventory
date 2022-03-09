<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->group('auth', function($routes){
    $routes->match(['get', 'post'], 'signin', 'AuthController::login', ['as' => 'login']);
    $routes->match(['get', 'post'], 'signup', 'AuthController::register', ['as' => 'register']);
    $routes->get('logout', 'AuthController::logout', ['as' => 'logout', 'filter' => 'authorize']);
});
$routes->group('', ['filter' => 'authorize'], function($routes){
    $routes->get('/', 'Home::index');
    $routes->group('report', function($routes){
        $routes->get('invoice/(:num)', 'ReportController::invoice/$1');
        $routes->get('today', 'ReportController::today_report');
    });
    $routes->group('chart', function($routes){
        $routes->get('order', 'ChartController::order');
        $routes->get('price-last', 'ChartController::price_last');
    });
    $routes->resource('brand', [
        'controller' => 'BrandController',
        'except' => 'show,update'
    ]);
    $routes->resource('category', [
        'controller' => 'CategoryController',
        'except' => 'show,update'
    ]);
    $routes->resource('user', [
        'controller' => 'UserController',
        'except' => 'show,update'
    ]);
    $routes->resource('order', [
        'controller' => 'OrderController',
        'except' => 'show,update'
    ]);
    $routes->resource('product', [
        'controller' => 'ProductController',
        'except' => 'show,update'
    ]);
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
