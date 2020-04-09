<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/JWT.php';
use \Firebase\JWT\JWT;

class UserController extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('User');
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

		$data['token'] = JWT::encode($payload, env('SECRETKEY'));
		return $this->response($data);
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

	public function decodeToken(){
		
		$header = $this->input->get_request_header('Authorization');

		try{
			$data = JWT::decode($header, env('SECRETKEY'), ['HS256']);
			var_dump("Current id: " . $data->id);
			return $data->id;
		}catch(\Exception $err){
			return $this->response([
				'success' => false,
				'message' => 'Invalid Token.'
			]);
		}
	}

	public function loggedUserData(){
		return $getUserLogged = $this->User->getUserData('id', $this->decodeToken());
	}

	public function update($id){

		$checkTokenId = $this->decodeToken();

		if($checkTokenId == $id) return $this->response($this->User->updateUser($id, $this->parseInput()));
		else return $this->response([
			'success' => false,
			'message' => 'Invalid Token.'
		]);
	}

	public function parseInput(){
		// Because input data will be JSON we need to decode first.
		// and use file_get_contents to get Method Type: Put, Delete.
		return json_decode(file_get_contents('php://input'));
	}
}
