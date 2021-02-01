<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Transactions_model extends CI_Model
{
    private $trans_num_for_bonus = 2;
    private $table_name = 'transaction';
    private $table_fields = ['customer_id', 'amount', 'bonus_amount'];
    
    public function deposit($data, $bonus_percent)
    {
        if (empty($data['customer_id']) || empty($data['amount'])) {
            return false;
        }
        if ($this->shouldBeBonusAdded($data['customer_id']) && $bonus_percent) {
            $data['bonus_amount'] = number_format($data['amount'] * $bonus_percent / 100, 2);
        }
        return $this->addNew($data);
    }
    
    public function withdrawal($data)
    {
        if (empty($data['customer_id']) || empty($data['amount'])) {
            return false;
        }
        $data['amount'] = -$data['amount'];
        return $this->addNew($data);
    }
    
    private function shouldBeBonusAdded($customer_id)
    {
        if (!$customer_id) {
            return false;
        }
        $sql = sprintf(
            "SELECT COUNT(*) AS cnt FROM %s WHERE customer_id = %d AND amount > 0",
            $this->table_name,
            $customer_id
        );
        $res = $this->db->query($sql)->row_array();
        if (!$res || empty($res['cnt'])) {
            return false;
        }
        return ($res['cnt'] % $this->trans_num_for_bonus === 0) ? true : false;
    }
    
    public function addNew($data)
    {
        if (!is_array($data)) {
            return false;
        }
        $db_data = array_intersect_key($data, array_fill_keys($this->table_fields, 1));
        if (!$db_data) {
            return false;
        }
        $sql = sprintf(
            "INSERT INTO %s (customer_id, amount, bonus_amount) VALUES (%d, %.2f, %.2f)",
            $this->table_name,
            $db_data['customer_id'],
            $db_data['amount'],
            $db_data['bonus_amount'] ?? 0
        );
        $res = $this->db->query($sql);
        if (!$res) {
            return false;
        }
        return $this->db->insert_id();
    }
    
    public function getById($transaction_id)
    {
        if (!$transaction_id) {
            return [];
        }
        $sql = sprintf("SELECT * FROM %s WHERE id = %d", $this->table_name, $transaction_id);
        return $this->db->query($sql)->row_array();
    }
    
    public function getBalanceByCustomerId($customer_id)
    {
        $sql = sprintf(
            "SELECT SUM(amount) AS balance
                        FROM %s
                        WHERE customer_id = %d",
            $this->table_name,
            $customer_id
        );
        $row = $this->db->query($sql)->row_array();
        return $row ? $row['balance'] : 0;
    }
}
