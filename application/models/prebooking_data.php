<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class prebooking_data extends CI_Model {

    public function __construct() {
        parent::__construct();
    }      
    
    public function doInsertPreBooking($mastercode, $customername, $customeraddress, $phonenumber, $age, 
                $occupation, $gender, $currentlyusingmotorcycle, $productcode, $prebookedamount,
                $moneyreceiptsnumber, $entryby){
        $data = array();
        $data['success'] = 0;        
       
        $sql = "INSERT INTO [dbo].[PreBooking]
           ([MasterCode] ,[CustomerName] ,[CustomerAddress] ,[PhoneNumber] ,[Age] ,
		   [Occupation] ,[Gender] ,[CurrentlyUsingMotorcycle] ,[ProductCode] ,[PreBookedAmount] ,
		   [MoneyReceiptsNumber] ,[EntryBy] ,[EntryDate])
            VALUES
           ('$mastercode', '$customername', '$customeraddress', '$phonenumber', '$age',
            '$occupation', '$gender', '$currentlyusingmotorcycle', '$productcode', '$prebookedamount',
            '$moneyreceiptsnumber', '$entryby', GETDATE())";

        $query = $this->db->query($sql);
        if ($query !== false) {
            return true;
        }else{
            return false;
        }
    }
    
    public function doLoadPreBookingReport($mastercode,
                $datefrom, $dateto){
        $data = array();
        $data['success'] = 0;        
       
        $sql = "SELECT 
                    B.MasterCode + ' - ' + C.CustomerName Dealer,  
                    B.CustomerName,
                    B.CustomerAddress,
                    B.PhoneNumber,
                    B.Age,
                    B.Occupation,
                    B.Gender,
                    B.CurrentlyUsingMotorcycle Currently_Using_Bike,
                    P.ProductCode  + ' - ' + P.ProductName Product,
                    B.PreBookedAmount AS Pre_Booked_Amount,
                    B.MoneyReceiptsNumber AS Money_Receipts_Number,
                    LEFT(EntryDate,12) AS Entry_Date
                FROM Prebooking B
                    INNER JOIN Customer C
                            ON B.MasterCode = C.CustomerCode
                    INNER JOIN Product P
                            ON B.ProductCode = P.ProductCode
                WHERE ('$mastercode' = '' OR B.MasterCode = '$mastercode')"
                . " AND B.EntryDate BETWEEN '$datefrom' AND '$dateto  23:59:59.00' ";
        
        $query = $this->db->query($sql);
        if ($query !== false) {
            return $query->result_array();
        }else{
            return false;
        }
    }
	
}
