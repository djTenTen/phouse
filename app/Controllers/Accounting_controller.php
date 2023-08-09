<?php 
namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;
use TCPDF; // PDF Library
class Accounting_controller extends BaseController{
    /** 
        All Display function has user validation of authentication
        if the user rejected/ejected it will return on the login page.
        It checks if user has session and authenticated
    */

    
    /**
        Properties being used on this file
        * @property acc_model to load the accounting model
        * @property request for the post function method
        * @property encrypter use for encryption
    */
    protected $acc_model;
    protected $request;
    protected $encrypter;
    

    /**
        * @method __construct()is being executed automatically when this file is loaded
        * load all the methods/object to the properties of this class
    */
    public function __construct(){

        \Config\Services::session();
        $this->acc_model = new \App\Models\Accounting_model;
        $this->request = \Config\Services::Request();
        $this->encrypter = \Config\Services::encrypter(); 
        helper(['form', 'url']);

    }


    /**
        ----------------------------------------------------------
        Expense Module Area
        ----------------------------------------------------------

        * @method expense() use to display the expense management page
        * @param state contains data submitted which sales should be displayed
        * @var data->sales contains the sales information
        * @var data->expenses contains the expense information
        * being displayed on the sales page
        * @var page is the name of the php file
        * @var data->title displays the title on the Tab browser
    */
    public function expense($state){

        $page = 'expense';
        $data['title'] = 'Expenses Management';
        
        $data['state'] = ucfirst($state);

        $data['expenses'] = $this->acc_model->viewexpense($state);
        
        echo view('includes/header', $data);
        echo view('accounting/'.$page, $data);
        echo view('includes/footer');

    }


    /**
        * @method addExpense() use to display the adding of expense page
        * @var data->braches contains the branches information
        * @var data->expensecategory contains the expense category information
        * @var data->bankaccount contains the bank account information
        * being displayed on the sales page
        * @var page is the name of the php file
        * @var data->title displays the title on the Tab browser
    */
    public function addExpense(){

        $page = 'addexpense';
        $data['title'] = 'Add Expense';
        
        $data['braches'] = $this->acc_model->getBranches();
        $data['expensecategory'] = $this->acc_model->getExpenseCategory();
        $data['bankaccount'] = $this->acc_model->getBankAccounts();
        
        
        echo view('includes/header', $data);
        echo view('accounting/'.$page, $data);
        echo view('includes/footer');

    }


    /**
        * @method editExpense() use to display the edit of expense page
        * @param eID encrypted data of expense_id
        * @var data->eID passing data from @param eID
        * @var data->braches contains the branches information
        * @var data->expensecategory contains the expense category information
        * @var data->bankaccount contains the bank account information
        * @var data->expdetails contains expense details information based on expense_id
        * @var data->edate chopping date result from @var expdetails
        * @var data->expitems contains expense item details information based on expense_id
        * @var data->exppayment contains expense payment information based on expense_id
        * being displayed on the sales page
        * @var page is the name of the php file
        * @var data->title displays the title on the Tab browser
    */
    public function editExpense($eID){

        $page = 'editexpense';
        $data['title'] = 'Edit Expense';
        
        $data['eID'] = $eID;

        $data['braches'] = $this->acc_model->getBranches();
        $data['expensecategory'] = $this->acc_model->getExpenseCategory();
        $data['bankaccount'] = $this->acc_model->getBankAccounts();
        
        $data['expdetails'] = $this->acc_model->getExpenseDetails($eID);
        $data['edate'] = explode('-', $data['expdetails']['date']);

        $data['expitems'] = $this->acc_model->getExpenseItems($eID);
        $data['exppayment'] = $this->acc_model->getExpensePayment($eID);

        echo view('includes/header', $data);
        echo view('accounting/'.$page, $data);
        echo view('includes/footer');

    }


