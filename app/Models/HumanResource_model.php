<?php
namespace App\Models;
use CodeIgniter\Model;
class HumanResource_model extends  Model {
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
    protected $tblb = "tbl_branch";
    protected $tble = "tbl_employee";
    protected $tblp = "tbl_payroll";
    protected $tblpi = "tbl_payroll_item";
    protected $tblu = "tbl_user";


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
        Employee Module area
        ---------------------------------------------------
        * @method viewEmployees() use to get the employee information based on status
        * @param state status information of employee
        * @return employee->as->multiple_result
    */
    public function viewEmployees($state){ 

        $query = $this->db->query("SELECT employee_id, first_name, middle_name, last_name, address, gender, birthday, contact_number, salary, salary_type, date_hired, b.name AS branch, 
        metrobank_account_number, tin_number, sss_number, philhealth_number, pagibig_number, barangay_clearance, nbi_clearance, emergency_contact_name, emergency_contact_number, emergency_contact_relation 
        FROM ".$this->tble." e
        LEFT JOIN ".$this->tblb." b ON e.assigned_branch = b.branch_id
        WHERE e.status = '$state' AND employee_id NOT IN (1, 2, 3, 4, 5) 
        ORDER BY first_name ASC");

        return $query->getResultArray();

    }


    /**
        * @method getBranch() use to get the branch information
        * @return employee->as->multiple_result
    */
    public function getBranch(){

        $query = $this->db->query("select * from ".$this->tblb." where status = 'active' ");
        return $query->getResultArray();

    }


    /**
        * @method saveEmployee() use to register the employee information
        * @var data data container of employee information
        * @return sql_execution bool
    */
    public function saveEmployee(){

        $data = [
            'first_name' => ucfirst($this->request->getPost("first-name")),
            'middle_name' => ucfirst($this->request->getPost("middle-name")),
            'last_name' => ucfirst($this->request->getPost("last-name")),
            'address' => ucfirst($this->request->getPost("address")),
            'gender' => $this->request->getPost("gender"),
            'birthday' => $this->request->getPost("birthday"),
            'contact_number' => $this->request->getPost("contact-number"),
            'salary' => $this->request->getPost("salary"),
            'salary_type' => $this->request->getPost("salary-type"),
            'has_payslip' => $this->request->getPost("has-payslip"),
            'date_hired' => $this->request->getPost("date-hired"),
            'position' => $this->request->getPost("position"),
            'assigned_branch' => $this->encrypter->decrypt($this->request->getPost("branch")),
            'metrobank_account_number' => $this->request->getPost("metrobank-account-number"),
            'tin_number' => $this->request->getPost("tin-number"),
            'sss_number' => $this->request->getPost("sss-number"),
            'philhealth_number' => $this->request->getPost("philhealth-number"),
            'pagibig_number' => $this->request->getPost("pagibig-number"),
            'barangay_clearance' => $this->request->getPost("barangay-clearance"),
            'nbi_clearance' => $this->request->getPost("nbi-clearance"),
            'emergency_contact_name' => $this->request->getPost("emergency-contact-person"),
            'emergency_contact_number' => $this->request->getPost("emergency-contact-number"),
            'emergency_contact_relation' => $this->request->getPost("emergency-contact-relation"),
            'status' => 'active',
            'added_by' => $this->encrypter->decrypt($_SESSION['userID']),
            'added_on' => $this->date.' '.$this->time
        ];

        $this->db->table($this->tble)->insert($data);

    }


    /**
        * @method updateEmployee() use to udpate the employee information based on id
        * @param eID encrypted data of employee_id
        * @var eid decrypted data of employee_id
        * @var data data container of employee information
        * @return sql_execution bool
    */
    public function updateEmployee($eID){

        $eid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$eID));

        $data = [
            'first_name' => ucfirst($this->request->getPost("first-name")),
            'middle_name' => ucfirst($this->request->getPost("middle-name")),
            'last_name' => ucfirst($this->request->getPost("last-name")),
            'address' => ucfirst($this->request->getPost("address")),
            'gender' => $this->request->getPost("gender"),
            'birthday' => $this->request->getPost("birthday"),
            'contact_number' => $this->request->getPost("contact-number"),
            'salary' => $this->request->getPost("salary"),
            'salary_type' => $this->request->getPost("salary-type"),
            'has_payslip' => $this->request->getPost("has-payslip"),
            'date_hired' => $this->request->getPost("date-hired"),
            'position' => $this->request->getPost("position"),
            'assigned_branch' => $this->encrypter->decrypt($this->request->getPost("branch")),
            'metrobank_account_number' => $this->request->getPost("metrobank-account-number"),
            'tin_number' => $this->request->getPost("tin-number"),
            'sss_number' => $this->request->getPost("sss-number"),
            'philhealth_number' => $this->request->getPost("philhealth-number"),
            'pagibig_number' => $this->request->getPost("pagibig-number"),
            'barangay_clearance' => $this->request->getPost("barangay-clearance"),
            'nbi_clearance' => $this->request->getPost("nbi-clearance"),
            'emergency_contact_name' => $this->request->getPost("emergency-contact-person"),
            'emergency_contact_number' => $this->request->getPost("emergency-contact-number"),
            'emergency_contact_relation' => $this->request->getPost("emergency-contact-relation"),
            'status' => 'active',
            'updated_by' => $this->encrypter->decrypt($_SESSION['userID']),
            'updated_on' => $this->date.' '.$this->time
        ];

