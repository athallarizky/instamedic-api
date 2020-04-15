<?php

class Medicine extends CI_Model{

    public function searchMedicine(){
        $key = $this->input->post('searchKey');
        $this->db->like('name', $key);
        $this->db->or_like('description', $key);
        $this->db->or_like('category', $key);
        return $this->db->get('Medicines')->result();
    }

    public function getAllMedicines(){
        return $this->db->get('Medicines')->result();
    }

    public function getMedicineData($key = null, $value = null){
        if($key != null && $value != null){
            return $this->db->get_where('Medicines', [$key => $value])->row();
        } else return $this->db->get('Medicines')->result();
    }

    public function saveMedicine($getUserLogged){
        
        /* Defined Data */
        $data = [
            'name'         => $this->input->post('name'),
            'description'  => $this->input->post('description'),
            'category'     => $this->input->post('category'),
            'addedBy'      => $getUserLogged->username,
        ];

        $insertData = $this->db->insert('medicines', $data);
        
        if($insertData) return [
            'success' => true,
            'message' => "Medicine Successfully Added."
        ];
        
    }

    public function updateMedicine($id, $data){

        /* Defined Data */
        $data = [
            'name'         => $data->name,
            'description'  => $data->description,
            'category'     => $data->category,
        ];

        try{
			$this->db->where('id', $id)->update('medicines', $data);
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

    public function deleteMedicine($id){

        try {
            $this->db->where('id', $id)->delete('medicines');
            return [
                'success' => true,
                'message' => 'Medicine Sucessfully Deleted.'
            ];
        } catch (Exception $err) {
            return [
				'success' => false,
				'message' => 'Medicine Delete Failed : ' . $err
			];
        }
        
    }
}