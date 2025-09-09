<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class stock extends MY_Controller {

    public function __construct() {
        parent::__construct();
        //$this->load->library('security');
        $this->load->model('stock_data');
        $this->load->model('product_data');
        $this->load->library('form_validation');
        $data = array();
    }

    public function index() {
        $data = array();
        $mastercode = $this->session->userdata('userid');
        $data['return'] = $this->stock_data->doLoadMyStock($mastercode, 'C');
        $data['StockInfo'] = $data['return']['first'];
        $data['StockDetails'] = $data['return']['secound'];
        $data['return'] = $this->stock_data->doLoadMyStock($mastercode, 'P');
        $data['SPStockInfo'] = $data['return']['first'];
        $data['SPStockDetails'] = $data['return']['secound'];
        $data['title'] = 'Home';
        $data['header'] = $this->load->view('header', $data, true);
        $data['sidebar'] = $this->load->view('sidebar', null, true);
        $data['content'] = $this->load->view('stock/content', null, true);
        $data['footer'] = $this->load->view('footer', null, true);
        $this->load->view('stock/index', $data);
    }


    public function mslstock() {
        $data = array();
        $mastercode = $this->session->userdata('userid');
        $data['return'] = $this->stock_data->doLoadMyStockMSL($mastercode, 'P');
        $data['StockInfo'] = $data['return']['first'];
        //echo '<pre>',print_r($data['StockInfo']);die();
        $excelexport = $this->input->get("excelexport", true);
        if($excelexport == "Y"){
            exportexcel($data['StockInfo'], "MSL Stock Data");
        }
        $data['title'] = 'Home';
        $data['header'] = $this->load->view('header', $data, true);
        $data['sidebar'] = $this->load->view('sidebar', null, true);
        $data['content'] = $this->load->view('stock/mslcontent', null, true);
        $data['footer'] = $this->load->view('footer', null, true);
        $this->load->view('stock/index', $data);
    }
    
    public function allocation(){
        $data = array();
        $mastercode = $this->session->userdata('userid');
        
        $data['title'] = 'Product Allocation';
        
        if(!empty($this->input->get("productcode", TRUE))){
            $data['productcode'] = base64_decode($this->input->get("productcode", TRUE));
            $data['allocationdata'] = $this->stock_data->reportStockAllocaiton($mastercode, $data['productcode']);
        }else{
            $data['productcode'] = '';
        }
        $data['productdata'] = $this->product_data->doLoadProducts($business = 'P')['data'];
        $data['allocationdataall'] = $this->stock_data->reportStockAllocaiton($mastercode, '');
        // echo "<pre />"; print_r($data['allocationdataall']); exit();
        
        //$data['allocationdata'] = $this->stock_data->reportStockAllocaiton($mastercode, '');
        
        $data['header'] = $this->load->view('header', $data, true);
        $data['sidebar'] = $this->load->view('sidebar', null, true);
        $data['content'] = $this->load->view('stock/allocation', null, true);
        $data['footer'] = $this->load->view('footer', null, true);
        
        $this->load->view('stock/index', $data);
    }
    
    public function docreateallocation(){
        $data = array();
        $mastercode     = $this->session->userdata('userid');
        $productcode    = $this->input->post("productcode", TRUE);
        $rackname       = $this->input->post("rackname", TRUE);
        
        $return = $this->stock_data->doInsertRackAllocation($mastercode, $productcode, $rackname);
        if($return == true){
            $this->session->set_userdata('show', 1);
            $this->session->set_userdata('success', '1');
            $this->session->set_userdata('message', 'Successfully update.');
        }else{
            $this->session->set_userdata('show', 0);
            $this->session->set_userdata('success', '0');
            $this->session->set_userdata('message', 'Something wrong.');
        }
        redirect(base_url(). 'stock/allocation');
    }


    public function entryallcoation(){
        $data = array();
        $data['title'] = 'Product Rack Allocation';
        $mastercode = $this->session->userdata('userid');
        
        
        print_r($data['allocationdata']); exit();
        $data['header'] = $this->load->view('header', $data, true);
        $data['sidebar'] = $this->load->view('sidebar', null, true);
        $data['content'] = $this->load->view('stock/allocation', $data, true);
        $data['footer'] = $this->load->view('footer', null, true);
        
        $this->load->view('stock/index', $data);
    }

    public function export() {
        $data = array();
        $mastercode = $this->session->userdata('userid');
        $business = $this->input->get("business", TRUE);

        $data['return'] = $this->stock_data->doLoadMyStock($mastercode, $business);
        $data['StockInfo'] = $data['return']['first'];

        for ($i = 0; $i < count($data['StockInfo']); $i++) {
            unset($data['StockInfo'][$i]['Quantity']);
        }

        $arrayheading[0] = array_keys($data['StockInfo'][0]);

        $result = array_merge($arrayheading, $data['StockInfo']);

        //echo "<pre />"; print_r($result);exit();

        $filename = 'stock';
        header("Content-Disposition: attachment; filename=\"{$filename}.xls\"");
        header("Content-Type: application/vnd.ms-excel;");
        header("Pragma: no-cache");
        header("Expires: 0");
        $out = fopen("php://output", 'w');
        foreach ($result as $data) {
            fputcsv($out, $data, "\t");
        }
        fclose($out);
    }

}
