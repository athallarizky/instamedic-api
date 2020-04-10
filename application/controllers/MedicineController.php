<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/ExpiredException.php';
require_once APPPATH . 'libraries/JWT.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\ExpiredException;

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
    
    //This method should be used as a helper
    private function decodeToken(){
		
		$header = $this->input->get_request_header('Authorization');

		try{
			$data = JWT::decode($header, env('SECRETKEY'), ['HS256']);
			var_dump("Current id: " . $data->id);
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
    
    public function getAll(){
        return $this->response($this->Medicine->getMedicineData());
    }

    public function getMedicine($id){
        return $this->response($this->Medicine->getMedicineData('id', $id));
    }

    public function create(){
        // Get who insert the data
        $getUserLogged = $this->User->getUserData('id', $this->decodeToken());

        $saveMedicine = $this->Medicine->saveMedicine($getUserLogged);
		return $this->response($saveMedicine);
    }

    public function update($id){
        // Get who update the data
        $getUserLogged = $this->User->getUserData('id', $this->decodeToken());

        $updateMedicine = $this->Medicine->updateMedicine($id, $getUserLogged, $this->parseInput());        
        return $this->response($updateMedicine);
    }

    public function delete($id){
        // Get who update the data
        $getUserLogged = $this->User->getUserData('id', $this->decodeToken());

        $deleteMedicine = $this->Medicine->deleteMedicine($id, $getUserLogged);
        return $this->response($deleteMedicine);
    }

    public function parseInput(){
		// Because input data will be JSON we need to decode first.
		// and use file_get_contents to get Method Type: Put, Delete.
		return json_decode(file_get_contents('php://input'));
	}


}