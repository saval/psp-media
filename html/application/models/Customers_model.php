<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Customers_model extends CI_Model
{
    private $table_name = 'customer';
    private $table_fields = ['first_name', 'last_name', 'country_code', 'email', 'gender'];
    
    public function addNew($data)
    {
        if (!is_array($data)) {
            return false;
        }
        $db_data = array_intersect_key($data, array_fill_keys($this->table_fields, 1));
        if (!$db_data || count($db_data) != count($this->table_fields)) {
            return false;
        }
        $db_data['bonus'] = $this->getBonusPercent();
        
        $this->db->trans_start();
        $this->db->insert('customer', $db_data);
        $customer_id = $this->db->insert_id();
        if (!$customer_id) {
            return false;
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        }
        return $customer_id;
    }
    
    public function getById($customer_id)
    {
        if (!$customer_id) {
            return [];
        }
        $sql = sprintf("SELECT * FROM %s WHERE id = %d", $this->table_name, $customer_id);
        return $this->db->query($sql)->row_array();
    }
    
    public function getByEmail($email, $excl_customer_id = null)
    {
        if (!$email) {
            return [];
        }
        $this->db->where('email', $email);
        if (!empty($excl_customer_id)) {
            $this->db->where('id!=', $excl_customer_id);
        }
        return $this->db->get($this->table_name)->row_array();
    }
    
    public function update($customer_id, $data)
    {
        if (!$customer_id || !is_array($data)) {
            return false;
        }
        $db_data = array_intersect_key($data, array_fill_keys($this->table_fields, 1));
        if (!$db_data) {
            return false;
        }
        $db_data['updated_date'] = date('Y-m-d H:i:s');
        
        $this->db->trans_start();
        $this->db->where('id', $customer_id);
        $this->db->update('customer', $db_data);
        $res = $this->db->affected_rows();
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        }
        return $res;
    }
    
    private function getBonusPercent()
    {
        return rand(5, 20);
    }
}
