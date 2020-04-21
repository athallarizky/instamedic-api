<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/SignatureInvalidException.php';
require_once APPPATH . 'libraries/ExpiredException.php';
require_once APPPATH . 'libraries/JWT.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\ExpiredException;
use \Firebase\JWT\SignatureInvalidException;

class MessageController extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('Message');
        $this->load->model('Consultation');
        $this->load->model('User');

        // Cors
		header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

    }

    public function receiptName($getUserLogged, $consultData){
        // Stupid ways haha.. I don't know how to use ORM in codeigniter
        return $getUserLogged->id == $consultData->createdBy ? $consultData->consultTo : $consultData->createdBy;
    }

    public function isAllowed($getUserLogged, $consultData){
        return ($getUserLogged->id != $consultData->createdBy) && ($getUserLogged->id != $consultData->consultTo) ? false : true;
    }

    public function create($id){

        // Get who create the data
        $getUserLogged = $this->User->getUserData('id', $this->decodeToken());

        // Get consultation data
        $consultData = $this->Consultation->getCounsult($id);

        if(!isset($consultData->id)) return $this->response([
            'success' => false,
            'message' => "Consultation Data Not Found. "
        ]);

        // Get receptName
        $receptName = $this->receiptName($getUserLogged, $consultData);

        if(!$this->isAllowed($getUserLogged, $consultData)) return $this->response([
            'success' => false,
            'message' => "403 : Not Allowed."
        ]);

        $saveMessage = $this->Message->saveMessage($getUserLogged, $consultData, $receptName, $this->parseInput());
        return $this->response($saveMessage);

    }

    public function get($id){

        // Get who create the data
        $getUserLogged = $this->User->getUserData('id', $this->decodeToken());

        // Get consultation data
        $consultData = $this->Consultation->getCounsult($id);

        if(!isset($consultData->id)) return $this->response([
            'success' => false,
            'message' => "Message Not Found. "
        ]);

        if(!$this->isAllowed($getUserLogged, $consultData)) return $this->response([
            'success' => false,
            'message' => "403 : Not Allowed."
        ]);

        return $this->response($this->Message->getMessage($id));
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


