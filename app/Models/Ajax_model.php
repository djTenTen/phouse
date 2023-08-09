<?php
namespace App\Models;
use CodeIgniter\Model;
class Ajax_model extends  Model {
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
    protected $tblsess = "tbl_session";
    protected $tbluat = "tbl_user_account_type";
    protected $tble = "tbl_employee";
    protected $tblb = "tbl_branch";
    protected $tblt = "tbl_tenant";
    protected $tblc = "tbl_category";
    protected $tblexp = "tbl_expense";
    protected $tblep = "tbl_expense_payment";
    protected $tblei = "tbl_expense_item";
    protected $tblec = "tbl_expense_category";
    protected $tblba = "tbl_bank_account";
    protected $tblbk = "tbl_bank";
    protected $tblft = "tbl_fund_transfer";
    protected $tblct = "tbl_contract";
    protected $tblcp = "tbl_contract_payment";
    protected $tblcl = "tbl_collection_list";
    protected $tblcli = "tbl_collection_list_item";
    protected $tblpr = "tbl_payroll";
    protected $tblpri = "tbl_payroll_item";
    protected $tbls = "tbl_support";
    protected $tblsr = "tbl_support_reply";

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
        ----------------------------------------------------------
        Login Module area
        ----------------------------------------------------------
        * @method checkAuthentication() is being executed during the login of the user
        * @param uID encrypted data of user_id
        * @var ueID decrypted data of user_id
        * @var db loads the connection to the database
        * @var result contains the login information of the user
        * if existed system will let you continue to use the system
        * otherwise it will @return eject and eject you
    */
    public function checkAuthentication(){
        
        $dsID = $this->encrypter->decrypt($_SESSION['sessionID']);
        $uID = $this->encrypter->decrypt($_SESSION['userID']);
        $system_session = $_SESSION['SysSess'];
        $result = $this->db->query("select * from ".$this->tblsess." where session_id = $dsID and system_session = '$system_session' and id =  $uID");
        
        if($result->getNumRows() > 0){

            $tf = $result->getRowArray();

            $date1 = new \DateTime($tf['log_time']);
            $date2 = new \DateTime($this->date.' '.$this->time);

            $interval = $date2->diff($date1);
            $totalMinutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

            if($totalMinutes >= 30){

                $this->db->table($this->tblsess)->where("session_id", $dsID)->delete();
                $_SESSION['authentication'] = false;
                $this->session->remove(['userID','sessionID','SysSess','name','accounttype','status']);
                $_SESSION['session_timeout'] = 'session_timeout';
                echo "invalid";

            }else{

                echo "valid";

            }

        }else{

            $_SESSION['authentication'] = false;
            $_SESSION['userID'] = 0;
            $_SESSION['sessionID'] = 0;
            $_SESSION['SysSess'] = 0;
            $_SESSION['eject'] = 'eject';
            echo "invalid";

        }

    }


    








    /**
        ----------------------------------------------------------
        User Module area
        ----------------------------------------------------------
        * @method validateUsername() use to validate the user if exist based on @param username
        * @param username contains inputed data from front end
        * @return bool
    */
    public function validateUsername($username){

        $query = "select username from ".$this->tblu." where username = ? limit 1";
        $res = $this->db->query($query, array($username));
        if($res->getNumRows() > 0){ 
            return true;
        }else{
            return false;
        }

    }

