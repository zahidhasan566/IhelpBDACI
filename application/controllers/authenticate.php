<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Authenticate extends CI_Controller {
    public $base = '';
    public $css = '';
    public $img = '';
    public $js = '';
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Users');

        $this->base = $this->config->item('base_url');
        $this->css = $this->config->item('css');
        $this->img = $this->config->item('img');
        $this->js = $this->config->item('js');
    }

    public function index() {
        $data = array();

        $data['msg'] = $this->session->userdata('msg');
        $data['msgtype'] = $this->session->userdata('msgtype');
        $data['title'] = 'Login';

        $data['loginheader'] = $this->load->view('loginheader', null, true);
        $data['loginfooter'] = $this->load->view('loginfooter', null, true);

        $this->load->view('login', $data);

        $this->session->set_userdata('msg', '');
        $this->session->set_userdata('msgtype', '');
    }

    public function login() {
        $userid = '';
        $password = '';

        $userid = $this->input->post('userid', true);
        $password = $this->input->post('password', true);

        $row = $this->Users->login($userid, ($password));
        if ($row['success']==true){
            $grpUser = $row['data']['grpUser']->grpUser;
            $grpISup = $row['data']['grpUser']->grpISup;
            $designation = $row['data']['grpUser']->designation;

            $this->session->set_userdata('userid', $userid);
            $this->session->set_userdata('userpass', $password);
            $this->session->set_userdata('grpUser', $grpUser);
            $this->session->set_userdata('grpISup', $grpISup);
            $this->session->set_userdata('designation', $designation);
            $this->session->set_userdata('userName', $row['data']['grpUser']->UserName);
            $this->session->set_userdata('msg', '');
            $this->session->set_userdata('msgtype', 'success');
            redirect(site_url());
            return true;
        } else {
            $this->session->set_userdata('msg', 'Invalid user id or password.');
            $this->session->set_userdata('msgtype', 'error');
            redirect('/authenticate');
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        $data['success'] = true;
        redirect(site_url());
    }

    public function loginservice() {
        $data['success'] = 0;
        $data['message'] = 'Invalid user id or password.';

        $userid = '';
        $password = '';

        $userid = $this->input->get_post('userid', true);
        $password = $this->input->get_post('password', true);

        $row = $this->Users->login($userid, ($password));


        if ($row['success']==true){
            $data['success'] = 1;
            $data['message'] = 'Login successfull. Please wait for data update.';
        }

        header('Content-type: application/json;charset=UTF-8');
        echo json_encode($data);
    }
}