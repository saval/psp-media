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
        $this->db->trans_start();
        if ($this->shouldBeBonusAdded($data['customer_id']) && $bonus_percent) {
            $data['bonus_amount'] = number_format($data['amount'] * $bonus_percent / 100, 2);
        }
        $transaction_id = $this->addNew($data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        }
        return $transaction_id;
    }
    
    public function withdrawal($data)
    {
        if (empty($data['customer_id']) || empty($data['amount'])) {
            return [false, false];
        }
        $this->db->trans_start();
        $current_balance = $this->getBalanceByCustomerId($data['customer_id']);
        if ($current_balance < $data['amount']) {
            return [false, true];
        }
    
        $data['amount'] = -$data['amount'];
        $transaction_id = $this->addNew($data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return [false, false];
        }
        return [$transaction_id, false];
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
    
    public function getReport($from_date, $to_date)
    {
        if (!$from_date || !$to_date) {
            return [];
        }
        $sql = sprintf(
            "SELECT DATE(t.created_date) AS date, c.country_code,
              COUNT(DISTINCT t.customer_id) AS unique_customers,
              SUM(CASE WHEN t.amount > 0 THEN 1 ELSE 0 END) AS deposits_cnt,
              FORMAT(SUM(CASE WHEN t.amount > 0 THEN t.amount ELSE 0 END), 2) AS total_deposits_amount,
              SUM(CASE WHEN t.amount > 0 THEN 0 ELSE 1 END) AS withdrawals_cnt,
              FORMAT(SUM(CASE WHEN t.amount > 0 THEN 0 ELSE t.amount END), 2) AS total_withdrawals_amount
            FROM transaction t
            JOIN customer c ON t.customer_id = c.id
            WHERE t.created_date BETWEEN '%s' AND '%s'
            GROUP BY DATE(t.created_date), c.country_code",
            $from_date,
            $to_date
        );
        return $this->db->query($sql)->result_array();
    }
}
