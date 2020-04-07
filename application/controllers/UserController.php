<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('User');
	}

	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function register()
	{
		$saveUser = $this->User->saveUser();
		return $this->response($saveUser);
	}

	public function response($data)
	{
		$this->output
			 ->set_content_type("application/json")
			 ->set_status_header(200)
			 ->set_output(json_encode($data, 
				 JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			 ->_display();
		
		exit;
	}
}