<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product_data extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function doLoadProducts($business = '', $procode = '', $withotherproduct = 0, $mastercode = '') {
        $data['success'] = 0;
        $data['msg'] = 'error';
        $data['data'] = array();

        $sql = "exec usp_LoadProduct '$business','$procode','$withotherproduct','$mastercode'";

        $query = $this->db->query($sql);
        if ($query !== false) {
            $row = utf8ize($query->result());
            $data['success'] = 1;
            $data['msg'] = '';
            $data['data'] = $row;
        }
        $query->free_result();

        return $data;
    }
    
    public function doLoadPreBookingProducts($business) {
        $data['success'] = 0;
        $data['msg'] = 'error';
        $data['data'] = array();

        $sql = "SELECT 
                    P.ProductCode,
                    P.ProductName 
                FROM PreBookingProduct PB
                    INNER JOIN Product P
                        ON PB.ProductCode = P.ProductCode AND PB.Business = P.Business
                WHERE PB.Active = 'Y' AND P.Business = '$business' ";

        $query = $this->db->query($sql);
        if ($query !== false) {
            $row = $query->result_array();
            $data['success'] = 1;
            $data['msg'] = '';
            $data['data'] = $row;
        }
        $query->free_result();

        return $data;
    }

    public function doLoadProductsExport($business = '', $userid = '') {
        $rows = array();
        $sql = "exec usp_LoadProductExport '$business', '$userid'";
        $query = $this->db->query($sql);
        $rows = $query->result_array();
        return $rows;
    }

    public function doLoadProductDetails() {
        $data['success'] = 0;
        $data['msg'] = 'error';
        $data['data'] = array();

        $sql = "exec usp_LoadProductDetails";

        $query = $this->db->query($sql);
        if ($query !== false) {
            $row = $query->result();
            $data['success'] = 1;
            $data['msg'] = '';
            $data['data'] = $row;
        }
        $query->free_result();

        return $data;
    }

    public function doLoadProductDescription($productcode) {
        $data['success'] = 0;
        $data['msgtype'] = 'error';
        $data['data'] = array();

        $sql = "exec usp_LoadProductDescription $productcode ";

        $query = $this->db->query($sql);
        if ($query !== false) {
            $data['proinfo'] = $query->result_array();
            $data['colorinfo'] = $query->next_result();
            $data['success'] = 1;
        }

        return $data;
    }

    public function doLoadProductsByChasis($chasisno) {
        $data['success'] = 0;
        $data['msg'] = 'error';
        $data['data'] = array();

        $sql = "exec usp_LoadProduct '$business','$chasisno'";

        $query = $this->db->query($sql);
        if ($query !== false) {
            $row = $query->result();
            $data['success'] = 1;
            $data['data'] = $row;
        }
        $query->free_result();

        return $data;
    }

    public function doLoadBikes($userid, $chassesno) {
        $data['success'] = 0;
        $data['msg'] = 'error';
        $data['data'] = array();

        $sql = "exec usp_LoadBikeList '$userid','$chassesno'";

        $query = $this->db->query($sql);
        if ($query !== false) {
            $row = $query->result();
            $data['success'] = 1;
            $data['msg'] = '';
            $data['data'] = $row;
        }
        $query->free_result();

        return $data;
    }

    public function doCheckBike($userid, $chassesno) {
        $data['success'] = 0;
        $data['msg'] = 'error';
        $data['data'] = array();

        $sql = "SELECT TOP 1
                        ReceiveDetailsId, D.ReceiveID, D.ProductCode, ReceivedQnty, SoldQnty, 
                        CAST(P.MRP AS NUMERIC(18, 2)) AS UnitPrice,D.Vat,ChassisNo,EngineNo,D.Color,D.FuelUsed,D.HorsePower,D.RPM, D.CubicCapacity,D.WheelBase,
                        D.Weight,D.TireSizeFront,D.TireSizeRear,D.Seats,D.NoofTyre,D.NoofAxel,D.ClassOfVehicle,D.MakerName,D.MakerCountry,
                        D.EngineType,D.NumberofCylinders
                        ,ImportYear, P.ProductName
                    FROM DealarReceiveInvoiceDetails D
                        INNER JOIN Product P
                            ON D.ProductCode = P.ProductCode
                        INNER JOIN DealarReceiveInvoiceMaster M
                            ON D.ReceiveID = M.ReceiveID
                    WHERE ChassisNo = '$chassesno'
                            AND SoldQnty = '0' AND M.MasterCode = '$userid'
                        ";

        $query = $this->db->query($sql);
        if ($query !== false) {
            $row = $query->result();
            $data['success'] = 1;
            $data['msg'] = '';
            $data['data'] = $row;
        }
        $query->free_result();

        return $data;
    }

    public function ProductList($producttype) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();
        $sql = " SELECT * FROM Product WHERE Business = '$producttype' ORDER BY ProductName ";
        $query = $this->db->query($sql);

        if ($query !== false) {
            $rows = $query->result_array();
        }
        return $rows;
    }

    public function BrandList($producttype) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $rows = array();
        $sql = " SELECT * FROM ProdBrand WHERE Business = '$producttype' ORDER BY BrandName ";
        $query = $this->db->query($sql);

        if ($query !== false) {
            $rows = $query->result_array();
        }
        return $rows;
    }
    
    public function doLoadCustomerDetails($userid, $chassesno, $docheck = "%") {            
        $data['success'] = 0;
        $data['msg'] = 'error';
        $data['data'] = array();

        $sql = "exec usp_doLoadCustomerDetails '$chassesno','$docheck' ";

        $query = $this->db->query($sql);
        if ($query !== false) {
            $row = $query->result();
            $data['success'] = 1;
            $data['msg'] = '';                
            $data['data'] = $row;
        }
        $query->free_result();

        return $data;
    }

    public function loadLastServiceHistory($chassisno)
    {
        $sql = "exec usp_doloadLastServiceHistory '$chassisno' ";

        $query = $this->db->query($sql);
        if ($query !== false) {
            $row = $query->result();   
        }

        return $row;
    }

    public function doLoadServiceCustomerInfo($chassisNo)
    {
        $sql = "select 
                    m.CustomerName, m.MobileNo, m.MasterCode + ' - ' + C.CustomerName SoldDealer, m.InvoiceDate, P.ProductCode + ' - ' +  p.ProductName Model
                    --*
                From DealarInvoiceDetails d
                    inner join DealarInvoiceMaster m
                        on d.InvoiceID = m.InvoiceID
                    inner join Customer c
                        on c.CustomerCode = m.MasterCode
                    inner join Product p
                        on p.ProductCode = d.ProductCode
                where ChassisNo = '$chassisNo'";

        $query = $this->db->query($sql);
        if ($query !== false) {
            $rows = $query->result_array();   
        }

        return $rows;
    }
    
    public function doLoadServiceHistories($chassisNo)
    {
        $sql = "exec usp_doLoadServiceHistories '$chassisNo'";

        $query = $this->db->query($sql);
        if ($query !== false) {
            $rows = $query->result_array();   
        }

        return $rows;
    }

    public function doUpdateMileage($jobcardid, $mileage)
    {
        $sql = "UPDATE tblJobCard SET Mileage='$mileage' where Id='$jobcardid'";
        $query = $this->db->query($sql);
        if($query !== false){
            return true;
        }
        return false;
    }
    public function doDeleteJobcard($jobcardid)
    {
        $sql = "DELETE FROM tblJobCard where Id='$jobcardid'";
        $query = $this->db->query($sql);
        if($query !== false){
            return true;
        }
        return false;
    }

}
