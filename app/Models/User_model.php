<?php
namespace App\Models;
use CodeIgniter\Model;
class User_model extends  Model {
    /** 
        most of the function are being called on coresponding controllers
        others directly called on the views.
        This part where all the query communication to the database are being executed
        * @var builder is use for the query builder
    */

    /** 
        Properties being used on this file
        * @property db for the call of database
        * @property request for the post function method
        * @property encrypter for the encryption/decryption method
        * @property time for the current internet time Asia/Singapore based
        * @property date for the current internet date Asia/Singapore based
    */
    protected $db;
    protected $request;
    protected $encrypter;
    protected $time;
    protected $date;


    /**
        * ---------------------------------------------------
        * @property declared table used on the model, ci4 intends to declared table this way
        * ---------------------------------------------------
    */
    protected $tblu = "tbl_user";
    protected $tbluat = "tbl_user_account_type";
    
    /**
        * @method func __construct() is being executed automatically when this file is loaded
        * load all the methods/object on the property of class above and used by other @method
    */
    public function __construct(){

        $this->db = \Config\Database::connect('default'); 
        $this->request = \Config\Services::request();
        $this->encrypter = \Config\Services::encrypter(); 
        date_default_timezone_set("Asia/Singapore"); 
        $this->time = date("H:i:s"); 
        $this->date = date("Y-m-d");

    }


    /**
        ---------------------------------------------------
        User Module area
        ---------------------------------------------------

        * @method getadder() use to get the admin information based on id
        * @param uID decrypted data of user_id
        * @return users->as->single_result
    */
    public function accountType(){
        
        $res = $this->db->query("select * from ".$this->tbluat." 
        where status = 'active' ");
        return $res->getResultArray();

    }


    /**
        * @method viewaActiveUser() use to get the active user information 
        * @return users->as->multiple_result
    */
    public function viewaActiveUser(){

        $res = $this->db->query("select tu.* , uat.name as accounttype
        from ".$this->tblu." as tu, ".$this->tbluat." as uat
        where tu.user_account_type_id = uat.user_account_type_id
        and user_id != 2
        and tu.status = 'active'");
        return $res->getResultArray();

    }


    /**
        * @method viewaInactiveUser() use to get the inactive user information 
        * @return users->as->multiple_result
    */
    public function viewaInactiveUser(){

        $res = $this->db->query("select tu.* , uat.name as accounttype
        from ".$this->tblu." as tu, ".$this->tbluat." as uat
        where tu.user_account_type_id = uat.user_account_type_id
        and user_id != 2
        and tu.status = 'inactive'");
        return $res->getResultArray();

    }


    /**
        * @method saveUser() use to register the user information 
        * @var data data container of user information
        * @return sql_execution bool
    */
    public function saveUser(){

        $data = array(
            'user_account_type_id' => $this->encrypter->decrypt($this->request->getPost('user-account-type')),
            'username' => $this->request->getPost('username'),
            'password' => $this->encrypter->encrypt($this->request->getPost('password')), 
            'name' => ucfirst($this->request->getPost('name')),
            'contact_number' => $this->request->getPost('contact-number'),
            'email_address' => $this->request->getPost('email-address'),
            'status' => 'active',
            'added_by' => $this->encrypter->decrypt($_SESSION['userID']),
            'added_on' => $this->date.' '.$this->time
        );
        $this->db->table($this->tblu)->insert($data);
 
    }


    /**
        * @method updateUser() use to update the user information based on id
        * @param uID encrypted data of user_id
        * @var uid decrypted data of user_id
        * @var data->true data container of user information without password
        * @var data->false data container of user information with password
        * @return sql_execution bool
    */
    public function updateUser($uID){

        $uid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$uID));
    
        if(empty($this->request->getPost('password')) or  $this->request->getPost('password') == '' or  $this->request->getPost('password') == null){

            $data = array(
                'user_account_type_id' => $this->encrypter->decrypt($this->request->getPost('user-account-type')),
                'username' => $this->request->getPost('username'),
                'name' => ucfirst($this->request->getPost('name')),
                'contact_number' => $this->request->getPost('contact-number'),
                'email_address' => $this->request->getPost('email-address'),
                'status' => $this->request->getPost('status'),
                'updated_by' => $this->encrypter->decrypt($_SESSION['userID']),
                'updated_on' => $this->date.' '.$this->time
            );

        }else{

            $data = array(
                'user_account_type_id' => $this->encrypter->decrypt($this->request->getPost('user-account-type')),
                'username' => $this->request->getPost('username'),
                'password' => $this->encrypter->encrypt($this->request->getPost('password')), 
                'name' => ucfirst($this->request->getPost('name')),
                'contact_number' => $this->request->getPost('contact-number'),
                'email_address' => $this->request->getPost('email-address'),
                'status' => $this->request->getPost('status'),
                'updated_by' => $this->encrypter->decrypt($_SESSION['userID']),
                'updated_on' => $this->date.' '.$this->time
            );

        }

        $this->db->table($this->tblu)->where('user_id', $uid)->update($data);

    }

    /**
        ---------------------------------------------------
        End of User Module area
        ---------------------------------------------------
    */

}
