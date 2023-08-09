<?php
	$session = \Config\Services::session();
	$arr = array();
	$_SESSION['last_uri'] = uri_string();
?>

<!DOCTYPE html>
<html lang="en">
<head>

	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Favicon -->
	<link rel="shortcut icon" type="image/x-icon" href="<?= site_url(); ?>asset/image/favicon.png">
	<title><?= $title;?> | The Penthouse</title>thepenthouse-logo-white.svg
	
	<!-- Vendor CSS -->
	<link href="<?= site_url(); ?>vendor/fontawesome/css/all.min.css" rel="stylesheet">
	<!-- DashForge CSS -->
	<link rel="stylesheet" href="<?= site_url(); ?>asset/css/style.css">
	<link rel="stylesheet" href="<?= site_url(); ?>theme/assets/css/dashforge.css">
	<link rel="stylesheet" href="<?= site_url(); ?>theme/lib/ionicons/css/ionicons.min.css">
	<!-- Vendor Scripts -->      
	<script src="<?= site_url(); ?>theme/lib/jquery/jquery.min.js"></script>
	<script src="<?= site_url(); ?>theme/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="<?= site_url(); ?>theme/lib/perfect-scrollbar/perfect-scrollbar.min.js"></script>
	<script src="<?= site_url(); ?>theme/lib/feather-icons/feather.min.js"></script>

	<!-- select2 -->
	<link rel='stylesheet' href='<?= site_url(); ?>plugin/select2/css/select2.min.css' type='text/css'>
	<script type='text/javascript' src='<?= site_url();?>plugin/select2/js/select2.full.min.js'></script>


	<link href="<?= site_url(); ?>theme/lib/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">
	<script src="<?= site_url(); ?>theme/lib/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
	<!-- ajax library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
	<!-- jQuery UI library -->
	
	<!-- <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/themes/smoothness/jquery-ui.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script> -->

	<!-- tinymce  -->
	<script src="<?= site_url(); ?>plugin/tinymce/js/tinymce.min.js"></script>
	<script src="<?= site_url(); ?>plugin/jquery-ui/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="<?= site_url(); ?>plugin/jquery-ui/jquery-ui.theme.min.css"/>
	<link rel="stylesheet" href="<?= site_url(); ?>plugin/jquery-ui/jquery-ui.min.css"/>

	<!-- datatable library -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
	<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>

	<!-- Template Scripts -->
	<script src="<?= site_url(); ?>theme/assets/js/dashforge.js"></script>
	<script src="<?= site_url(); ?>theme/assets/js/dashforge.aside.js"></script>
	<!-- Font Awesome -->
	<script src="https://kit.fontawesome.com/c29453de59.js" crossorigin="anonymous"></script>
	<!-- Chartjs -->
	<script src="<?= site_url(); ?>vendor/chartjs/chart.js"></script>
	<!-- Page JS -->
	<script src="<?= site_url(); ?>asset/js/main.min.js"></script>
	<script src="<?= site_url(); ?>asset/js/main.js"></script>
	<script src="<?= site_url(); ?>asset/js/page.js"></script>


	<script>
		function validateSession() {
			$.ajax({
				url: "<?= site_url('checkauthentication');?>",
				type: "GET",
				success: function(response) {
					// Handle the response from the server
					if (response === "invalid") {
						window.location.reload();
					}

				}
			});
		}
		$(document).ready(function() {
			// Call validateSession() every 2 seconds
			setInterval(validateSession, 1000);
		});
	</script>


</head>

<body>

