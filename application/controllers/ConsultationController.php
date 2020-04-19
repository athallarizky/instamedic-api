<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/SignatureInvalidException.php';
require_once APPPATH . 'libraries/ExpiredException.php';
require_once APPPATH . 'libraries/JWT.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\ExpiredException;
use \Firebase\JWT\SignatureInvalidException;

class ConsultationController extends CI_Controller{
    public function __construct(){
		parent::__construct();
        $this->load->model('Consultation');
        $this->load->model('User');

        // Cors
		header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
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

        $saveConsult = $this->Consultation->saveConsult($getUserLogged, $doctorUsername, $this->parseInput());
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
    
    public function parseInput(){
		// Because input data will be JSON we need to decode first.
		// and use file_get_contents to get Method Type: Put, Delete.
		return json_decode(file_get_contents('php://input'));
	}



}