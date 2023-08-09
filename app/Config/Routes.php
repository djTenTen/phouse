<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Login_controller');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

/*
 * --------------------------------------------------------------------
 * All request is directed here at route, page display, update, add, delete of data are going through this route
 * --------------------------------------------------------------------
 */
$routes->get('/error', 'Login_controller::errors');



/** 
    Login
*/
//route for the login page
$routes->get('/', 'Login_controller::loginpage');
//route for login
$routes->post('/authenticate', 'Login_controller::authenticate');
$routes->post('/logout', 'Login_controller::Logout'  ,['filter' => 'auth']);

// routse for dasgboard after login
$routes->get('/dashboard', 'Dashboard_controller::dashboard' ,['filter' => 'auth']);

/** 
    User Management
*/
$routes->get('/users/usermanagement', 'User_controller::usermanagement',['filter' => 'auth']);
$routes->post('/users/saveuser', 'User_controller::saveUser',['filter' => 'auth']);
$routes->post('/users/update/(:any)', 'User_controller::updateUser/$1',['filter' => 'auth']);

/** 
    Accounting Management : Expense
*/
$routes->get('/expense/add', 'Accounting_controller::addExpense',['filter' => 'auth']);
$routes->get('/expense/view/(:any)', 'Accounting_controller::expense/$1',['filter' => 'auth']);
$routes->get('/expense/edit/(:any)', 'Accounting_controller::editExpense/$1',['filter' => 'auth']);
$routes->post('/expense/save', 'Accounting_controller::saveExpense',['filter' => 'auth']);
$routes->post('/expense/markpending/(:any)', 'Accounting_controller::expensePending/$1',['filter' => 'auth']);
$routes->post('/expense/markcompleted/(:any)', 'Accounting_controller::expenseCompleted/$1',['filter' => 'auth']);
$routes->post('/expense/markcancelled/(:any)', 'Accounting_controller::expenseCancelled/$1',['filter' => 'auth']);
$routes->post('/expense/update/(:any)', 'Accounting_controller::updateExpense/$1',['filter' => 'auth']);
$routes->get('/expense/print/(:any)', 'Accounting_controller::printExpenseVoucher/$1',['filter' => 'auth']);
/** 
    Accounting Management : Fund Transfer
*/
$routes->get('/fundtransfer/add', 'Accounting_controller::addFundTransfer',['filter' => 'auth']);
$routes->post('/fundtransfer/save', 'Accounting_controller::saveFundTransfer',['filter' => 'auth']);
$routes->post('/fundtransfer/markcancelled/(:any)', 'Accounting_controller::fundtransferCancelled/$1',['filter' => 'auth']);
$routes->post('/fundtransfer/markcompleted/(:any)', 'Accounting_controller::fundtransferCompleted/$1',['filter' => 'auth']);
$routes->get('/fundtransfer/view/(:any)', 'Accounting_controller::fundTransfer/$1',['filter' => 'auth']);
$routes->get('/fundtransfer/view/(:any)', 'Accounting_controller::fundTransfer/$1',['filter' => 'auth']);
$routes->get('/fundtransfer/print/(:any)', 'Accounting_controller::printFundTransfer/$1',['filter' => 'auth']);
/** 
    Accounting Management : Collection
*/
$routes->get('/collection/add', 'Accounting_controller::addCollection',['filter' => 'auth']);
$routes->post('/collection/save', 'Accounting_controller::saveCollection',['filter' => 'auth']);
$routes->post('/collection/update/(:any)/(:any)', 'Accounting_controller::updateCollection/$1/$2',['filter' => 'auth']);
$routes->get('/collection/view/(:any)', 'Accounting_controller::collection/$1',['filter' => 'auth']);
$routes->get('/collection/viewcollection/(:any)', 'Accounting_controller::viewCollection/$1',['filter' => 'auth']);
$routes->get('/collection/print/(:any)', 'Accounting_controller::printCollection/$1',['filter' => 'auth']);
/** 
    Accounting Management : Accounts
*/
$routes->get('/account/payable/view', 'Accounting_controller::accountsPayable',['filter' => 'auth']);
$routes->post('/account/payable/update/(:any)', 'Accounting_controller::updateAccountPayable/$1',['filter' => 'auth']);
$routes->get('/account/receivable/view', 'Accounting_controller::accountsReceivable',['filter' => 'auth']);


/** 
    Human Resource: Employee Management
*/
$routes->get('/employee/add', 'HumanResource_controller::addEmployee',['filter' => 'auth']);
$routes->post('/employee/save', 'HumanResource_controller::saveEmployee',['filter' => 'auth']);
$routes->get('/employee/view/(:any)', 'HumanResource_controller::employee/$1',['filter' => 'auth']);
$routes->post('/employee/deactivate/(:any)', 'HumanResource_controller::deactivateEmployee/$1',['filter' => 'auth']);
$routes->post('/employee/activate/(:any)', 'HumanResource_controller::activateEmployee/$1',['filter' => 'auth']);
$routes->post('/employee/update/(:any)', 'HumanResource_controller::updateEmployee/$1',['filter' => 'auth']);
/** 
    Human Resource: Payroll Management
*/
$routes->get('/payroll/add', 'HumanResource_controller::addPayroll',['filter' => 'auth']);
$routes->post('/payroll/save', 'HumanResource_controller::savePayroll',['filter' => 'auth']);
$routes->get('/payroll/view', 'HumanResource_controller::viewPayroll',['filter' => 'auth']);


