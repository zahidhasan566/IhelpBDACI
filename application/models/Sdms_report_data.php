<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sdms_report_data extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function doLoadSdmsInvoiceList($datefrom, $dateto, $customercode)
    {
        $data['success'] = false;
        $data['msgtype'] = 'error';

        $sql = "SELECT 
                           B.BusinessName, InvoiceNo, InvoiceDate,  D.DepotName, C.CustomerCode, C.CustomerName
                    FROM Invoice I
                    INNER JOIN Business B
                           ON I.Business= B.Business
                    INNER JOIN Depot D
                           ON I.DepotCode = D.DepotCode
                    INNER JOIN Customer C
                           ON C.CustomerCode = I.CustomerCode
                    WHERE InvoiceDate BETWEEN '$datefrom' AND '$dateto'
                           AND C.CustomerCode = '$customercode' ";
        $query = $this->db->query($sql);

        if ($query !== false) {
            $data['invoice_list'] = $query->result_array();
        }
        return $data;
    }

    public function doLoadSdmsInvoicDetails($InvoiceNo)
    {
        $data['success'] = false;
        $data['msgtype'] = 'error';

        $sql = "SELECT C.CustomerCode, CT.CustTypeName, C.CustomerName, C.ContactPerson,C.Add1 + ' ' + C.Add2 Address , c.Mobile, c.Phone,
                    I.InvoiceDate, i.CISSNo, I.DeliveryDate, L.Level1Name, T.TTYName, I.PODate, I.PONumber, I.Reference,
                    P.ProductName, ID.SalesQTY + ID.BonusQTY Quantity, ID.SalesTP + ID.SalesVat DP, ID.Discount, ID.NET,
                    IDb.BatchNo ChassisNo, sb.engineno
                    FROM Invoice I
                        INNER JOIN InvoiceDetails ID
                            ON I.InvoiceNo  = ID.Invoiceno
                        INNER JOIN InvoiceDetailsBatch IDB
                            ON ID.Invoiceno = IDB.Invoiceno AND ID.ProductCode = IDB.ProductCode
                        INNER JOIN StockBatch sb 
                            ON idb.BatchNo = sb.BatchNo AND IDB.ProductCode = sb.ProductCode
                        INNER JOIN Product P
                            ON ID.ProductCode = P.ProductCode
                        INNER JOIN Customer C
                            ON I.CustomerCode = C.CustomerCode
                        INNER JOIN Territory T
                            ON T.TTYCode = C.TTYCode
                        INNER JOIN CustomerType CT
                            ON C.CustomerType = CT.CustomerType
                        INNER JOIN Level1 L
                            ON I.Level1 = L.Level1
                    WHERE I.InvoiceNo = '$InvoiceNo'
                    ORDER BY ID.NET DESC";

        $query = $this->db->query($sql);

        if ($query !== false) {
            $data['invoice_details'] = $query->result_array();
        }
        return $data;
    }

    public function doLoadSdmsCustomer($customercode, $business)
    {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        //echo $business; exit();

        // if($business == "Spare-Parts"){
        //     $sql = "SELECT * FROM CustomerMapping WHERE CustomerMasterCode = '$customercode' and business = 'P' ";
        //     $query = $this->db->query($sql);
        //     $customerdata = $query->result_array();
        //     $customercode = $customerdata[0]['CustomerCode'];
        //     //var_dump($customercode); exit();
        // }

        $sql = "SELECT C.*,	CM.CustomerCode CustomerCodeSP, CC.PaymentMode PaymentModeSP
                    FROM Customer C 
                        LEFT JOIN CustomerMapping CM
                            ON C.CustomerCode = CM.CustomerMasterCode
                            AND CM.Business = 'P'
                        LEFT JOIN Customer CC
                            ON CC.CustomerCode = CM.CustomerCode
                    WHERE C.CustomerCode = '$customercode'";

        $query = $this->db->query($sql);

        if ($query !== false) {
            return $query->result_array();
        }
        return $data;
    }

    public function doLoadSdmsCustomerLedger($datefrom, $dateto, $customercode, $PaymentMode, $DepotCode)
    {
        $data['success'] = false;
        $data['msgtype'] = 'error';

        $sql = "exec sp_CustomerLedgerNew '$datefrom','$dateto','$customercode','$PaymentMode','$DepotCode'";
        //exit();
        $query = $this->db->query($sql);

        if ($query !== false) {
            $data['customer_ledger_opening'] = $query->result_array();
            $data['customer_ledger_details'] = $query->next_result();
            $data['customer_ledger_closing'] = $query->next_result();
            return $data;
        }
        return $data;
    }

    public function doLoadSdmsCustomerWiseProductSold($datefrom, $dateto, $customercode, $Business)
    {
        $data['success'] = false;
        $data['msgtype'] = 'error';

        $sql = "SELECT 
                           B.BusinessName, C.CustomerCode, C.CustomerName, D.DepotName,
                           P.ProductCode,P.ProductName, SUM(ID.SalesQTY) SalesQTY, SUM(ID.BonusQTY) AS BonusQTY,  SUM(ID.SalesQTY + ID.BonusQTY) TotalQnty,
                           ID.Discount, ID.SalesTP,  SUM(ID.SalesTP * ID.SalesQTY) Gross, SUM(SalesVat * ID.SalesQTY) VAT
                    FROM Invoice I
                           INNER JOIN Customer C
                                  ON I.CustomerCode= C.CustomerCode
                           INNER JOIN Depot D
                                  ON C.DepotCode = D.DepotCode
                           INNER JOIN Business B
                                  ON I.Business = B.Business
                           INNER JOIN InvoiceDetails ID
                                  ON I.InvoiceNo = ID.Invoiceno
                           INNER JOIN Product P
                                  ON P.ProductCode = ID.ProductCode
                    WHERE InvoiceDate BETWEEN '$datefrom' AND '$dateto'
                    AND C.CustomerCode = '$customercode'
                    AND I.Business  = '$Business'
                    AND I.Returned = 'N'
                    GROUP BY B.BusinessName, C.CustomerCode, C.CustomerName, D.DepotName,
                           P.ProductCode,P.ProductName, ID.Discount, ID.SalesTP
                    ORDER BY ProductName
                    ";

        $query = $this->db->query($sql);

        if ($query !== false) {
            return $query->result_array();
        }
        return $data;
    }

    function importDealerOfferData($month, $dealer_offer_data)
    {
        $query = "SELECT * FROM DealerOffer where Month='$month'";
        $prevData = $this->db->query($query);
        if($prevData != null){
            $query = "DELETE FROM DealerOffer where Month='$month'";
            $this->db->query($query);
        }

        foreach($dealer_offer_data as $deal){
            $query = "INSERT INTO DealerOffer(Month,Code,Dealer,Particulars,Amount) VALUES ('$month','$deal[0]','$deal[1]','$deal[2]','$deal[3]')";
            $this->db->query($query);
        }
        return "success";
    }

    public function doLoadSdmsDealerOfferList($month, $customercode)
    {
        $data['success'] = false;
        $data['msgtype'] = 'error';

        $sql = "EXEC usp_doLoadDealerOfferReport '$month','$customercode'";
        // echo $sql;exit();
        $query = $this->db->query($sql);

        $e = $this->db->_error_message();
        // echo $e."hi";exit();
		if($e){ 
            return false; 
        }

        if ($query !== false) {
            $data['dealer_offer_list'] = $query->result_array();
        }
        return $data;
    }
    
    public function doLoadNationalDayWiseSummery($datefrom, $dateto, $customercode)
    {
        $data['success'] = false;
        $data['msgtype'] = 'error';

        $sql = "EXEC usp_doLoadNationalDayWiseSummery '$datefrom','$dateto','$customercode'";
        // echo $sql;exit();
        $query = $this->db->query($sql);

        $e = $this->db->_error_message();
        // echo $e."hi";exit();
		if($e){ 
            return false; 
        }

        if ($query !== false) {
            $data['day_wise_list'] = $query->result_array();
        }
        return $data;
    }

    public function doLoadClaimWarrantyList($datefrom, $dateto, $customercode)
    {
        $data['success'] = false;
        $data['msgtype'] = 'error';

        if($customercode==''){
            $sql = "SELECT DC.DCWarrantyID,DC.MasterCode,C.CustomerName,DC.WCDate,
            DC.ChassisNo,DC.Mileage,DC.ApproveBy, DP.ProductCode,P.ProductName,
            DP.Quantity,DP.ServiceCharge,DP.UnitPrice 
            from DealarWarrantyClaim as DC 
            left join DealarWarrantyClaimProduct DP on DP.DCWarrantyID=DC.DCWarrantyID
            left join Product P on P.ProductCode=DP.ProductCode
            left join Customer C on C.CustomerCode=DC.MasterCode
            where DC.ApproveBy<>''
            and DP.ProductCode is not null
            and DC.WCDate between '$datefrom' and '$dateto'
            order by DC.MasterCode asc";
        }else{
        $sql = "SELECT DC.DCWarrantyID,DC.MasterCode,C.CustomerName,DC.WCDate,
                    DC.ChassisNo,DC.Mileage,DC.ApproveBy, DP.ProductCode,P.ProductName,
                    DP.Quantity,DP.ServiceCharge,DP.UnitPrice 
                    from DealarWarrantyClaim as DC 
                    left join DealarWarrantyClaimProduct DP on DP.DCWarrantyID=DC.DCWarrantyID
                    left join Product P on P.ProductCode=DP.ProductCode
                    left join Customer C on C.CustomerCode=DC.MasterCode
                    where DC.ApproveBy<>''
                    and DP.ProductCode is not null
                    and DC.MasterCode='$customercode' 
                    and DC.WCDate between '$datefrom' and '$dateto'
                    order by DC.WCDate asc";
        }
        $query = $this->db->query($sql);

        if ($query !== false) {
            $data['warranty_list'] = $query->result_array();
        }
        return $data;
    }
}