        $this->db->table($this->tble)->where("employee_id", $eid)->update($data);

    }


    /**
        * @method activateEmployee() use to activate employee information based on id
        * @param eID encrypted data of employee_id
        * @var eid decrypted data of employee_id
        * @return sql_execution bool
    */
    public function activateEmployee($eID){

        $eid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$eID));
        $this->db->table($this->tble)->where("employee_id", $eid)->update(['status' => 'active']);

    }


    /**
        * @method deactivateEmployee() use to deactivate employee information based on id
        * @param eID encrypted data of employee_id
        * @var eid decrypted data of employee_id
        * @return sql_execution bool
    */
    public function deactivateEmployee($eID){

        $eid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$eID));
        $this->db->table($this->tble)->where("employee_id", $eid)->update(['status' => 'active']);

    }























    /**
        ---------------------------------------------------
        Payroll Module area
        ---------------------------------------------------
        * @method getcutoff() use to compute or determine the cutoff for the computation of salary
        * @param state status information of employee
        * @return cutoff->as->array
    */
    public function getcutoff(){

        $offset = "";

        $start_log = date('Y-m-d', strtotime("November 1, 2020"));
        $ctrl = date('Y-m-t', strtotime($start_log . ' +1 day'));
        $i = 0;

        $cutoff = array();
        while($ctrl <= date('Y-m-t')){

            $first = date('M. j, Y', strtotime(date('F 1, Y', strtotime($ctrl)) .  $offset));
            $second = date('M. j, Y', strtotime(date('F 15, Y', strtotime($ctrl)) .  $offset));
            $third = date('M. j, Y', strtotime(date('F 16, Y', strtotime($ctrl)) .  $offset));
            $forth = date('M. j, Y', strtotime(date('F t, Y', strtotime($ctrl)) .  $offset));
            
            if(strtotime($second) >= strtotime($start_log)){
                array_push($cutoff, $first . ' - ' . $second);
            }
            if(strtotime('today') >= strtotime($third)){
                array_push($cutoff, $third . ' - ' . $forth);
            }
            $ctrl = date('Y-m-t', strtotime($ctrl . ' +1 day'));

        }

        $i = count($cutoff);

        $arr = [];
        for ($x = 0; $x < count($cutoff); $x++) {

            $i--;
            $date = explode (" - ", $cutoff[$i]);
            $coverage = date(str_replace('-', '/', 'Y-m-d'), strtotime($date[0])).' - '.date(str_replace('-', '/', 'Y-m-d'), strtotime($date[1]));

            $query = $this->db->query("SELECT payroll_id FROM ".$this->tblp." WHERE coverage = '$coverage'");

            if($query->getNumRows() == 0){

                $data = [
                    'cover' => $coverage,
                    'cutoff' => $cutoff[$i]
                ];
                array_push($arr, $data);
            }
        
        }

        return $arr;

    }


    /**
        * @method getEmployee() use to get the active employee information
        * @return employee->as->multiple_result
    */
    public function getEmployee(){

        $query = $this->db->query("SELECT employee_id, first_name, middle_name, last_name, b.name AS branch 
            FROM ".$this->tble." te
            LEFT JOIN ".$this->tblb." b ON te.assigned_branch = b.branch_id
            WHERE te.status = 'active' 
            AND te.employee_id NOT IN (1, 2, 3, 4, 5) 
            ORDER BY b.branch_id ASC, first_name ASC");

        return $query->getResultArray();

    }

    
    /**
        * @method viewPayroll() use to get the payroll information
        * @return payroll->as->multiple_result
    */
    public function viewPayroll(){

        $query = $this->db->query("SELECT p.payroll_id, p.coverage, pi.earned_salary, pi.sss, pi.philhealth, pi.pagibig, pi.deduction, u.name AS added_by, p.added_on 
            FROM ".$this->tblp." p
            INNER JOIN (SELECT payroll_id, SUM(earned_salary) AS earned_salary, SUM(sss) AS sss, SUM(philhealth) AS philhealth, SUM(pagibig) AS pagibig, SUM(deduction) AS deduction 
            FROM ".$this->tblpi." 
            GROUP BY payroll_id ) pi ON p.payroll_id = pi.payroll_id 
            INNER JOIN ".$this->tblu." u ON p.added_by = u.user_id
            ORDER BY p.added_on DESC");

        return $query->getResultArray();

    }


    /**
        * @method savePayroll() use to register the payroll information
        * @var payroll data container of payroll information
        * @var lastid contains last payroll_id
        * @var pitem data container of payroll items
        * @return sql_execution bool
    */
    public function savePayroll(){

        $payroll = [
            'coverage' => $this->request->getPost("cutoff"),
            'added_by' => $this->encrypter->decrypt($_SESSION['userID']),
            'added_on' => $this->date.' '.$this->time
        ];

        $this->db->table($this->tblp)->insert($payroll);
        
        $lastid = $this->db->query("select payroll_id from ".$this->tblp." order by payroll_id desc limit 1")->getRowArray();

        foreach($_POST['employee'] as $i => $val){

            $pitem = [
                'payroll_id' => $lastid['payroll_id'],
                'employee_id' => $this->encrypter->decrypt($_POST['employee'][$i]),
                'earned_salary' => $_POST['earned-salary'][$i],
                'sss' => $_POST['sss'][$i],
                'philhealth' => $_POST['philhealth'][$i],
                'pagibig' => $_POST['pagibig'][$i],
                'deduction' => $_POST['deduction'][$i],
                'remark' => $_POST['remark'][$i]
            ];

            $this->db->table($this->tblpi)->insert($pitem);
    
        }
        
    }

















}
