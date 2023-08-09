<?php

    $encrypter = \Config\Services::encrypter();

    /*
        * @$arr is the array container for the login control information
        * @foreach to get all the information and push the data on array to be 
        * verified on certain part like add,edit,delete access
    */
    $arr = array();
    // $user_model = new \App\Models\User_model; // to access the users_model
    // foreach($user_model->getUserAccess($_SESSION['groupid']) as $access){
    //     array_push($arr, $access['name']);
    // }

?>

<div class="container">



    <?php
        // message session thrown from the controller
        if(!empty($_SESSION['user_registered'])){
            echo '<div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Register Success!</h4>
            <p>User has been successfully registered</p>
        </div>';
            unset($_SESSION['user_registered']);
        }
        if(!empty($_SESSION['user_updated'])){
            echo '<div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Update Success!</h4>
            <p>User has been successfully updated</p>
        </div>';
            unset($_SESSION['user_updated']);
        }
        if(!empty($_SESSION['user_deleted'])){
            echo '<div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Delete Success!</h4>
            <p>User has been successfully deleted</p>
        </div>';
            unset($_SESSION['user_deleted']);
        }

        if(!empty($_SESSION['group_added'])){
            echo '<div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Group Registration Success!</h4>
            <p>User group has been successfully registered</p>
        </div>';
            unset($_SESSION['group_added']);
        }

        if(!empty($_SESSION['group_updated'])){
            echo '<div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Update Success!</h4>
            <p>User group has been successfully updated</p>
        </div>';
            unset($_SESSION['group_updated']);
        }
        
    ?>


    <h1> User Management</h1>



    <ul class="nav nav-tabs" id="myTab" role="tablist">


        <li class="nav-item">
            <a class="nav-link active" id="adduser-tab" data-toggle="tab" href="#adduser" role="tab" aria-controls="adduser" aria-selected="true">Add New</a>
        </li>   
        <li class="nav-item">
            <a class="nav-link" id="viewactive-tab" data-toggle="tab" href="#viewactive" role="tab" aria-controls="viewactive" aria-selected="false">View Active</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="inactiveusers-tab" data-toggle="tab" href="#inactiveusers" role="tab" aria-controls="inactiveusers" aria-selected="false">View Inactive</a>
        </li>
        
    </ul>
    
    <!-- FIRST TAB -->
    <div class="tab-content bd bd-gray-300 bd-t-0 pd-20" id="myTabContent">

      
        <div class="tab-pane fade show active" id="adduser" role="tabpanel" aria-labelledby="adduser-tab">
            <h6>Add New User</h6>
                <?= form_open('users/saveuser');?>

                <div class="row">
                    <div class="form-group col-lg-6">
                        <label class="d-block">Name</label>
                        <input type="text" class="form-control" name="name" value="" required autofocus>
                    </div>

                    <div class="col-lg-6">
                        <label class="d-block">Username</label>
                        <input class="form-control" type="text" name="username" value="" id="username" onkeydown="btnhiding()" required>
                        <div id="msg"></div>
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="d-block">Password</label>
                        <input type="password" class="form-control" name="password" value="" required>
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="d-block">Confirm Password</label>
                        <input type="password" class="form-control" name="confirm-password" value="" required>
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="d-block">Contact Number</label>
                        <input type="text" class="form-control" name="contact-number" value="">
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="d-block">Email Address</label>
                        <input type="email" class="form-control" name="email-address" value="">
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="d-block">User Account Type</label>
                        <select class="form-control" name="user-account-type" required>
                            <option value="" selected>Select Account Type</option>
                            <?php foreach($accounttype as $att){?>
                                <option value="<?= $encrypter->encrypt($att['user_account_type_id']);?>"><?= $att['name'];?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>

                <div class="mg-t-20">
                    <button class="btn btn-primary mg-r-15" name="submit" type="submit">Register User</button>
                </div>

                <?= form_close();?>

        </div>
        <!-- END OF FIRST TAB -->

        <!-- START OF 2ND TAB -->
        <div class="tab-pane fade " id="viewactive" role="tabpanel" aria-labelledby="viewactive-tab">
            <h6>Active Users</h6>
            
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="align-middle" style="width: 1%;">ID</th>
                        <th class="align-middle" style="width: 15%;">Username</th>
                        <th class="align-middle" style="width: 15%;">Name</th>
                        <th class="align-middle" style="width: 15%;">Account Type</th>
                        <th class="align-middle" style="width: 15%;">Status</th>
                        <th class="align-middle" style="width: 20%;">Added By</th>
                        <th class="align-middle" style="width: 20%;">Added On</th>
                        <th class="align-middle" style="width: 1%;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($activeus as $ausr){?>
                    <tr>
                        <td><?= $ausr['user_id']; ?></td>
                        <td><?= $ausr['username']; ?></td>
                        <td><?= $ausr['name']; ?></td>
                        <td><?= $ausr['accounttype']; ?></td>
                        <td><?php if($ausr['status'] == 'active'){ echo '<span class="badge badge-success">Active</span>'; } else { echo '<span class="badge badge-danger">Inactive</span>'; } ?></td>
                        <td><?= $ausr['added_by']; ?></td>
                        <td><?= $ausr['added_on']?></td>
                        <td>
                            <div class="nowrap">
                                <button type="button" class="btn btn-primary btn-icon btn-sm load-data" data-toggle="modal" data-target="#modaledit" data-user-id="<?= str_ireplace(['/','+'],['~','$'],$encrypter->encrypt($ausr['user_id']))?>"><i class="fas fa-edit"></i></button>
                            </div>
                        </td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
            
        </div>
        <!-- END OF 2ND TAB -->
        <!-- START OF 3RD TAB -->
        <div class="tab-pane fade" id="inactiveusers" role="tabpanel" aria-labelledby="inactiveusers-tab">
            <h6>Inactive Users</h6>
            

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="align-middle" style="width: 1%;">ID</th>
                        <th class="align-middle" style="width: 15%;">Username</th>
                        <th class="align-middle" style="width: 15%;">Name</th>
                        <th class="align-middle" style="width: 15%;">Account Type</th>
                        <th class="align-middle" style="width: 15%;">Status</th>
                        <th class="align-middle" style="width: 20%;">Added By</th>
                        <th class="align-middle" style="width: 20%;">Added On</th>
                        <th class="align-middle" style="width: 1%;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($inactiveus as $iausr){?>
                    <tr>
                        <td><?= $iausr['user_id']; ?></td>
                        <td><?= $iausr['username']; ?></td>
                        <td><?= $iausr['name']; ?></td>
                        <td><?= $iausr['accounttype']; ?></td>
                        <td><?php if($iausr['status'] == 'active'){ echo '<span class="badge badge-success">Active</span>'; } else { echo '<span class="badge badge-danger">Inactive</span>'; } ?></td>
                        <td><?= $iausr['added_by']; ?></td>
                        <td><?= $iausr['added_on']?></td>
                        <td>
                            <div class="nowrap">
                                <button type="button" class="btn btn-primary btn-icon btn-sm load-data" data-toggle="modal" data-target="#modaledit" data-user-id="<?= str_ireplace(['/','+'],['~','$'],$encrypter->encrypt($iausr['user_id']))?>"><i class="fas fa-edit"></i></button>
                            </div>
                        </td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
            
        </div>
        <!-- END OF 3RD TAB -->

    </div>


       
    
</div>



<!-- MODAL EDIT -->
<!-- Modal -->
<div class="modal fade" id="modaledit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit supplier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                <form id="myform" action="" method="post">
                
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label class="d-block">Name</label>
                            <input type="text" class="form-control name" name="name" value="" required autofocus>
                        </div>

                        <div class="col-lg-6">
                            <label class="d-block">Username</label>
                            <input class="form-control username" type="text" name="username" value="" id="username" onkeydown="btnhiding()" required>
                            <div id="msg"></div>
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="d-block">Password</label>
                            <input type="password" class="form-control password" name="password" value="" >
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="d-block">Confirm Password</label>
                            <input type="password" class="form-control password" name="confirm-password" value="" >
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="d-block">Contact Number</label>
                            <input type="text" class="form-control contact-number" name="contact-number" value="">
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="d-block">Email Address</label>
                            <input type="email" class="form-control email-address" name="email-address" value="">
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="d-block">User Account Type</label>
                            <select class="form-control" name="user-account-type" required>
                                <option class="accounttypeid" value="" selected>Select Account Type</option>
                                <?php foreach($accounttype as $att){?>
                                    <option value="<?= $encrypter->encrypt($att['user_account_type_id']);?>"><?= $att['name'];?></option>
                                <?php }?>
                            </select>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Status</label>
                                <div class="custom-control custom-switch">
                                    <input value="inactive" name="status" class="custom-control-label sti" type="hidden" id="" />
                                    <input value="active" name="status" type="checkbox" class="custom-control-input sta" id="customSwitch">
                                    <label class="custom-control-label" for="customSwitch">Inactive / Active</label>
                                </div>
                            </div>
                        </div>

                    </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!-- END OF MODAL EDIT -->

<!-- FOR THE DATATABLES -->
<script>

    $('#datatable1').DataTable({
    language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
        lengthMenu: '_MENU_ items/page',
    }
    });

    $('#datatable2').DataTable({
    language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
        lengthMenu: '_MENU_ items/page',
    }
    });