    /**
        * @method printExpenseVoucher() use to print the expense voucher based on expense_id
        * @param eID encrypted data of expense_id
        * @var data->expdetails contains expense details information based on expense_id
        * @var data->edate chopping date result from @var expdetails
        * @var data->expitems contains expense item details information based on expense_id
        * @var data->exppayment contains expense payment information based on expense_id
        * being displayed on the sales page
        * @var page is the name of the php file
    */
    public function printExpenseVoucher($eID){

        $data['expdetails'] = $this->acc_model->getExpenseDetails($eID);

        $date = date_create($data['expdetails']['date']);
        $data['edate'] = date_format($date,"F d, Y");;

        $data['expitems'] = $this->acc_model->getExpenseItems($eID);
        $data['exppayment'] = $this->acc_model->getExpensePayment($eID);

        $page = 'printexpensevoucher';
        view('accounting/'.$page, $data);

    }


    /**
        * @method saveExpense() is use to route the registration of expense to the model
        * @var session->expense_added the msg display on the Interface
        * @return to->expense_add page
    */
    public function saveExpense(){

        $this->acc_model->saveExpense();
        $_SESSION['expense_added'] = 'expense_added';
        return redirect()->to(site_url('expense/add'));

    }


    /**
        * @method updateExpense() is use to route the update of expense to the model
        * @param eID encypted data of expense_id
        * @var session->expense_updated the msg display on the Interface
        * @return to->expense_view_all
    */
    public function updateExpense($eID){

        $this->acc_model->updateExpense($eID);
        $_SESSION['expense_updated'] = 'expense_updated';
        return redirect()->to(site_url('expense/view/all'));

    }


    /**
        * @method expensePending() is use to route the update of expense to the model
        * @param eID encypted data of expense_id
        * @var session->expense_pending the msg display on the Interface
        * @return to->expense_view_pending
    */
    public function expensePending($eID){

        $this->acc_model->expensePending($eID);
        $_SESSION['expense_pending'] = 'expense_pending';
        return redirect()->to(site_url('expense/view/pending'));

    }


    /**
        * @method expenseCompleted() is use to route the update of expense to the model
        * @param eID encypted data of expense_id
        * @var session->expense_compeleted the msg display on the Interface
        * @return to->expense_view_completed
    */
    public function expenseCompleted($eID){

        $this->acc_model->expenseCompleted($eID);
        $_SESSION['expense_compeleted'] = 'expense_compeleted';
        return redirect()->to(site_url('expense/view/completed'));

    }


    /**
        * @method expenseCompleted() is use to route the update of expense to the model
        * @param eID encypted data of expense_id
        * @var session->expenseCancelled the msg display on the Interface
        * @return to->expense_view_cancelled
    */
    public function expenseCancelled($eID){

        $this->acc_model->expenseCancelled($eID);
        $_SESSION['expense_cancelled'] = 'expense_cancelled';
        return redirect()->to(site_url('expense/view/cancelled'));

    }














    /**
        ----------------------------------------------------------
        Fund Transfer Module Area
        ----------------------------------------------------------

        * @method addFundTransfer() use to display the fund transfer adding page
        * @var data->braches contains the branches information
        * @var data->expensecategory contains the expense category information
        * @var data->bankaccount contains the bank account information
        * being displayed on the sales page
        * @var page is the name of the php file
        * @var data->title displays the title on the Tab browser
    */
    public function addFundTransfer(){

        $page = 'addfundtransfer';
        $data['title'] = 'Add Fund Transfer';
        
        $data['braches'] = $this->acc_model->getBranches();
        $data['expensecategory'] = $this->acc_model->getExpenseCategory();
        $data['bankaccount'] = $this->acc_model->getBankAccounts();
        
        echo view('includes/header', $data);
        echo view('accounting/'.$page, $data);
        echo view('includes/footer');

    }


