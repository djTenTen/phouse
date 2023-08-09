<?php
namespace App\Models;
use CodeIgniter\Model;
class Login_model extends Model{
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
    protected $session;
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
    protected $tbls = "tbl_session";
    protected $tbluat = "tbl_user_account_type";


    /**
        * @method func __construct() is being executed automatically when this file is loaded
        * load all the methods/object on the property of class above and used by other @method
    */
    public function __construct(){

        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect('default'); 
        $this->request = \Config\Services::request();
        $this->encrypter = \Config\Services::encrypter(); 
        date_default_timezone_set("Asia/Singapore"); 
        $this->time = date("H:i:s"); 
        $this->date = date("Y-m-d");

    }


    /**
        * @method authenticate() is being executed during the login of the user
        * @param un->pss are the credentials of a user
        * @var time contains of internet time Asie/Singapore based
        * @var date contains of internet date Asie/Singapore based
        * @var db loads the connection to the database
        * @var epss contains the hashed password from database
        * @var uid contains user_id
        * @method password_verify use to compare the hashed password from db to the inputed password
        * if success it will record a access log, other wise it @return failed
    */
    public function authenticate(string $un, string $pss){

        // fetching encrypted data password from database based on username
        $query = "select tu.* , uat.name as accounttype from ".$this->tblu." tu, ".$this->tbluat." uat
        where BINARY username = ?
        and tu.user_account_type_id = uat.user_account_type_id
        and tu.status = 'active'";
        $res = $this->db->query($query, array($un));
        
        //verify if user exist
        if($res->getNumRows() > 0){

            foreach($res->getResultArray() as $row){

                try { $this->encrypter->decrypt($row['password']);} catch (\Throwable $th) {return 'failed';}

                $epss = $this->encrypter->decrypt($row['password']);
                $uid = $row['user_id'];

                if($epss == $pss){

                    if($row['status'] == 'active'){

                        $data  = [
                            'type' => 'admin',
                            'id' => $row['user_id'],
                            'system_session' => session_id(),
                            'log_time' => $this->date.' '.$this->time
                        ];
                        $this->db->table($this->tbls)->insert($data);

                        $getSID = $this->db->query("select * from ".$this->tbls." order by session_id desc limit 1")->getRowArray();

                        $userdata = [
                            'sessionID' => $getSID['session_id'],
                            'SysSess' => $getSID['system_session'],
                            'userID' => $row['user_id'],
                            'username' => $row['username'],
                            'name' => $row['name'],
                            'accounttype' => $row['accounttype'],
                            'uatid' => $row['user_account_type_id'],
                            'status' => $row['status']
                        ];
                      
                        return $userdata;

                    } else {
                        return 'inactive';
                    }
                } else {
                    return 'failed';
                }

            }
            
        } else {
            return 'failed';
        }

    }


    


    /**
        * @method updateAuthentication() is to update the user's authentication
        * @var userID encrypted data of user_id
        * @var sessionID encrypted data of session_id
        * @return sql_execution bool
    */
    public function updateAuthentication(){

        $builder = $this->db->table($this->tbls);
        $builder->where("system_session",  $_SESSION['SysSess']);
        $builder->where("id", $this->encrypter->decrypt($_SESSION['userID']) );
        $builder->update(['log_time' => $this->date.' '.$this->time]);

    }



    public function logout(){

        $dsID = $this->encrypter->decrypt($_SESSION['sessionID']);
        $this->db->table($this->tbls)->where("id", $this->encrypter->decrypt($_SESSION['userID']) )->where("session_id", $dsID)->delete();
        session_destroy();

    }

}