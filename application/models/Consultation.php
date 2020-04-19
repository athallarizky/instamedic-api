<?php


class Consultation extends CI_Model{

    public function getConsultList($username){   
        return $this->db->where('createdBy', $username)
                        ->or_where('consultTo', $username)
                        ->get('Consultations')
                        ->result();
    }

    public function getCounsult($id){
        return $this->db->get_where('Consultations', ["id" => $id])->row();
    }

    public function saveConsult($getUserLogged, $doctorUsername, $data){

        /* Defined Data */
        $data = [
            'subject'     => $data->subject,
            'description' => $data->description,
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

        if($consult->createdBy != $username && $consult->consultTo != $username) return [
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