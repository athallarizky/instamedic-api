<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/ExpiredException.php';
require_once APPPATH . 'libraries/JWT.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\ExpiredException;

class UserController extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('User');

		// Cors
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
		header("Access-Control-Allow-Headers: Content-Type");
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
		$saveUser = $this->User->saveUser($this->parseInput());
		return $this->response($saveUser);
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

	/* Method Below This Comment Should be Used as Helper. This isnt' best practice. */
    public function response($data){
		$this->output
			 ->set_content_type("application/json")
			 ->set_status_header(200)
			 ->set_output(json_encode($data, 
				 JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			 ->_display();
		
		exit;
    }
    
    private function decodeToken(){
		
		$header = $this->input->get_request_header('Authorization');

		try{
			$data = JWT::decode($header, env('SECRETKEY'), ['HS256']);
			return $data->id;
		}catch (ExpiredException $err){
			return $this->response([
				'success' => false,
				'message' => 'Error Token : Expired Token.'
			]);
		}catch(Exception $err){
			return $this->response([
				'success' => false,
				'message' => 'Error Token : Invalid Token.'
			]);
		}
	}

	public function parseInput(){
		// Because input data will be JSON we need to decode first.
		// and use file_get_contents to get Method Type: Put, Delete.
		return json_decode(file_get_contents('php://input'));
	}
}
