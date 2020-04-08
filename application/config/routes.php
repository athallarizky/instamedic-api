<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Authentication Module Routes //
$route['api/register']['POST'] = 'UserController/register';
$route['api/login']['POST']    = 'UserController/login';
$route['api/decodeToken']['GET'] = 'UserController/decodeToken';

// User Module Routes //
$route['api/update/(:any)'] = 'UserController/update/$1';


$route['default_controller'] = 'pagecontroller';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
