<?php
    $system_model = new \App\Models\System_model; 
	$logo = $system_model->getLogo();
	$siteName = $system_model->getSiteName();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" type="image/x-icon" href="<?= site_url(); ?>asset/image/favicon.png">

    <link rel="stylesheet" href="<?= site_url();?>theme/assets/css/dashforge.css">
    <link rel="stylesheet" href="<?= site_url();?>theme/assets/css/dashforge.auth.css">

    <title>Document</title>
</head>
<body>

    <div class="content content-fixed content-auth-alt">
        <div class="container ht-100p tx-center">
            <div class="ht-100p d-flex flex-column align-items-center justify-content-center">
                <div class="wd-70p wd-sm-250 wd-lg-300 mg-b-15"><img src="<?= site_url(); ?>asset/image/favicon.png" class="img-fluid" alt=""></div>
                <h1 class="tx-color-01 tx-24 tx-sm-32 tx-lg-36 mg-xl-b-5">ERROR 404: URL NOT FOUND</h1>
                <h5 class="tx-16 tx-sm-18 tx-lg-20 tx-normal mg-b-20">Uh-Ohh..</h5>
                <h6 >The page you are trying to access may have been moved, deleted or possibly never existed.</h6>
            </div>
        </div><!-- container -->
    </div><!-- content -->

    
</body>
</html>