<aside class="aside aside-fixed">
	<div class="aside-header">
		<a href="<?= site_url();?>" class="aside-logo">The<span>Penthouse</span></a>
		<a href="" class="aside-menu-link">
			<i data-feather="menu"></i>
			<i data-feather="x"></i>
		</a>
	</div>
	<div class="aside-body">
		<ul class="nav nav-aside">
			<li class="nav-label">Main</li>
			
			<li class="nav-item"><a href="<?= site_url();?>dashboard" class="nav-link"><i class="fad fa-tachometer-slow"></i> <span>Dashboard</span></a></li>
			
			<li class="nav-label mg-t-25">Accounting</li>
			
			<li class="nav-item with-sub">
				<a class="nav-link"><i class="fad fa-file-invoice-dollar"></i> <span>Expense</span></a>
				<ul>
					<li><a href="<?= site_url();?>expense/view/all">View All Expense</a></li>
					<li><a href="<?= site_url();?>expense/view/pending">View Pending Expense</a></li>
					<li><a href="<?= site_url();?>expense/view/completed">View Completed Expense</a></li>
					<li><a href="<?= site_url();?>expense/view/cancelled">View Cancelled Expense</a></li>
					<li><a href="<?= site_url();?>expense/add">Add New Expenses</a></li>
				</ul>
			</li>

			<li class="nav-item with-sub">
				<a class="nav-link"><i class="fad fa-random"></i> <span>Fund Transfer</span></a>
				<ul>
					<li><a href="<?= site_url();?>fundtransfer/view/all">View All Transfer</a></li>
					<li><a href="<?= site_url();?>fundtransfer/view/completed">View Completed Transfer</a></li>
					<li><a href="<?= site_url();?>fundtransfer/view/cancelled">View Cancelled Transfer</a></li>
					<li><a href="<?= site_url();?>fundtransfer/add">Add New Transfer</a></li>
				</ul>
			</li>

			<li class="nav-item with-sub">
				<a class="nav-link"><i class="fad fa-clipboard-list-check"></i> <span>Collection List</span></a>
				<ul>
					<li><a href="<?= site_url();?>collection/view/all">View All Collection</a></li>
					<li><a href="<?= site_url();?>collection/add">Add New Collection</a></li>
				</ul>
			</li>
			
			<li class="nav-item"><a href="<?= site_url();?>account/payable/view" class="nav-link"><i class="fad fa-money-check-edit-alt"></i> <span>Accounts Payable</span></a></li>
			
			<li class="nav-item"><a href="<?= site_url();?>account/receivable/view" class="nav-link"><i class="fad fa-envelope-open-dollar"></i> <span>Accounts Receivable</span></a></li>

			<li class="nav-label mg-t-25">Human Resource</li>
			
			<li class="nav-item with-sub">
				<a class="nav-link"><i class="fad fa-id-card"></i> <span>Employee</span></a>
				<ul>
					<li><a href="<?= site_url();?>employee/view/active">View Active Employee</a></li>
					<li><a href="<?= site_url();?>employee/view/inactive">View Inactive Employee</a></li>
					<li><a href="<?= site_url();?>employee/add">Add New Employee</a></li>
				</ul>
			</li>
			
			<li class="nav-item"><a href="<?= site_url();?>module/payroll/daily-time-record.php" class="nav-link"><i class="fad fa-clock"></i> <span>Daily Time Record</span></a></li>

			<li class="nav-item with-sub">
				<a class="nav-link"><i class="fad fa-envelope-open-dollar"></i> <span>Payroll</span></a>
				<ul>
					<li><a href="<?= site_url();?>payroll/view">View All Payroll</a></li>
					<li><a href="<?= site_url();?>payroll/add">Create New Payroll</a></li>
				</ul>
			</li>
			
			<li class="nav-label mg-t-25">Rentals</li>
			
			<li class="nav-item with-sub">
				<a class="nav-link"><i class="fad fa-users"></i> <span>Tenant</span></a>
				<ul>
					<li><a href="<?= site_url();?>tenant/view/active">View Active Tenant</a></li>
					<li><a href="<?= site_url();?>tenant/view/inactive">View Inactive Tenant</a></li>
					<li><a href="<?= site_url();?>tenant/add">Add New Tenant</a></li>
				</ul>
			</li>
			
			<li class="nav-item with-sub">
				<a href="#" class="nav-link"><i class="fad fa-user-headset"></i> <span>Support</span></a>
				<ul>
					<li><a href="<?= site_url();?>ticket/view/all">View All Ticket</a></li>
					<li><a href="<?= site_url();?>ticket/view/open">View Open Ticket</a></li>
					<li><a href="<?= site_url();?>ticket/view/closed">View Closed Ticket</a></li>
					<li><a href="<?= site_url();?>ticket/add">Add Support Ticket</a></li>
				</ul>
			</li>

			<li class="nav-item with-sub">
				<a href="#" class="nav-link"><i class="fad fa-file-signature"></i> <span>Contract</span></a>
				<ul>
					<li><a href="<?= site_url();?>contract/view/pending">View Pending Contract</a></li>
					<li><a href="<?= site_url();?>contract/view/completed">View Completed Contract</a></li>
					<li><a href="<?= site_url();?>contract/view/cancelled">View Cancelled Contract</a></li>
					<li><a href="<?= site_url();?>contract/view/terminated">View Terminated Contract</a></li>
					<li><a href="<?= site_url();?>contract/add">Add New Contract</a></li>
				</ul>
			</li>
			
			<li class="nav-item"><a href="<?= site_url();?>rental/view" class="nav-link"><i class="fad fa-business-time"></i> <span>Rental</span></a></li>


		</ul>
	</div>
</aside>

<div class="content ht-100v pd-0">
	<div class="content-header">
		<div>&nbsp;</div>
		<nav class="nav">
			<div class="dropdown dropdown-profile">
				<a href="#" class="dropdown-link" data-toggle="dropdown" data-display="static">
					<div class="avatar avatar-sm"><img src="<?php echo site_url(); ?>asset/image/avatar.jpg" class="rounded-circle mx-auto"></div>
				</a><!-- dropdown-link -->
				<div class="dropdown-menu dropdown-menu-right tx-13">
					<div class="avatar avatar-lg mg-b-15 mx-auto"><img src="<?php echo site_url(); ?>asset/image/avatar.jpg" class="rounded-circle"></div>

					<h6 class="mg-b-5 tx-semibold text-center"><?= $_SESSION['name'];?></h6>
					<p class="mg-b-25 tx-12 tx-color-03 text-center"><?= $_SESSION['accounttype'];?></p>
					
					<div class="dropdown-divider"></div>

					<a href="<?= site_url(); ?>users/usermanagement" class="dropdown-item"><i class="fa-duotone fa-users mr-2"></i> User Management</a>
					
					<div class="dropdown-divider"></div>
					
					<a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal"><i class="fa-duotone fa-right-from-bracket mr-2"></i>Sign Out</a>

				</div><!-- dropdown-menu -->
			</div><!-- dropdown -->
		</nav>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="logoutModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				Are you sure to Sign Out?
			</div>
			<div class="modal-footer">
				<?= form_open("logout");?>
					<button type="submit" class="btn btn-primary">Confirm</button>
				<?= form_close();?>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
			</div>
			</div>
		</div>
	</div>
	<!-- END OF MODAL VIEW -->


	<div class="content-body">
		<div class="mg-b-20 mg-lg-b-25 mg-xl-b-30">
			<div>
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb breadcrumb-style1 mg-b-10">
					<li class="breadcrumb-item"><?= $title;?></li>
					<li class="breadcrumb-item active" aria-current="page"><a href=""><span class="tx-primary"></span></a></li>
					</ol>
				</nav>
				<h4 class="mg-b-30 tx-spacing--1"></h4>
			</div>
		</div>