    /**
        * @method fundTransfer() use to display the fund transfer management page
        * @param state contains data submitted which sales should be displayed
        * @var data->state contains data submitted which sales should be displayed
        * @var data->fundtransfer contains the fund transfer information based on @param state
        * being displayed on the sales page
        * @var page is the name of the php file
        * @var data->title displays the title on the Tab browser
    */
    public function fundTransfer($state){

        $page = 'fundtransfer';
        $data['title'] = 'Fund Transfer';

        $data['state'] = ucfirst($state);
        $data['fundtransfer'] = $this->acc_model->viewFundTransfers($state);

        echo view('includes/header', $data);
        echo view('accounting/'.$page, $data);
        echo view('includes/footer');

    }


    /**
        * @method printFundTransfer() use to print the fund transfer based on fund_transfer_id
        * @param ftID encrypted data of fund_transfer_id
        * @var data->ftdetails contains fund transfer information depends on the fund_transfer_id
        * @var data->from contains bank name from
        * @var data->to contains bank name to
        * being displayed on the sales page
        * @var page is the name of the php file
    */
    public function printFundTransfer($ftID){

        $page = 'printfundtransfer';

        $data['ftdetails'] = $this->acc_model->getFundtransfer($ftID);
        $data['from'] = $this->acc_model->getbankname($data['ftdetails']['transfer_from']);
        $data['to'] = $this->acc_model->getbankname($data['ftdetails']['transfer_to']);

        view('accounting/'.$page, $data);

    }


    /**
        * @method saveFundTransfer() is use to route the registration of fund transfer to the model
        * @var session->fundtransfer_added the msg display on the Interface
        * @return to->fundtransfer_add
    */
    public function saveFundTransfer(){

        $this->acc_model->saveFundTransfer();
        $_SESSION['fundtransfer_added'] = 'fundtransfer_added';
        return redirect()->to(site_url('fundtransfer/add'));

    }


    /**
        * @method fundtransferCancelled() is use to route the update of fund transfer to the model
        * @param ftID encypted data of fund_transfer_id
        * @var session->fundtransfer_cancelled the msg display on the Interface
        * @return to->fundtransfer_view_cancelled
    */
    public function fundtransferCanceslled($ftID){

        $this->acc_model->fundtransferCancelled($ftID);
        $_SESSION['fundtransfer_cancelled'] = 'fundtransfer_cancelled';
        return redirect()->to(site_url('fundtransfer/view/cancelled'));

    }


    /**
        * @method fundtransferCompleted() is use to route the update of fund transfer to the model
        * @param ftID encypted data of fund_transfer_id
        * @var session->fundtransfer_completed the msg display on the Interface
        * @return to->fundtransfer_view_completed
    */
    public function fundtransferCompleted($ftID){

        $this->acc_model->fundtransferCompleted($ftID);
        $_SESSION['fundtransfer_completed'] = 'fundtransfer_completed';
        return redirect()->to(site_url('fundtransfer/view/completed'));

    }
    

    
    


   

    













    /**
        ----------------------------------------------------------
        Collection Module Area
        ----------------------------------------------------------

        * @method addCollection() use to display the collection adding page
        * @var data->bankaccount contains the bank account information
        * @var data->paymentcontracts contains contracts information
        * being displayed on the sales page
        * @var page is the name of the php file
        * @var data->title displays the title on the Tab browser
    */
    public function addCollection(){

        $page = 'addcollection';
        $data['title'] = 'Add Collection';

        $data['bankaccount'] = $this->acc_model->getBankAccounts();
        $data['paymentcontracts'] = $this->acc_model->getPaymentContract();
        
        echo view('includes/header', $data);
        echo view('accounting/'.$page, $data);
        echo view('includes/footer');

    }


    /**
        * @method collection() use to display the collection management page
        * @param state contains data submitted which sales should be displayed
        * @var data->collection contains collection information
        * being displayed on the sales page
        * @var page is the name of the php file
        * @var data->title displays the title on the Tab browser
    */
    public function collection($state){

        $page = 'collection';
        $data['title'] = 'Collection Management';

        $data['collection'] = $this->acc_model->viewCollection();        

        echo view('includes/header', $data);
        echo view('accounting/'.$page, $data);
        echo view('includes/footer');

    }
    