    /**
        * @method editUser() use to get user information based on id
        * @param uID encrypted data of userid_id
        * @var uid decrypted data of userid_id
        * @var array->arr used as storage of data before being encode as json
        * @return json_encode
    */
    public function editUser($uID){

        $uid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$uID));

        $query = $this->db->query("select tu.* , uat.name as accounttype
        from ".$this->tblu." as tu, ".$this->tbluat." as uat
        where tu.user_account_type_id = uat.user_account_type_id
        and user_id = $uid");
        $arr = [];
        foreach($query->getResultArray() as $row){

            $uati = $this->encrypter->encrypt($row['user_account_type_id']);
            $data = [
                'userID' => $uID,
                'user_id' => $row['user_id'],
                'username' => $row['username'],
                'name' => $row['name'],
                'accounttypeid' => $uati,
                'accounttypename' => $row['accounttype'],
                'contact_number' => $row['contact_number'],
                'email_address' => $row['email_address'],
                'status' => $row['status']
            ];
            array_push($arr, $data);
            
        }

        echo json_encode($arr);

    }








    /**
        ----------------------------------------------------------
        Expense Module area
        ----------------------------------------------------------
        * @method getExpenseDetails() use to get expense details based on id
        * @param eID encrypted data of userid_id
        * @var eid decrypted data of userid_id
        * @var data used as storage of data before being encode as json
        * @return json_encode
    */
    public function getExpenseDetails($eID){

        $eid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$eID));

        $query = $this->db->query("SELECT e.expense_id, e.payee, e.date,e.status, u.name as added_by, e.added_on
        FROM ".$this->tblexp." e
        INNER JOIN ".$this->tblu." u ON e.added_by = u.user_id
        WHERE e.expense_id = $eid");

        $row = $query->getRowArray();

        $data = [
            'expense_id' => $row['expense_id'], 
            'payee' => $row['payee'], 
            'date' => $row['date'], 
            'status' => $row['status'], 
            'added_by' => $row['added_by'], 
            'added_on' => $row['added_on'], 
        ];

        echo json_encode($data); 

    }


    /**
        * @method getExpenseItems() use to get expense item details based on id
        * @param eID encrypted data of userid_id
        * @var eid decrypted data of userid_id
        * @var array->arr used as storage of data before being encode as json
        * @return json_encode
    */
    public function getExpenseItems($eID){

        $eid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$eID));

        $query = $this->db->query("SELECT b.name as branch, ec.name as category, ei.description, ei.amount
            FROM ".$this->tblei." ei
            INNER JOIN ".$this->tblec." ec ON ei.expense_category_id = ec.expense_category_id
            INNER JOIN ".$this->tblb." b ON ei.branch_id = b.branch_id
            WHERE ei.expense_id = $eid");

        $arr = [];
        foreach($query->getResultArray() as $row){

            $data = [
                'branch' => $row['branch'],
                'category' => $row['category'],
                'description' => $row['description'],
                'amount' => $row['amount'],
            ];
            array_push($arr, $data);
        }

        echo json_encode($arr);

    }


    /**
        * @method getExpensePayment() use to get expense payment details based on id
        * @param eID encrypted data of userid_id
        * @var eid decrypted data of userid_id
        * @var array->arr used as storage of data before being encode as json
        * @return json_encode
    */
    public function getExpensePayment($eID){

        $eid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$eID));

        $query = $this->db->query("SELECT b.name as bank, ba.name as bank_account, ep.type, ep.date, ep.check_number, ep.amount, ep.status, ep.date_posted
        FROM ".$this->tblep." ep
        INNER JOIN ".$this->tblba." ba ON ep.bank_account_id = ba.bank_account_id
        INNER JOIN ".$this->tblbk." b ON ba.bank_id = b.bank_id
        WHERE ep.expense_id = $eid");

        $arr = [];
        foreach($query->getResultArray() as $row){

            $data = [
                'bank' => $row['bank'],
                'bank_account' => $row['bank_account'],
                'type' => $row['type'],
                'date' => $row['date'],
                'check_number' => $row['check_number'],
                'amount' => $row['amount'],
                'status' => $row['status'],
                'date_posted' => $row['date_posted'],
            ];
            array_push($arr, $data);
        }

        echo json_encode($arr);

    }



    /**
        ----------------------------------------------------------
        Fund Transfer Module area
        ----------------------------------------------------------
        * @method getFundTransferDetails() use to get fund transfer details based on id
        * @param ftID encrypted data of fund_transfer_id
        * @var ftid decrypted data of fund_transfer_id
        * @var data used as storage of data before being encode as json
        * @return json_encode
    */
    public function getFundTransferDetails($ftID){

        $ftid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$ftID));
        $query = $this->db->query("select * from ".$this->tblft." where fund_transfer_id = $ftid");
        
        $row = $query->getRowArray();

        $data = [
            'fund_transfer_id' => $row['fund_transfer_id'], 
            'purpose' => $row['purpose'], 
            'check_number' => $row['check_number'], 
            'status' => $row['status'], 
            'amount' => $row['amount'], 
            'date' => $row['date']
        ];

        echo json_encode($data);

    }


    /**
        * @method getContractDetails() use to get contract details based on id
        * @param cID encrypted data of contract_id
        * @var cid decrypted data of contract_id
        * @var data used as storage of data before being encode as json
        * @return json_encode
    */
    public function getContractDetails($cID){

        $cid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$cID));

        $query = $this->db->query("SELECT c.contract_id, t.name AS tenant, b.name AS branch, c.slot_number, c.commission, c.monthly_rate, c.duration, c.start, c.end, c.status 
        FROM ".$this->tblct." c
        INNER JOIN ".$this->tblt." t ON c.tenant_id = t.tenant_id
        INNER JOIN ".$this->tblb." b ON c.branch_id = b.branch_id
        WHERE c.contract_id = $cid
        ORDER BY c.added_on DESC");

        $row = $query->getRowArray();
        
        $data = [
            'contract_id' => $row['contract_id'], 
            'tenant' => $row['tenant'], 
            'branch' => $row['branch'], 
            'slot_number' => $row['slot_number'], 
            'commission' => $row['commission'], 
            'monthly_rate' => $row['monthly_rate'],
            'duration' => $row['duration'],
            'start' => $row['start'],
            'end' => $row['end'],
            'status' => $row['status']
        ];

        echo json_encode($data);

    }


    /**
        * @method getSecDepDetails() use to get security deposit details based on id
        * @param cID encrypted data of contract_id
        * @var cid decrypted data of contract_id
        * @var array->arr used as storage of data before being encode as json
        * @return json_encode
    */
    public function getSecDepDetails($cID){

        $cid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$cID));

        $query = $this->db->query("SELECT cp.type, b.name AS bank, cp.check_number, cp.amount, cp.date, cp.status 
        FROM ".$this->tblcp." cp
        LEFT JOIN ".$this->tblbk." b ON cp.bank_id = b.bank_id
        WHERE cp.contract_id = $cid
        AND cp.identifier = 'security-deposit'");

        $arr = [];
        foreach($query->getResultArray() as $row){

            $data = [
                'type' => $row['type'],
                'bank' => $row['bank'],
                'check_number' => $row['check_number'],
                'amount' => $row['amount'],
                'date' => $row['date'],
                'status' => $row['status'],
            ];
            array_push($arr, $data);
        }

        echo json_encode($arr);

    }


    /**
        * @method getCheckDepDetails() use to get check deposit details based on id
        * @param cID encrypted data of contract_id
        * @var cid decrypted data of contract_id
        * @var array->arr used as storage of data before being encode as json
        * @return json_encode
    */
    public function getCheckDepDetails($cID){

        $cid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$cID));

        $query = $this->db->query("SELECT cp.type, b.name AS bank, cp.check_number, cp.amount, cp.date, cp.status 
            FROM ".$this->tblcp." cp
            LEFT JOIN ".$this->tblbk." b ON cp.bank_id = b.bank_id
            WHERE cp.contract_id = $cid");

        $arr = [];

        foreach($query->getResultArray() as $row){

            $data = [
                'type' => $row['type'],
                'bank' => $row['bank'],
                'check_number' => $row['check_number'],
                'amount' => $row['amount'],
                'date' => $row['date'],
                'status' => $row['status'],
            ];

            array_push($arr, $data);
        }

        echo json_encode($arr);


    }
















    /**
        ----------------------------------------------------------
        Employee Module area
        ----------------------------------------------------------
        * @method getEmployeeInfo() use to get employee details based on id
        * @param eID encrypted data of employee_id
        * @var eid decrypted data of employee_id
        * @var data used as storage of data before being encode as json
        * @return json_encode
    */
    public function getEmployeeInfo($eID){

        $eid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$eID));

        $query = $this->db->query("select e.*, b.name as branch , b.branch_id
        from ".$this->tble." e, ".$this->tblb." b  
        where e.assigned_branch = b.branch_id
        and employee_id = $eid");
        $row = $query->getRowArray();

        $data = [
            'first_name' => $row['first_name'],
            'middle_name' => $row['middle_name'],
            'last_name' => $row['last_name'],
            'address' => $row['address'],
            'gender' => $row['gender'],
            'birthday' => $row['birthday'],
            'contact_number' => $row['contact_number'],
            'salary' => $row['salary'],
            'salary_type' => $row['salary_type'],
            'has_payslip' => $row['has_payslip'],
            'date_hired' => $row['date_hired'],
            'position' => $row['position'],
            'assigned_branch' => $this->encrypter->encrypt($row['assigned_branch']),
            'branch' => $row['branch'],
            'metrobank_account_number' => $row['metrobank_account_number'],
            'tin_number' => $row['tin_number'],
            'sss_number' => $row['sss_number'],
            'philhealth_number' => $row['philhealth_number'],
            'pagibig_number' => $row['pagibig_number'],
            'barangay_clearance' => $row['barangay_clearance'],
            'nbi_clearance' => $row['nbi_clearance'],
            'emergency_contact_name' => $row['emergency_contact_name'],
            'emergency_contact_number' => $row['emergency_contact_number'],
            'emergency_contact_relation' => $row['emergency_contact_relation'],
            'status' => $row['status']
        ];

        echo json_encode($data);

    }






















    /**
        ----------------------------------------------------------
        Payroll Module area
        ----------------------------------------------------------
        * @method getEmployeeInfo() use to get payroll details based on id
        * @param pID encrypted data of payroll_id
        * @var pid decrypted data of payroll_id
        * @var data used as storage of data before being encode as json
        * @return json_encode
    */
    public function getPayrollInfo($pID){

        $pid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$pID));

        $query = $this->db->query("SELECT p.payroll_id, p.coverage, u.name AS added_by, p.added_on 
        FROM ".$this->tblpr." p
        INNER JOIN ".$this->tblu." u ON p.added_by = u.user_id
        WHERE p.payroll_id = $pid");
        $row = $query->getRowArray();

        $date = explode(" - ", $row['coverage']);

        $data = [
            'payroll_id' => $row['payroll_id'],
            'coverage' => $row['coverage'],
            'date1' => date_format(date_create($date[0]),"F d, Y"),
            'date2' => date_format(date_create($date[1]),"F d, Y"),
            'added_by' => $row['added_by'],
            'added_on' => $row['added_on']
        ];

        echo json_encode($data);

    }



    /**
        ----------------------------------------------------------
        Payroll Module area
        ----------------------------------------------------------
        * @method getPayrollItems() use to get payroll items details based on id
        * @param pID encrypted data of payroll_id
        * @var pid decrypted data of payroll_id
        * @var data used as storage of data before being encode as json
        * @return json_encode
    */
    public function getPayrollItems($pID){

        $pid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$pID));

        $query = $this->db->query("SELECT pi.payroll_item_id, e.employee_id, e.first_name, e.middle_name, e.last_name, e.assigned_branch, pi.earned_salary, pi.sss, pi.philhealth, pi.pagibig, pi.deduction, pi.remark 
        FROM ".$this->tblpri." pi
        INNER JOIN (SELECT emp.employee_id, emp.first_name, emp.middle_name, emp.last_name, b.branch_id, b.name AS assigned_branch FROM ".$this->tblpe." emp INNER JOIN ".$this->tblb." b ON emp.assigned_branch = b.branch_id) e 
        ON pi.employee_id = e.employee_id
        WHERE pi.payroll_id = $pid
        ORDER BY e.branch_id ASC, e.first_name ASC");

        $arr = [];
        foreach($query->getResultArray() as $row){

            $data = [
                'payroll_item_id' => $row['payroll_item_id'],
                'employee_id' => $row['employee_id'],
                'assigned_branch' => $row['assigned_branch'],
                'first_name' => $row['first_name'],
                'middle_name' => $row['middle_name'],
                'last_name' => $row['last_name'],
                'earned_salary' => $row['earned_salary'],
                'sss' => $row['sss'],
                'philhealth' => $row['philhealth'],
                'pagibig' => $row['pagibig'],
                'deduction' => $row['deduction'],
                'net_salary' => $row['earned_salary'] - ($row['sss'] + $row['philhealth'] + $row['pagibig'] + $row['deduction']),
                'remark' => $row['remark']
            ];
            array_push($arr, $data);

        }

        echo json_encode($arr);

    }


    






















    /**
        ----------------------------------------------------------
        Tenant Module area
        ----------------------------------------------------------
        * @method getTenantInfo() use to get payroll items details based on id
        * @param tID encrypted data of tenant_id
        * @var tid decrypted data of tenant_id
        * @var data used as storage of data before being encode as json
        * @return json_encode
    */
    public function getTenantInfo($tID){

        $tid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$tID));

        $query = $this->db->query("select tt.*, tc.name as category 
        from ".$this->tblt." tt,".$this->tblc." tc 
        where tenant_id = $tid
        and tt.category_id = tc.category_id");
        $row = $query->getRowArray();

        $data = [
            'tenant_id' => $row['tenant_id'],
            'category_id' => $this->encrypter->encrypt($row['category_id']),
            'category' => $row['category'],
            'name' => $row['name'],
            'facebook' => $row['facebook'],
            'instagram' => $row['instagram'],
            'email_address' => $row['email_address'],
            'contact_person' => $row['contact_person'],
            'contact_number' => $row['contact_number'],
            'status' => $row['status']
        ];

        echo json_encode($data);

    }


    /**
        * @method getTenantConcern() use to get tenant ticket/concern details based on id
        * @param tID encrypted data of tenant_id
        * @var tid decrypted data of tenant_id
        * @var data used as storage of data before being encode as json
        * @return json_encode
    */
    public function getTenantConcern($tID){

        $tid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$tID));
        
        $query = $this->db->query("SELECT s.support_id, s.subject, IFNULL(COUNT(sr.support_id), 0) AS reply, s.status, u.name AS added_by, s.added_on 
        FROM ".$this->tbls." s
        INNER JOIN ".$this->tblu." u ON s.added_by = u.user_id
        LEFT JOIN ".$this->tblsr." sr ON s.support_id = sr.support_id
        WHERE s.tenant_id = $tid
        GROUP BY s.support_id, s.subject, s.status, u.name, s.added_on");

        $arr = [];
        foreach($query->getResultArray() as $row){

            $data = [
                'support_id' => $row['support_id'],
                'subject' => $row['subject'],
                'reply' => $row['reply'],
                'status' => $row['status'],
                'added_by' => $row['added_by'],
                'added_on' => date_format(date_create($row['added_on']),"F d, Y")
            ];
            array_push($arr, $data);

        }

        echo json_encode($arr);

    }























    /**
        ----------------------------------------------------------
        Support Module area
        ----------------------------------------------------------
        * @method getBranchName() use to get branch name details based on id
        * @param bID data of branch_id
        * @var data used as storage of data before being encode as json
        * @return json_encode
    */
    public function getBranchName($bID){

        $query = $this->db->query("select name as branch from ".$this->tblb." where branch_id = $bID");
        return $query->getRowArray();

    }


    /**
        * @method getTicketInfo() use to get tenant ticket/concern details based on id
        * @param sID encrypted data of support_id
        * @var sid decrypted data of support_id
        * @var data used as storage of data before being encode as json
        * @return json_encode
    */
    public function getTicketInfo($sID){

        $sid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$sID));

        $query = $this->db->query("SELECT s.support_id, s.branch_id, s.subject, t.name AS tenant 
        FROM ".$this->tbls." s
        INNER JOIN ".$this->tblt." t ON s.tenant_id = t.tenant_id
        WHERE s.support_id = $sid ");
        $row = $query->getRowArray();

        $cb = "";
        foreach(json_decode($row['branch_id']) as $bi){
            $br = $this->getBranchName($bi);
            $cb .= ' '.$br['branch'].' ';
        }
        $data = [
            'support_id' => $row['support_id'],
            'concerned_branch' => $cb,
            'subject' => $row['subject'],
            'tenant' => $row['tenant']
        ];

        echo json_encode($data);

    }


    /**
        * @method getReply() use to get support reply information based on id
        * @param sID encrypted data of support_id
        * @var sid decrypted data of support_id
        * @var data used as storage of data before being encode as json
        * @return json_encode
    */
    public function getReply($sID){

        $sid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$sID));

        $query = $this->db->query("SELECT s.concern AS content, u.name AS added_by, s.added_on AS added_on 
        FROM ".$this->tbls." s
        INNER JOIN ".$this->tblu." u ON s.added_by = u.user_id
        WHERE s.support_id = $sid
        UNION ALL
        SELECT sr.reply AS content, u.name AS added_by, sr.added_on AS added_on 
        FROM ".$this->tblsr." sr
        INNER JOIN ".$this->tblu." u ON sr.added_by = u.user_id
        WHERE sr.support_id = $sid
        ORDER BY added_on DESC;");

        $arr = [];
        foreach($query->getResultArray() as $row){
            $data = [
                'content' => $row['content'],
                'added_by' => $row['added_by'],
                'added_on' => $row['added_on']
            ];
            array_push($arr, $data);
        }
        echo json_encode($arr);

    }

















    /**
        ----------------------------------------------------------
        Contract Module area
        ----------------------------------------------------------
        * @method getContractInfo() use to get Contract Information based on id
        * @param cID encrypted data of contract_id
        * @var cid decrypted data of contract_id
        * @var data used as storage of data before being encode as json
        * @return json_encode
    */
    public function getContractInfo($cID){

        $cid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$cID));

        $query = $this->db->query("SELECT c.contract_id, t.name AS tenant, c.tenant_id, c.branch_id ,b.name AS branch, c.slot_number, c.commission, c.monthly_rate, c.duration, c.start, c.end, c.status 
        FROM ".$this->tblct." c
        INNER JOIN ".$this->tblt." t ON c.tenant_id = t.tenant_id
        INNER JOIN ".$this->tblb." b ON c.branch_id = b.branch_id
        WHERE c.contract_id = $cid
        ORDER BY c.added_on DESC;");

        $row = $query->getRowArray();

        $data = [
            'contract_id' => $this->encrypter->encrypt($row['contract_id']),
            'tenant' => $row['tenant'],
            'tenant_id' => $this->encrypter->encrypt($row['tenant_id']),
            'branch' => $row['branch'],
            'branch_id' => $this->encrypter->encrypt($row['branch_id']),
            'slot_number' => $row['slot_number'],
            'commission' => $row['commission'],
            'monthly_rate' => $row['monthly_rate'],
            'mm' => date("m", strtotime($row['start'])),
            'dd' => date("d", strtotime($row['start'])),
            'yy' => date("Y", strtotime($row['start'])),
            'MM' =>  date("F", strtotime($row['start'])),
            'end' => $row['end'],
            'status' => $row['status']
        ];

        echo json_encode($data);

    

    }













   


}