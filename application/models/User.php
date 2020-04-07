<?php

class User extends CI_Model{

    public function checkUsername($username){

		$this->db->where('username', $username);
  		$check =  $this->db->count_all_results('users'); 

		if($check > 0) return true;	
		else return false;

    }
    
    public function checkEmail($email){

		$this->db->where('email', $email);
  		$check =  $this->db->count_all_results('users'); 

		if($check > 0) return true;	
		else return false;

	}

    public function saveUser()
    {
    
        /* Defined Data */
        $data = [
            'fullname'     => $this->input->post('fullname'),
            'username'     => $this->input->post('username'),
            'email'        => $this->input->post('email'),
            'password'     => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
        ];

        /* Username Check */
       $checkUsername = $this->checkUsername($data['username']);
       if($checkUsername) return [
        'success' => false,
        'message' => "Username Already Registered."
       ];

       /* Email Check */
       $checkEmail = $this->checkEmail($data['email']);
       if($checkEmail) return [
        'success' => false,
        'message' => "Email Already Registered."
       ];


       /* Inserting Data */
       $insertData = $this->db->insert('users', $data);

       if($insertData) return [
            'success' => true,
            'message' => "User Successfully Registered."
       ];
    }
}