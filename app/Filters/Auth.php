<?php namespace App\Filters;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Auth implements FilterInterface{

    protected $login_model;

    public function __construct(){
        $this->login_model = new \App\Models\Login_model; // to access the login_model
    }

    public function before(RequestInterface $request, $arguments = null){

        if(!session()->get('authentication')){
            return redirect()->to(site_url()); 
        }
        
    }


    //--------------------------------------------------------------------
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null){
        
       try { $this->login_model->updateAuthentication();} catch (\Throwable $th) {return 'failed';}

    }


}