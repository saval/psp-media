<?php
use chriskacerguis\RestServer\RestController;
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends RestController
{
    protected $http_status;
    
    public function customers_post($customer_id = null)
    {
        $this->load->helper(['form']);
        $this->load->library('form_validation');
        $this->load->model('customers_model');
        
        if (!empty($customer_id)) {
            return $this->customers_put($customer_id);
        }
        
        if ($this->form_validation->run('customer') == false) {
            $errors = $this->form_validation->error_array();
            $this->response([
                'status' => false,
                'error' => $errors
            ], RestController::HTTP_BAD_REQUEST);
        }
        
        $form = $this->input->post();
        $form['first_name'] = $this->security->xss_clean($form['first_name']);
        $form['last_name'] = $this->security->xss_clean($form['last_name']);
    
        $customer_id = $this->customers_model->addNew($form);
        if (!$customer_id) {
            $this->response([
                'status' => false,
                'error' => ''
            ], RestController::HTTP_INTERNAL_ERROR);
        }
        $customer = $this->customers_model->getById($customer_id);
        $this->response(['customer' => $customer], RestController::HTTP_CREATED);
    }
    
    /*
     * CI does not allow to validate PUT requests, so this is quick workaround
     */
    public function customers_put($customer_id)
    {
        if ($this->form_validation->run('edit_customer') == false) {
            $errors = $this->form_validation->error_array();
            $this->response([
                'status' => false,
                'error' => $errors
            ], RestController::HTTP_BAD_REQUEST);
        }

        $form = $this->input->post();
        foreach (['first_name', 'last_name'] as $field_name) {
            if (isset($form[$field_name])) {
                $form[$field_name] = $this->security->xss_clean($form[$field_name]);
            }
        }
        if (isset($form['email'])) {
            if (!$this->form_validation->email_is_unique($form['email'], $customer_id)) {
                $this->response([
                    'status' => false,
                    'error' => 'Specified email already registered.'
                ], RestController::HTTP_BAD_REQUEST);
            }
        }

        if (!$this->customers_model->update($customer_id, $form)) {
            $this->response([
                'status' => false,
                'error' => 'Nothing has been updated'
            ], RestController::HTTP_OK);
        }
        $customer = $this->customers_model->getById($customer_id);
        $this->response(['customer' => $customer], RestController::HTTP_OK);
    }
}
