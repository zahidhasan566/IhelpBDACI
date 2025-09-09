<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

class MY_Controller extends CI_Controller {    
    public $userid = '';    
    public $base = '';
    public $data = array();
    public $css = '';
    public $img = '';
    public $js = '';    

    function __construct() {
        parent::__construct();        
        $this->base = $this->config->item('base_url');
        $this->css = $this->config->item('css');
        $this->img = $this->config->item('img');
        $this->js = $this->config->item('js');        

        // $this->data['header'] = $this->load->view('header', null, true);
        // $this->data['footer'] = $this->load->view('footer', null, true);        
        // $this->data['sidebar'] = $this->load->view('sidebar', null, true);

        // $this->userid = $this->session->userdata('userid');    

        if (empty($this->userid)){            
            if ($this->input->is_ajax_request()){
                $data['success'] = 2;
                $data['redirect'] = site_url('/authenticate');
                echo json_encode($data);
                exit();
            } else {
                redirect('/authenticate');                   
            }                               
        }
    }
}