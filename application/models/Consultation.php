<?php


class Consultation extends CI_Model{

    public function getConsultList($id){   
        return $this->db->where('createdBy', $id)
                        ->or_where('consultTo', $id)
                        ->get('Consultations')
                        ->result();
    }

    public function getCounsult($id){
        return $this->db->get_where('Consultations', ["id" => $id])->row();
    }

    public function saveConsult($getUserLogged, $doctorId, $data){

        /* Defined Data */
        $data = [
            'subject'     => $data->subject,
            'description' => $data->description,
            'createdBy'   => $getUserLogged->id,
            'consultTo'   => $doctorId,
        ];

        $insertData = $this->db->insert('consultations', $data);
        
        if($insertData) return [
            'success' => true,
            'message' => "Consultation Data Successfully Added."
        ];
    }

    public function deleteConsult($id, $getUserLogged){
        $consult = $this->db->get_where('Consultations', ['id' => $id])->row();

        if(!isset($consult->createdBy)) return [
            'success' => false,
            'message' => "Consultation Data Not Found. "
        ];
        // var_dump($consult->consultTo . " = " . $getUserLogged->id );
        // die();
        if($consult->createdBy != $getUserLogged->id && $consult->consultTo != $getUserLogged->id) return [
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