<?php


class Consultation extends CI_Model{

    public function getConsultList($username){
        return $this->db->get_where('Consultations', ["createdBy" => $username])->result();
    }

    public function saveConsult($getUserLogged, $doctorUsername){

        /* Defined Data */
        $data = [
            'subject'     => $this->input->post('subject'),
            'description' => $this->input->post('description'),
            'createdBy'   => $getUserLogged->username,
            'consultTo'   => $doctorUsername,
        ];

        $insertData = $this->db->insert('consultations', $data);
        
        if($insertData) return [
            'success' => true,
            'message' => "Consultation Data Successfully Added."
        ];
    }
}