<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Setting extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('security');
        $this->load->model('users');
        $this->load->library('form_validation');
        $data = array();
    }

    public function changepassword() {
        $data = array();
        $mastercode = $this->session->userdata('userid');                                      
        
        $data['title'] = 'Home';
        $data['header'] = $this->load->view('header', $data, true);
        $data['sidebar'] = $this->load->view('sidebar', null, true);
        $data['content'] = $this->load->view('setting/changepassword', null, true);
        $data['footer'] = $this->load->view('footer', null, true);
        $this->load->view('stock/index', $data);
    }
    
    public function updatepassword(){
        $userid = $this->session->userdata('userid');  
        $currentpassword = passencode($this->input->post("currentpassword", TRUE));
        $newpassword = passencode($this->input->post("newpassword", TRUE));
        $confirmpassword = passencode($this->input->post("confirmpassword", TRUE));
        
        $return = $this->users->checkPassword($userid, $currentpassword);
        if($return == 1){
            if($newpassword == $confirmpassword){
                //update
                $this->users->changePassword($userid, $newpassword);
                $this->session->set_flashdata('msgtype', 'success');
                $this->session->set_flashdata('msg', 'Successfully update;');
                redirect(base_url().'setting/changepassword');                       
            }else{
                $this->session->set_flashdata('msgtype', 'error');
                $this->session->set_flashdata('msg', 'Password Mismatch');
                redirect(base_url().'setting/changepassword');   
            }    
        }else{
            $this->session->set_flashdata('msgtype', 'error');
            $this->session->set_flashdata('msg', 'Password dosen\'t match with our database');
            redirect(base_url().'setting/changepassword');
        }
        //print_r($_POST);    
    }
 
    public function test(){
        //$this->users->changePassword('su', passencode('tech'));    
    } 
         

}
