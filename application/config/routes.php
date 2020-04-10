<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Authentication Module Routes //
$route['api/register']['POST']   = 'UserController/register';
$route['api/login']['POST']      = 'UserController/login';
$route['api/decodeToken']['GET'] = 'UserController/decodeToken';

// User Module Routes //
$route['api/user/getLoggedUser']['GET']    = 'UserController/loggedUserData';
$route['api/user/update/(:any)']['PUT']    = 'UserController/update/$1';
$route['api/user/delete/(:any)']['DELETE'] = 'UserController/delete/$1'; //not yet

// Medicine Module Routes //
$route['api/medicine/getAll']['GET']             = 'MedicineController/getAll';
$route['api/medicine/getMedicine/(:any)']['GET'] = 'MedicineController/getMedicine/$1';
$route['api/medicine/create']['POST']            = 'MedicineController/create';
$route['api/medicine/update/(:any)']['PUT']      = 'MedicineController/update/$1';
$route['api/medicine/delete/(:any)']['DELETE']   = 'MedicineController/delete/$1';

$route['default_controller'] = 'pagecontroller';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
