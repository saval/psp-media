<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tests extends CI_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('customers_model');
        $this->load->library("unit_test");
        $this->unit->set_test_items(array('test_name', 'result'));
    }
    
    /**
     * Index Page for this controller, shows list of tests with results
     */
    public function index()
    {
        $data['customers_model_tests_res'] = $this->testCustomersModel();
        $this->load->view('tests/index', $data);
    }
    
    /**
     * Run test cases for the Customers_model and return array with results
     */
    public function testCustomersModel()
    {
        $customer = $this->customers_model->getById(0);
        $this->unit->run($customer, [], 'customers_model->getById with 0 as parameter');
    
        $res1 = $this->db->query('SELECT MAX(id) AS id FROM customer')->row_array();
        if (!$res1) {
            $customer = $this->customers_model->getById(1);
            $this->unit->run($customer, [], 'customers_model->getById with ID that missing in the DB');
        } else {
            $customer = $this->customers_model->getById($res1['id'] + 1);
            $this->unit->run($customer, [], 'customers_model->getById with ID that missing in the DB');
        }
        
        // add test record
        $customer1 = array(
            'id' => $res1 ? $res1['id'] + 1 : 1,
            'first_name' => 'Test_fname',
            'last_name' => 'Test_lname',
            'email' => 'unittest@test.com',
            'gender' => 'not_to_say',
            'country_code' => 'UA',
            'bonus' => 15
        );
        $this->db->insert('customer', $customer1);
        $customer = $this->customers_model->getById($customer1['id']);
        unset($customer['created_date'], $customer['updated_date']);
        $this->unit->run($customer, $customer1, 'customers_model->getById with existing ID');
        $this->db->query('DELETE FROM customer WHERE id = ' . $customer1['id']);
        
        return $this->unit->result();
    }
}
