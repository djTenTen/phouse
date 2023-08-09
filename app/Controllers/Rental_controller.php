<?php 
namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;
use TCPDF; // PDF Library
class Rental_controller extends BaseController{
    /** 
        All Display function has user validation of authentication
        if the user rejected/ejected it will return on the login page.
        It checks if user has session and authenticated
    */

    
    /**
        Properties being used on this file
        * @property ajax_model to load the ajax model
        * @property request for the post function method
        * @property encrypter use for encryption
        * @property time to load the current time
        * @property date to load the current date
    */
    protected $rental_model;
    protected $request;
    protected $encrypter;
    protected $time;
    protected $date;

    /**
        * @method __construct()is being executed automatically when this file is loaded
        * load all the methods/object to the properties of this class
    */
    public function __construct(){

        \Config\Services::session();
        $this->rental_model = new \App\Models\Rental_model;
        $this->request = \Config\Services::Request();
        $this->encrypter = \Config\Services::encrypter(); 
        helper(['form', 'url']);

        date_default_timezone_set("Asia/Singapore"); 
        $this->time = date("H:i:s"); 
        $this->date = date("Y-m-d");

    }

    

    /**
        ----------------------------------------------------------
        Tenant Module Area
        ----------------------------------------------------------

        * @method addTenant() use to display the tenand adding page
        * @var data->category contains the tenant category information
        * being displayed on the sales page
        * @var page is the name of the php file
        * @var data->title displays the title on the Tab browser
    */
    public function addTenant(){

        $page = 'addtenant';
        $data['title'] = 'Add Tenant';

        $data['category'] = $this->rental_model->getCategory();
        
        echo view('includes/header', $data);
        echo view('rental/'.$page, $data);
        echo view('includes/footer');

    }


    /**
        * @method tenant() use to display the tenant management page
        * @param state contains data submitted which sales should be displayed
        * @var data->tenants contains the tenant information based on @param state
        * @var data->category contains the tenant category information
        * being displayed on the sales page
        * @var page is the name of the php file
        * @var data->title displays the title on the Tab browser
    */
    public function tenant($state){

        $page = 'tenant';
        $data['title'] = 'Tenant Management';

        $data['state'] = ucfirst($state);
        $data['tenants'] = $this->rental_model->viewTenants($state);
        $data['category'] = $this->rental_model->getCategory();
        
        echo view('includes/header', $data);
        echo view('rental/'.$page, $data);
        echo view('includes/footer');

    }


    /**
        * @method saveTenant() is use to route the registration of tenant to the model
        * @var session->tenant_added the msg display on the Interface
        * @return to->tenant_add page
    */
    public function saveTenant(){

        $this->rental_model->saveTenant();
        $_SESSION['tenant_added'] = 'tenant_added';
        return redirect()->to(site_url('tenant/add'));

    }


    /**
        * @method updateTenant() is use to route the update of tenant to the model
        * @param tID encypted data of tenant_id
        * @var session->tenant_updated the msg display on the Interface
        * @return to->tenant_view_active page
    */
    public function updateTenant($tID){

        $this->rental_model->updateTenant($tID);
        $_SESSION['tenant_updated'] = 'tenant_updated';
        return redirect()->to(site_url('tenant/view/active'));

    }


    /**
        * @method deactivateTenant() is use to deactivation of tenant to the model
        * @param tID encypted data of tenant_id
        * @var session->tenant_deactivated the msg display on the Interface
        * @return to->tenant_view_inactive page
    */
    public function deactivateTenant($tID){

        $this->rental_model->deactivateTenant($tID);
        $_SESSION['tenant_deactivated'] = 'tenant_deactivated';
        return redirect()->to(site_url('tenant/view/inactive'));

    }

    
    /**
        * @method activateTenant() is use to activation of tenant to the model
        * @param tID encypted data of tenant_id
        * @var session->tenant_activated the msg display on the Interface
        * @return to->tenant_view_active page
    */
    public function activateTenant($tID){

        $this->rental_model->activateTenant($tID);
        $_SESSION['tenant_activated'] = 'tenant_activated';
        return redirect()->to(site_url('tenant/view/active'));

    }























