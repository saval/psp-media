<?php
$config = array(
    'customer' => array(
        array(
            'field' => 'gender',
            'label' => 'Gender',
            'rules' => 'required|in_list[male,female,not_to_say]'
        ),
        array(
            'field' => 'first_name',
            'label' => 'First name',
            'rules' => 'required|max_length[50]'
        ),
        array(
            'field' => 'last_name',
            'label' => 'Last name',
            'rules' => 'required|max_length[50]'
        ),
        array(
            'field' => 'country_code',
            'label' => 'Country',
            'rules' => 'required|exact_length[2]|valid_country'
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'required|valid_email|email_is_unique'
        ),
    ),
    'edit_customer' => array(
        array(
            'field' => 'gender',
            'label' => 'Gender',
            'rules' => 'in_list[male,female,not_to_say]'
        ),
        array(
            'field' => 'first_name',
            'label' => 'First name',
            'rules' => 'max_length[50]'
        ),
        array(
            'field' => 'last_name',
            'label' => 'Last name',
            'rules' => 'max_length[50]'
        ),
        array(
            'field' => 'country_code',
            'label' => 'Country',
            'rules' => 'exact_length[2]|valid_country'
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'valid_email'
        )
    ),
    'deposit' => array(
        array(
            'field' => 'customer_id',
            'label' => 'Customer ID',
            'rules' => 'required|numeric|valid_customer'
        ),
        array(
            'field' => 'amount',
            'label' => 'Amount',
            'rules' => 'required|numeric|greater_than[0]'
        )
    ),
    'withdrawal' => array(
        array(
            'field' => 'customer_id',
            'label' => 'Customer ID',
            'rules' => 'required|numeric|valid_customer'
        ),
        array(
            'field' => 'amount',
            'label' => 'Amount',
            'rules' => 'required|numeric|greater_than[0]'
        )
    )
);
