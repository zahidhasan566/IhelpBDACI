<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Setup extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        //$this->load->library('security');
        $this->load->model('Service_data');
        $this->load->model('Product_data');
        $this->load->model('Setup_Data', 'setup_data');
        $this->load->model('Dealer_data', 'dealer');
        $this->load->model('db_data');
        //$this->load->library('form_validation');
        $data = array();
    }

    public function gazipurDataSync()
    {
        $data['title'] = 'Setup >> Gazipur Data Sync';
        $servicetype = 0;
        $data['userid'] = strtoupper(trim($this->session->userdata('userid')));

        if(!empty($_POST)){
            // echo "<pre/>";print_r("hi hello");exit();
            $response = $this->setup_data->syncGazipurData();

            if($response==true){
                $this->session->set_flashdata('message', 'Data Sync Successfully');
            }else{
                $this->session->set_flashdata('error', 'Something wrong. Unable to Sync Data !');
            }
        }

        $data['header'] = $this->load->view('header', $data, true);
        $data['footer'] = $this->load->view('footer', null, true);
        $data['sidebar'] = $this->load->view('sidebar', null, true);

        $data['content'] = $this->load->view('setup/gazipur_data_sync', $data, true);
        $this->load->view('dashboard', $data);
    }
   
    public function customerAssigningList()
    {
        $data['title'] = 'Setup >> Customer Assigning List';
        $servicetype = 0;
        $data['userid'] = strtoupper(trim($this->session->userdata('userid')));

        $data['assigningList'] = $this->setup_data->doLoadCustomerAssigningList();

        $data['header'] = $this->load->view('header', $data, true);
        $data['footer'] = $this->load->view('footer', null, true);
        $data['sidebar'] = $this->load->view('sidebar', null, true);

        $data['content'] = $this->load->view('setup/customer_assigning_list', $data, true);
        $this->load->view('dashboard', $data);
    }

    public function createCustomerAssigning()
    {
        $data['title'] = 'Setup >> Assign New Customer';
        $servicetype = 0;
        $data['userid'] = strtoupper(trim($this->session->userdata('userid')));
        $data['userTypes'] = $this->setup_data->doLoadUserTypes();
        $data['CustomerList']   = $this->setup_data->CustomerList();

        if(!empty($_POST)){
            // echo "<pre/>";print_r("hi hello");exit();
            $response = $this->setup_data->insertNewCustomerData($_POST);

            if($response==true){
                $this->session->set_flashdata('msg', 'Customer Assigned Successfully');
            }else{
                $this->session->set_flashdata('err', 'Something wrong. Unable to insert data !');
            }

            return redirect('setup/createCustomerAssigning');
        }

        $data['header'] = $this->load->view('header', $data, true);
        $data['footer'] = $this->load->view('footer', null, true);
        $data['sidebar'] = $this->load->view('sidebar', null, true);

        $data['content'] = $this->load->view('setup/add_customer_assigning', $data, true);
        $this->load->view('dashboard', $data);
    }
    
    
    public function editCustomerAssigning()
    {
        $data['title'] = 'Setup >> Edit Assigned Customer';
        $servicetype = 0;
        $data['userid'] = strtoupper(trim($this->session->userdata('userid')));
        $data['userTypes'] = $this->setup_data->doLoadUserTypes();
        $data['CustomerList']   = $this->setup_data->CustomerList();

        $data['UserCustomerId'] = $this->input->get('UserCustomerId',TRUE);

        if(empty($_POST)){
            $data['assignedCust'] = $this->setup_data->doLoadAssignedCustomer($data['UserCustomerId'])[0];
        }
        // echo "<pre/>";print_r($data['assignedCust']);exit();

        if(!empty($_POST)){
            $response = $this->setup_data->updateAssignedCustomerData($_POST);

            if($response==true){
                $this->session->set_flashdata('upmsg', 'Customer Updated Successfully');
            }else{
                $this->session->set_flashdata('uperr', 'Something wrong. Unable to update data !');
            }

            return redirect('setup/customerAssigningList');
        }

        $data['header'] = $this->load->view('header', $data, true);
        $data['footer'] = $this->load->view('footer', null, true);
        $data['sidebar'] = $this->load->view('sidebar', null, true);

        $data['content'] = $this->load->view('setup/edit_customer_assigning', $data, true);
        $this->load->view('dashboard', $data);
    }

   
}
