<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/JWT.php';
use \Firebase\JWT\JWT;

class MedicineController extends CI_Controller{
    public function __construct(){
		parent::__construct();
        $this->load->model('Medicine');
        $this->load->model('User');
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
    
    private function decodeToken(){
		
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
    
    public function getAll(){
        return $this->response($this->Medicine->getAllMedicines());
    }

    public function create(){
        // Get Who insert the data
        $getUserLogged = $this->User->getUserData('id', $this->decodeToken());

        $saveMedicine = $this->Medicine->saveMedicine($getUserLogged);
		return $this->response($saveMedicine);
    }


}