    /**
        * @method viewCollection() use to display the get the collection page
        * @param clID contains encrypted data of collection_id
        * @var data->clID passing data from @param clID
        * @var data->colldetails contains collection details information based on collection_id
        * @var data->collitems contains collection items details information based on collection_id
        * being displayed on the sales page
        * @var page is the name of the php file
        * @var data->title displays the title on the Tab browser
    */
    public function viewCollection($clID){

        $page = 'viewcollection';
        $data['title'] = 'Collection Management';

        $data['clID'] = $clID;
        $data['colldetails'] = $this->acc_model->viewCollectionDetails($clID);        
        $data['collitems'] = $this->acc_model->viewcollectionitems($clID);   
        
        echo view('includes/header', $data);
        echo view('accounting/'.$page, $data);
        echo view('includes/footer');

    }


    /**
        * @method printCollection() use to print the collection page
        * @param clID contains encrypted data of collection_id
        * @var data->checks contains check amount information based on collection_id
        * @var data->colldet contains collection details information based on collection_id
        * being displayed on the sales page
        * @var page is the name of the php file
        * @var data->title displays the title on the Tab browser
    */
    public function printCollection($clID){

        $page = 'printcollection';

        $data['checks'] = $this->acc_model->getCheckAmount($clID); 
        $data['colldet'] = $this->acc_model-> viewCollectionDetails($clID); 
       
        view('accounting/'.$page, $data);

    }
    

    /**
        * @method saveCollection() is use to route the registration of collection to the model
        * @var session->collection_added the msg display on the Interface
        * @return to->collection_add
    */
    public function saveCollection(){

        $this->acc_model->saveCollection();
        $_SESSION['collection_added'] = 'collection_added';
        return redirect()->to(site_url('collection/add'));

    }
    
    
    /**
        * @method updateCollection() is use to route the update of collection to the model
        * @param clID contains encrypted data of collection_id
        * @param cliID contains encrypted data of collection_item_id
        * @var session->collection_updated the msg display on the Interface
        * @return to->collection_viewcollection_clID
    */
    public function updateCollection($cliID,$clID){

        $this->acc_model->updateCollection($cliID);
        $_SESSION['collection_updated'] = 'collection_updated';
        return redirect()->to(site_url('collection/viewcollection/'.$clID));

    }



    




 












    /**
        ----------------------------------------------------------
        Collection Module Area
        ----------------------------------------------------------

        * @method accountsPayable() use to display the accounts payable management page
        * @var data->payable contains the payable information
        * @var data->paymentdues contains payment dues information
        * being displayed on the sales page
        * @var page is the name of the php file
        * @var data->title displays the title on the Tab browser
    */
    public function accountsPayable(){

        $page = 'accountspayable';
        $data['title'] = 'Accounts Payable';

        $data['payable'] = $this->acc_model->viewAccountPayable();
        $data['paymentdues'] = $this->acc_model->viewPaymentsDue();
        
        echo view('includes/header', $data);
        echo view('accounting/'.$page, $data);
        echo view('includes/footer');

    }


    /**
        * @method accountsReceivable() use to display the accounts receivable management page
        * @var data->receivable contains accounts receivable information
        * being displayed on the sales page
        * @var page is the name of the php file
        * @var data->title displays the title on the Tab browser
    */
    public function accountsReceivable(){

        $page = 'accountsreceivable';
        $data['title'] = 'Accounts Receivable';

        $data['receivable'] = $this->acc_model->viewAccountReceivable();
        
        echo view('includes/header', $data);
        echo view('accounting/'.$page, $data);
        echo view('includes/footer');

    }


    /**
        * @method updateAccountPayable() is use to route the update of accounts payable to the model
        * @param epID contains encrypted data of expense_payment_id
        * @var session->payment_completed the msg display on the Interface
        * @return to->account_payable_view
    */
    public function updateAccountPayable($epID){

        $this->acc_model->updateAccountPayable($epID);
        $_SESSION['payment_completed'] = 'payment_completed';
        return redirect()->to(site_url('account/payable/view'));

    }
    


    
    
    

    



}