<?php
namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;
class Dashboard_controller extends BaseController{
    /** 
        This login controlle doesnt have user authentication
        it's only used for redirection
    */


    /**
        Properties being used on this file
        * @property session to load the user session
        * @property request for the post function method
        * @property encrypter for the encryption
    */
    protected $session;
    protected $request;
    protected $encrypter;


    /**
        * @method __construct()is being executed automatically when this file is loaded
        * load all the methods/object to the properties of this class
    */
    public function __construct(){

        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->encrypter = \Config\Services::encrypter();

    }


    /**
        * @method dashboard() this is the landing page after successfull login
        * @var page is the name of the php file
        * @var data->title displays the title on the Tab browser
    */
    public function dashboard(){

        $page = 'dashboard';
        $data['title'] = 'Dashboard';
        
        echo view('includes/header', $data);
        echo view('dashboard/'.$page, $data);
        echo view('includes/footer');

    }







}