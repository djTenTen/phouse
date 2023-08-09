<?php
    $encrypter = \Config\Services::encrypter();
?>
<div class="container">

    <?php
        // Message thrown from the controller
        if(!empty($_SESSION['tenant_added'])){
            echo '<div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Tenant Registration Success!</h4>
            <p>Tenant has been successfully registered</p>
        </div>';
            unset($_SESSION['tenant_added']);
        }  
    ?>

    <h1>Add Tenant</h1>

    <?= form_open("tenant/save")?>
        <div class="row">
            <div class="form-group col-lg-6">
                <label class="d-block">Name</label>
                <input type="text" class="form-control" name="name" value="" required autofocus>
            </div>

            <div class="form-group col-lg-6">
                <label class="d-block">Category</label>
                <select name="category" class="custom-select" required>
                    <option value="" selected>Select Category</option>
                    <?php foreach($category as $cate){?>
                        <option value="<?= $encrypter->encrypt($cate['category_id'])?>"><?= $cate['name']?></option>
                    <?php }?>
                </select>
            </div>

            <div class="form-group col-lg-6">
                <label class="d-block">Facebook</label>
                <div class="input-group mg-b-10">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fab fa-facebook-f"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control" name="facebook" value="">
                </div>
            </div>

            <div class="form-group col-lg-6">
                <label class="d-block">Instagram</label>
                <div class="input-group mg-b-10">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fab fa-instagram"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control" name="instagram" value="">
                </div>
            </div>

            <div class="form-group col-lg-6">
                <label class="d-block">Contact Person</label>
                <input type="text" class="form-control" name="contact-person" value="" required>
            </div>

            <div class="form-group col-lg-6">
                <label class="d-block">Contact Number</label>
                <input type="text" class="form-control" name="contact-number" value="" required>
            </div>

            <div class="form-group col-lg-6">
                <label class="d-block">Email Address</label>
                <input type="text" class="form-control" name="email-address" value="">
            </div>

        </div>

        <div class="mg-t-20">
            <button class="btn btn-primary mg-r-15" name="submit" type="submit">Register Tenant</button>
        </div>
    </form>

</div>