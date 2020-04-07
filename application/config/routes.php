<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['api/register']['POST'] = 'UserController/register';
$route['api/login']['POST']    = 'UserController/login';

$route['default_controller'] = 'pagecontroller';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
