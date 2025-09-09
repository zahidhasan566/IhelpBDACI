<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Model{

	public function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    public function login($userid, $password) {
        $data['success'] = false;
        $data['msgtype'] = 'error';
        $sql = "SELECT password, grpsup, grpsup grpUser, grpISup,UserName, designation
                FROM MotorMC.Dbo.UserManager
                WHERE userid = '$userid'
                    AND (active = 'Y' OR active = 0);";
        $query = $this->db->query($sql);
	$e = $this->db->_error_message();

        if ($query !== false) {
            $row = $query->row();
            if (count($row) > 0) {
            //print_r($row); exit();
                //echo passdecode($row->password); exit();
                if (passdecode($row->password) == $password){
                    //echo var_dump($row); exit();
                    $data['data']['grpUser']    = $row;
                    $data['success']            = true;
                    $data['msg']                = 'Login successfull.';
                    $data['msgtype']            = 'success';
                }
            }
        }
        return $data;
    }

    public function checkPassword($userid, $oldpass) {
        $sql = "SELECT userid
                FROM MotorMC.Dbo.UserManager
                WHERE userid = '$userid'
                    AND Password = '$oldpass' ";

        $query = $this->db->query($sql);
        if ($query !== false) {
            $row = $query->row();
            if (count($row) > 0) {
                return 1;
            } else {
                return 0;
            }
        }
    }

    public function changePassword($userid, $newpass) {
         $sql = "UPDATE MotorMC.Dbo.UserManager SET
                    Password = '$newpass'
                WHERE userid = '$userid' ";

        $query = $this->db->query($sql);
        if ($query !== false) {
            return true;
        } else {
            return false;
        }
    }
    public function checkPasswordChanged($userid, $depotcode) {
        $passchanged = 0;
        $sql = "SELECT passwordchanged
                FROM users
                WHERE userid = '$userid'
                    AND depotcode = '$depotcode'
                    AND is_active = 1;";

        $query = $this->db->query($sql);
        if ($query !== false) {
            $row = $query->row();
            if ($row !== false && count($row)>0) {
                $passchanged = $row->passwordchanged;
            }
        }
        return $passchanged;
    }

    public function create($data) {
        $success = true;
        $sql = "INSERT INTO users (userid, username, depotcode, password, is_active, ipaddress)
                VALUES('".$data['userid']."','".$data['username']."','".$data['depotcode']."',".
                "'".$data['password']."',".$data['is_active'].",'".$data['ipaddress']."')";

        $query = $this->db->query($sql);

        $e = $this->db->_error_message();
        if ($e == '') {
            $success = true;
        } else {
            $success = $e;
        }

        return $success;
    }

    public function getUsers() {
        $rows = array();
        $sql = "SELECT u.userid, u.username,
                    d.depotcode + ' - '+d.shortname AS depotname, u.is_active,
                    (CASE WHEN u.groupid = 1
                        THEN 'Administrator'
                        ELSE 'User'
                    END) AS ugroup, u.depotcode
                FROM users u
                    INNER JOIN [epsserver].[epsmirror].dbo.depot d ON
                        u.depotcode = d.depotcode
                WHERE UPPER(u.userid) != 'ADMIN'";

        $query = $this->db->query($sql);
        if ($query !== false){
            $rows = $query->result();
        }
        return $rows;
    }

    public function ResetPassword($userid, $depotcode) {
        $password = base64_encode('1234');
        $passchanged = 0;
        $sql = "UPDATE users
                    SET password = '$password'
                WHERE userid = '$userid';";

        $query = $this->db->query($sql);
        if ($query !== false) {
            $passchanged = 1;
        }
        return $passchanged;
    }
}
/**
* Emd of User_manager Model
*/
