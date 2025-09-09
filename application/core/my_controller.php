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

        $this->data['header'] = $this->load->view('header', null, true);
        $this->data['footer'] = $this->load->view('footer', null, true);        
        $this->data['sidebar'] = $this->load->view('sidebar', null, true);

        $this->userid = $this->session->userdata('userid');    

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

    /**
     * load view with common header and footer
     *
     * @param [type] $page
     * @param array $data
     * @param string $template
     * @return void
     */
    public function loadView($page,$data= [],$template = 'dashboard') {
        $data['header'] = $this->load->view('header', $data, true);
        $data['footer'] = $this->load->view('footer', null, true);
        $data['sidebar'] = $this->load->view('sidebar', null, true);
        $data['content'] = $this->load->view($page, $data, true);

        $this->load->view($template, $data);
    }

    public function loadPrintView($page, $data = [], $template = 'dasboard')
    {
        $templateData['head'] = $this->load->view('print/head', $data, true);
        $templateData['content'] = $this->load->view($page, $data, true);
        $templateData['footer'] = $this->load->view('print/footer', $data, true);
        $this->load->view('print/dashboard', $templateData);
    }

    public function insertGetID($table,$data) {

        $keysStr = implode(",",array_keys($data));
        foreach($data as $key=>$item) {
            $data[$key] = str_replace("'","''",$data[$key]);
        }
        $valuesStr = implode("'',''",$data);
        $sql = "insert into $table($keysStr) values(''$valuesStr'');SELECT SCOPE_IDENTITY() AS [SCOPE_IDENTITY]";
        // die($sql);
        $sql = "exec usp_insertGetID '$sql'";
        $query = $this->db->query($sql);
        // echo '<pre>',print_r($query->result_array());die();
        if(!empty($result = $query->result_array())) {
            return $result[0]['SCOPE_IDENTITY'];
        }
        return null;
    }

    
}