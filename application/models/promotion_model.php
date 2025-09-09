<?php


class Promotion_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function promotionList() {
        $sql = "select * From ProductPromo where Business='C' and Active='1' order by EntryDate desc";
        $CI = & get_instance();
        $CI->db = $this->load->database('default',true);
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function brandList() {
        $sql = "select * from ProdBrand where Business = 'C'";
        $CI = & get_instance();
        $CI->db = $this->load->database('default',true);
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getProductByBrand($brandCode) {
        $sql = "select * from Product where BrandCode = '$brandCode'";
        $CI = & get_instance();
        $CI->db = $this->load->database('default',true);
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getPromoBrands($id) {
        $sql = "select ppd.ProductCode,p.ProductName from ProductPromoDetails ppd
                join Product p on p.ProductCode = ppd.ProductCode
                where ppd.PromoId = '$id'";
        $CI = & get_instance();
        $CI->db = $this->load->database('default',true);
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function savePromotion($data)
    {
        $CI = & get_instance();
        $CI->db = $this->load->database('default',true);
        $this->db->insert('ProductPromo',$data);
        return true;
    }

    public function updatePromotion($data,$id)
    {
        $CI = & get_instance();
        $CI->db = $this->load->database('default',true);
        $this->db->where('PromoId',$id);
        $this->db->update('ProductPromo',$data);
        return true;
    }

    public function changeStatus($id)
    {
        $sql = "select * from ProductPromo where PromoId = '$id'";
        $CI = & get_instance();
        $CI->db = $this->load->database('default',true);
        $query = $this->db->query($sql);
        $data = $query->row();
        $this->db->where('PromoId',$id);
        if ($data->Active == 1) {
            $this->db->update('ProductPromo',['Active' => 0]);
        } else {
            $this->db->update('ProductPromo',['Active' => 1]);
        }
    }

    public function savePromotionDetails($data)
    {
        $CI = & get_instance();
        $CI->db = $this->load->database('default',true);
        $this->db->insert('ProductPromoDetails',$data);
        return true;
    }

    public function deletePromoDetails($id) {
        $this->db->where('PromoId',$id);
        $this->db->delete('ProductPromoDetails');
        return true;
    }

    public function exportPromoDealerWise($exportedData) {
        $filename = 'promo_report_dealer_wise_'.date('Y-m-d').'.csv';
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");

        $file = fopen('php://output', 'w');

        $header = array(
            "Promotion Name",
            "Start Date",
            "End Date",
            "Promotion Amount",
            "Sales Quantity",
            "Customer Name",
            "Customer Code"
        );
        fputcsv($file, $header);
        foreach ($exportedData as $key=>$line){
            fputcsv($file,$line);
        }
        fclose($file);
        exit;
    }
    public function exportTopSheet($exportedData) {
        $filename = 'promo_top_sheet_'.date('Y-m-d').'.csv';
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");

        $file = fopen('php://output', 'w');

        $header = array(
            "Customer Name",
            "Customer Code",
            "Sales Quantity",
            "Total Promo Amount"
        );
        fputcsv($file, $header);
        foreach ($exportedData as $key=>$line){
            fputcsv($file,$line);
        }
        fclose($file);
        exit;
    }
}
