<?php
namespace App\Models;
use CodeIgniter\Model;
class Rental_model extends  Model {
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
    protected $tblc = "tbl_category";
    protected $tblt = "tbl_tenant";
    protected $tblb = "tbl_branch";
    protected $tbls = "tbl_support";
    protected $tblsc = "tbl_support_category";
    protected $tblsr = "tbl_support_reply";
    protected $tblbk = "tbl_bank";
    protected $tblba = "tbl_bank_account";
    protected $tblct = "tbl_contract";
    protected $tblcp = "tbl_contract_payment";
    protected $tblcr = "tbl_contract_refund";
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
        Tenant Module area
        ---------------------------------------------------
        * @method viewTenants() use to get the tenant information based on status
        * @param state status information of tenants
        * @return tenant->as->multiple_result
    */
    public function viewTenants($state){

        $query = $this->db->query("select * from ".$this->tblt." where status='$state' ");
        return $query->getResultArray();

    }


    /**
        * @method viewTenants() use to count the branches based on id
        * @param tID contains tenant_id
        * @return branch->as->single_result
    */
    public function countBranches($tID){

        $query = $this->db->query("SELECT COUNT(ct.contract_id) AS count 
        FROM ".$this->tblct." ct 
        WHERE ct.tenant_id = $tID 
        AND '".$this->date."' BETWEEN ct.start 
        AND ct.end AND ct.status = 'completed'");

        return $query->getRowArray();

    }


    /**
        * @method getCategory() use to get the tenant category information
        * @return category->as->multiple_result
    */
    public function getCategory(){

        $query = $this->db->query("select * from ".$this->tblc." where status = 'active' order by name asc");
        return $query->getResultArray();

    }


    /**
        * @method saveTenant() use to register the tenant information
        * @var data data container of tenant information
        * @return sql_execution bool
    */
    public function saveTenant(){

        $data = [
            'category_id' => $this->encrypter->decrypt($this->request->getPost("category")),
            'name' => ucfirst($this->request->getPost("name")),
            'facebook' => $this->request->getPost("facebook"),
            'instagram' => $this->request->getPost("instagram"),
            'email_address' => $this->request->getPost("email-address"),
            'contact_person' => $this->request->getPost("contact-person"),
            'contact_number' => $this->request->getPost("contact-number"),
            'status' => 'active',
            'added_by' => $this->encrypter->decrypt($_SESSION['userID']),
            'added_on' => $this->date.' '.$this->time
        ];

        $this->db->table($this->tblt)->insert($data);

    }


    /**
        * @method updateTenant() use to update the tenant information based on id
        * @param tID encrypted data of tenant_id
        * @var tid decrypted data of tenant_id
        * @var data data container of tenant information
        * @return sql_execution bool
    */
    public function updateTenant($tID){

        $tid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$tID));

        $data = [
            'category_id' => $this->encrypter->decrypt($this->request->getPost("category")),
            'name' => ucfirst($this->request->getPost("name")),
            'facebook' => $this->request->getPost("facebook"),
            'instagram' => $this->request->getPost("instagram"),
            'email_address' => $this->request->getPost("email-address"),
            'contact_person' => $this->request->getPost("contact-person"),
            'contact_number' => $this->request->getPost("contact-number"),
            'status' => 'active',
            'updated_by' => $this->encrypter->decrypt($_SESSION['userID']),
            'updated_on' => $this->date.' '.$this->time
        ];

