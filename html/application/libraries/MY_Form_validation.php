<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{
    /**
     * Country code validator. Only white-listed values allowed.
     */
    public function valid_country($country_code)
    {
        $this->CI->load->config('locations');
        $data['countries'] = $this->CI->config->item('countries');
    
        if (!array_key_exists($country_code, $data['countries'])) {
            $this->set_message('valid_country', 'The country code must be in the ISO 639-1 format.');
            return false;
        }
        return true;
    }
    
    /**
     * Email uniqueness validator. With option to check all the rest accounts except specified.
     */
    public function email_is_unique($email, $customer_id = null)
    {
        $this->CI->load->model('customers_model');
        $customer = $this->CI->customers_model->getByEmail($email, $customer_id);
        if ($customer) {
            $this->set_message('email_is_unique', 'Specified email already registered.');
            return false;
        }
        return true;
    }
    
    /**
     * Customer ID validator. Only existing in the DB customers allowed.
     */
    public function valid_customer($customer_id)
    {
        $this->CI->load->model('customers_model');
        $customer = $this->CI->customers_model->getById($customer_id);
        if (!$customer) {
            $this->set_message('valid_customer', 'Specified customer ID not found.');
            return false;
        }
        return true;
    }
}

/* End of file MY_Form_validation.php */
/* Location: ./application/core/MY_Form_validation.php */
