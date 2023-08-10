<?php

	\Config\Services::session();

	// if(!empty($_SESSION['userID'])){
	//	redirect()->to(site_url('dashboard'));
	// }
	
?>	  

<!DOCTYPE html> 
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="image/x-icon" href="<?= site_url(); ?>asset/image/thepenthouse-logo-white.svg">
	<!-- vendor css -->
	<link href="<?= site_url(); ?>theme/lib/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
	<link href="<?= site_url(); ?>theme/lib/ionicons/css/ionicons.min.css" rel="stylesheet">
	<!-- DashForge CSS -->
	<link rel="stylesheet" href="<?= site_url(); ?>theme/assets/css/dashforge.css">
	<link rel="stylesheet" href="<?= site_url(); ?>theme/assets/css/dashforge.auth.css">


	<style>
		html, body {
			height: 100%;
			width: 100%;
			position: relative;
		}

		body {
			background: linear-gradient( rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7) ), url('<?php site_url(); ?>asset/image/background.jpg') no-repeat;
			background-size: cover;
			background-position: center center;
		}

		.content-auth {
			min-height: auto;
			padding: 80px 40px 40px;
		}

		@media (max-width: 480px) {
			.content-auth {
				padding: 40px;
			}
		}

		div.sign-wrapper div.site-logo {
			width: 85%;
			margin: 0px auto;
		}

		div.sign-wrapper div.site-logo svg {
			display: block;
			height: auto;
			margin: 0px auto;
			width: 90%;
		}

		div.sign-wrapper {
			max-width: 400px;
			width: 80%;
		}

		@media (max-width: 480px) {
			div.sign-wrapper {
				min-width: 320px;
				width: auto !important;
			}
		}

		div.sign-wrapper form {
			background: #FFF;
			padding: 30px;
		}
	</style>

	<title><?= $title;?></title>

</head>
<body>

	<div class="content content-auth">
		<div class="container">
			<img src="<?= site_url(); ?>/asset/image/thepenthouse-logo-white.svg" class="wd-80p mx-auto d-block mg-b-40">
			
			<div class="sign-wrapper mg-l-auto mg-r-auto">
			
				<div class="mg-b-30 mg-r-auto mg-l-auto wd-90p">
				
					<?php

						if(!empty($_SESSION['Login_Failed'])){
							echo '<div class="alert alert-danger" role="alert">
							<h4 class="alert-heading">Access Denied!</h4>
							<p>Invalid username and password please try again</p>
						</div>';
							unset($_SESSION['Login_Failed']);
						}

						if(!empty($_SESSION['Account_Inactive'])){
							echo '<div class="alert alert-danger" role="alert">
							<h4 class="alert-heading">Account inactive</h4>
							<p>Please contact to your administrator for further actions</p>
						</div>';
							unset($_SESSION['Account_Inactive']);
						}

						if(!empty($_SESSION['eject'])){
							echo '<div class="alert alert-warning" role="alert">
							<h4 class="alert-heading">Account Logout</h4>
							<p>Your session has been ended.</p>
						</div>';
							unset($_SESSION['eject']);
						}

						if(!empty($_SESSION['session_timeout'])){
							echo '<div class="alert alert-warning" role="alert">
							<h4 class="alert-heading">Session Timeout</h4>
							<p>Your session has been ended.</p>
						</div>';
							unset($_SESSION['session_timeout']);
						}

					?>

				</div>

				<div class="bg-primary">

					<?= form_open('authenticate');?>

						<h3 class="tx-color-01 mg-b-5">Sign In</h3>
						<p class="tx-color-03 tx-16 mg-b-40">Welcome back! Please sign-in to continue.</p>

						<div class="form-group">
							<label>Username</label>
							<input type="text" class="form-control" id="email" placeholder="Enter email" name="email" required>
						</div>
						<div class="form-group">
							<div class="d-flex justify-content-between mg-b-5">
								<label class="mg-b-0-f">Password</label>
							</div>
							<input type="password" class="form-control" id="pwd" placeholder="Enter password" name="pswd" required>
						</div>
						<button type="submit" class="btn btn-primary btn-block">Login</button>
					<?= form_close();?>

				</div>
				
				
			</div>
		</div><!-- container -->
	</div><!-- content -->
	
</body>
</html>