/** 
    Rental: Tenant Management
*/
$routes->get('/tenant/add', 'Rental_controller::addTenant',['filter' => 'auth']);
$routes->post('/tenant/save', 'Rental_controller::saveTenant',['filter' => 'auth']);
$routes->post('/tenant/update/(:any)', 'Rental_controller::updateTenant/$1',['filter' => 'auth']);
$routes->get('/tenant/view/(:any)', 'Rental_controller::tenant/$1',['filter' => 'auth']);
$routes->post('/tenant/deactivate/(:any)', 'Rental_controller::deactivateTenant/$1',['filter' => 'auth']);
$routes->post('/tenant/activate/(:any)', 'Rental_controller::activateTenant/$1',['filter' => 'auth']);
/** 
    Rental: Ticket Management
*/
$routes->get('/ticket/add', 'Rental_controller::addTicket',['filter' => 'auth']);
$routes->post('/ticket/save', 'Rental_controller::saveTicket',['filter' => 'auth']);
$routes->get('/ticket/view/(:any)', 'Rental_controller::ticket/$1',['filter' => 'auth']);
$routes->post('/ticket/reply/(:any)', 'Rental_controller::replyTicket/$1',['filter' => 'auth']);
/** 
    Rental: Contract Management
*/
$routes->get('/contract/add', 'Rental_controller::addContract',['filter' => 'auth']);
$routes->post('/contract/save', 'Rental_controller::saveContract',['filter' => 'auth']);
$routes->get('/contract/view/(:any)', 'Rental_controller::contract/$1',['filter' => 'auth']);
$routes->get('/contract/viewcontract/(:any)', 'Rental_controller::viewContract/$1',['filter' => 'auth']);
$routes->post('/contract/update/(:any)', 'Rental_controller::udpateContract/$1',['filter' => 'auth']);
$routes->post('/contract/addpayment/(:any)', 'Rental_controller::addPaymentContract/$1',['filter' => 'auth']);
$routes->post('/contract/addrefund/(:any)', 'Rental_controller::addRefund/$1',['filter' => 'auth']);
$routes->post('/contract/publish/(:any)', 'Rental_controller::publishContract/$1',['filter' => 'auth']);
/** 
    Rental:
*/
$routes->get('/rental/view', 'Rental_controller::rental',['filter' => 'auth']);


/** 
  Ajax Routes
*/
$routes->get('/checkauthentication', 'Ajax_controller::checkAuthentication');
$routes->post('/validateusername', 'Ajax_controller::validateUsername' ,['filter' => 'auth']);
$routes->get('/users/edit/(:any)', 'Ajax_controller::editUser/$1' ,['filter' => 'auth']);
$routes->get('/expense/getdetails/(:any)', 'Ajax_controller::getExpenseDetails/$1' ,['filter' => 'auth']);
$routes->get('/expense/getitems/(:any)', 'Ajax_controller::getExpenseItems/$1' ,['filter' => 'auth']);
$routes->get('/expense/getpayment/(:any)', 'Ajax_controller::getExpensePayment/$1' ,['filter' => 'auth']);
$routes->get('/fundtransfer/getdetails/(:any)', 'Ajax_controller::getFundTransferDetails/$1' ,['filter' => 'auth']);
$routes->get('/account/contract/details/(:any)', 'Ajax_controller::getContractDetails/$1' ,['filter' => 'auth']);
$routes->get('/account/secdep/details/(:any)', 'Ajax_controller::getSecDepDetails/$1' ,['filter' => 'auth']);
$routes->get('/account/checkdep/details/(:any)', 'Ajax_controller::getCheckDepDetails/$1' ,['filter' => 'auth']);
$routes->get('/employee/getinfo/(:any)', 'Ajax_controller::getEmployeeInfo/$1' ,['filter' => 'auth']);
$routes->get('/payroll/getinfo/(:any)', 'Ajax_controller::getPayrollInfo/$1' ,['filter' => 'auth']);
$routes->get('/payroll/getitem/(:any)', 'Ajax_controller::getPayrollItems/$1' ,['filter' => 'auth']);
$routes->get('/tenant/getinfo/(:any)', 'Ajax_controller::getTenantInfo/$1' ,['filter' => 'auth']);
$routes->get('/tenant/getconcern/(:any)', 'Ajax_controller::getTenantConcern/$1' ,['filter' => 'auth']);
$routes->get('/ticket/getinfo/(:any)', 'Ajax_controller::getTicketInfo/$1' ,['filter' => 'auth']);
$routes->get('/ticket/getreply/(:any)', 'Ajax_controller::getReply/$1' ,['filter' => 'auth']);
$routes->post('/contract/duration', 'Ajax_controller::contractDuration/$1' ,['filter' => 'auth']);
$routes->get('/contract/getinfo/(:any)', 'Ajax_controller::getContractInfo/$1' ,['filter' => 'auth']);

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
