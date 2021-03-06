<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Authentication Module Routes //
$route['api/register']['POST']   = 'UserController/register';
$route['api/login']['POST']      = 'UserController/login';
$route['api/decodeToken']['GET'] = 'UserController/decodeToken';

// User Module Routes //
$route['api/user/getLoggedUser']['OPTIONS'] = 'UserController/loggedUserData';
$route['api/user/getLoggedUser']['GET']     = 'UserController/loggedUserData';

$route['api/user/update/(:any)']['OPTIONS'] = 'UserController/update/$1';
$route['api/user/update/(:any)']['PUT']     = 'UserController/update/$1';

$route['api/user/getAllDoctor']['OPTIONS']  = 'UserController/getAllDoctor';
$route['api/user/getAllDoctor']['GET']      = 'UserController/getAllDoctor';

$route['api/user/getDoctorById/(:any)']['OPTIONS']  = 'UserController/getDoctorById/$1';
$route['api/user/getDoctorById/(:any)']['GET']      = 'UserController/getDoctorById/$1';

$route['api/user/getDoctorByUsername/(:any)']['OPTIONS']  = 'UserController/getDoctorByUsername/$1';
$route['api/user/getDoctorByUsername/(:any)']['GET']  = 'UserController/getDoctorByUsername/$1';

$route['api/user/createDoctor']['OPTIONS']  = 'UserController/createDoctor';
$route['api/user/createDoctor']['POST']     = 'UserController/createDoctor';

$route['api/user/deleteDoctor/(:any)']['OPTIONS']  = 'UserController/deleteDoctor/$1';
$route['api/user/deleteDoctor/(:any)']['DELETE']  = 'UserController/deleteDoctor/$1';

// Medicine Module Routes //
$route['api/medicine/getAll']['GET']             = 'MedicineController/getAll';
$route['api/medicine/get/(:any)']['GET']         = 'MedicineController/get/$1';

$route['api/medicine/create']['OPTIONS']         = 'MedicineController/create';
$route['api/medicine/create']['POST']            = 'MedicineController/create';

$route['api/medicine/update/(:any)']['OPTIONS']  = 'MedicineController/update/$1';
$route['api/medicine/update/(:any)']['PUT']      = 'MedicineController/update/$1';

$route['api/medicine/delete/(:any)']['OPTIONS']  = 'MedicineController/delete/$1';
$route['api/medicine/delete/(:any)']['DELETE']   = 'MedicineController/delete/$1';

$route['api/medicine/search']['POST']            = 'MedicineController/search';

// Consultation Module Routes //
$route['api/consultation/get/(:any)']['OPTIONS']       = 'ConsultationController/get/$1';
$route['api/consultation/get/(:any)']['GET']       = 'ConsultationController/get/$1';

$route['api/consultation/getAll/(:any)']['OPTIONS']       = 'ConsultationController/getAll/$1';
$route['api/consultation/getAll/(:any)']['GET']       = 'ConsultationController/getAll/$1';

$route['api/consultation/create/(:any)']['OPTIONS']   = 'ConsultationController/create/$1';
$route['api/consultation/create/(:any)']['POST']   = 'ConsultationController/create/$1';

$route['api/consultation/delete/(:any)']['OPTIONS'] = 'ConsultationController/delete/$1';
$route['api/consultation/delete/(:any)']['DELETE'] = 'ConsultationController/delete/$1';

// Consultation: messages Module Routes //
$route['api/consultation/message/get/(:any)']['OPTIONS']      = 'MessageController/get/$1';
$route['api/consultation/message/get/(:any)']['GET']      = 'MessageController/get/$1';

$route['api/consultation/message/create/(:any)']['OPTIONS']  = 'MessageController/create/$1';
$route['api/consultation/message/create/(:any)']['POST']  = 'MessageController/create/$1';


$route['default_controller'] = 'pagecontroller';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
