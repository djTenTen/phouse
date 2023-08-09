<?php 
namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;
use TCPDF; // PDF Library
class HumanResource_controller extends BaseController{
    /** 
        All Display function has user validation of authentication
        if the user rejected/ejected it will return on the login page.
        It checks if user has session and authenticated
    */

    
    /**
        Properties being used on this file
        * @property hr_model to load the humanresource model
        * @property request for the post function method
        * @property encrypter use for encryption
    */
    protected $hr_model;
    protected $request;
    protected $encrypter;
    

    /**
        * @method __construct()is being executed automatically when this file is loaded
        * load all the methods/object to the properties of this class
    */
    public function __construct(){

        \Config\Services::session();
        $this->hr_model = new \App\Models\HumanResource_model;
        $this->request = \Config\Services::Request();
        $this->encrypter = \Config\Services::encrypter(); 
        helper(['form', 'url']);

    }


    /**
        ----------------------------------------------------------
        Employee Module Area
        ----------------------------------------------------------
        * @method employee() use to display the employee management page
        * @param state contains data submitted which sales should be displayed
        * @var data->employee contains employee information
        * @var data->branch contains the branch information
        * being displayed on the sales page
        * @var page is the name of the php file
        * @var data->title displays the title on the Tab browser
    */
    public function employee($state){

        $page = 'employee';
        $data['title'] = 'Employee Management';

        $data['state'] =ucfirst($state);
        $data['employee'] = $this->hr_model->viewEmployees($state);
        $data['branch'] = $this->hr_model->getBranch();
        
        echo view('includes/header', $data);
        echo view('humanresource/'.$page, $data);
        echo view('includes/footer');

    }


    /**
        * @method addEmployee() use to display the employee adding page
        * @var data->branch contains the branch information
        * being displayed on the sales page
        * @var page is the name of the php file
        * @var data->title displays the title on the Tab browser
    */
    public function addEmployee(){

        $page = 'addemployee';
        $data['title'] = 'Add Employee';
    
        $data['branch'] = $this->hr_model->getBranch();
        
        echo view('includes/header', $data);
        echo view('humanresource/'.$page, $data);
        echo view('includes/footer');

    }


    /**
        * @method saveEmployee() is use to route the registration of employee to the model
        * @var session->employee_added the msg display on the Interface
        * @return to->employee_add page
    */
    public function saveEmployee(){

        $this->hr_model->saveEmployee();
        $_SESSION['employee_added'] = 'employee_added';
        return redirect()->to(site_url('employee/add'));

    }


    /**
        * @method updateEmployee() is use to route the udpate of employee to the model
        * @param eID encrypted data of employee_id
        * @var session->employee_updated the msg display on the Interface
        * @return to->employee_view_active page
    */
    public function updateEmployee($eID){

        $this->hr_model->updateEmployee($eID);
        $_SESSION['employee_updated'] = 'employee_updated';
        return redirect()->to(site_url('employee/view/active'));

    }


    /**
        * @method activateEmployee() is use to route the activation of employee to the model
        * @param eID encrypted data of employee_id
        * @var session->employee_activated the msg display on the Interface
        * @return to->employee_view_active page
    */
    public function activateEmployee($eID){

        $this->hr_model->activateEmployee($eID);
        $_SESSION['employee_activated'] = 'employee_activated';
        return redirect()->to(site_url('employee/view/active'));

    }


    /**
        * @method deactivateEmployee() is use to route the deactivation of employee to the model
        * @param eID encrypted data of employee_id
        * @var session->employee_desctivated the msg display on the Interface
        * @return to->employee_view_inactive page
    */
    public function deactivateEmployee($eID){

        $this->hr_model->deactivateEmployee($eID);
        $_SESSION['employee_desctivated'] = 'employee_desctivated';
        return redirect()->to(site_url('employee/view/inactive'));

    }




















    /**
        ----------------------------------------------------------
        Employee Module Area
        ----------------------------------------------------------
        * @method addPayroll() use to display the payroll adding page
        * @var data->cutoff contains cutoff information
        * @var data->employee contains the employee information
        * being displayed on the sales page
        * @var page is the name of the php file
        * @var data->title displays the title on the Tab browser
    */
    public function addPayroll(){

        $page = 'addpayroll';
        $data['title'] = 'Add Payroll';

        $data['cutoff'] = $this->hr_model->getcutoff();
        $data['employee'] = $this->hr_model->getEmployee();
        
        echo view('includes/header', $data);
        echo view('humanresource/'.$page, $data);
        echo view('includes/footer');

    }


    /**
        * @method viewPayroll() use to display the payroll management page
        * @var data->payroll contains payroll information
        * being displayed on the sales page
        * @var page is the name of the php file
        * @var data->title displays the title on the Tab browser
    */
    public function viewPayroll(){

        $page = 'payroll';
        $data['title'] = 'Payroll';

        $data['payroll'] = $this->hr_model->viewPayroll();
        
        echo view('includes/header', $data);
        echo view('humanresource/'.$page, $data);
        echo view('includes/footer');

    }


    /**
        * @method savePayroll() is use to route the registration of payroll to the model
        * @var session->payroll_added the msg display on the Interface
        * @return to->payroll_add page
    */
    public function savePayroll(){

        $this->hr_model->savePayroll();
        $_SESSION['payroll_added'] = 'payroll_added';
        return redirect()->to(site_url('payroll/add'));

    }
    
    


    


}