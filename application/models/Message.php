<?php

class Message extends CI_Model{
    
    public function saveMessage($getUserLogged, $consultData, $receptName, $data){

        /* Defined Data */
        $data = [
            'body'         => $data->body,
            'senderName'   => $getUserLogged->id,
            'receptName'   => $receptName,
            'consult_id'     => $consultData->id,
        ];

        $insertData = $this->db->insert('messages', $data);
        
        if($insertData) return [
            'success' => true,
            'message' => "Messages Successfully Added."
        ];

    }

    public function getMessage($id){
        return $this->db->get_where('Messages', ["consult_id" => $id])->result();
    }
}