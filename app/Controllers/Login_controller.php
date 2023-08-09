<?php
namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;
class Login_controller extends BaseController{
    /** 
        This login controlle doesnt have user authentication
        it's only used for redirection
    */


    /**
        Properties being used on this file
        * @property session to load the user session
        * @property request for the post function method
        * @property encrypter for the encryption
        * @property mdl to load the login model
    */
    protected $session;
    protected $request;
    protected $encrypter;
    protected $mdl;


    /**
        * @method __construct()is being executed automatically when this file is loaded
        * load all the methods/object to the properties of this class
    */
    public function __construct(){

        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->encrypter = \Config\Services::encrypter();
        $this->mdl = new \App\Models\Login_model;

    }


    public function errors(){
       return view("errors/error521");
    }


    /**
        * @method loginpage() the default/starting page of the system
        * @var data->title displays the title on the Tab browser
    */
    public function loginpage(){

        $db = \Config\Database::connect('default'); 

        try { $db->connect('default'); } catch (\Throwable $th) { return view("errors/error500"); }

        $res = $db->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME='db_furniturehouse'");

        if($res->getNumRows() > 0){
            if(session()->get('authentication')){
                return redirect()->to(site_url('dashboard')); 
            }else{
                $page = 'login';
                $data['title'] = 'The Penthouse';
                return view('login/'.$page,$data);
            }
        }else{
            return redirect()->to(site_url('error500'));
        }

    }


    /**
        * @method authenticate() is to authenticate the user on the system
        * @var u contains username of the user
        * @var p contains password of the user
        * @var res contains data from the result of the authentication from the model
        * @throws session->Login_Failed if incorrect username and passowrd
        * @throws session->Account_Inactive if user is deactivated
        * @return to->login_page after
        * @return redirected->to->dashboard if success contains user data under @var sessdata
    */
    public function authenticate(){

        $u = $this->request->getPost('email');
        $p = $this->request->getPost('pswd');

        $res = $this->mdl->authenticate($u,$p);

        if($res == 'failed'){
            $this->session->setFlashdata('Login_Failed','Login_Failed');
            return redirect()->to(site_url());
        }elseif($res == 'inactive'){
            $this->session->setFlashdata('Account_Inactive','Account_Inactive');
            return redirect()->to(site_url());
        }else{          
            
            $sessdata = [
                'authentication' => true,
                'userID' => $this->encrypter->encrypt($res['userID']),
                'sessionID' => $this->encrypter->encrypt($res['sessionID']),
                'SysSess' => $res['SysSess'],
                'name' => $res['name'],
                'accounttype' => $res['accounttype'],
                'status' => $res['status'],
            ];
            $this->session->set($sessdata);

            if(empty($_SESSION['last_uri'])){
                return redirect()->to(site_url('dashboard'));
            }else{
                return redirect()->to(site_url($_SESSION['last_uri']));
            }

        }

    }



    public function Logout(){

        $this->mdl->logout();
        return redirect()->to(site_url());

    }

}
