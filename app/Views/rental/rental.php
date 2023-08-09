
<?php 
    $rental_model = new \App\Models\Rental_model;
    $category = array();
    foreach($cate as $ctg){
        $category[$ctg['category_id']] = array("name" => $ctg['name'], "count" => 0);
    }
?>
<div class="container">
    
    <h1>Rental Report</h1>

    <div id="rental-report-page">
        <div class="mg-b-30">
            <form method="get" class="row no-confirm">
                <div class="col-lg-4">
                    <select class="form-control custom-select" name="month">
                        <?php
                            $ctrl = "January 1, 2022";
                            while(date('Y-m-d', strtotime($ctrl)) <= date('Y-m-d', strtotime("today +12 months"))){
                                echo date("Y-m", strtotime($ctrl)) . " - " . date("Y-m", strtotime($month));
                        ?>
                        <option value="<?php echo date("Y-m", strtotime($ctrl)); ?>"<?php if(date("Y-m", strtotime($ctrl)) == date("Y-m", strtotime($month))){ echo ' selected'; } ?>><?php echo date('F 1, Y', strtotime($ctrl)); ?> - <?php echo date('F t, Y', strtotime($ctrl)); ?></option>
                        <?php
                                $ctrl = date('Y-m-d', strtotime($ctrl . " +1 month"));
                            }
                        ?>
                    </select>
                </div>
                <div class="col-lg-2"><button type="submit" class="btn btn-primary btn-block">Submit</button></div>
            </form>
        </div>

        <ul class="nav nav-tabs" role="tablist">
            <?php
                $ctrl = 0;
                $branches = array();
                foreach($branch as $b){
                    if($b['name'] == 'Office'){ continue;}
                    $b['shortcode'] = str_replace(array(" ", ",", "."), "-", (strtolower($b['name'])));
                    array_push($branches, $b);
            ?>
                    <li class="nav-item"><a class="nav-link<?php if($ctrl == 0){ echo ' active'; } ?>" data-toggle="tab" href="#tab-<?= $b['shortcode']; ?>" role="tab" aria-controls="<?= $b['shortcode']; ?>" aria-selected="true"><?= $b['name']; ?></a></li>
            <?php
                    $ctrl++;
                }
            ?>
        </ul>
        <div class="tab-content bd bd-gray-300 bd-t-0 pd-20">
            <?php
                foreach($branches as $i => $br){
                    if($br['name'] == 'Office'){ continue;}
            ?>
            <div class="tab-pane fade<?php if($i == 0){ echo ' show active'; } ?>" id="tab-<?= $br['shortcode']; ?>" role="tabpanel" aria-labelledby="tab-<?= $br['shortcode']; ?>">
                <?php
                    foreach($category as $i => $cat){
                        $category[$i]['count'] = 0;
                    }
                ?>
                <div class="row">
                    <div class="col-lg-8">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 1%;"></th>
                                    <th style="width: 25%;">Tenant Name</th>
                                    <th style="width: 50%;">Duration</th>
                                    <th style="width: 25%;">Monthly Rent</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $occupied = 0; 

                                    $decodedData = json_decode($br['slot']);

                                    if ($decodedData === null && json_last_error() !== JSON_ERROR_NONE) {
                                        // JSON is not valid or decoding failed
                                        echo "Invalid JSON format or decoding failed.";
                                        
                                    } else {
                                        $slot = json_decode($br['slot'], true);
                                        $regular = 0;
                                        $contract = 0;

                                        foreach($slot['slots'] as $i => $val){
                                            $status = "";
                                            $regular += (int)$val;
                                            $tenants = array();
                                            $collected = 0;

                                            foreach($rental_model->getRents($i,$br['branch_id'],date('Y-m-t', strtotime($month))) as $res){

                                                array_push($tenants, $res);

                                                $collected += $res['monthly_rate'];
                                                $contract += $res['monthly_rate'];

                                                $category[$res['category_id']]['count'] += 1;

                                                $curr = 0;

                                                while($res['duration'] >= $curr){
                                                    $curr++;
                                                    if(date('Y-m-16', strtotime($month)) < date('Y-m-16', strtotime( $res['start']."+".$curr." month"))){
                                                        break;
                                                    }
                                                }

                                                $clearing = 0;
                                                $completed = 0;

                                                $paid = $rental_model->getRentTotal("month-".$curr,$res['category_id']);
                                                if($paid != null){
                                                    if($paid['status'] == 'clearing'){
                                                        $clearing += $paid['total'];
                                                    } else {
                                                        $completed += $paid['total'];
                                                    }
                                                }
                                            
                                                if($completed >= $res['monthly_rate']){
                                                    $status = '';
                                                } else if($clearing >= $res['monthly_rate']){
                                                    $status = 'warning';
                                                } else {
                                                    $status = 'danger';
                                                }
                                            }
                                            

                                            if(count($tenants) > 0){ $occupied++; }
                                ?>
                                <tr class="alert alert-<?= $status; ?>">
                                    <td class="text-center"><?= $i; ?></td>
                                    <td><?php foreach($tenants as $x => $tenant){ if($x > 0){ echo "<br>"; } echo '<a data-id="'.$tenant['contract_id'].'" class="data-information">' . $tenant['tenant'] . "</a>"; } ?></td>
                                    <td><?php foreach($tenants as $x => $tenant){ if($x > 0){ echo "<br>"; } echo date_format(date_create($tenant['start']),"M. d, Y"). " - " . date_format(date_create($tenant['end']),"M. d, Y") . " <strong>[" . $tenant['duration'] . " Month/s]</strong>"; } ?></td>
                                    <td class="text-right"><?= number_format($collected, 2); ?></td>
                                </tr>
                                <?php
                                        }
                                        $percentage = ($occupied / count($slot['slots'])) * 100;
                                    }
                                ?>

                            </tbody>
                        </table>
                    </div>

                    <div class="col-lg-4">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>Regular Rates</td>
                                    <td class="text-right"><?= number_format($regular, 2); ?></td>
                                </tr>

                                <tr>
                                    <td>Receivables</td>
                                    <td class="text-right"><?= number_format($contract, 2); ?></td>
                                </tr>

                                <tr>
                                    <td>Net Income</td>
                                    <td class="text-right"><?= number_format(($contract - $regular), 2); ?></td>
                                </tr>
                            </tbody>
                        </table>
                        

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td colspan="3">Category</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach($category as $cat){ ?>
                                <tr>
                                    <td style="width: 50%;"><?= $cat['name']; ?></td>
                                    <td class="text-center" style="width: 25%;"><?= $cat['count']; ?></td>
                                    <td class="text-center" style="width: 25%;"><?= number_format(($cat['count'] / count($slot['slots'])) * 100, 2); ?>%</td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td class="text-right">Total</td>
                                    <td class="text-center"><?= $occupied; ?> / <?= count($slot['slots']); ?></td>
                                    <td class="text-center"><?= number_format($percentage, 2); ?>%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
            <?php
                }
            ?>
        </div>
    </div>

</div>