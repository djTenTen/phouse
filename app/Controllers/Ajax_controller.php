<?php 
namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;
use TCPDF; // PDF Library
class Ajax_controller extends BaseController{
    /** 
        This is the Ajax Controller route request from the uri
    */
    

    /**
        Properties being used on this file
        * @property ajax_model to load the ajax model
        * @property request for the post function method
        * @property encrypter use for encryption
    */
    protected $ajax_model;
    protected $request;
    protected $encrypter;
    

    /**
        * @method __construct()is being executed automatically when this file is loaded
        * load all the methods/object to the properties of this class
    */
    public function __construct(){

        \Config\Services::session();
        $this->ajax_model = new \App\Models\Ajax_model;
        $this->request = \Config\Services::Request();
        $this->encrypter = \Config\Services::encrypter(); 
        helper(['form', 'url']);

    }

    
    /**
        ----------------------------------------------------------
        Login Module area
        ----------------------------------------------------------
        * @method checkAuthentication() route to model to check the authentication
        * @return invalid data
    */
    public function checkAuthentication(){

        $res = $this->ajax_model->checkAuthentication();
        
        if($res == 'invalid'){
            return $res;
        }

    }


    /**
        ----------------------------------------------------------
        Users Module area
        ----------------------------------------------------------
        * @method validateUsername() route to model for user validation
        * @return htmlcontent data
    */
    public function validateUsername() {

        $requestBody = json_decode($this->request->getBody());
		$username = $requestBody->username;
			
		if ('post' === $this->request->getMethod() && $username) {
			$result = $this->ajax_model->validateUsername($username);
			if ($result === true) {
				echo '<span style="color:red;">Username already taken</span>';
			} else {
				echo '<span style="color:green;">Username Available</span>';
			}
		} else {
			echo '<span style="color:red;">You must enter username</span>';
		}

	}


    /**
        * @method editUser() get the user's information
        * @param uID encrypted data of user_id
        * @return json_data
    */
    public function editUser($uID){
        return $this->ajax_model->editUser($uID);
    }





















    /**
        ----------------------------------------------------------
        Expense Module area
        ----------------------------------------------------------
        * @method getExpenseDetails() get the expense details
        * @param eID encrypted data of expense_id
        * @return json_data
    */
    public function getExpenseDetails($eID){
        return $this->ajax_model->getExpenseDetails($eID);
    }


    /**
        * @method getExpenseItems() get the expense items details
        * @param eID encrypted data of expense_id
        * @return json_data
    */
    public function getExpenseItems($eID){
        return $this->ajax_model->getExpenseItems($eID);
    }


    /**
        * @method getExpensePayment() get the expense payment details
        * @param eID encrypted data of expense_id
        * @return json_data
    */
    public function getExpensePayment($eID){
        return $this->ajax_model->getExpensePayment($eID);
    }

    















    /**
        ----------------------------------------------------------
        Fund Transfer Module area
        ----------------------------------------------------------
        * @method getFundTransferDetails() get the fund transfer details
        * @param ftID encrypted data of fund_transfer_id
        * @return json_data
    */
    public function getFundTransferDetails($ftID){
        return $this->ajax_model->getFundTransferDetails($ftID);
    }






















    /**
        ----------------------------------------------------------
        Contract Module area
        ----------------------------------------------------------
        * @method getContractDetails() get the contract details
        * @param cID encrypted data of contract_id
        * @return json_data
    */
    public function getContractDetails($cID){
        return $this->ajax_model->getContractDetails($cID);
    }


    /**
        * @method getSecDepDetails() get the security deposit details
        * @param cID encrypted data of contract_id
        * @return json_data
    */
    public function getSecDepDetails($cID){
        return $this->ajax_model->getSecDepDetails($cID);
    }


    /**
        * @method getCheckDepDetails() get the check security deposit details
        * @param cID encrypted data of contract_id
        * @return json_data
    */
    public function getCheckDepDetails($cID){
        return $this->ajax_model->getCheckDepDetails($cID);
    }

    














    /**
        ----------------------------------------------------------
        Employee Module area
        ----------------------------------------------------------
        * @method getEmployeeInfo() get the employee details
        * @param eID encrypted data of employee_id
        * @return json_data
    */
    public function getEmployeeInfo($eID){
        return $this->ajax_model->getEmployeeInfo($eID);
    }

    
    














    /**
        ----------------------------------------------------------
        Payroll Module area
        ----------------------------------------------------------
        * @method getPayrollInfo() get the payroll details
        * @param pID encrypted data of payroll_id
        * @return json_data
    */
    public function getPayrollInfo($pID){
        return $this->ajax_model->getPayrollInfo($pID);
    }


    /**
        * @method getPayrollItems() get the payroll item details
        * @param pID encrypted data of payroll_id
        * @return json_data
    */
    public function getPayrollItems($pID){
        return $this->ajax_model->getPayrollItems($pID);
    }


















    /**
        ----------------------------------------------------------
        Tenant Module area
        ----------------------------------------------------------
        * @method getTenantInfo() get the tenant details
        * @param tID encrypted data of tenant_id
        * @return json_data
    */
    public function getTenantInfo($tID){
        return $this->ajax_model->getTenantInfo($tID);
    }


    /**
        * @method getTenantConcern() get the tenant concern details
        * @param tID encrypted data of tenant_id
        * @return json_data
    */
    public function getTenantConcern($tID){
        return $this->ajax_model->getTenantConcern($tID);
    }
    
















    /**
        ----------------------------------------------------------
        Ticket Module area
        ----------------------------------------------------------
        * @method getTicketInfo() get the tenant details
        * @param sID encrypted data of support_id
        * @return json_data
    */
    public function getTicketInfo($sID){
        return $this->ajax_model->getTicketInfo($sID);
    }


    /**
        * @method getReply() get the reply on tenant concern
        * @param sID encrypted data of support_id
        * @return json_data
    */
    public function getReply($sID){
        return $this->ajax_model->getReply($sID);
    }


    
    


    













    /**
        ----------------------------------------------------------
        Ticket Module area
        ----------------------------------------------------------
        * @method getTicketInfo() get compute and display the contract duration
        * @param sID encrypted data of support_id
        * @var array->result
        * @return json_data
    */
    public function contractDuration(){

        $result = array();

        if(isset($_POST['mm']) && $_POST['mm'] != '' && isset($_POST['contract-duration']) && $_POST['contract-duration'] != ''){

            $ct = $this->request->getPost('yy').'-'.$this->request->getPost('mm').'-'.$this->request->getPost('dd');

            $result['duration'] = $_POST['contract-duration'];
            $result['start'] = date("M. j, Y", strtotime($ct));
            $result['end'] = date("M. j, Y", strtotime($result['start'] . " + " . $result['duration'] . " month -1 day"));
            $result['month'] = array();

            $ctrl = $result['start'];
            $i = 0;

            while($i < $result['duration']){
                $i++;
                $result['month'][$i] = date("M. j, Y", strtotime($ctrl)) . " - " . date("M. j, Y", strtotime($ctrl . " +1 month -1 day"));
                $ctrl = date("M. j, Y", strtotime($ctrl . " +1 month"));
            }
        } else {
            $result['error'] = "error";
        }

        echo json_encode($result);


    }
















    /**
        ----------------------------------------------------------
        Ticket Module area
        ----------------------------------------------------------
        * @method getContractInfo() get the contract details
        * @param cID encrypted data of contract_id
        * @return json_data
    */
    public function getContractInfo($cID){
        return $this->ajax_model->getContractInfo($cID);
    }
    



    
    
}