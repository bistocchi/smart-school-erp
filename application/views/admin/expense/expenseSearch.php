<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-credit-card"></i> <?php echo $this->lang->line('expenses'); ?></h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <form role="form" action="<?php echo site_url('admin/expense/expenseSearch') ?>" method="post" class="">
                                        <?php echo $this->customlib->getCSRF(); ?>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search') . " " . $this->lang->line('type'); ?></label><small class="req"> *</small>
                                                <select class="form-control" name="search_type" onchange="showdate(this.value)">
                                                    <?php foreach ($searchlist as $key => $search) {
                                                    ?>
                                                        <option value="<?php echo $key ?>" <?php
                                                                                            if ((isset($search_type)) && ($search_type == $key)) {
                                                                                                echo "selected";
                                                                                            }
                                                                                            ?>><?php echo $search ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('search_type'); ?></span>
                                            </div>
                                        </div>

                                        <div id='date_result'>

                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <form role="form" action="<?php echo site_url('admin/expense/expenseSearch') ?>" method="post" class="">
                                        <?php echo $this->customlib->getCSRF(); ?>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search'); ?></label>
                                                <input autofocus="" type="text" value="<?php echo set_value('search_text', ""); ?>" name="search_text" class="form-control" placeholder="Search by Expense">
                                                <span class="text-danger"><?php echo form_error('search_text'); ?></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="submit" name="search" value="search_full" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>

                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>

                    </div>
                    <?php if (isset($resultList)) {
                    ?><div class="" id="exp">
                            <div class="box-header ptbnull"></div>
                            <div class="box-header ptbnull">
                                <h3 class="box-title titlefix"><i class="fa fa-money"></i> <?php echo $this->lang->line('expense_result'); ?></h3>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <div class="download_label"> <?php echo $this->lang->line('expense_result'); ?> </div>
                                    <table class="table table-striped table-bordered table-hover example">
                                        <thead>
                                            <tr>
                                                <th><?php echo $this->lang->line('name'); ?></th>
                                                <th><?php echo $this->lang->line('invoice_no'); ?></th>
                                                <th><?php echo $this->lang->line('expense_head'); ?></th>
                                                <th><?php echo $this->lang->line('date'); ?></th>
                                                <th class="text-right"><?php echo $this->lang->line('amount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            if (empty($resultList)) {
                                            ?>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>

                                            </tr>
                                        </tfoot>
                                        <?php
                                            } else {
                                                $count       = 1;
                                                $grand_total = 0;
                                                foreach ($resultList as $key => $value) {
                                                    $grand_total = $grand_total + $value['amount'];
                                        ?>
                                            <tr class="<?php echo $value['payment_at'] != null ?  'success' : 'danger' ?>">
                                                <td><?php echo $value['name']; ?></td>
                                                <td><?php echo $value['invoice_no']; ?></td>
                                                <td><?php echo $value['exp_category'] ?></td>

                                                <td> <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['date'])); ?> </td>
                                                <td class="pull-right"><?php echo ($value['amount']); ?> </td>
                                                <td class="pull-right">
                                                    <?php
                                                    if ($this->rbac->hasPrivilege('expense', 'can_edit')) :
                                                    ?>
                                                        <a data-placement="left" href="<?php echo base_url(); ?>admin/expense/edit/<?php echo $value['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    <?php
                                                    endif;

                                                    if ($this->rbac->hasPrivilege('expense', 'can_delete')) :
                                                    ?>
                                                        <a data-placement="left" href="<?php echo base_url(); ?>admin/expense/delete/<?php echo $value['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                    <?php endif; ?>
													
													<!--por altasis em 07-05-2021 -->
													<?php if($value['payment_at'] != null){ ?>													<!--por altasis em 07-05-2021 -->
													<a data-placement="left" href="<?php echo base_url(); ?>admin/recibo/gerar/<?php echo $value['id'] ?>" target="_blank" class="btn btn-default btn-xs" data-toggle="tooltip" title="PDF" onclick="">
														<i class="fa fa-file-pdf-o"></i>
													</a>
													<?php } ?>
                                                </td>
												
												
                                            </tr>
                                        <?php
                                                    $count++;
                                                }
                                        ?>
                                        <tr class="total-bg">
                                            <td colspan="4"></td>

                                            <td class="pull-right text-bold"><?php echo $this->lang->line('grand_total'); ?> : <?php echo ($currency_symbol . number_format($grand_total, 2, '.', '')); ?>

                                            </td>
                                        </tr>
                                    <?php
                                            }
                                    ?>

                                    </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                </div>
            <?php
                    }
            ?>

            </div>

        </div> <!-- /.row -->

    </section><!-- /.content -->
</div>
<script type="text/javascript">
    <?php
    if ($search_type == 'period') {
    ?>

        $(document).ready(function() {
            showdate('period');
        });

    <?php
    }
    ?>
    $(document).ready(function() {
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';


        $.extend($.fn.dataTable.defaults, {
            paging: false,
            bSort: false,
        });
    });
</script>