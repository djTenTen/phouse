<?php 
namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;
class User_controller extends BaseController{
    /** 
        All Display function has user validation of authentication
        if the user rejected/ejected it will return on the login page.
        It checks if user has session and authenticated
    */


    /**
        Properties being used on this file
        * @property user_model to load the user model
        * @property request for the post function method
    */
    //protected $warehouse_model;
    protected $user_model;
    protected $request;


    /**
        * @method __construct()is being executed automatically when this file is loaded
        * load all the methods/object to the properties of this class
    */
    public function __construct(){
        \Config\Services::session();
        //$this->warehouse_model = new \App\Models\Warehouse_model;
        $this->user_model = new \App\Models\User_model;
        $this->request = \Config\Services::Request();
        helper(['form', 'url']);

    }


    


    /**
        ----------------------------------------------------------
        User Management Module area
        ----------------------------------------------------------
        * @method usermanagement() use to display the users page
        * @var data->ausr contains the active user information
        * @var data->iusr contains the inactive user information
        * @var data->grp contains the group user information
        * @var data->prntmodule contains the parent module group information
        * being displayed on the sales page
        * @var page is the name of the php file
        * @var data->title displays the title on the Tab browser
    */
    public function usermanagement(){

        $page = 'usermanagement';
        $data['title'] = 'User Management';
        $data['accounttype'] = $this->user_model->accountType();
        $data['activeus'] = $this->user_model->viewaActiveUser();
        $data['inactiveus'] = $this->user_model->viewaInactiveUser();

        echo view('includes/header', $data);
        echo view('users/'.$page, $data);
        echo view('includes/footer');

    }


    /**
        * @method saveUser() is use to route the registration of user to the model
        * @var session->user_registered the msg display on the Interface
        * @return to->usermanagement page
    */
    public function saveUser(){

        $this->user_model->saveUser();
        $_SESSION['user_registered'] = 'user_registered';
        return redirect()->to(site_url('users/usermanagement'));

    }


    /**
        * @method updateUser() is use to route the update of user to the model
        * @param uID encypted data of user_id
        * @var session->user_updated the msg display on the Interface
        * @return to->usermanagement page
    */
    public function updateUser($uID){

        $this->user_model->updateUser($uID);
        $_SESSION['user_updated'] = 'user_updated';
        return redirect()->to(site_url('users/usermanagement'));

    }


    /**
        ----------------------------------------------------------
        End User Management Module area
        ----------------------------------------------------------
    */








    


}