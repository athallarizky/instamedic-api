<?php

class Medicine extends CI_Model{

    public function getAllMedicines(){
        return $this->db->get('Medicines')->result();
    }

    public function saveMedicine($getUserLogged){

        if( $getUserLogged->role == 'user'){
            return [
                'success' => false,
                'message' => "403 : You Don't have permission."
            ];
        }

        /* Defined Data */
        $data = [
            'name'         => $this->input->post('name'),
            'description'  => $this->input->post('description'),
            'category'     => $this->input->post('category'),
            'addedBy'      => $getUserLogged->username,
        ];

        $insertData = $this->db->insert('medicines', $data);
        
        if($insertData){
            return [
                'success' => true,
                'message' => "Medicine Successfully Added."
            ];
        }
    }
}