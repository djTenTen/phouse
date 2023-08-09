<?php
namespace App\Models;
use CodeIgniter\Model;
class Accounting_model extends  Model {
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
    protected $tblec = "tbl_expense_category";
    protected $tblba = "tbl_bank_account";
    protected $tblbk = "tbl_bank";
    protected $tble = "tbl_expense";
    protected $tblei = "tbl_expense_item";
    protected $tblep = "tbl_expense_payment";
    protected $tblft = "tbl_fund_transfer";
    protected $tblc = "tbl_contract";
    protected $tblcp = "tbl_contract_payment";
    protected $tblcl = "tbl_collection_list";
    protected $tblcli = "tbl_collection_list_item";
    protected $tblt = "tbl_tenant";
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
        Expense Module area
        ---------------------------------------------------
        * @method viewexpense() use to get the expense information based on status
        * @param stats status information of expense
        * @return expense->as->multiple_result
    */
    public function viewexpense($state){

        if($state == 'all') {
            $query = $this->db->query("SELECT e.expense_id,e.date,e.payee,COALESCE(i.total, 0) as total,e.status,u.name as added_by,e.added_on
            FROM ".$this->tble." e
            LEFT JOIN (
                SELECT expense_id, SUM(amount) as total
                FROM ".$this->tblei."
                GROUP BY expense_id
            ) i ON e.expense_id = i.expense_id
            INNER JOIN ".$this->tblu." u ON e.added_by = u.user_id
            ORDER BY e.added_on DESC");

            return $query->getResultArray();

        } else {
            $query = $this->db->query("SELECT e.expense_id,e.date,e.payee,COALESCE(i.total, 0) as total,e.status,u.name as added_by,e.added_on
            FROM ".$this->tble." e
            LEFT JOIN (
                SELECT expense_id, SUM(amount) as total
                FROM ".$this->tblei."
                GROUP BY expense_id
            ) i ON e.expense_id = i.expense_id
            INNER JOIN ".$this->tblu." u ON e.added_by = u.user_id
            WHERE e.status = '$state'
            ORDER BY e.added_on DESC");
            return $query->getResultArray();
        }

    }


    /**
        * @method getBranches() use to get the branch information
        * @return branches->as->multiple_result
    */
    public function getBranches(){

        $query = $this->db->query("select * 
        from ". $this->tblb." 
        where branch_id != 0
        and status = 'active'
        ");
        return $query->getResultArray();

    }


    /**
        * @method getExpenseCategory() use to get the expense category information
        * @return expense_category->as->multiple_result
    */
    public function getExpenseCategory(){

        $query = $this->db->query("select * 
        from ".$this->tblec."
        where status = 'active'");
        return $query->getResultArray();

    }


    /**
        * @method getExpenseCategory() use to get the expense category information
        * @return expense_category->as->multiple_result
    */
    public function getBankAccounts(){

        $query = $this->db->query("SELECT ba.bank_account_id, b.name AS bank, ba.name,ba.account_number
        FROM ".$this->tblba." ba
        INNER JOIN ".$this->tblbk ." b ON ba.bank_id = b.bank_id
        WHERE ba.status = 'active'
        ORDER BY bank ASC, ba.name ASC");
        return $query->getResultArray();

    }


    /**
        * @method getExpenseDetails() use to get the expense details information based on id
        * @param eID encrypted data of expense_id
        * @var eid decrypted data of expense_id
        * @return expense->as->single_result
    */
    public function getExpenseDetails($eID){

        $eid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$eID));

        $query = $this->db->query("SELECT e.expense_id, e.payee,e.receiver, e.date,e.status, u.name as added_by, e.added_on
        FROM ".$this->tble." e
        INNER JOIN ".$this->tblu." u ON e.added_by = u.user_id
        WHERE e.expense_id = $eid");

        return $query->getRowArray();

    }


    /**
        * @method getExpenseItems() use to get the expense item details information based on id
        * @param eID encrypted data of expense_id
        * @var eid decrypted data of expense_id
        * @return expense->as->multiple_result
    */
    public function getExpenseItems($eID){

        $eid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$eID));

        $query = $this->db->query("SELECT ei.branch_id, ei.expense_category_id, b.name as branch, ec.name as category, ei.description, ei.amount
            FROM ".$this->tblei." ei
            INNER JOIN ".$this->tblec." ec ON ei.expense_category_id = ec.expense_category_id
            INNER JOIN ".$this->tblb." b ON ei.branch_id = b.branch_id
            WHERE ei.expense_id = $eid");

       return $query->getResultArray();

    }


    /**
        * @method getExpensePayment() use to get the expense paymtent item details information based on id
        * @param eID encrypted data of expense_id
        * @var eid decrypted data of expense_id
        * @return expense->as->multiple_result
    */
    public function getExpensePayment($eID){

        $eid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$eID));

        $query = $this->db->query("SELECT ep.bank_account_id, b.name as bank,b.shortname as bankshortname, ba.name as bank_account, ep.type, ep.date, ep.check_number, ep.amount, ep.status, ep.date_posted
        FROM ".$this->tblep." ep
        INNER JOIN ".$this->tblba." ba ON ep.bank_account_id = ba.bank_account_id
        INNER JOIN ".$this->tblbk." b ON ba.bank_id = b.bank_id
        WHERE ep.expense_id = $eid");

        return $query->getResultArray();

    }


    /**
        * @method getbankname() use to get the bank name information based on id
        * @param bID data of bank_id
        * @return expense->as->single_result
    */
    public function getbankname($bID){

        $query = $this->db->query("SELECT ".$this->tblba.".bank_account_id, bank.name as bank, ".$this->tblba.".name, ".$this->tblba.".account_number 
        FROM ".$this->tblba."
        INNER JOIN (SELECT ".$this->tblbk.".bank_id, ".$this->tblbk.".name FROM ".$this->tblbk.") bank ON ".$this->tblba.".bank_id = bank.bank_id
        WHERE ".$this->tblba.".bank_account_id = $bID");

        return $query->getRowArray();
        
    }


    /**
        * @method saveExpense() use to register the expense information
        * @var expdate contains expiration date
        * @var expense data container of expense information
        * @var lastexp contains last expense_id
        * @var paydate data container of payment date
        * @var expensepayment data container of expense payment information
        * @return sql_execution bool
    */
    public function saveExpense(){

        $expdate = $this->request->getPost('yye').'-'.$this->request->getPost('mme').'-'.$this->request->getPost('dde');
        $expense = [
            'payee' => ucfirst($this->request->getPost("payee")),
            'receiver' => ucfirst($this->request->getPost("receiver")),
            'date' => $expdate,
            'status' => 'pending',
            'added_by' => $this->encrypter->decrypt($_SESSION['userID']),
            'added_on' => $this->date.' '.$this->time
        ];
        $this->db->table($this->tble)->insert($expense);

        $lastexp = $this->db->query("select expense_id from ".$this->tble." order by expense_id desc limit 1")->getRowArray();

        foreach($_POST['expense-branch'] as $i => $val){
            $expenseitem = [
                'expense_id' => $lastexp['expense_id'],
                'branch_id' => $this->encrypter->decrypt($_POST['expense-branch'][$i]),
                'expense_category_id' => $this->encrypter->decrypt($_POST['expense-category'][$i]),
                'description' => $_POST['expense-description'][$i],
                'amount' => $_POST['expense-amount'][$i]
            ];
            $this->db->table($this->tblei)->insert($expenseitem);
        }
       
        foreach ($_POST['payment-source'] as $i => $val) {

            $paydate = $_POST['yyp'][$i].'-'.$_POST['mmp'][$i].'-'.$_POST['ddp'][$i];
            $expensepayment = [
                'expense_id' => $lastexp['expense_id'],
                'bank_account_id' => $this->encrypter->decrypt($_POST['payment-source'][$i]),
                'type' => $_POST['payment-type'][$i],
                'date' => $paydate,
                'check_number' => $_POST['payment-check-no'][$i],
                'amount' => $_POST['payment-amount'][$i],
                'status' => 'pending'

            ];
            $this->db->table($this->tblep)->insert($expensepayment);
        }

    }


    /**
        * @method updateExpense() use to udpate the expense information based on id
        * @param eID encrypted data of expense_id
        * @var eid decrypted data of expense_id
        * @var expdate contains expiration date
        * @var expense data container of expense information
        * @var paydate data container of payment date
        * @var expensepayment data container of expense payment information
        * @return sql_execution bool
    */
    public function updateExpense($eID){

        $eid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$eID));

        $expdate = $this->request->getPost('yye').'-'.$this->request->getPost('mme').'-'.$this->request->getPost('dde');
        $expense = [
            'payee' => ucfirst($this->request->getPost("payee")),
            'receiver' => ucfirst($this->request->getPost("receiver")),
            'date' => $expdate,
            'status' => 'pending',
            'added_by' => $this->encrypter->decrypt($_SESSION['userID']),
            'added_on' => $this->date.' '.$this->time
        ];

        $this->db->table($this->tble)->where("expense_id", $eid)->update($expense);
        $this->db->table($this->tblei)->where("expense_id", $eid)->delete();

        foreach($_POST['expense-branch'] as $i => $val){
            $expenseitem = [
                'expense_id' => $eid,
                'branch_id' => $this->encrypter->decrypt($_POST['expense-branch'][$i]),
                'expense_category_id' => $this->encrypter->decrypt($_POST['expense-category'][$i]),
                'description' => $_POST['expense-description'][$i],
                'amount' => $_POST['expense-amount'][$i]
            ];
            $this->db->table($this->tblei)->insert($expenseitem);
        }

        $this->db->table($this->tblep)->where("expense_id", $eid)->delete();
       
        foreach ($_POST['payment-source'] as $i => $val) {

            $paydate = $_POST['yyp'][$i].'-'.$_POST['mmp'][$i].'-'.$_POST['ddp'][$i];

            $expensepayment = [
                'expense_id' => $eid,
                'bank_account_id' => $this->encrypter->decrypt($_POST['payment-source'][$i]),
                'type' => $_POST['payment-type'][$i],
                'date' => $paydate,
                'check_number' => $_POST['payment-check-no'][$i],
                'amount' => $_POST['payment-amount'][$i],
                'status' => 'pending'

            ];
            $this->db->table($this->tblep)->insert($expensepayment);
        }

    }


    /**
        * @method expensePending() use to udpate the expense into pending based on id
        * @param eID encrypted data of expense_id
        * @var eid decrypted data of expense_id
        * @var data data container of expense information
        * @return sql_execution bool
    */
    public function expensePending($eID){

        $eid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$eID));
        $data = [
            'status' => 'pending',
            'updated_by' => $this->encrypter->decrypt($_SESSION['userID']),
            'updated_on' => $this->date.' '.$this->time
        ];
        $this->db->table($this->tble)->where("expense_id", $eid)->update($data);

    }


    /**
        * @method expenseCompleted() use to udpate the expense into completed based on id
        * @param eID encrypted data of expense_id
        * @var eid decrypted data of expense_id
        * @var data data container of expense information
        * @return sql_execution bool
    */
    public function expenseCompleted($eID){

        $eid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$eID));
        $data = [
            'status' => 'completed',
            'updated_by' => $this->encrypter->decrypt($_SESSION['userID']),
            'updated_on' => $this->date.' '.$this->time
        ];
        $this->db->table($this->tble)->where("expense_id", $eid)->update($data);

    }

    
    /**
        * @method expenseCancelled() use to udpate the expense into cancelled based on id
        * @param eID encrypted data of expense_id
        * @var eid decrypted data of expense_id
        * @var data data container of expense information
        * @return sql_execution bool
    */
    public function expenseCancelled($eID){

        $eid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$eID));
        $data = [
            'status' => 'cancelled',
            'updated_by' => $this->encrypter->decrypt($_SESSION['userID']),
            'updated_on' => $this->date.' '.$this->time
        ];
        $eid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$eID));
        $this->db->table($this->tble)->where("expense_id", $eid)->update($data);

    }














    



    /**
        ---------------------------------------------------
        Fund Transfer Module area
        ---------------------------------------------------
        * @method viewFundTransfers() use to get the fund transfer information based on status
        * @param state status information of transfer fund
        * @return expense->as->multiple_result
    */
    public function viewFundTransfers($state){ 

        if($state == 'all'){
            $query = $this->db->query("SELECT ft.fund_transfer_id, ft.transfer_from, ft.transfer_to, ft.amount, ft.status, u.name as added_by, ft.added_on 
            FROM ".$this->tblft." ft 
            INNER JOIN ".$this->tblu." u ON ft.added_by = u.user_id
            ORDER BY ft.added_on DESC;");
            return $query->getResultArray();
        }else{
            $query = $this->db->query("SELECT ft.fund_transfer_id, ft.transfer_from, ft.transfer_to, ft.amount, ft.status, u.name as added_by, ft.added_on 
            FROM ".$this->tblft." ft
            INNER JOIN ".$this->tblu." u ON ft.added_by = u.user_id
            WHERE ft.status = '$state'
            ORDER BY ft.added_on DESC;");
            return $query->getResultArray();
        }

    }

    /**
        * @method getFundtransfer() use to get the fund transfer information based on id
        * @param ftID encrypted data of fund transfer id
        * @var ftid decrypted data of fund transfer id
        * @return expense->as->single_result
    */
    public function getFundtransfer($ftID){

        $ftid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$ftID));

        $query = $this->db->query("SELECT ft.fund_transfer_id,  ft.transfer_from, ft.transfer_to,  ft.purpose,  ft.check_number,  ft.amount,  ft.date,  ft.status, u.name AS added_by 
        FROM ".$this->tblft." AS ft
        LEFT JOIN ".$this->tblu." AS u ON ft.added_by = u.user_id
        WHERE  ft.fund_transfer_id = '$ftid';");

        return $query->getRowArray();

    }


    /**
        * @method saveFundTransfer() use to save the fund transfer information
        * @var date contains fund transfer date
        * @var data data container fund transfer information
        * @return sql_execution bool
    */
    public function saveFundTransfer(){

        $date = $this->request->getPost('yye').'-'.$this->request->getPost('mme').'-'.$this->request->getPost('dde');

        $data = [
            'transfer_from' => $this->encrypter->decrypt($this->request->getPost('transfer-from')),
            'transfer_to' => $this->encrypter->decrypt($this->request->getPost('transfer-to')),
            'purpose' => ucfirst($this->request->getPost('purpose')),
            'check_number' => $this->request->getPost('check-number'),
            'amount' => $this->request->getPost('amount'),
            'date' => $date,
            'status' => 'completed',
            'added_by' => $this->encrypter->decrypt($_SESSION['userID']),
            'added_on' => $this->date.' '.$this->time
        ];

        $this->db->table($this->tblft)->insert($data);

    }


    /**
        * @method fundtransferCancelled() use to udpate the fund transfer into cancelled based on id
        * @param ftID encrypted data of fund transfer id
        * @var ftid decrypted data of fund transfer id
        * @var data data container of fund transfer information
        * @return sql_execution bool
    */
    public function fundtransferCancelled($ftID){

        $ftid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$ftID));
        $data = [
            'status' => 'cancelled',
            'updated_by' => $this->encrypter->decrypt($_SESSION['userID']),
            'updated_on' => $this->date.' '.$this->time
        ];
        $this->db->table($this->tblft)->where("fund_transfer_id", $ftid)->update($data);

    }


    /**
        * @method fundtransferCompleted() use to udpate the fund transfer into completed based on id
        * @param ftID encrypted data of fund transfer id
        * @var ftid decrypted data of fund transfer id
        * @var data data container of fund transfer information
        * @return sql_execution bool
    */
    public function fundtransferCompleted($ftID){

        $ftid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$ftID));
        $data = [
            'status' => 'completed',
            'updated_by' => $this->encrypter->decrypt($_SESSION['userID']),
            'updated_on' => $this->date.' '.$this->time
        ];
        $this->db->table($this->tblft)->where("fund_transfer_id", $ftid)->update($data);

    }



















    /**
        ---------------------------------------------------
        Collection Module area
        ---------------------------------------------------
        * @method getPaymentContract() use to get the fund transfer information based on status
        * @param state status information of transfer fund
        * @return contract->as->multiple_result
    */
    public function getPaymentContract(){

        $query = $this->db->query("SELECT  cp.contract_payment_id, cp.contract_id, contract.tenant, contract.branch, contract.slot_number, cp.identifier, contract.start, cp.type, bank.name AS bank, cp.check_number, cp.amount, cp.date 
        FROM ".$this->tblcp." AS cp
        LEFT JOIN ".$this->tblbk." AS bank ON cp.bank_id = bank.bank_id
        INNER JOIN (SELECT  c.contract_id,  t.name AS tenant,  b.name AS branch, c.slot_number, c.status, c.start 
                    FROM ".$this->tblc." AS c INNER JOIN  ".$this->tblb." AS b ON c.branch_id = b.branch_id INNER JOIN ".$this->tblt." AS t ON c.tenant_id = t.tenant_id
                    WHERE c.status = 'completed') AS contract ON cp.contract_id = contract.contract_id
        WHERE cp.status IN ('pending', 'bounced') 
        AND cp.date <= DATE_ADD(CURRENT_DATE(), INTERVAL 3 DAY)
        ORDER BY contract.branch ASC, contract.tenant ASC;");

        return $query->getResultArray();

    }


    /**
        * @method viewCollection() use to get the collection information
        * @return collection->as->multiple_result
    */
    public function viewCollection(){

        $query = $this->db->query("SELECT cl.collection_list_id, ba.bank, ba.name AS bank_account, ba.account_number, cl.date, cli.count, cli.total, u.name AS added_by, cl.added_on
            FROM ".$this->tblcl." AS cl
            INNER JOIN (SELECT ba.bank_account_id, b.name AS bank, ba.name, ba.account_number FROM ".$this->tblba." AS ba INNER JOIN ( SELECT b.bank_id, b.name FROM ".$this->tblbk." AS b) AS b ON ba.bank_id = b.bank_id) AS ba ON cl.bank_account_id = ba.bank_account_id
            INNER JOIN (SELECT cli.collection_list_id, COUNT(cli.collection_list_id) AS count, SUM(cp.amount) AS total
            FROM ".$this->tblcli." AS cli INNER JOIN ( SELECT cp.contract_payment_id, cp.amount
            FROM ".$this->tblcp." AS cp) AS cp ON cli.contract_payment_id = cp.contract_payment_id
            GROUP BY cli.collection_list_id) AS cli ON cl.collection_list_id = cli.collection_list_id
            INNER JOIN (SELECT u.user_id, u.name FROM ".$this->tblu." AS u) AS u ON cl.added_by = u.user_id
            ORDER BY cl.added_on DESC");

        return $query->getResultArray();

    }


    /**
        * @method viewCollectionDetails() use to get the collection details information based on id
        * @param clID encrypted data of fund collection id
        * @var clid decrypted data of fund collection id
        * @return collection->as->single_result
    */
    public function viewCollectionDetails($clID){

        $clid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$clID));

        $query = $this->db->query("SELECT cl.collection_list_id, b.name as bank,ba.account_name AS bank_account_name, ba.name AS bank_account, ba.account_number, cl.date, COUNT(cli.collection_list_id) AS count, SUM(cp.amount) AS total, u.name AS added_by, cl.added_on 
        FROM ".$this->tblcl." AS cl 
        INNER JOIN ".$this->tblba." AS ba ON cl.bank_account_id = ba.bank_account_id
        INNER JOIN ".$this->tblbk." AS b ON ba.bank_id = b.bank_id
        INNER JOIN ".$this->tblcli." AS cli ON cl.collection_list_id = cli.collection_list_id
        INNER JOIN ".$this->tblcp." AS cp ON cli.contract_payment_id = cp.contract_payment_id
        INNER JOIN ".$this->tblu." AS u ON cl.added_by = u.user_id
        WHERE cl.collection_list_id = $clid
        GROUP BY cl.collection_list_id
        ORDER BY cl.added_on DESC");

        return $query->getRowArray();

    }


    /**
        * @method viewcollectionitems() use to get the collection item details information based on id
        * @param clID encrypted data of fund collection id
        * @var clid decrypted data of fund collection id
        * @return collection->as->multiple_result
    */
    public function viewcollectionitems($clID){

        $clid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$clID));

        $query = $this->db->query("SELECT cli.collection_list_item_id, c.contract_id, br.name AS branch, t.name AS tenant, c.start, c.slot_number,cp.date, cp.contract_payment_id, cp.identifier, b.name as bank, cp.check_number, cp.date AS payment_date, cp.type, cp.amount, cli.remarks, cli.status
        FROM ".$this->tblc." AS c
        INNER JOIN ".$this->tblcp." AS cp ON c.contract_id = cp.contract_id
        LEFT JOIN ".$this->tblbk." AS b ON cp.bank_id = b.bank_id
        INNER JOIN ".$this->tblcli." AS cli ON cp.contract_payment_id = cli.contract_payment_id
        INNER JOIN ".$this->tblb." AS br ON c.branch_id = br.branch_id
        INNER JOIN ".$this->tblt." AS t ON c.tenant_id = t.tenant_id
        WHERE cli.collection_list_id = $clid");

        return $query->getResultArray();
        
    }


    /**
        * @method getCheckAmount() use to get the check amount details information based on id
        * @param clID encrypted data of fund collection id
        * @var clid decrypted data of fund collection id
        * @return collection->as->multiple_result
    */
    public function getCheckAmount($clID){

        $clid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$clID));

        $query = $this->db->query("SELECT cli.collection_list_item_id, b.name AS bank_name, b.shortname AS bank_shortname, cp.check_number, cp.amount, cli.status
        FROM ".$this->tblcli." AS cli
        LEFT JOIN ".$this->tblcp." AS cp ON cli.contract_payment_id = cp.contract_payment_id
        LEFT JOIN ".$this->tblbk." AS b ON cp.bank_id = b.bank_id
        WHERE cli.collection_list_id = $clid AND cli.status IN ('pending', 'clearing')");

        return $query->getResultArray();

    }


    /**
        * @method saveCollection() use to register the collection information
        * @var date contains collection date
        * @var data data container of collection information
        * @var lastid contains the last id of collection
        * @var collist data container of collection item information
        * @return sql_execution bool
    */
    public function saveCollection(){

        $date = $this->request->getPost('yy').'-'.$this->request->getPost('mm').'-'.$this->request->getPost('dd');

        $data = [
            'bank_account_id' => $this->encrypter->decrypt($this->request->getPost("bank-account")),
            'date' => $date,
            'added_by' => $this->encrypter->decrypt($_SESSION['userID']),
            'added_on' => $this->date.' '.$this->time
        ];

        $this->db->table($this->tblcl)->insert($data);

        $lastid = $this->db->query("select collection_list_id from ".$this->tblcl." order by collection_list_id desc limit 1")->getRowArray();

        foreach($_POST['collection-list-item'] as $i => $val){
            
            $collist = [
                'collection_list_id' => $lastid['collection_list_id'],
                'contract_payment_id' => $this->encrypter->decrypt($_POST['collection-list-item'][$i]),
                'status' => 'clearing'
            ];

            $this->db->table($this->tblcli)->insert($collist);
            $this->db->table($this->tblcp)->where("contract_payment_id", $this->encrypter->decrypt($_POST['collection-list-item'][$i]))->update(['status' => 'clearing']);

        }

    }


    /**
        * @method updateCollection() use to udpate the collection status based on id
        * @param cliID encrypted data of collection_list_item_id
        * @var eid decrypted data of collection_list_item_id
        * @var data data container of expense information
        * @return sql_execution bool
    */
    public function updateCollection($cliID){

        $cliid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$cliID));

        $data = [
            'status' => $this->request->getPost("status"),
            'remarks' => ucfirst($this->request->getPost("remarks"))
        ];

        $this->db->table($this->tblcli)->where("collection_list_item_id", $cliid)->update($data);

    }
    

























    /**
        ---------------------------------------------------
        Accounts Payable Module area
        ---------------------------------------------------
        * @method viewAccountPayable() use to get the account payable information
        * @return contract->as->multiple_result
    */
    public function viewAccountPayable(){

        $query = $this->db->query("SELECT ep.expense_payment_id, ep.expense_id, e.payee, ba.bank_account_id, b.name as bank,ba.name, ba.account_number, ep.type, ep.date, ep.check_number, ep.amount
        FROM ".$this->tblep." AS ep
        INNER JOIN ".$this->tble." AS e ON ep.expense_id = e.expense_id
        INNER JOIN ".$this->tblba." AS ba ON ba.bank_account_id = ep.bank_account_id
        INNER JOIN ".$this->tblbk." AS b ON ba.bank_id = b.bank_id
        WHERE ep.status = 'pending'");

        return $query->getResultArray();

    }


    /**
        * @method viewPaymentsDue() use to get the due payments information
        * @return contract->as->multiple_result
    */
    public function viewPaymentsDue(){

        $query = $this->db->query("SELECT ba.bank_account_id, b.name AS bank, ba.name, ba.account_number
        FROM ".$this->tblba." AS ba
        INNER JOIN ".$this->tblbk." AS b ON ba.bank_id = b.bank_id");

        return $query->getResultArray();

    }


    /**
        * @method viewAccountReceivable() use to get account receivable information
        * @return contract->as->multiple_result
    */
    public function viewAccountReceivable(){

        $query = $this->db->query("SELECT cp.contract_id, c.tenant, c.branch, c.slot_number, cp.identifier, c.start, cp.type, bk.name AS bank, cp.check_number, cp.amount, cp.date 
            FROM ".$this->tblcp." cp
            LEFT JOIN ".$this->tblbk." bk ON cp.bank_id = bk.bank_id
            INNER JOIN (SELECT c.contract_id, t.name AS tenant, b.name AS branch, c.slot_number, c.status, c.start 
                        FROM ".$this->tblc." c
                        INNER JOIN ".$this->tblb." b ON c.branch_id = b.branch_id
                        INNER JOIN ".$this->tblt." t ON c.tenant_id = t.tenant_id
                        WHERE c.status = 'completed') c ON cp.contract_id = c.contract_id
            WHERE cp.status IN ('pending', 'bounced') 
            AND cp.date <= '2023-07-27' 
            ORDER BY c.branch ASC, c.tenant ASC");

        return $query->getResultArray();

    }

    /**
        * @method updateCollection() use to udpate the collection status based on id
        * @param epID encrypted data of expense_payment_id
        * @var epid decrypted data of expense_payment_id
        * @var date data container of account payable information
        * @return sql_execution
    */
    public function updateAccountPayable($epID){

        $date = $this->request->getPost('yy').'-'.$this->request->getPost('mm').'-'.$this->request->getPost('dd');
        $epid = $this->encrypter->decrypt(str_ireplace(['~','$'],['/','+'],$epID));

        $data = [
            'status' => 'completed',
            'date_posted' => $date
        ];

        $this->db->table($this->tblep)->where("expense_payment_id", $epid)->update($data);

    }


}