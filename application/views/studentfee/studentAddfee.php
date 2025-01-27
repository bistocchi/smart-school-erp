<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
$language = $this->customlib->getLanguage();
$language_name = $language["short_code"];
?>
<input type="hidden" value="<?php echo $student['id']; ?>" name="student_user_id" />
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <section class="content-header">
                <h1>
                    <i class="fa fa-money"></i> <?php echo $this->lang->line('fees_collection'); ?><small><?php echo $this->lang->line('student_fee'); ?></small>
                </h1>
            </section>

        </div>
        <div>
            <a id="sidebarCollapse" class="studentsideopen"><i class="fa fa-navicon"></i></a>
            <aside class="studentsidebar">
                <div class="stutop" id="">
                    <!-- Create the tabs -->
                    <div class="studentsidetopfixed">
                        <p class="classtap"><?php echo $student["class"]; ?> <a href="#" data-toggle="control-sidebar" class="studentsideclose"><i class="fa fa-times"></i></a></p>
                        <ul class="nav nav-justified studenttaps">
                            <?php foreach ($class_section as $skey => $svalue) {
                            ?>
                                <li <?php
                                    if ($student["section_id"] == $svalue["section_id"]) {
                                        echo "class='active'";
                                    }
                                    ?>><a href="#section<?php echo $svalue["section_id"] ?>" data-toggle="tab"><?php print_r($svalue["section"]); ?></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <?php foreach ($class_section as $skey => $snvalue) {
                        ?>
                            <div class="tab-pane <?php
                                                    if ($student["section_id"] == $snvalue["section_id"]) {
                                                        echo "active";
                                                    }
                                                    ?>" id="section<?php echo $snvalue["section_id"]; ?>">
                                <?php
                                foreach ($studentlistbysection as $stkey => $stvalue) {
                                    if ($stvalue['section_id'] == $snvalue["section_id"]) {
                                ?>
                                        <div class="studentname">
                                            <a class="" href="<?php echo base_url() . "studentfee/addfee/" . $stvalue["id"] ?>">
                                                <div class="icon"><img src="<?php echo base_url() . $stvalue["image"]; ?>" alt="User Image"></div>
                                                <div class="student-tittle"><?php echo $stvalue["firstname"] . " " . $stvalue["lastname"]; ?></div>
                                            </a>
                                        </div>
                                <?php
                                    }
                                }
                                ?>
                            </div>
                        <?php } ?>
                        <div class="tab-pane" id="sectionB">
                            <h3 class="control-sidebar-heading">Recent Activity 2</h3>
                        </div>

                        <div class="tab-pane" id="sectionC">
                            <h3 class="control-sidebar-heading">Recent Activity 3</h3>
                        </div>
                        <div class="tab-pane" id="sectionD">
                            <h3 class="control-sidebar-heading">Recent Activity 3</h3>
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                </div>
            </aside>
        </div>
    </div>
    <!-- /.control-sidebar -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-4">
                                <h3 class="box-title"><?php echo $this->lang->line('student_fees'); ?></h3>
                            </div>
                            <div class="col-md-8">
                                <div class="btn-group pull-right">
                                    <a href="<?php echo base_url() ?>studentfee" type="button" class="btn btn-primary btn-xs">
                                        <i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?></a>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!--./box-header-->
                    <div class="box-body" style="padding-top:0;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="sfborder">
                                    <div class="col-md-2">
                                        <img width="115" height="115" class="round5" src="<?php
                                                                                            if (!empty($student['image'])) {
                                                                                                echo base_url() . $student['image'];
                                                                                            } else {
                                                                                                echo base_url() . "uploads/student_images/no_image.png";
                                                                                            }
                                                                                            ?>" alt="No Image">
                                    </div>

                                    <div class="col-md-10">
                                        <div class="row">
                                            <table class="table table-striped mb0 font13">
                                                <tbody>
                                                    <tr>
                                                        <th class="bozero"><?php echo $this->lang->line('name'); ?></th>
                                                        <td class="bozero"><?php echo $student['firstname'] . " " . $student['lastname'] ?></td>

                                                        <th class="bozero"><?php echo $this->lang->line('class_section'); ?></th>
                                                        <td class="bozero"><?php echo $student['class'] . " (" . $student['section'] . ")" ?> </td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('guardian_name'); ?></th>
                                                        <td><?php echo $student['guardian_name']; ?></td>
                                                        <th><?php echo $this->lang->line('admission_no'); ?></th>
                                                        <td><?php echo $student['admission_no']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('mobile_no'); ?></th>
                                                        <td><?php echo $student['mobileno']; ?></td>
                                                        <th><?php echo $this->lang->line('roll_no'); ?></th>
                                                        <td> <?php echo $student['roll_no']; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('category'); ?></th>
                                                        <td>
                                                            <?php
                                                            foreach ($categorylist as $value) {
                                                                if ($student['category_id'] == $value['id']) {
                                                                    echo $value['category'];
                                                                }
                                                            }
                                                            ?>
                                                        </td>
                                                        <?php if ($sch_setting->rte) { ?>
                                                            <th><?php echo $this->lang->line('rte'); ?></th>
                                                            <td><b class="text-danger"> <?php echo $student['rte']; ?> </b>
                                                            </td>
                                                        <?php } ?>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            Ano
                                                        </th>
                                                        <td>
                                                            <?php

                                                            echo form_dropdown('year_fees', $optionsYear, [$current_year], 'class="form-control"');
                                                            ?>
                                                        </td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="col-md-12">
                                <div style="background: #dadada; height: 1px; width: 100%; clear: both; margin-bottom: 10px;"></div>
                            </div>
                        </div>
                        <div class="row no-print">
                            <div class="col-md-12 mDMb10">
                                <a href="#" class="btn btn-sm btn-info printSelected"><i class="fa fa-print"></i> <?php echo $this->lang->line('print_selected'); ?> </a>

                                <button type="button" class="btn btn-sm btn-warning collectSelected" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait.."><i class="fa fa-money"></i> <?php echo $this->lang->line('collect') . " " . $this->lang->line('selected') ?></button>


                                <button type="button" class="btn btn-sm btn-secondary billetSelected" id="load_billet" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processando.."><i class="fa fa-barcode"></i> <?php echo $this->lang->line('billet_new') . " " . $this->lang->line('selected') ?></button>
                                <button type="button" class="btn btn-sm btn-danger billetCancelSelected" id="load_billet_cancel" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processando.."><i class="fa fa-ban"></i> <?php echo $this->lang->line('billet_cancel') . " " . $this->lang->line('selected') ?></button>
                                <span class="pull-right"><?php echo $this->lang->line('date'); ?>: <?php echo date($this->customlib->getSchoolDateFormat()); ?></span>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <div class="download_label "><?php echo $this->lang->line('student_fees') . ": " . $student['firstname'] . " " . $student['lastname'] ?> </div>
                            <table class="table table-striped table-bordered table-hover example table-fixed-header">
                                <thead class="header">
                                    <tr>
                                        <th style="width: 10px"><input type="checkbox" id="select_all" /></th>
                                        <th align="left"><?php echo $this->lang->line('fees_item_code'); ?></th>
                                        <th align="left"><?php echo $this->lang->line('fees_group'); ?></th>
                                        <!-- <th align="left"><?php echo $this->lang->line('fees_code'); ?></th> -->
                                        <th align="left" class="text text-left"><?php $this->lang->line('due_date'); ?></th>
                                        <th align="left" class="text text-left"><?php echo $this->lang->line('status'); ?></th>
                                        <th class="text text-right"><?php echo $this->lang->line('amount') ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <!-- <th class="text text-left"><?php echo $this->lang->line('payment_id'); ?></th> -->
                                        <th class="text text-left"><?php echo $this->lang->line('mode'); ?></th>
                                        <th class="text text-left"><?php echo $this->lang->line('date'); ?></th>
                                        <th class="text text-right"><?php echo $this->lang->line('discount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th class="text text-right"><?php echo $this->lang->line('fine'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th class="text text-right"><?php echo $this->lang->line('paid'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th class="text text-right"><?php echo $this->lang->line('balance'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th class="text text-right"><?php echo $this->lang->line('action'); ?></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total_amount = 0;
                                    $total_deposite_amount = 0;
                                    $total_fine_amount = 0;
                                    $total_discount_amount = 0;
                                    $total_balance_amount = 0;
                                    $alot_fee_discount = 0;

                                    foreach ($student_due_fee as $key => $fee) {

                                        foreach ($fee->fees as $fee_key => $fee_value) {
                                            $fee_paid = 0;
                                            $fee_discount = 0;
                                            $fee_fine = 0;
                                            $feetype_balance = -1;
                                            $description = $fee_value->name . " (" . $fee_value->type . ")";

                                            // if ($fee_value->billet->count() > 0) {
                                            //     $fee_discount = $fee_value->billet->first()->body_json->fee_discount;
                                            // }
                                            if ($fee_value->deposite) {
                                                $fee_deposits = json_decode(($fee_value->deposite->amount_detail));

                                                foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                                                    $fee_paid = $fee_paid + ($fee_deposits_value->amount - $fee_deposits_value->amount_discount) + $fee_deposits_value->amount_fine;
                                                    $fee_discount +=  $fee_deposits_value->amount_discount;
                                                    $fee_fine +=  $fee_deposits_value->amount_fine;
                                                }
                                            }

                                            $total_amount = $total_amount + $fee_value->amount;
                                            $total_discount_amount = $total_discount_amount + $fee_discount;
                                            $total_deposite_amount = $total_deposite_amount + $fee_paid;
                                            $total_fine_amount = $total_fine_amount + $fee_fine;
                                            $feetype_balance = number_format($fee_value->amount, 2) - number_format(($fee_paid + $fee_discount), 2) + $fee_fine;
                                            $total_balance_amount = $total_balance_amount + $feetype_balance;
                                            // $description = $fee_value->name . " (" . $fee_value->type . ")";

                                            // dump([$fee_value->title, $fee_paid, $fee_discount, $fee_value->amount,  $fee_paid + $fee_discount, $feetype_balance]);

                                    ?>
                                            <?php
                                            if ($feetype_balance > 0 && strtotime($fee_value->due_date) < strtotime(date('Y-m-d'))) {
                                            ?>
                                                <tr class="danger font12">
                                                <?php
                                            } else {
                                                ?>
                                                <tr class="dark-gray">
                                                <?php
                                            }
                                                ?>
                                                <td>
                                                    <input <?php echo  $fee_value->deposite ? 'disabled' : '' ?> class="checkbox" type="checkbox" name="fee_checkbox" data-fee_date_payment="<?php echo $fee_value->due_date; ?>" data-fee_fine="<?php echo $fee_fine; ?>" data-fee_is_pdf="<?php echo $fee_value->billet->count() > 0 ? $fee_value->billet->first()->id : 0; ?>" data-fee_title="<?php echo $fee_value->title; ?>" data-fee_amount="<?php echo $fee_value->amount; ?>" data-fee_discount="<?php echo  $fee_discount; ?>" data-fee_master_id="<?php echo $fee_value->id; ?>" data-fee_session_group_id="<?php echo $fee_value->fee_session_group_id; ?>" data-fee_groups_feetype_id="<?php echo $fee_value->feetype_id; ?>" data-fee_line_1="<?php echo $description; ?>" data-fee_line_2="<?php echo $fee_value->code; ?>">
                                                </td>
                                                <td align="left">
                                                    <?php
                                                    echo $fee_value->id;
                                                    if ($fee_value->billet->count() > 0) {
                                                        echo '<br/><small class="text-success">' . $fee_value->billet->first()->bank_bullet_id . '</small>';
                                                    }
                                                    ?>
                                                </td>
                                                <td align="left"><?php
                                                                    echo $fee_value->title;
                                                                    ?></td>
                                                <!-- <td align="left"><?php echo $fee_value->code; ?></td> -->
                                                <td align="left" class="text text-left">

                                                    <?php
                                                    if ($fee_value->due_date == "0000-00-00") {
                                                    } else {

                                                        echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fee_value->due_date));
                                                    }
                                                    ?>
                                                </td>
                                                <td align="left" class="text text-left width85">
                                                    <?php
                                                   
                                                    if ($feetype_balance == 0) {
                                                    ?><span class="label label-success"><?php echo $this->lang->line('paid'); ?>
                                                        </span>
                                                    <?php } else if (!empty($fee_value->deposite)) { ?>
                                                        <span class="label label-warning">
                                                            <?php echo $this->lang->line('partial'); ?></span>
                                                    <?php
                                                    } else { ?>
                                                        <span class="label label-danger">
                                                            <?php echo $this->lang->line('unpaid'); ?>
                                                        </span><?php
                                                            }
                                                                ?>
                                                </td>
                                                <td class="text text-right"><?php echo $fee_value->amountReal; ?></td>

                                                <td class="text text-left"></td>
                                                <td class="text text-left"></td>
                                                <td class="text text-left"></td>
                                                <td class="text text-right"><?php
                                                                            echo (number_format($fee_discount, 2, '.', ''));
                                                                            ?></td>
                                                <td class="text text-right"><?php
                                                                            echo (number_format($fee_fine, 2, '.', ''));
                                                                            ?></td>
                                                <td class="text text-right"><?php
                                                                            echo (number_format($fee_paid, 2, '.', ''));
                                                                            ?></td>
                                                <td class="text text-right"><?php
                                                                            $display_none = "ss-none";
                                                                            if ($feetype_balance > 0) {
                                                                                $display_none = "";

                                                                                echo (number_format($feetype_balance, 2, '.', ''));
                                                                            }
                                                                            ?>

                                                </td>
                                                <td style="width: 40px;">
                                                    <div class="btn-group pull-right">
                                                        <?php if ($fee_value->billet->count() > 0) :
                                                            $hasBillet = $fee_value->billet->first();
                                                            if ($hasBillet->bank_bullet_id == null) : ?>
                                                                <button class="btn btn-xs btn-default" title="<?php echo $this->lang->line('billet_preview_waiting'); ?>"><i class="fa  fa-file-pdf-o text-default"></i> </button>
                                                            <?php
                                                            endif;

                                                            if ($hasBillet->bank_bullet_id != null) : ?>
                                                                <button class="btn btn-xs btn-default preview_billet" data-title="<?php echo $fee_value->title; ?>" data-billet_id="<?php echo $hasBillet->bank_bullet_id;  ?>" data-fee_billet_id="<?php echo $fee_value->id ?>" data-fee_session_group_id="<?php echo $fee_value->fee_session_group_id ?>" data-fee_groups_feetype_id="<?php echo $fee_value->feetype_id ?>" title="<?php echo $this->lang->line('billet_preview'); ?>"><i class="fa  fa-file-pdf-o text-danger"></i> </button>
                                                            <?php

                                                            endif;
                                                            ?>


                                                        <?php endif; ?>
                                                        <?php if ($fee_value->billet->count() == 0) : ?>
                                                            <button type="button" data-student_session_id="<?php echo $fee->student_session_id; ?>" data-student_fees_master_id="<?php echo $fee->id; ?>" data-fee_groups_feetype_id="<?php echo $fee_value->feetype_id; ?>" data-group="<?php echo $fee_value->name; ?>" data-type="<?php echo $fee_value->code; ?>" class="btn btn-xs btn-default myCollectFeeBtn <?php echo $display_none; ?>" title="<?php echo $this->lang->line('add_fees'); ?>" data-toggle="modal" data-target="#myFeesModal"><i class="fa fa-plus"></i></button>


                                                            <button class="btn btn-xs btn-default printInv" 
                                                                    data-fee_master_id="<?php echo $fee_value->id ?>" 
                                                                    data-fee_session_group_id="<?php echo $fee_value->fee_session_group_id ?>" data-fee_groups_feetype_id="<?php echo $fee_value->feetype_id ?>" title="<?php echo $this->lang->line('print'); ?>"><i class="fa fa-print"></i> </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td style="width:20px;">
                                                    <?php if ($fee_value->billet->count() == 0  && !$fee_value->deposite) : ?>
                                                        <button class="btn btn-xs btn-default delete-item" data-fee_item_id="<?php echo $fee_value->id ?>" title="<?php echo $this->lang->line('fee_item_delete'); ?>"><i class="fa fa-trash"></i> </button>
                                                    <?php endif; ?>
                                                </td>


                                                </tr>


                                                <?php
                                                if (($fee_value->deposite)) {

                                                    $fee_deposits = json_decode(($fee_value->deposite->amount_detail));


                                                    foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                                                ?>
                                                        <tr class="white-td">

                                                            <td align="left"></td>
                                                            <td align="left"></td>
                                                            <td align="left"></td>
                                                            <td align="left"></td>
                                                            <td class="text-right"><img src="<?php echo base_url(); ?>backend/images/table-arrow.png" alt="" /></td>
                                                            <td class="text text-left">


                                                                <a href="#" data-toggle="popover" class="detail_popover"> <?php echo $fee_value->student_fees_deposite_id . "/" . $fee_deposits_value->inv_no; ?></a>
                                                                <div class="fee_detail_popover" style="display: none">
                                                                    <?php
                                                                    if ($fee_deposits_value->description == "") {
                                                                    ?>
                                                                        <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                                    <?php
                                                                    } else {
                                                                    ?>
                                                                        <p class="text text-info"><?php echo $fee_deposits_value->description; ?></p>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </div>


                                                            </td>

                                                            <td class="text text-left"><?php

                                                                                        echo $this->lang->line(strtolower($fee_deposits_value->payment_mode)); ?></td>
                                                            <td class="text text-left">

                                                                <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fee_deposits_value->date)); ?>
                                                            </td>
                                                            <td class="text text-right"><?php echo (number_format($fee_deposits_value->amount_discount, 2, '.', '')); ?></td>
                                                            <td class="text text-right"><?php echo (number_format($fee_deposits_value->amount_fine, 2, '.', '')); ?></td>
                                                            <td class="text text-right"><?php echo (number_format($fee_deposits_value->amount, 2, '.', '')); ?></td>
                                                            <td></td>
                                                            <td class="text text-right">
                                                                <div class="btn-group pull-right">

                                                                    <?php if ($this->rbac->hasPrivilege('collect_fees', 'can_delete')) { ?>
                                                                        <?php if ($fee_value->deposite && $fee_value->billet->count() == 0) : ?>
                                                                            <button class="btn btn-default btn-xs" data-invoiceno="<?php echo $fee_value->id; ?>" data-main_invoice="<?php echo $fee_value->deposite->id ?>" data-toggle="modal" data-target="#confirm-delete" title="<?php echo $this->lang->line('revert'); ?>">
                                                                                <i class="fa fa-undo"> </i>
                                                                            </button>
                                                                        <?php endif; ?>
                                                                    <?php } ?>
                                                                    <button class="btn btn-xs btn-default printDoc" 
                                                                            data-feed_id="<?php echo $fee_value->id ?>" 
                                                                            data-main_invoice="<?php echo $fee_value->student_fees_deposite_id ?>" 
                                                                            data-sub_invoice="<?php echo $fee_deposits_value->inv_no ?>" 
                                                                            title="<?php echo $this->lang->line('print'); ?>"><i class="fa fa-print"></i> </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                <?php
                                                    }
                                                }
                                                ?>
                                        <?php
                                        }
                                    }
                                        ?>
                                        <?php
                                        if (!empty($student_discount_fee)) {

                                            foreach ($student_discount_fee as $discount_key => $discount_value) {
                                        ?>
                                                <tr class="dark-light">
                                                    <td></td>
                                                    <td align="left"> <?php echo $this->lang->line('discount'); ?> </td>
                                                    <td align="left">
                                                        <?php echo $discount_value['code']; ?>
                                                    </td>
                                                    <td align="left"></td>
                                                    <td align="left" class="text text-left">
                                                        <?php
                                                        if ($discount_value['status'] == "applied") {
                                                        ?>
                                                            <a href="#" data-toggle="popover" class="detail_popover">

                                                                <?php echo $this->lang->line('discount_of') . " " . $currency_symbol . $discount_value['amount'] . " " . $this->lang->line($discount_value['status']) . " : " . $discount_value['payment_id']; ?>

                                                            </a>
                                                            <div class="fee_detail_popover" style="display: none">
                                                                <?php
                                                                if ($discount_value['student_fees_discount_description'] == "") {
                                                                ?>
                                                                    <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                                <?php
                                                                } else {
                                                                ?>
                                                                    <p class="text text-danger"><?php echo $discount_value['student_fees_discount_description'] ?></p>
                                                                <?php
                                                                }
                                                                ?>

                                                            </div>
                                                        <?php
                                                        } else {
                                                            echo '<p class="text text-danger">' . $this->lang->line('discount_of') . " " . $currency_symbol . $discount_value['amount'] . " " . $this->lang->line($discount_value['status']);
                                                        }
                                                        ?>

                                                    </td>
                                                    <td></td>
                                                    <td class="text text-left"></td>
                                                    <td class="text text-left"></td>
                                                    <td class="text text-left"></td>
                                                    <td class="text text-right">
                                                        <?php
                                                        $alot_fee_discount = $alot_fee_discount;
                                                        ?>
                                                    </td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>
                                                        <div class="btn-group pull-right">
                                                            <?php
                                                            if ($discount_value['status'] == "applied") {
                                                            ?>

                                                                <?php if ($this->rbac->hasPrivilege('collect_fees', 'can_delete')) { ?>
                                                                    <button class="btn btn-default btn-xs" data-discounttitle="<?php echo $discount_value['code']; ?>" data-discountid="<?php echo $discount_value['id']; ?>" data-toggle="modal" data-target="#confirm-discountdelete" title="<?php echo $this->lang->line('revert'); ?>">
                                                                        <i class="fa fa-undo"> </i>
                                                                    </button>
                                                            <?php
                                                                }
                                                            }
                                                            ?>

                                                            <button type="button" data-modal_title="<?php echo $this->lang->line('discount') . " : " . $discount_value['code']; ?>" data-student_fees_discount_id="<?php echo $discount_value['id']; ?>" class="btn btn-xs btn-default applydiscount" title="<?php echo $this->lang->line('apply_discount'); ?>"><i class="fa fa-check"></i>
                                                            </button>

                                                        </div>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>


                                        <tr class="box box-solid total-bg">
                                            <td align="left"></td>
                                            <td align="left"></td>
                                            <td align="left"></td>
                                            <td align="left"></td>
                                            <td align="left" class="text text-left"><?php echo $this->lang->line('grand_total'); ?></td>
                                            <td class="text text-right"><?php
                                                                        echo ($currency_symbol . number_format($total_amount, 2, ',', '.'));
                                                                        ?></td>
                                            <td class="text text-left"></td>
                                            <td class="text text-left"></td>
                                            <td class="text text-left"></td>

                                            <td class="text text-right"><?php
                                                                        echo sprintf('%s %s', $currency_symbol, number_format($total_discount_amount + $alot_fee_discount, 2, ',', '.'));
                                                                        ?></td>
                                            <td class="text text-right"><?php
                                                                        echo sprintf('%s %s', $currency_symbol, number_format($total_fine_amount, 2, ',', '.'));
                                                                        ?></td>
                                            <td class="text text-right"><?php
                                                                        echo sprintf('%s %s', $currency_symbol, number_format($total_deposite_amount, 2, ',', '.'));
                                                                        ?></td>
                                            <td class="text text-right"><?php
                                                                        echo sprintf('%s %s', $currency_symbol, number_format($total_balance_amount - $alot_fee_discount, 2, ',', '.'));
                                                                        ?></td>
                                            <td class="text text-right"></td>
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>


            </div>
            <!--/.col (left) -->

        </div>

    </section>

</div>


<div class="modal fade" id="myFeesModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title title text-center fees_title"></h4>
            </div>
            <div class="modal-body pb0">
                <div class="form-horizontal balanceformpopup">
                    <div class="box-body">

                        <input type="hidden" class="form-control" id="std_id" value="<?php echo $student["student_session_id"]; ?>" readonly="readonly" />
                        <input type="hidden" class="form-control" id="parent_app_key" value="<?php echo $student['parent_app_key'] ?>" readonly="readonly" />
                        <input type="hidden" class="form-control" id="guardian_phone" value="<?php echo $student['guardian_phone'] ?>" readonly="readonly" />
                        <input type="hidden" class="form-control" id="guardian_email" value="<?php echo $student['guardian_email'] ?>" readonly="readonly" />
                        <input type="hidden" class="form-control" id="student_fees_master_id" value="0" readonly="readonly" />
                        <input type="hidden" class="form-control" id="fee_groups_feetype_id" value="0" readonly="readonly" />
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label"><?php echo $this->lang->line('date'); ?></label>
                            <div class="col-sm-9">
                                <input id="date" name="admission_date" placeholder="" type="text" class="form-control date_fee" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" readonly="readonly" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label"><?php echo $this->lang->line('amount'); ?><small class="req"> *</small></label>
                            <div class="col-sm-9">

                                <input type="text" autofocus="" class="form-control modal_amount" id="amount" value="0">

                                <span class="text-danger" id="amount_error"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label"> <?php echo $this->lang->line('discount'); ?> <?php echo $this->lang->line('group'); ?></label>
                            <div class="col-sm-9">
                                <select class="form-control modal_discount_group" id="discount_group">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                </select>

                                <span class="text-danger" id="amount_error"></span>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label"><?php echo $this->lang->line('discount'); ?><small class="req"> *</small></label>
                            <div class="col-sm-9">
                                <div class="row">
                                    <div class="col-md-5 col-sm-5">
                                        <div class="">
                                            <input type="text" class="form-control" id="amount_discount" value="0">

                                            <span class="text-danger" id="amount_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 ltextright">

                                        <label for="inputPassword3" class="control-label"><?php echo $this->lang->line('fine'); ?><small class="req">*</small></label>
                                    </div>
                                    <div class="col-md-5 col-sm-5">
                                        <div class="">
                                            <input type="text" class="form-control" id="amount_fine" value="0">

                                            <span class="text-danger" id="amount_fine_error"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--./col-sm-9-->
                        </div>




                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label"><?php echo $this->lang->line('payment'); ?> <?php echo $this->lang->line('mode'); ?></label>
                            <div class="col-sm-9">
                                <label class="radio-inline">
                                    <input type="radio" name="payment_mode_fee" value="Cash" checked="checked"><?php echo $this->lang->line('cash'); ?>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="payment_mode_fee" value="Cheque"><?php echo $this->lang->line('cheque'); ?>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="payment_mode_fee" value="DD"><?php echo $this->lang->line('dd'); ?>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="payment_mode_fee" value="bank_transfer"><?php echo $this->lang->line('bank_transfer'); ?>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="payment_mode_fee" value="upi"><?php echo $this->lang->line('upi'); ?>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="payment_mode_fee" value="card"><?php echo $this->lang->line('card'); ?>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="payment_mode_fee" value="manual"><?php echo $this->lang->line('manual_payment'); ?>
                                </label>
                                <span class="text-danger" id="payment_mode_error"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label"><?php echo $this->lang->line('note'); ?></label>

                            <div class="col-sm-9">
                                <textarea class="form-control" rows="3" id="description" placeholder=""></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                <button type="button" class="btn cfees save_button" id="load" data-action="collect" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"> <?php echo $currency_symbol; ?> <?php echo $this->lang->line('collect_fees'); ?> </button>
                <button type="button" class="btn cfees save_button" id="load" data-action="print" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"> <?php echo $currency_symbol; ?> <?php echo $this->lang->line('collect') . " & " . $this->lang->line('print') ?></button>

            </div>
        </div>

    </div>
</div>



<div class="modal fade" id="myDisApplyModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title title text-center discount_title"></h4>
            </div>
            <div class="modal-body pb0">
                <div class="form-horizontal">
                    <div class="box-body">
                        <input type="hidden" class="form-control" id="student_fees_discount_id" value="" />
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label"><?php echo $this->lang->line('payment_id'); ?> <small class="req">*</small></label>
                            <div class="col-sm-9">

                                <input type="text" class="form-control" id="discount_payment_id">

                                <span class="text-danger" id="discount_payment_id_error"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label"><?php echo $this->lang->line('description'); ?></label>

                            <div class="col-sm-9">
                                <textarea class="form-control" rows="3" id="dis_description" placeholder=""></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                <button type="button" class="btn cfees dis_apply_button" id="load" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"> <?php echo $this->lang->line('apply_discount'); ?></button>
            </div>
        </div>

    </div>
</div>


<div class="delmodal modal fade" id="confirm-discountdelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('confirmation'); ?></h4>
            </div>

            <div class="modal-body">

                <p>Are you sure want to revert <b class="discount_title"></b> discount, this action is irreversible.</p>
                <p>Do you want to proceed?</p>
                <p class="debug-url"></p>
                <input type="hidden" name="discount_id" id="discount_id" value="">

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                <a class="btn btn-danger btn-discountdel"><?php echo $this->lang->line('revert'); ?></a>
            </div>
        </div>
    </div>
</div>


<div class="delmodal modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('confirmation'); ?></h4>
            </div>

            <div class="modal-body">

                <p>Are you sure want to delete <b class="invoice_no"></b> invoice, this action is irreversible.</p>
                <p>Do you want to proceed?</p>
                <p class="debug-url"></p>
                <input type="hidden" name="main_invoice" id="main_invoice" value="">
                <input type="hidden" name="sub_invoice" id="sub_invoice" value="">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                <a class="btn btn-danger btn-ok"><?php echo $this->lang->line('revert'); ?></a>
            </div>
        </div>
    </div>
</div>


<div class="norecord modal fade" id="confirm-norecord" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">


                <p>No Record Found --r</p>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>

            </div>
        </div>
    </div>
</div>



<div id="listCollectionModal" class="modal fade">
    <div class="modal-dialog">
        <form action="<?php echo site_url('studentfee/addfeegrp'); ?>" method="POST" id="collect_fee_group">
            <input type="hidden" value="<?php echo $student['id']; ?>" name="user_id" />
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php echo $this->lang->line('collect') . " " . $this->lang->line('fees'); ?></h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary payment_collect" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing"><i class="fa fa-money"></i> <?php echo $this->lang->line('pay'); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>



<div id="loadingModal" class="modal fade" style="z-index: 10000;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-loader-body" style="
                display: flex;
                width: 100%;
                margin: 0 15px;
                justify-content: center;
                align-items: center;
                padding: 20px;
             " data-loading="<i class='fa fa-spinner fa-spin '></i>  Aguarde estamos processando..."></div>
        </div>

    </div>
</div>


<div id="listBilletModal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <form method="POST" id="billet_fee_group">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php echo $this->lang->line('billet_generate'); ?></h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary payment_collect_confirm" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing"><i class="fa fa-barcode"></i> <?php echo $this->lang->line('billet_button_generate'); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>



<!-- begin: show Billet PDF-->
<div id="showPrinterPDF" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">
                    <span class="modal-inner-title"></span>
                    <span class="modal-inner-loader pull-right" style="float: right; display: inline-block; padding:5px 15px;" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing"></span>
                </h4>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>
<!-- end: show Billet PDF-->



<script type="text/javascript">
    var base_url = '<?php echo base_url() ?>';
    $(document).ready(function() {
        $(".date_fee").datepicker({
            format: date_format,
            autoclose: true,
            language: '<?php echo $language_name; ?>',
            endDate: '+0d',
            todayHighlight: true
        });

        $('.delete-item').on('click.DeleteFeeItem', function(e) {

            if (!confirm('Deseja realizar essa operacao ? ')) return;
            var fee_item_id = $(this).data('fee_item_id')
            $.ajax({
                url: `${base_url}studentfee/destroyItem/${fee_item_id}`,
                dataType: 'json',
                method: 'post',
                success: function() {
                    window.location.reload();
                }
            })
        })


        $('.preview_billet').on('click.showPDF', function(e) {
            e.preventDefault();
            var $this = $(this)
            var billet_id = $this.data('billet_id')

            var $el = $('#showPrinterPDF').modal({
                backdrop: 'static',
                keyboard: false,
                show: false
            });

            $el.find('.modal-inner-title').html($this.data('title'))
            var $loader = $el.find('.modal-inner-loader');
            //var textLoaderReset = $loader.html()

            $loader.html($loader.data('loading-text'))
            $el.modal('show');
            var $pdf = $('<iframe frameborder="0" width="100%" height="400px">') // create an iframe
            // add the source

            $el.find('.modal-body').html($pdf);

            $.ajax({
                url: `${base_url}studentfee/getBillet/${billet_id}`,
                processData: false,
                success: function(data) {
                    // var myResponse = eval(data);
                    $pdf.attr('src', `${base_url}studentfee/getBillet/${billet_id}`)
                    setTimeout(() => {
                        $loader.html('')
                    }, 2000)



                }
            })



        })

        $(document).on('click', '.printDoc', function() {
             var feed_id = $(this).data('feed_id');
            var main_invoice = $(this).data('main_invoice');
            var sub_invoice = $(this).data('sub_invoice');
            var student_session_id = '<?php echo $student['student_session_id'] ?>';
            $.ajax({
                url: '<?php echo site_url("studentfee/printComprovantePagamento") ?>',
                type: 'post',
                data: {
                    'student_session_id': student_session_id,
                    'main_invoice': main_invoice,
                    'sub_invoice': sub_invoice,
                    'student_fee_id' : feed_id
                },
                success: function(response) {
                    Popup(response);
                }
            });
        });
        $(document).on('click', '.printInv', function() {
            var fee_master_id = $(this).data('fee_master_id');
            var fee_session_group_id = $(this).data('fee_session_group_id');
            var fee_groups_feetype_id = $(this).data('fee_groups_feetype_id');
            $.ajax({
                url: '<?php echo site_url("studentfee/printFeesByGroup") ?>',
                type: 'post',
                data: {
                    'fee_groups_feetype_id': fee_groups_feetype_id,
                    'fee_master_id': fee_master_id,
                    'fee_session_group_id': fee_session_group_id
                },
                success: function(response) {
                    Popup(response);
                }
            });
        });
    });
</script>


<script type="text/javascript">
    $(document).on('click', '.save_button', function(e) {
        var $this = $(this);
        var action = $this.data('action');
        $this.button('loading');
        var form = $(this).attr('frm');
        var feetype = $('#feetype_').val();
        var date = $('#date').val();
        var student_session_id = $('#std_id').val();
        var amount = $('#amount').val();
        var amount_discount = $('#amount_discount').val();
        var amount_fine = $('#amount_fine').val();
        var description = $('#description').val();
        var parent_app_key = $('#parent_app_key').val();
        var guardian_phone = $('#guardian_phone').val();
        var guardian_email = $('#guardian_email').val();
        var student_fees_master_id = $('#student_fees_master_id').val();
        var fee_groups_feetype_id = $('#fee_groups_feetype_id').val();
        var payment_mode = $('input[name="payment_mode_fee"]:checked').val();
        var student_fees_discount_id = $('#discount_group').val();
        $.ajax({
            url: '<?php echo site_url("studentfee/addstudentfee") ?>',
            type: 'post',
            data: {
                'user_id': $('input[name="student_user_id"]').val(),
                action: action,
                student_session_id: student_session_id,
                date: date,
                type: feetype,
                amount: amount,
                amount_discount: amount_discount,
                amount_fine: amount_fine,
                description: description,
                student_fees_master_id: student_fees_master_id,
                fee_groups_feetype_id: fee_groups_feetype_id,
                payment_mode: payment_mode,
                guardian_phone: guardian_phone,
                guardian_email: guardian_email,
                student_fees_discount_id: student_fees_discount_id,
                parent_app_key: parent_app_key
            },
            dataType: 'json',
            success: function(response) {
                $this.button('reset');
                if (response.status === "success") {
                    if (action === "collect") {
                        location.reload(true);
                    } else if (action === "print") {
                        Popup(response.print, true);
                    }
                } else if (response.status === "fail") {
                    $.each(response.error, function(index, value) {
                        var errorDiv = '#' + index + '_error';
                        $(errorDiv).empty().append(value);
                    });
                }
            }
        });
    });
</script>


<script>
    function Popup(data, winload = false) {
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({
            "position": "absolute",
            "top": "-1000000px"
        });
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body>');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function() {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
            if (winload) {
                window.location.reload(true);
            }
        }, 500);


        return true;
    }
    $(document).ready(function() {
        $('.delmodal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
        $('#listCollectionModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });

      

        $('#confirm-delete').on('show.bs.modal', function(e) {
            $('.invoice_no', this).text("");
            $('#main_invoice', this).val("");
            $('#sub_invoice', this).val("");

            $('.invoice_no', this).text($(e.relatedTarget).data('invoiceno'));
            $('#main_invoice', this).val($(e.relatedTarget).data('main_invoice'));
            $('#sub_invoice', this).val($(e.relatedTarget).data('sub_invoice'));


        });

        $('#confirm-discountdelete').on('show.bs.modal', function(e) {
            $('.discount_title', this).text("");
            $('#discount_id', this).val("");
            $('.discount_title', this).text($(e.relatedTarget).data('discounttitle'));
            $('#discount_id', this).val($(e.relatedTarget).data('discountid'));
        });
        var isDeleting = false;
        $('#confirm-delete').off('click', '.btn-ok').on('click', '.btn-ok', function(e) {
            if (isDeleting) return;
            var $modalDiv = $(e.delegateTarget);
            var main_invoice = $('#main_invoice').val();
            var sub_invoice = $('#sub_invoice').val();

            $modalDiv.addClass('modalloading');
            isDeleting = true
            $.ajax({
                type: "post",
                url: '<?php echo site_url("studentfee/deleteFee") ?>',
                dataType: 'JSON',
                data: {
                    'main_invoice': main_invoice,
                    'sub_invoice': sub_invoice
                },
                success: function(data) {
                    $modalDiv.modal('hide').removeClass('modalloading');
                    location.reload(true);
                },
                complete: function() {
                    isDeleting = false;
                }
            });


        });

        $('#confirm-discountdelete').on('click', '.btn-discountdel', function(e) {
            var $modalDiv = $(e.delegateTarget);
            var discount_id = $('#discount_id').val();


            $modalDiv.addClass('modalloading');
            $.ajax({
                type: "post",
                url: '<?php echo site_url("studentfee/deleteStudentDiscount") ?>',
                dataType: 'JSON',
                data: {
                    'discount_id': discount_id
                },
                success: function(data) {
                    $modalDiv.modal('hide').removeClass('modalloading');
                    location.reload(true);
                }
            });


        });


        $(document).on('click', '.btn-ok', function(e) {
            var $modalDiv = $(e.delegateTarget);
            var main_invoice = $('#main_invoice').val();
            var sub_invoice = $('#sub_invoice').val();

            $modalDiv.addClass('modalloading');
            $.ajax({
                type: "post",
                url: '<?php echo site_url("studentfee/deleteFee") ?>',
                dataType: 'JSON',
                data: {
                    'main_invoice': main_invoice,
                    'sub_invoice': sub_invoice
                },
                success: function(data) {
                    $modalDiv.modal('hide').removeClass('modalloading');
                    location.reload(true);
                }
            });


        });
        $('.detail_popover').popover({
            placement: 'right',
            title: '',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function() {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
    });
    var fee_amount = 0;
    var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
</script>
<script type="text/javascript">
    var ibase_url = "<?php echo trim(site_url(), '/'); ?>";
    $(document).ready(function() {
        $('select[name="year_fees"]').change(function() {
            var path = window.location.pathname;
            window.location.href = `${ibase_url}/${path.replace(/^\/+/gi,'')}?due_date=${this.value}`
        });
    })
    $("#myFeesModal").on('shown.bs.modal', function(e) {
        e.stopPropagation();
        var discount_group_dropdown = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        var data = $(e.relatedTarget).data();
        var modal = $(this);
        var type = data.type;
        var amount = data.amount;
        var group = data.group;
        var fee_groups_feetype_id = data.fee_groups_feetype_id;
        var student_fees_master_id = data.student_fees_master_id;
        var student_session_id = data.student_session_id;

        $('.fees_title').html("");
        $('.fees_title').html("<b>" + group + ":</b> " + type);
        $('#fee_groups_feetype_id').val(fee_groups_feetype_id);
        $('#student_fees_master_id').val(student_fees_master_id);



        $.ajax({
            type: "post",
            url: '<?php echo site_url("studentfee/geBalanceFee") ?>',
            dataType: 'JSON',
            data: {
                'fee_groups_feetype_id': fee_groups_feetype_id,
                'student_fees_master_id': student_fees_master_id,
                'student_session_id': student_session_id
            },
            beforeSend: function() {
                $('#discount_group').html("");
                $("span[id$='_error']").html("");
                $('#amount').val("");
                $('#amount_discount').val("0");
                $('#amount_fine').val("0");
                modal.addClass('modal_loading');
            },
            success: function(data) {

                if (data.status === "success") {
                    fee_amount = data.balance;

                    $('#amount').val(data.balance);
                    $('#amount_fine').val(data.remain_amount_fine);


                    $.each(data.discount_not_applied, function(i, obj) {
                        discount_group_dropdown += "<option value=" + obj.student_fees_discount_id + " data-disamount=" + obj.amount + ">" + obj.code + "</option>";
                    });
                    $('#discount_group').append(discount_group_dropdown);




                }
            },
            error: function(xhr) { // if error occured
                alert("Error occured.please try again");

            },
            complete: function() {
                modal.removeClass('modal_loading');
            }
        });


    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $.extend($.fn.dataTable.defaults, {
            searching: false,
            ordering: false,
            paging: false,
            bSort: false,
            info: false
        });
    });
    $(document).ready(function() {
        $('.table-fixed-header').fixedHeader();
    });

    //  $(window).on('resize', function () {
    //    $('.header-copy').width($('.table-fixed-header').width())
    //});

    (function($) {

        $.fn.fixedHeader = function(options) {
            var config = {
                topOffset: 50
                //bgColor: 'white'
            };
            if (options) {
                $.extend(config, options);
            }

            return this.each(function() {
                var o = $(this);

                var $win = $(window);
                var $head = $('thead.header', o);
                var isFixed = 0;
                var headTop = $head.length && $head.offset().top - config.topOffset;

                function processScroll() {
                    if (!o.is(':visible')) {
                        return;
                    }
                    if ($('thead.header-copy').size()) {
                        $('thead.header-copy').width($('thead.header').width());
                    }
                    var i;
                    var scrollTop = $win.scrollTop();
                    var t = $head.length && $head.offset().top - config.topOffset;
                    if (!isFixed && headTop !== t) {
                        headTop = t;
                    }
                    if (scrollTop >= headTop && !isFixed) {
                        isFixed = 1;
                    } else if (scrollTop <= headTop && isFixed) {
                        isFixed = 0;
                    }
                    isFixed ? $('thead.header-copy', o).offset({
                        left: $head.offset().left
                    }).removeClass('hide') : $('thead.header-copy', o).addClass('hide');
                }
                $win.on('scroll', processScroll);

                // hack sad times - holdover until rewrite for 2.1
                $head.on('click', function() {
                    if (!isFixed) {
                        setTimeout(function() {
                            $win.scrollTop($win.scrollTop() - 47);
                        }, 10);
                    }
                });

                $head.clone().removeClass('header').addClass('header-copy header-fixed').appendTo(o);
                var header_width = $head.width();
                o.find('thead.header-copy').width(header_width);
                o.find('thead.header > tr:first > th').each(function(i, h) {
                    var w = $(h).width();
                    o.find('thead.header-copy> tr > th:eq(' + i + ')').width(w);
                });
                $head.css({
                    margin: '0 auto',
                    width: o.width(),
                    'background-color': config.bgColor
                });
                processScroll();
            });
        };

    })(jQuery);


    $(".applydiscount").click(function() {
        $("span[id$='_error']").html("");
        $('.discount_title').html("");
        $('#student_fees_discount_id').val("");
        var student_fees_discount_id = $(this).data("student_fees_discount_id");
        var modal_title = $(this).data("modal_title");


        $('.discount_title').html("<b>" + modal_title + "</b>");

        $('#student_fees_discount_id').val(student_fees_discount_id);
        $('#myDisApplyModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
    });




    $(document).on('click', '.dis_apply_button', function(e) {
        var $this = $(this);
        $this.button('loading');

        var discount_payment_id = $('#discount_payment_id').val();
        var student_fees_discount_id = $('#student_fees_discount_id').val();
        var dis_description = $('#dis_description').val();

        $.ajax({
            url: '<?php echo site_url("admin/feediscount/applydiscount") ?>',
            type: 'post',
            data: {
                discount_payment_id: discount_payment_id,
                student_fees_discount_id: student_fees_discount_id,
                dis_description: dis_description
            },
            dataType: 'json',
            success: function(response) {
                $this.button('reset');
                if (response.status === "success") {
                    location.reload(true);
                } else if (response.status === "fail") {
                    $.each(response.error, function(index, value) {
                        var errorDiv = '#' + index + '_error';
                        $(errorDiv).empty().append(value);
                    });
                }
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        accounting.settings = {
            currency: {
                symbol: "R$ ", // default currency symbol is '$'
                format: "%s%v", // controls output: %s = symbol, %v = value/number (can be object: see below)
                decimal: ",", // decimal point separator
                thousand: ".", // thousands separator
                precision: 2 // decimal places
            },
            number: {
                precision: 0, // default precision on numbers is 0
                thousand: ",",
                decimal: "."
            }
        }



        $(document).on('click', '.printSelected', function() {
            var array_to_print = [];
            $.each($("input[name='fee_checkbox']:checked"), function() {
                var fee_session_group_id = $(this).data('fee_session_group_id');
                var fee_master_id = $(this).data('fee_master_id');
                var fee_groups_feetype_id = $(this).data('fee_groups_feetype_id');
                item = {};
                item["fee_session_group_id"] = fee_session_group_id;
                item["fee_master_id"] = fee_master_id;
                item["fee_groups_feetype_id"] = fee_groups_feetype_id;

                array_to_print.push(item);
            });
            if (array_to_print.length === 0) {
                alert("<?php echo $this->lang->line('no_record_selected'); ?>");
            } else {
                $.ajax({
                    url: '<?php echo site_url("studentfee/printFeesByGroupArray") ?>',
                    type: 'post',
                    data: {
                        'data': JSON.stringify(array_to_print)
                    },
                    success: function(response) {
                        Popup(response);
                    }
                });
            }
        });


        $(document).on('click', '.collectSelected', function() {
            var $this = $(this);
            var array_to_collect_fees = [];
            $.each($("input[name='fee_checkbox']:checked"), function() {
                var fee_session_group_id = $(this).data('fee_session_group_id');
                var fee_master_id = $(this).data('fee_master_id');
                var fee_groups_feetype_id = $(this).data('fee_groups_feetype_id');
                item = {};
                item["fee_session_group_id"] = fee_session_group_id;
                item["fee_master_id"] = fee_master_id;
                item["fee_groups_feetype_id"] = fee_groups_feetype_id;

                array_to_collect_fees.push(item);
            });

            $.ajax({
                type: 'POST',
                url: base_url + "studentfee/getcollectfee",
                data: {
                    'data': JSON.stringify(array_to_collect_fees)
                },
                dataType: "JSON",
                beforeSend: function() {
                    $this.button('loading');
                },
                success: function(data) {

                    $("#listCollectionModal .modal-body").html(data.view);
                    $(".date").datepicker({
                        format: date_format,
                        autoclose: true,
                        language: '<?php echo $language_name; ?>',
                        endDate: '+0d',
                        todayHighlight: true
                    });
                    $("#listCollectionModal").modal('show');
                    $this.button('reset');
                },
                error: function(xhr) { // if error occured
                    alert("Error occured.please try again");

                },
                complete: function() {
                    $this.button('reset');
                }
            });

        });


        // add generate bolete 

        var loading = false;

        function isNotDayUtil() {
            return getDaysExceptions().indexOf(moment().format('YYYY-MM-DD')) >= 0 || [6, 7].indexOf(Number(moment().format('E'))) >= 0
        }

        function sortArray(
            data,
            key,
            sort
        ) {
            return data.sort((a, b) => {

                const keyA = a[key];
                const keyB = b[key];
                let comparison = 0;
                if (keyA > keyB) comparison = sort ? 1 : -1;
                if (keyA < keyB) comparison = sort ? -1 : 1;
                return comparison;
            });
        }

        $(document).on('click', '.billetSelected', function() {
            var $this = $(this);
            var array_to_collect_fees = [],
                listOfItemSelected = [];


            // var hasPaymentOld = [];
            // if( isNotDayUtil() ) {
            //     return alert('Essa operação só pode ser realizada em dias úteis.');
            // }
            var feeGroupsFeetypeId,
                feeSessionGroupId,
                feeMasterId;

            var listOfBilletsGenerated = []
            var listOfBilletOld = []

            $.each($("input[name='fee_checkbox']:checked"), function() {
                var fee_session_group_id = $(this).data('fee_session_group_id');
                var fee_master_id = $(this).data('fee_master_id');
                var fee_groups_feetype_id = $(this).data('fee_groups_feetype_id');

                if (!feeGroupsFeetypeId)
                    feeGroupsFeetypeId = fee_groups_feetype_id

                if (!feeSessionGroupId)
                    feeSessionGroupId = fee_session_group_id

                if (!feeMasterId)
                    feeMasterId = fee_master_id

                item = {};
                item["fee_session_group_id"] = fee_session_group_id;
                item["fee_master_id"] = fee_master_id;
                item["fee_groups_feetype_id"] = fee_groups_feetype_id;
                item["fee_amount"] = $(this).data('fee_amount');
                item["fee_title"] = $(this).data('fee_title');
                item["fee_discount"] = $(this).data('fee_discount');
                item["fee_line_1"] = $(this).data('fee_line_1');
                item["fee_line_2"] = $(this).data('fee_line_2');
                item["fee_date_payment"] = $(this).data('fee_date_payment');
                item["fee_date_payment_old"] = $(this).data('fee_date_payment');


                item["fee_fine"] = $(this).data('fee_fine');
                var existBillet = $(this).data('fee_is_pdf');
                if (existBillet !== 0) {
                    listOfBilletsGenerated.push(item["fee_title"]);
                }


                var date = moment(item["fee_date_payment"]);
                item["fee_date_payment_unix"] = date.unix()

                // console.log(date.format('YYYY-MM-DD' ), moment().format('YYYY-MM-DD' ))

                // console.log(moment().diff(date, 'days'))
                var hasDateOld = moment().diff(date, 'days') > 0;

                item['fee_billet_old'] = hasDateOld;

                if (hasDateOld) listOfBilletOld.push(fee_groups_feetype_id)
                array_to_collect_fees.push(item);

            });
            // console.log(listOfBilletsGenerated)
            // console.log(listOfBilletOld)
            // return;
            if (listOfBilletsGenerated.length > 0) {

                $.notify({
                    // options
                    title: '<b>Já existe boleto(s) criado(s) para o(s) lançamento</b><br/>',
                    message: ` ${listOfBilletsGenerated.join('<br/>')}`
                }, {
                    // settings
                    type: 'danger'
                });
                return;
            }

            if (array_to_collect_fees.length == 0) return $.notify({
                // options
                message: `É necessário selecionar o minimo de 1 item`
            }, {
                // settings
                type: 'info'
            });
            var html = `
                            <div class="row">
                            <div class="form-group">
                            </div>
                                <div class="col-xs-12 form-group">
                                <strong>Alguns itens selecionados, estão com a data vencimento para gerar boleto inválidas. <br/> Será necessário adicionar uma nova data para gerar o boleto</strong>
                                </div>
                                
                                <div class="form-group col-xs-12">
                                        <label for="inputPassword3" class="col-sm-3 control-label"> <?php echo $this->lang->line('billet_due_date'); ?></label>
                                        <div class="col-sm-9">
                                            <div class="col-md-5 col-xs-6 input-group date">
                                                <input type="text" name="fee_date_payment_new" class="form-control">
                                                <div class="input-group-addon">
                                                    <span class="glyphicon glyphicon-th"></span>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                <div class="form-group col-xs-12">
                                        <label for="inputPassword3" class="col-sm-3 control-label"> <?php echo $this->lang->line('discount'); ?></label>
                                        <div class="col-sm-9">
                                            <div class="row">
                                                <div class="col-xs-12"  style="display: flex; ">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="discount_option" id="discount_option_1" value="1">
                                                        <label class="form-check-label"  for="discount_option_1">Percentual %</label>
                                                    </div>
                                                    <div class="form-check form-check-inline" style="margin-left:15px;">
                                                        <input class="form-check-input" checked type="radio" name="discount_option" id="discount_option_2" value="2">
                                                        <label class="form-check-label" for="discount_option_2">Valor</label>
                                                    </div>
                                                </div>
                                                <div class="col-xs-5" >
                                                    <input name="discount_value" value="" class="form-control" placeholder="0.00" />
                                                </div>
                                                
                                            </div>
                                           
                                        </div>
                                </div>
                                <div class="form-group col-xs-12 ">
                                     <table class="table table-bordered">
                                       <thead>
                                          <tr> 
                                          <th>Código</th>
                                          <th>Vencimento</th>
                                            <th>Descricao</th>
                                            <th>Valor</th>
                                            <th>Desconto</th>
                                            <th>Total</th>
                                          </tr>
                                       </thead>
                                       <tbody id="billet_for_generate"></tbody>
                                     </table>
                                </div>
                            </div>

                        `;

            // console.log(listOfBilletOld)
            if (listOfBilletOld.length > 0) {
                $("#listBilletModal .modal-body").html(html);
                setTimeout(function() {
                    array_to_collect_fees = buildListItemsWithDiscount(array_to_collect_fees);
                }, 200)
                var $modal = $("#listBilletModal .modal-footer");

                $("#listBilletModal .modal-body").html(html);
                $(".date").datepicker({
                    format: date_format,
                    autoclose: true,
                    language: '<?php echo $language_name; ?>',
                    startDate: '+0d',
                    todayHighlight: true
                });

                toggleMaskDiscount()

                $('[name="discount_option"]').change(function() {
                    toggleMaskDiscount()
                    array_to_collect_fees = buildListItemsWithDiscount(array_to_collect_fees);
                })
                $('[name="discount_value"]').focusout(function() {
                    array_to_collect_fees = buildListItemsWithDiscount(array_to_collect_fees);
                })

                $("#listBilletModal").modal('show');
                $this.button('reset');
                var fvalidator = $('form#billet_fee_group');
                fvalidator.validate({
                    rules: {
                        fee_date_payment_new: {
                            required: true
                        }
                    }
                });

                $('form#billet_fee_group').on('submit', function(e) {
                    e.preventDefault();
                    if (!fvalidator.valid() || loading) return;
                    loading = true;
                    array_to_collect_fees.map(row => {

                        if (listOfBilletOld.indexOf(row.fee_groups_feetype_id) >= 0) {
                            let datePay = ($('input[name="fee_date_payment_new"]').val().split(/\-|\//gi).reverse().join('-'));
                            row.fee_date_payment = datePay
                        }
                        return row;
                    })
                    createAllBillets(array_to_collect_fees, $modal);

                });
                return;
            }
            createAllBillets(array_to_collect_fees, $this);

        });

        function toggleMaskDiscount() {
            var discount_option = $('[name="discount_option"]:checked').val()
            if (discount_option == 2) {
                $('[name="discount_value"]').unmask().mask("#.##0,00", {
                    reverse: true
                });
            } else {
                $('[name="discount_value"]').unmask().mask('##0,00%', {
                    reverse: true
                });
            }
        }

        // console.log(getDaysExceptions());
        function getDiscount(amount) {
            var discount_option = $('[name="discount_option"]:checked').val()
            var discount_value = Math.floor(String($('[name="discount_value"]').val()).replace('.', '').replace(',', '.').replace('%', ''))
            if (discount_option == 1) {
                return (discount_value / 100) * amount
            }
            return discount_value
        }

        function buildListItemsWithDiscount(items) {
            var collect_fees = [],
                listOfItemSelected = [];
            sortArray(items, 'fee_date_payment_unix', true).forEach(function(item) {
                item["fee_discount"] = getDiscount(item["fee_amount"]);
                collect_fees.push(item)
                listOfItemSelected.push(`
                               <tr class="${item['fee_billet_old'] ===  true ? 'text-danger': ''  }">
                                  <td>${item["fee_master_id"]}</td>
                                  <td>${moment(item["fee_date_payment"]).format('DD/MM/YYYY')}</td>
                                  <td>${item["fee_title"]}</td>
                                  <td  data-value="${item["fee_amount"]}" >${accounting.formatMoney(item["fee_amount"])}</td>
                                  <td data-value="${item["fee_discount"]}">${accounting.formatMoney(item["fee_discount"])}</td>
                                  <td >${accounting.formatMoney(item["fee_amount"] - item["fee_discount"])}</td>
                               </tr>
                            `)

            })
            $('#listBilletModal .modal-body #billet_for_generate').html(listOfItemSelected.join(''))
            return collect_fees;


        }

        function createAllBillets(array_to_collect_fees, $this) {
            var $LoaderModalBody = $('#loadingModal .modal-loader-body');
            $LoaderModalBody.html($LoaderModalBody.data('loading'));
            var $LoaderModal = $('#loadingModal').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            })
            $.ajax({
                type: 'POST',
                url: base_url + "studentfee/generateBillet",
                data: {
                    'data': JSON.stringify(array_to_collect_fees),
                    'user_id': $('input[name="student_user_id"]').val()
                },
                dataType: "JSON",
                beforeSend: function() {
                    $this.button('loading');
                },
                success: function(data) {

                    $LoaderModalBody.html('Os boletos foram enviados para serem gerados com sucesso ')
                    window.location.reload();

                },
                error: function(xhr) { // if error occured
                    $.notify({
                        // options
                        message: `Tivemos um problema na conexão com o servidor, por favor recarregue essa página e tente novamente`
                    }, {
                        // settings
                        type: 'danger'
                    });
                    $LoaderModal.modal('hide')
                },
                complete: function() {
                    $this.button('reset');
                    loading = false;
                }
            });
        }
        ///cancel billet


        $(document).on('click', '.billetCancelSelected', function() {
            var $this = $(this);
            var array_to_collect_fees = [];



            $.each($("input[name='fee_checkbox']:checked"), function() {
                var fee_session_group_id = $(this).data('fee_session_group_id');
                var fee_master_id = $(this).data('fee_master_id');
                var fee_groups_feetype_id = $(this).data('fee_groups_feetype_id');
                item = {};
                item["fee_session_group_id"] = fee_session_group_id;
                item["fee_master_id"] = fee_master_id;
                item["fee_groups_feetype_id"] = fee_groups_feetype_id;
                item["fee_amount"] = $(this).data('fee_amount');
                item["fee_discount"] = $(this).data('fee_discount');
                item["fee_line_1"] = $(this).data('fee_line_1');
                item["fee_line_2"] = $(this).data('fee_line_2');
                item["fee_date_payment"] = $(this).data('fee_date_payment');
                item["fee_fine"] = $(this).data('fee_fine');
                item["billet_id"] = $(this).data('fee_is_pdf');
                if ($(this).data('fee_is_pdf') !== 0)
                    array_to_collect_fees.push(item);
            });

            if (array_to_collect_fees.length == 0) return alert("Nenhum item válido foi selecionado");

            var options = [{
                    value: 'ACERTOS',
                    label: 'ACERTOS',
                },
                {
                    value: 'DEVOLUCAO',
                    label: 'DEVOLUÇÃO',
                },
                {
                    value: 'SUBISTITUICAO',
                    label: 'SUBISTITUIÇÃO',
                },
                // {
                //     value: 'PROTESTOAPOSBAIXA',
                //     label: 'PROTESTO APÓS BAIXA',
                // },
                {
                    value: 'PAGODIRETOAOCLIENTE',
                    label: 'PAGO DIRETO AO CLIENTE',
                },
                {
                    value: 'FALTADESOLUCAO',
                    label: 'FALTA DE SOLUÇÃO',
                },

                {
                    value: 'APEDIDODOCLIENTE',
                    label: 'APEDIDO DO CLIENTE',
                }
            ];
            var html = `
                <div class="row">
                <div class="form-group">
                </div>
                    <div class="form-group">
                        <div class="col-md-4 col-xs-4 control-label">
                            <label class="control-label">Selecione o motivo</label>
                        </div>
                        <div class="col-md-8 col-xs-8">    
                            <select name="billet_cancel_motvive" class="form-control">
                                ${options.map(v => `<option value="${v.value}">${v.label}</option>`).join('')}
                            </select>
                        <div>
                    </div>
                </div>
              
            `;
            var $modal = $("#listBilletModal .modal-footer");
            $("#listBilletModal .modal-body").html(html);
            $("#listBilletModal").modal('show');
            $this.button('reset');
            $('form#billet_fee_group').off('submit').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: base_url + "studentfee/cancelBillet",
                    data: {
                        'data': JSON.stringify(array_to_collect_fees),
                        'motive': $('select[name="billet_cancel_motvive"]').val(),

                    },
                    dataType: "JSON",
                    beforeSend: function() {
                        $modal.button('loading');
                    },
                    success: function(data) {

                        alert('Os boletos foram cancelados com sucesso')
                        window.location.reload();

                    },
                    error: function(xhr) { // if error occured
                        alert("Error occured.please try again");

                    },
                    complete: function() {
                        $modal.button('reset');
                    }
                });
            })






        });


    });





    $(function() {
        $(document).on('change', "#discount_group", function() {
            var amount = $('option:selected', this).data('disamount');

            var balance_amount = (parseFloat(fee_amount) - parseFloat(amount)).toFixed(2);
            if (typeof amount !== typeof undefined && amount !== false) {
                $('div#myFeesModal').find('input#amount_discount').prop('readonly', true).val(amount);
                $('div#myFeesModal').find('input#amount').val(balance_amount);

            } else {
                $('div#myFeesModal').find('input#amount').val(fee_amount);
                $('div#myFeesModal').find('input#amount_discount').prop('readonly', false).val(0);
            }

        });
    });

    $("#collect_fee_group").submit(function(e) {
        var form = $(this);
        var url = form.attr('action');
        var smt_btn = $(this).find("button[type=submit]");
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'JSON',
            data: form.serialize(), // serializes the form's elements.
            beforeSend: function() {
                smt_btn.button('loading');
            },
            success: function(response) {

                if (response.status === 1) {

                    location.reload(true);
                } else if (response.status === 0) {
                    $.each(response.error, function(index, value) {
                        var errorDiv = '#form_collection_' + index + '_error';
                        $(errorDiv).empty().append(value);
                    });
                }
            },
            error: function(xhr) { // if error occured

                alert("Error occured.please try again");

            },
            complete: function() {
                smt_btn.button('reset');
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.
    });

    $("#select_all").change(function() { //"select all" change 
        $('input:checkbox').not(this).prop('checked', this.checked);
        // $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
    });



    function getDaysExceptions() {

        return [
            moment().month(1).date(1).format('YYYY-MM-DD'), // Confraternização Universal - Lei nº 662, de 06/04/49
            moment().month(4).date(21).format('YYYY-MM-DD'),
            moment().month(5).date(1).format('YYYY-MM-DD'), // Tiradentes - Lei nº 662, de 06/04/49
            moment().month(9).date(7).format('YYYY-MM-DD'), // Dia da Independência - Lei nº 662, de 06/04/49
            moment().month(10).date(12).format('YYYY-MM-DD'), // N. S. Aparecida - Lei nº 6802, de 30/06/80
            moment().month(11).date(2).format('YYYY-MM-DD'), // Todos os santos - Lei nº 662, de 06/04/49
            moment().month(11).date(15).format('YYYY-MM-DD'), // Proclamação da republica - Lei nº 662, de 06/04/49
            moment().month(12).date(25).format('YYYY-MM-DD'), // Natal - Lei nº 662, de 06/04/49

            ...getSpecialDay()
        ]
    }

    function subtrairDias(data, dias) {
        return new Date(data.getTime() - (dias * 24 * 60 * 60 * 1000));
    }


    function getSpecialDay() {
        var ano = moment().format('YYYY');
        var X = 24;
        var Y = 5;
        var a = ano % 19;
        var b = ano % 4;
        var c = ano % 7;
        var d = (19 * a + X) % 30
        var e = (2 * b + 4 * c + 6 * d + Y) % 7
        var soma = d + e

        if (soma > 9) {
            dia = (d + e - 9);
            mes = 03;
        } else {
            dia = (d + e + 22);
            mes = 02;
        }

        return [
            moment(new Date(ano, mes, dia).toDateString()).format('YYYY-MM-DD'),
            moment(subtrairDias(new Date(ano, mes, dia), 47)).format('YYYY-MM-DD'),
            // moment(subtrairDias(new Date(ano,mes,dia), 48)).format('YYYY-MM-DD'),
            moment(subtrairDias(new Date(ano, mes, dia), 46)).format('YYYY-MM-DD'),
        ]
    }
</script>