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

    public function deleteConsult($id, $username){
        $consult = $this->db->get_where('Consultations', ['id' => $id])->row();

        if(!isset($consult->createdBy)) return [
            'success' => false,
            'message' => "Consultation Data Not Found. "
        ];

        if($consult->createdBy != $username) return [
            'success' => false,
            'message' => "Consultation Data Delete Failed. "
        ];

        $deleteConsult = $this->db->where('id', $id)->delete('consultations');

        if($deleteConsult) return [
            'success' => true,
            'message' => "Consultation Data Successfully Deleted."
        ];
    }
}