        $this->db->table($this->tblt)->where("tenant_id", $tid)->update($data);

    }


    /**
        * @method deactivateTenant() use to deactivate the tenant information based on id
        * @param tID encrypted data of tenant_id
        * @var tid decrypted data of tenant_id
        * @var data data container of tenant information
        * @return sql_execution bool
    */
    public function deactivateTenant($tID){

        $tid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$tID));
        $data = [
            'status' => 'inactive',
            'updated_by' => $this->encrypter->decrypt($_SESSION['userID']),
            'updated_on' => $this->date.' '.$this->time
        ];
        $this->db->table($this->tblt)->where("tenant_id", $tid)->update($data);

    }


    /**
        * @method activateTenant() use to activate the tenant information based on id
        * @param tID encrypted data of tenant_id
        * @var tid decrypted data of tenant_id
        * @var data data container of tenant information
        * @return sql_execution bool
    */
    public function activateTenant($tID){

        $tid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$tID));
        $data = [
            'status' => 'active',
            'updated_by' => $this->encrypter->decrypt($_SESSION['userID']),
            'updated_on' => $this->date.' '.$this->time
        ];
        $this->db->table($this->tblt)->where("tenant_id", $tid)->update($data);

    }





















    /**
        ---------------------------------------------------
        Ticket/Support Module area
        ---------------------------------------------------
        * @method viewTickets() use to get the ticket information based on status
        * @param state status information of ticket
        * @return ticket->as->multiple_result
    */
    public function viewTickets($state){

        if($state == 'all'){

            $query = $this->db->query("SELECT s.support_id, s.branch_id, sc.name AS category, t.name AS tenant, s.subject, IFNULL(COUNT(sr.support_id), 0) AS reply, s.status, u.name AS added_by, s.added_on 
            FROM ".$this->tbls." s
            INNER JOIN ".$this->tblsc." sc ON s.support_category_id = sc.support_category_id
            INNER JOIN ".$this->tblt." t ON s.tenant_id = t.tenant_id
            INNER JOIN ".$this->tblu." u ON s.added_by = u.user_id
            LEFT JOIN ".$this->tblsr." sr ON s.support_id = sr.support_id
            GROUP BY s.support_id, s.branch_id, sc.name, t.name, s.subject, s.status, u.name, s.added_on
            ORDER BY s.added_on DESC");
            return $query->getResultArray();
            
        }else{

            $query = $this->db->query("SELECT s.support_id, s.branch_id, sc.name AS category, t.name AS tenant, s.subject, IFNULL(COUNT(sr.support_id), 0) AS reply, s.status, u.name AS added_by, s.added_on 
            FROM ".$this->tbls." s
            INNER JOIN ".$this->tblsc." sc ON s.support_category_id = sc.support_category_id
            INNER JOIN ".$this->tblt." t ON s.tenant_id = t.tenant_id
            INNER JOIN ".$this->tblu." u ON s.added_by = u.user_id
            LEFT JOIN ".$this->tblsr." sr ON s.support_id = sr.support_id
            WHERE s.status = '$state'
            GROUP BY s.support_id, s.branch_id, sc.name, t.name, s.subject, s.status, u.name, s.added_on
            ORDER BY s.added_on DESC");
            return $query->getResultArray();

        }

       

    }


    /**
        * @method getBranchName() use to get the branch name based on id
        * @param bID contains brach_id
        * @return branch->as->single_result
    */
    public function getBranchName($bID){

        $query = $this->db->query("select name as branch from ".$this->tblb." where branch_id = $bID");
        return $query->getRowArray();

    }


    /**
        * @method getActiveTenants() use to get the active tenants information
        * @return tenant->as->multiple_result
    */
    public function getActiveTenants(){

        $query = $this->db->query("select * from ".$this->tblt."  ");
        return $query->getResultArray();

    }

    /**
        * @method getBranches() use to get the active branches information
        * @return branch->as->multiple_result
    */
    public function getBranches(){

        $query = $this->db->query("select * from ".$this->tblb."  where status = 'active' ");
        return $query->getResultArray();

    }


    /**
        * @method getSupportCategory() use to get the active support category information
        * @return support_category->as->multiple_result
    */
    public function getSupportCategory(){

        $query = $this->db->query("SELECT support_category_id, name, status 
        FROM ".$this->tblsc." 
        WHERE status = 'active' 
        ORDER BY name ASC");
        return $query->getResultArray();

    }


    /**
        * @method saveTicket() use to register the ticket information
        * @var array->branch contains branches information
        * @var data data container of ticket information
        * @return sql_execution bool
    */
    public function saveTicket(){

        $branch = [];
        foreach($_POST['branch'] as $val){
            array_push($branch, $this->encrypter->decrypt($val));
        }

        $data = [
            'branch_id' => json_encode($branch),
            'support_category_id' => $this->encrypter->decrypt($this->request->getPost("category")),
            'tenant_id' => $this->encrypter->decrypt($this->request->getPost("tenant")),
            'subject' => ucfirst($this->request->getPost("subject")),
            'concern' => $this->request->getPost("concern"),
            'status' => 'open',
            'added_by' => $this->encrypter->decrypt($_SESSION['userID']),
            'added_on' => $this->date.' '.$this->time
        ];

        $this->db->table($this->tbls)->insert($data);

    }


    /**
        * @method replyTicket() use to reply to the ticket based on id
        * @param sID encrypted data of support_id
        * @var sid decrypted data of support_id
        * @var data data container of ticket information
        * @return sql_execution bool
    */
    public function replyTicket($sID){

        $sid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$sID));

        $data = [
            'support_id' => $sid, 
            'reply' => '<p>'.$this->request->getPost("content").'</p>', 
            'added_by' => $this->encrypter->decrypt($_SESSION['userID']),
            'added_on' => $this->date.' '.$this->time
        ];

        $this->db->table($this->tblsr)->insert($data);
    }
    



















    /**
        ---------------------------------------------------
        Contract Module area
        ---------------------------------------------------
        * @method viewTickets() use to get the ticket information based on status
        * @param state status information of ticket
        * @return ticket->as->multiple_result
    */
    public function getBanks(){

        $query = $this->db->query("SELECT bank_id, name FROM ".$this->tblbk." ORDER BY name ASC");
        return $query->getResultArray();

    }


    /**
        * @method viewContracts() use to get contract information based on status
        * @param state status information of ticket
        * @return contract->as->multiple_result
    */
    public function viewContracts($state){

        $query = $this->db->query("SELECT c.contract_id, t.name AS tenant, b.name AS branch, c.slot_number, c.commission, c.monthly_rate, c.duration, c.start, c.end, c.status 
        FROM ".$this->tblct." c
        INNER JOIN ".$this->tblt." t ON c.tenant_id = t.tenant_id
        INNER JOIN ".$this->tblb." b ON c.branch_id = b.branch_id
        WHERE c.status = '$state'
        ORDER BY c.added_on DESC");

        return $query->getResultArray();

    }


    /**
        * @method getContracts() use to get contract information based on id
        * @param cID encrypted data of contract_id
        * @var cid decrypted data of contract_id
        * @return contract->as->single_result
    */
    public function getContracts($cID){

        $cid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$cID));
        
        $query = $this->db->query("SELECT c.contract_id, t.name AS tenant, b.name AS branch, c.slot_number, c.commission, c.monthly_rate, c.duration, c.start, c.end, c.status 
        FROM ".$this->tblct." c
        INNER JOIN ".$this->tblt." t ON c.tenant_id = t.tenant_id
        INNER JOIN ".$this->tblb." b ON c.branch_id = b.branch_id
        WHERE c.contract_id = $cid
        ORDER BY c.added_on DESC;");

        return $query->getRowArray();

    }


    /**
        * @method getSecurityDeposits() use to get security deposits information based on id
        * @param cID encrypted data of contract_id
        * @var cid decrypted data of contract_id
        * @return security_deposit->as->multiple_result
    */
    public function getSecurityDeposits($cID){

        $cid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$cID));

        $query = $this->db->query("SELECT cp.type, b.name AS bank, cp.check_number, cp.amount, cp.date, cp.status 
        FROM ".$this->tblcp." cp
        LEFT JOIN ".$this->tblbk." b ON cp.bank_id = b.bank_id
        WHERE cp.contract_id = $cid
        AND cp.identifier = 'security-deposit'");

        return $query->getResultArray();

    }


    /**
        * @method getMonthly() use to get monthly deposits information based on id
        * @param cID contains of contract_id
        * @param i contains of month identifier
        * @return monthly->as->multiple_result
    */
    public function getMonthly($cID,$i){

        $query = $this->db->query("SELECT cp.type, b.name AS bank, cp.check_number, cp.amount, cp.date, cp.status 
        FROM ".$this->tblcp." cp
        LEFT JOIN ".$this->tblbk." b ON cp.bank_id = b.bank_id
        WHERE cp.contract_id = $cID
        AND cp.identifier = 'month-".$i."' ");

        return $query->getResultArray();

    }


    /**
        * @method getRefunds() use to get refund information based on id
        * @param cID contains of contract_id
        * @var cid decrypted data of contract_id
        * @return refund->as->multiple_result
    */
    public function getRefunds($cID){

        $cid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$cID));

        $query = $this->db->query("SELECT cr.contract_refund_id, ba.account_number, ba.name AS bank_account, cr.check_number, cr.amount, cr.date, cr.status, cr.remarks 
        FROM ".$this->tblcr." cr
        INNER JOIN ".$this->tblba." ba ON cr.bank_account_id = ba.bank_account_id
        WHERE cr.contract_id = $cid ");

        return $query->getResultArray();

    }


    /**
        * @method saveContract() use to register the contract information
        * @var datestart contains date
        * @var dateend contains date
        * @var secdate contains security deposit date
        * @var array->cp on first loop data container for sercurity deposit information
        * @var array->cp on second loop data container for monthly deposit information
        * @var array->data data container of contract details
        * @var data data container of ticket information
        * @return sql_execution bool
    */
    public function saveContract(){

        $datestart = $this->request->getPost('yy').'-'.$this->request->getPost('mm').'-'.$this->request->getPost('dd');
        $dateend = date('Y-m-d', strtotime($datestart ." + " . $this->request->getPost("contract-duration") . " month -1 day"));
        
        $data = [
            'tenant_id' => $this->encrypter->decrypt($this->request->getPost("tenant")),
            'branch_id' => $this->encrypter->decrypt($this->request->getPost("branch")),
            'commission' => $this->request->getPost("commission"),
            'monthly_rate' => $this->request->getPost("monthly-rate"),
            'slot_number' => $this->request->getPost("slot-number"),
            'start' => $datestart,
            'added_by' => $this->encrypter->decrypt($_SESSION['userID']),
            'added_on' => $this->date.' '.$this->time
        ];

        $this->db->table($this->tblct)->insert($data);

        $id = $this->db->query("select contract_id from ".$this->tblct." order by contract_id desc limit 1")->getRowArray();

        // First Loop Security Deposit
        foreach($_POST['security-deposit']['type'] as $i => $val){

            $secdate = $_POST['security-deposit']['yy'][$i].'-'.$_POST['security-deposit']['mm'][$i].'-'.$_POST['security-deposit']['dd'][$i];

            if(in_array($_POST['security-deposit']['type'][$i], array("cash", "check", "bank-deposit", "online-bank-transfer"))){
                
                if(($_POST['security-deposit']['type'][$i] == 'cash' || $_POST['security-deposit']['type'][$i] == 'bank-deposit' || $_POST['security-deposit']['type'][$i] == 'online-bank-transfer') && $_POST['security-deposit']['amount'][$i] != '' && $secdate != ""){
                    
                    $cp = [
                        'contract_id' => $id['contract_id'],
                        'identifier' => 'security-deposit',
                        'type' => $_POST['security-deposit']['type'][$i],
                        'amount' => $_POST['security-deposit']['amount'][$i],
                        'date' => $secdate,
                        'status' => 'pending'
                    ];

                    $this->db->table($this->tblcp)->insert($cp);

                }else if($_POST['security-deposit']['type'][$i] == 'check' && $_POST['security-deposit']['bank'][$i] != '' && $_POST['security-deposit']['check-no'][$i] != "" && $_POST['security-deposit']['amount'][$i] != "" && $secdate != ""){

                    $cp = [
                        'contract_id' => $id['contract_id'],
                        'identifier' => 'security-deposit',
                        'type' => $_POST['security-deposit']['type'][$i],
                        'bank_id' => $this->encrypter->decrypt($_POST['security-deposit']['bank'][$i]),
                        'check_number' => $_POST['security-deposit']['check-no'][$i],
                        'amount' => $_POST['security-deposit']['amount'][$i],
                        'date' => $secdate,
                        'status' => 'pending'
                    ];

                    $this->db->table($this->tblcp)->insert($cp);

                }

            }

        }

        // Second Loop Monthly Deposit
        foreach($_POST['month'] as $x => $month){

            foreach($_POST['month'][$x]['type'] as $y => $val){

                $paydate = $_POST['month'][$x]['yy'][$y].'-'.$_POST['month'][$x]['mm'][$y].'-'.$_POST['month'][$x]['dd'][$y];

                if(in_array($_POST['month'][$x]['type'][$y], array("cash", "check", "bank-deposit", "online-bank-transfer"))){

                    if(($_POST['month'][$x]['type'][$y] == 'cash' || $_POST['month'][$x]['type'][$y] == 'bank-deposit' || $_POST['month'][$x]['type'][$y] == 'online-bank-transfer') && $_POST['month'][$x]['amount'][$y] != '' && $paydate != ''){

                        $cp = [
                            'contract_id' => $id['contract_id'],
                            'identifier' => 'month-'.$x,
                            'type' => $_POST['month'][$x]['type'][$y],
                            'amount' => $_POST['month'][$x]['amount'][$y],
                            'date' => $paydate,
                            'status' => 'pending'
                        ];
    
                        $this->db->table($this->tblcp)->insert($cp);


                    }else if($_POST['month'][$x]['type'][$y] == 'check' && $_POST['month'][$x]['bank'][$y] != '' && $_POST['month'][$x]['check-no'][$y] != '' && $_POST['month'][$x]['amount'][$y] != '' && $paydate != ''){

                        $cp = [
                            'contract_id' => $id['contract_id'],
                            'identifier' => 'month-'.$x,
                            'type' => $_POST['month'][$x]['type'][$y],
                            'bank_id' => $this->encrypter->decrypt($_POST['month'][$x]['bank'][$y]),
                            'check_number' => $_POST['month'][$x]['check-no'][$y],
                            'amount' => $_POST['month'][$x]['amount'][$y],
                            'date' => $paydate,
                            'status' => 'pending'
                        ];
    
                        $this->db->table($this->tblcp)->insert($cp);

                    }

                }

            }

        }

    }


    /**
        * @method udpateContract() use to update contract information based on id
        * @param cID contains of contract_id
        * @var cid decrypted data of contract_id
        * @var datestart contains date start of contract
        * @var array->data data container of contract information
        * @return sql_execution bool
    */
    public function udpateContract($cID){

        $cid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$cID));
        $datestart = $this->request->getPost('yy').'-'.$this->request->getPost('mm').'-'.$this->request->getPost('dd');
        
        $data = [
            'commission' => $this->request->getPost("commission"),
            'monthly_rate' => $this->request->getPost("monthly-rate"),
            'slot_number' => $this->request->getPost("slot-number"),
            'start' => $datestart,
            'updated_by' => $this->encrypter->decrypt($_SESSION['userID']),
            'updated_on' => $this->date.' '.$this->time
        ];

        $this->db->table($this->tblct)->where("contract_id",$cid)->update($data);

    }
   

    /**
        * @method addPaymentContract() use to add a payment information based on id
        * @param cID contains of contract_id
        * @var cid decrypted data of contract_id
        * @var date contains date posted
        * @var array->cp data container of contract payment information
        * @return sql_execution bool
    */
    public function addPaymentContract($cID){

        $cid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$cID));

        $date = $this->request->getPost('yy').'-'.$this->request->getPost('mm').'-'.$this->request->getPost('dd');

        if(in_array($this->request->getPost("type"), array("cash", "bank-deposit", "online-bank-transfer")) && $this->request->getPost("amount") != '' && $date != ''){

            $cp = [
                'contract_id' => $cid,
                'identifier' => $this->request->getPost("identifier"),
                'type' => $this->request->getPost("type"),
                'amount' =>  $this->request->getPost("amount"),
                'date' => $date,
                'status' => 'pending'
            ];
    
            $this->db->table($this->tblcp)->insert($cp);

        } else if($this->request->getPost("type") == 'check' && $_POST['bank'] != '' && $_POST['check-number'] != "" && $this->request->getPost("amount") != "" && $date != ""){
            $cp = [
                'contract_id' => $cid,
                'identifier' => $this->request->getPost("identifier"),
                'type' => $this->request->getPost("type"),
                'bank_id' => $this->encrypter->decrypt($this->request->getPost("bank")),
                'check_number' => $this->request->getPost("check-number"),
                'amount' =>  $this->request->getPost("amount"),
                'date' => $date,
                'status' => 'pending'
            ];

            $this->db->table($this->tblcp)->insert($cp);

        }
        

    }


    /**
        * @method addRefund() use to post a refund information based on id
        * @param cID contains of contract_id
        * @var cid decrypted data of contract_id
        * @var date contains date posted
        * @var array->data data container of refund information
        * @return sql_execution bool
    */
    public function addRefund($cID){

        $cid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$cID));

        $date = $this->request->getPost('yy').'-'.$this->request->getPost('mm').'-'.$this->request->getPost('dd');

        $data = [
            'contract_id' => $cid, 
            'bank_account_id' => $this->encrypter->decrypt($this->request->getPost("bank-account")),
            'check_number' => $this->request->getPost("check-number"), 
            'amount' => $this->request->getPost("amount"), 
            'date' => $date, 
            'remarks' => $this->request->getPost("remarks"),
            'status' => 'pending',
            'added_by' => $this->encrypter->decrypt($_SESSION['userID']),
            'added_on' => $this->date.' '.$this->time
        ];

        $this->db->table($this->tblcr)->insert($data);

    }


    /**
        * @method publishContract() use to publish contract information based on id
        * @param cID contains of contract_id
        * @var cid decrypted data of contract_id
        * @return sql_execution bool
    */
    public function publishContract($cID){

        $cid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$cID));
        $this->db->table($this->tblct)->where("contract_id", $cid)->update(['status' => 'completed']);

    }



































    /**
        ---------------------------------------------------
        Rent Module area
        ---------------------------------------------------
        * @method getRents() use to get the rent information based on @param
        * @param i contains month identifier
        * @param bID contains branch_id
        * @param month contains month
        * @return rents->as->multiple_result
    */
    public function getRents($i,$bID,$month){

        $query = $this->db->query("SELECT c.contract_id, t.name AS tenant, t.category_id, c.start, c.end, c.monthly_rate, c.duration 
        FROM ".$this->tblct." c
        INNER JOIN ".$this->tblt." t ON c.tenant_id = t.tenant_id
        WHERE c.slot_number = '".$i."' 
        AND c.branch_id = '".$bID."' 
        AND '".$month."' BETWEEN c.start AND c.end 
        AND c.status = 'completed'");

        return $query->getResultArray();

    }


    /**
        * @method getRentTotal() use to get the total rent information based on @param
        * @param curr contains month current month
        * @param cID contains contract_id
        * @return rents->as->single_result
    */
    public function getRentTotal($curr,$cID){

        $query = $this->db->query("SELECT cp.status, IFNULL(SUM(cp.amount), 0) AS total 
        FROM ".$this->tblcp." cp
        WHERE cp.identifier = '".$curr."' 
        AND cp.contract_id = '$cID' 
        AND cp.status IN ('clearing', 'completed')
        GROUP BY cp.status;");

        return $query->getRowArray();
    }



    








}