    /**
        ----------------------------------------------------------
        Ticket Module Area
        ----------------------------------------------------------
        * @method ticket() use to display the ticket management page
        * @param state contains data submitted which sales should be displayed
        * @var data->tickets contains the ticket information base on @param state
        * @var data->state displays the current state to the page
        * being displayed on the sales page
        * @var page is the name of the php file
        * @var data->title displays the title on the Tab browser
    */
    public function ticket($state){

        $page = 'ticket';
        $data['title'] = 'Ticket';

        $data['state'] = ucfirst($state);
        $data['tickets'] = $this->rental_model->viewTickets($state);
        
        echo view('includes/header', $data);
        echo view('rental/'.$page, $data);
        echo view('includes/footer');

    }


    /**
        * @method addTicket() use to display the ticket adding page
        * @param state contains data submitted which sales should be displayed
        * @var data->tenants contains tenant information
        * @var data->branch contains branch information
        * @var data->branch contains supports category information
        * being displayed on the sales page
        * @var page is the name of the php file
        * @var data->title displays the title on the Tab browser
    */
    public function addTicket(){

        $page = 'addticket';
        $data['title'] = 'Add Ticket';

        $data['tenants'] = $this->rental_model->getActiveTenants();
        $data['branch'] = $this->rental_model->getBranches();
        $data['category'] = $this->rental_model->getSupportCategory();
        
        echo view('includes/header', $data);
        echo view('rental/'.$page, $data);
        echo view('includes/footer');

    }


    /**
        * @method saveTicket() is use to route the registration of ticket to the model
        * @var session->ticket_added the msg display on the Interface
        * @return to->ticket_add page
    */
    public function saveTicket(){

        $this->rental_model->saveTicket();
        $_SESSION['ticket_added'] = 'ticket_added';
        return redirect()->to(site_url('ticket/add'));

    }


    /**
        * @method saveTicket() is use to route the reply of ticket to the model
        * @param sID encrypted data of support_id
        * @var session->ticket_replied the msg display on the Interface
        * @return to->ticket_view_all page
    */
    public function replyTicket($sID){

        $this->rental_model->replyTicket($sID);
        $_SESSION['ticket_replied'] = 'ticket_replied';
        return redirect()->to(site_url('ticket/view/all'));

    }


















    /**
        ----------------------------------------------------------
        Ticket Module Area
        ----------------------------------------------------------
        * @method contract() use to display the contract management page
        * @param state contains data submitted which sales should be displayed
        * @var data->contract contains the contract information base on @param state
        * @var data->state displays the current state to the page
        * being displayed on the sales page
        * @var page is the name of the php file
        * @var data->title displays the title on the Tab browser
    */
    public function contract($state){

        $page = 'contract';
        $data['title'] = 'Contract Management';

        $data['state'] = ucfirst($state);
        $data['contracts'] = $this->rental_model->viewContracts($state);
        
        echo view('includes/header', $data);
        echo view('rental/'.$page, $data);
        echo view('includes/footer');

    }


    /**
        * @method viewContract() use to display the contract management page
        * @param cID encrypted data of contract_id
        * @var data->ct contains the contract information base on @param cID
        * @var data->secdep contains the security deposit information base on @param cID
        * @var data->secdep contains the refunds information base on @param cID
        * @var data->bank contains the bank information
        * being displayed on the sales page
        * @var page is the name of the php file
        * @var data->title displays the title on the Tab browser
    */
    public function viewContract($cID){

        $page = 'viewcontract';
        $data['title'] = 'Contract Management';

        $data['ct'] = $this->rental_model->getContracts($cID);
        $data['secdep'] = $this->rental_model->getSecurityDeposits($cID);
        $data['refunds'] = $this->rental_model->getRefunds($cID);
        $data['bank'] = $this->rental_model->getBanks();
        
        echo view('includes/header', $data);
        echo view('rental/'.$page, $data);
        echo view('includes/footer');

    }


