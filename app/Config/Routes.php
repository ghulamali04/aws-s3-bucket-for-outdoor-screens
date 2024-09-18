<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('/screen/screen1/(:any)', 'Home::screen_1/$1');
$routes->get('/screen/screen2/(:any)', 'Home::screen_2/$1');
$routes->get('/screen/screen3/(:any)', 'Home::screen_3/$1');
$routes->post('/screen/screen1/update', 'Home::update_screen_1');
$routes->post('/screen/screen2/update', 'Home::update_screen_2');
$routes->post('/screen/screen3/update', 'Home::update_screen_3');
$routes->get('/api/currentevent/(:any)', 'Home::api/$1');
$routes->group('dashboard', static function ($routes) {
    $routes->get('login', 'SignInController::index');
    $routes->post('login', 'SignInController::loginAuth');
    $routes->get('logout', 'SignInController::logout', ['filter' => 'authGuard']);
    $routes->get('users/settings', 'UserController::index', ['filter' => 'authGuard']);
    $routes->post('users/settings/update', 'UserController::update_profile', ['filter' => 'authGuard']);
    $routes->post('users/settings/update/password', 'UserController::change_password', ['filter' => 'authGuard']);
    $routes->get('/', 'Home::dashboard', ['filter' => 'authGuard']);
    $routes->get('events', 'EventController::index', ['filter' => 'authGuard']);
    $routes->get('events/create', 'EventController::create_event', ['filter' => 'authGuard']);
    $routes->post('events/save', 'EventController::save_event', ['filter' => 'authGuard']);
    $routes->get('events/edit/(:any)', 'EventController::edit_event/$1', ['filter' => 'authGuard']);
    $routes->post('events/update', 'EventController::update_event', ['filter' => 'authGuard']);
    $routes->post('events/details/save', 'EventController::save_detail', ['filter' => 'authGuard']);
    $routes->post('events/details/update', 'EventController::update_detail', ['filter' => 'authGuard']);
    $routes->post('events/status/update', 'EventController::change_status', ['filter' => 'authGuard']);
    $routes->get('events/delete/(:any)', 'EventController::delete_event/$1', ['filter' => 'authGuard']);
});
$routes->post('/file/upload', 'EventController::upload_file', ['filter' => 'authGuard']);
$routes->delete('/file/revert', 'EventController::revert_file', ['filter' => 'authGuard']);
$routes->get('/test', 'EventController::test');
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
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
