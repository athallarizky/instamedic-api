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

    public function getUserData($key,$value){
        return $this->db->get_where('users', [$key => $value])->row();
    }

    public function checkAuth($data){

        $data = [
            'username' => $data->username,
            'password' => $data->password
        ];

        $passwordHashed = $this->getUserData('username', $data['username']);
        
        if(is_null($passwordHashed)) return false;

        if(password_verify($data['password'], $passwordHashed->password)) return true;
        else return false;
        
    }

    public function saveUser($data){
    
        /* Defined Data */
        $data = [
            'fullname'     => $data->fullname,
            'username'     => $data->username,
            'email'        => $data->email,
            'password'     => password_hash($data->password, PASSWORD_DEFAULT),
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

    public function updateUser($id, $data){
        $data = [
            'fullname' => $data->fullname,
            'username' => $data->username,
            // 'password' => password_hash($data->password, PASSWORD_DEFAULT),
        ];

        try{
			$this->db->where('id', $id)->update('users', $data);
			return [
                'success' => true,
                'message' => "User Successfully Updated."
            ];
		}catch(Exception $err){
			return [
				'success' => false,
				'message' => 'User Update Failed : ' . $err
			];
		}
    }

    public function getDoctorData($id = null){
        if($id != null){
            return $this->db->get_where('users', ['id' => $id, 'role' => 'doctor'])->row();
        } else return $this->db->get_where('users', ['role' => 'doctor'])->result();

    }

    public function getDoctorByUsername($username){
        return $this->db->get_where('users', ['username' => $username, 'role' => 'doctor'])->row();
    }

    // Note: This isn't best practice, Maybe someday this method can be reafctored using saveUser() above
    public function saveDoctor($data){
        /* Defined Data */
        $data = [
            'fullname'    => $data->fullname,
            'username'    => $data->username,
            'email'       => $data->email,
            'specialist'  => $data->specialist,
            'role'        => 'doctor',
            'password'    => password_hash($data->password, PASSWORD_DEFAULT),
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
            'message' => "Doctor Successfully Registered."
       ];
    }

    public function deleteDoctor($id){
        try {
            $this->db->where('id', $id)->delete('users');
            return [
                'success' => true,
                'message' => 'Doctor Sucessfully Deleted.'
            ];
        } catch (Exception $err) {
            return [
				'success' => false,
				'message' => 'Doctor Delete Failed : ' . $err
			];
        }
    }

    public function isAllowed($getUserLogged){
        return $getUserLogged->role != 'user' ? true : false;
    }

    public function isAdmin($getUserLogged){
        return $getUserLogged->role == 'admin' ? true : false;
    }
}