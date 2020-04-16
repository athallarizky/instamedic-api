<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/ExpiredException.php';
require_once APPPATH . 'libraries/JWT.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\ExpiredException;

class ConsultationController extends CI_Controller{
    public function __construct(){
		parent::__construct();
        $this->load->model('Consultation');
        $this->load->model('User');

        // Cors
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
    }

    public function get($username){
        
        // Get who get the data
        $getUserLogged = $this->User->getUserData('id', $this->decodeToken());
        // die($getUserLogged);
        if($getUserLogged->username != $username) return $this->response([
            'success' => false,
            'message' => '403 : Not Allowed.'
        ]);

        return $this->response($this->Consultation->getConsultList($username));
    }

    public function create($doctorUsername){

        // Get who create the data
        $getUserLogged = $this->User->getUserData('id', $this->decodeToken());

        // Check permission
        $checkPermission = $this->User->isAllowed($getUserLogged);
        if($checkPermission) return $this->response([
            'success' => false,
            'message' => '403 : Not Allowed.'
        ]);

        $saveConsult = $this->Consultation->saveConsult($getUserLogged, $doctorUsername);
		return $this->response($saveConsult);
    }

    public function delete($id){
        // Get who delete the data
        $getUserLogged = $this->User->getUserData('id', $this->decodeToken());
        
        $deleteConsult = $this->Consultation->deleteConsult($id, $getUserLogged->username);
        return $this->response($deleteConsult);
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



}