</script>

<script>
    //ajax function for uservalidation
    $(document).ready(function() {
        $("#username").on("input", function(e) {
            $('#msg').hide();
            if ($('#username').val() == null || $('#username').val() == "") {
                $('#msg').show();
                $("#msg").html("Username is a required field.").css("color", "red");
            }else {
                $.ajax({
                    type: 'post',
                    url: "<?= site_url('validateusername');//site_url('user/check_username_availability') ?>",
                    data: JSON.stringify({username: $('#username').val()}),
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'html',
                    cache: false,
                    beforeSend: function (f) {
                        $('#msg').show();
                        $('#msg').html('Checking...');
                    },
                    success: function(msg) {
                        $('#msg').show();
                        $("#msg").html(msg);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $('#msg').show();
                        $("#msg").html(textStatus + " " + errorThrown);
                    }
                });
            }
        });



        $(".load-data").on("click", function() {
            // Show the modal
            var uID = $(this).data('user-id');
            // Fetch data using AJAX

            $.ajax({
                url: "<?= site_url('users/edit/')?>" + uID,  // Replace with your actual data endpoint URL
                method: "GET",
                dataType: 'json',
                success: function(data) {
                
                    // Populate the modal body with the fetched data
                    $(".name").val(data[0].name);
                    $(".username").val(data[0].username);
                    $(".password").val(data[0].password);
                    $(".contact-number").val(data[0].contact_number);
                    $(".email-address").val(data[0].email_address);
                    $(".accounttypeid").val(data[0].accounttypeid);
                    $(".accounttypeid").html(data[0].accounttypename);
                   // $(".accounttypename").val(data[0].accounttypename);



                    $('#myform').attr('action', "<?= site_url("users/update/");?>" + data[0].userID);

                    if(data[0].status == 'active'){
                        $(".sta").prop('checked', true);
                        $(".sti").prop('checked', false);
                    }else if(data[0].status == 'inactive'){
                        $(".sta").prop('checked', false);
                        $(".sti").prop('checked', true);
                    }

                },
                error: function() {
                    // Handle error if the data fetch fails
                    $(".modal-body").html("Error loading data");
                }
            });

        });



    });
</script>
