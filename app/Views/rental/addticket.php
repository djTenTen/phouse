<?php
    $encrypter = \Config\Services::encrypter();
?>
<div class="container">

    <?php
        // Message thrown from the controller
        if(!empty($_SESSION['ticket_added'])){
            echo '<div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Ticket Registration Success!</h4>
            <p>Ticket has been successfully registered</p>
        </div>';
            unset($_SESSION['ticket_added']);
        }  
    ?>

    <h1>Add Ticket</h1>

    <?= form_open("ticket/save")?>
        <div class="row">
            <div class="form-group col-lg-4">
                <label class="d-block">Tenant</label>
                <select class="custom-select" name="tenant" required>
                    <option value="" selected>Select Tenant</option>
                    <?php foreach($tenants as $t){?>
                    <option value="<?= $encrypter->encrypt($t['tenant_id']); ?>"><?= $t['name']; ?></option>
                    <?php }?>
                </select>
            </div>

            <div class="form-group col-lg-8">
                <label class="d-block">Branch of concern</label>
                <div class="col-form-label">
                    <?php foreach($branch as $b){?>
                        <div class="custom-control custom-checkbox d-inline-block mg-r-10">
                            <input type="checkbox" class="custom-control-input" name="branch[]" id="branch-<?= $b['name']; ?>" value="<?= $encrypter->encrypt($b['branch_id']); ?>">
                            <label class="custom-control-label" for="branch-<?= $b['name']; ?>"><?= $b['name']; ?></label>
                        </div>
                    <?php }?>
                </div>
            </div>

            <div class="form-group col-lg-4">
                <label class="d-block">Category</label>
                <select name="category" class="custom-select" required>
                    <option value="" selected>Select Category</option>
                    <?php foreach($category as $c){?>
                        <option value="<?= $encrypter->encrypt($c['support_category_id']); ?>"><?= $c['name']; ?></option>
                    <?php }?>
                </select>
            </div>

            <div class="form-group col-lg-8">
                <label class="d-block">Subject</label>
                <input type="text" class="form-control" name="subject" value="" required>
            </div>

            <div class="form-group col-lg-12">
                <label class="d-block">Main Concern</label>
                <textarea class="form-control" rows="20" id="concern" name="concern"></textarea>
            </div>
        </div>

        <div class="mg-t-20">
            <button class="btn btn-primary mg-r-15" name="submit" type="submit">Add Ticket</button>
        </div>
    </form>


</div>