<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/JWT.php';
use \Firebase\JWT\JWT;

class UserController extends CI_Controller {

	private $secretKey = "YXRoYWxsYXNheWFuZ3Jpc21h";

	public function __construct(){
		parent::__construct();
		$this->load->model('User');
	}

	public function index(){
		$this->load->view('welcome_message');
	}

	public function register(){
		$saveUser = $this->User->saveUser();
		return $this->response($saveUser);
	}

	public function response($data){
		$this->output
			 ->set_content_type("application/json")
			 ->set_status_header(200)
			 ->set_output(json_encode($data, 
				 JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			 ->_display();
		
		exit;
	}

	public function login(){
		$date = new DateTime();

		if(!$this->User->checkAuth()){
			return $this->response([
				'success' => false,
				'message' => 'Username or Password is Incorrect.'
			]);
		}

		$getUserLogged = $this->User->getUserData('username', $this->input->post('username'));
		
		$payload = [
			'id'   	   => $getUserLogged->id,
			'iat'      => $date->getTimestamp(),
			'exp'      => $date->getTimestamp() + (3600*1)
		];

		$data['token'] = JWT::encode($payload, $this->secretKey);

		$this->response($data);
	}

	public function decodeToken(){
		
		$header = $this->input->get_request_header('Authorization');

		try{
			$data = JWT::decode($header, $this->secretKey, ['HS256']);
			return $data->id;
		}catch(\Exception $err){
			return $this->response([
				'success' => false,
				'message' => 'Invalid Token.'
			]);
		}
	}
}