    /**
        * @method addContract() use to display the contract adding page
        * @var data->tenants contains tenants information
        * @var data->branch contains branch information
        * @var data->category contains tenant category information
        * @var data->bank contains the bank information
        * being displayed on the sales page
        * @var page is the name of the php file
        * @var data->title displays the title on the Tab browser
    */
    public function addContract(){

        $page = 'addcontract';
        $data['title'] = 'Add Contract';

        $data['tenants'] = $this->rental_model->getActiveTenants();
        $data['branch'] = $this->rental_model->getBranches();
        $data['bank'] = $this->rental_model->getBanks();
        $data['category'] = $this->rental_model->getSupportCategory();
        
        echo view('includes/header', $data);
        echo view('rental/'.$page, $data);
        echo view('includes/footer');

    }


    /**
        * @method saveContract() is use to route the registration of contract to the model
        * @var session->contract_added the msg display on the Interface
        * @return to->contract_add page
    */
    public function saveContract(){

        $this->rental_model->saveContract();
        $_SESSION['contract_added'] = 'contract_added';
        return redirect()->to(site_url('contract/add'));

    }


    /**
        * @method udpateContract() is use to route the update of contract to the model
        * @param cID encrypted data of contract_id
        * @var session->contract_updated the msg display on the Interface
        * @return to->contract_view_pending page
    */
    public function udpateContract($cID){

        $this->rental_model->udpateContract($cID);
        $_SESSION['contract_updated'] = 'contract_updated';
        return redirect()->to(site_url('contract/view/pending'));

    }


    /**
        * @method addPaymentContract() is use to route the registration of payment contract to the model
        * @param cID encrypted data of contract_id
        * @var session->contract_payment_added the msg display on the Interface
        * @return to->contract_viewcontract_cID page
    */
    public function addPaymentContract($cID){

        $this->rental_model->addPaymentContract($cID);
        $_SESSION['contract_payment_added'] = 'contract_payment_added';
        return redirect()->to(site_url('contract/viewcontract/'.$cID));

    }


    /**
        * @method addRefund() is use to route the registration of refund contract to the model
        * @param cID encrypted data of contract_id
        * @var session->refund_added the msg display on the Interface
        * @return to->contract_viewcontract_cID page
    */
    public function addRefund($cID){

        $this->rental_model->addRefund($cID);
        $_SESSION['refund_added'] = 'refund_added';
        return redirect()->to(site_url('contract/viewcontract/'.$cID));

    }


    /**
        * @method publishContract() is use to route the publication of contract to the model
        * @param cID encrypted data of contract_id
        * @var session->contract_published the msg display on the Interface
        * @return to->contract_viewcontract_cID page
    */
    public function publishContract($cID){

        $this->rental_model->publishContract($cID);
        $_SESSION['contract_published'] = 'contract_published';
        return redirect()->to(site_url('contract/viewcontract/'.$cID));

    }













    /**
        ----------------------------------------------------------
        Rental Module Area
        ----------------------------------------------------------
        * @method rental() use to display the rental report page
        * @var data->month contains the date being shown on report
        * @var data->tenants contains tenants information
        * @var data->branch contains branch information
        * @var data->category contains tenant category information
        * being displayed on the sales page
        * @var page is the name of the php file
        * @var data->title displays the title on the Tab browser
    */
    public function rental(){

        $page = 'rental';
        $data['title'] = 'Rental';

        if(empty($this->request->getPost('month'))){
            $data['month'] = $this->date;
        }else{
            $data['month'] = date_format(date_create($this->request->getPost('month')),"Y-m-d");
        }

        $data['tenants'] = $this->rental_model->getActiveTenants();
        $data['branch'] = $this->rental_model->getBranches();
        $data['cate'] = $this->rental_model->getCategory();
        
        echo view('includes/header', $data);
        echo view('rental/'.$page, $data);
        echo view('includes/footer');

    }

    


    

    

    
    



    




    


    
    
    






}