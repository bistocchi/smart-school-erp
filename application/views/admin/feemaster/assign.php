<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> <?php echo $this->lang->line('fees_collection'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">
                        <form role="form" action="<?php echo site_url('admin/feemaster/assign/' . $id) ?>" method="post" class="row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('class'); ?></label>
                                    <select autofocus="" id="class_id" name="class_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($classlist as $class) {
                                        ?>
                                            <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) echo "selected=selected" ?>><?php echo $class['class'] ?></option>
                                        <?php
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('section'); ?></label>
                                    <select id="section_id" name="section_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                </div>
                            </div>
                            <?php if ($sch_setting->category) {  ?>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('category'); ?></label>
                                        <select id="category_id" name="category_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($categorylist as $category) {
                                            ?>
                                                <option value="<?php echo $category['id'] ?>" <?php if (set_value('category_id') == $category['id']) echo "selected=selected"; ?>><?php echo $category['category'] ?></option>
                                            <?php
                                                $count++;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('gender'); ?></label>
                                    <select class="form-control" name="gender">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($genderList as $key => $value) {
                                        ?>
                                            <option value="<?php echo $key; ?>" <?php if (set_value('gender') == $key) echo "selected"; ?>><?php echo $value; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <?php if ($sch_setting->rte) { ?>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('rte'); ?></label>
                                        <select id="rte" name="rte" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($RTEstatusList as $k => $rte) {
                                            ?>
                                                <option value="<?php echo $k; ?>" <?php if (set_value('rte') == $k) echo "selected"; ?>><?php echo $rte; ?></option>

                                            <?php
                                                $count++;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            <?php } ?>


                            <div class="col-sm-12">
                                <div class="form-group">
                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </form>

                    </div>

                    <form method="post" action="<?php echo site_url('studentfee/assign') ?>" id="assign_form">


                        <?php
                        if (isset($resultlist)) {
                            
                        ?>
                            <div class="box-header ptbnull"></div>
                            <div class="">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><i class="fa fa-users"></i> <?php echo $this->lang->line('assign_fees_group'); ?>
                                        <?php echo form_error('student'); ?></h3>
                                    <div class="box-tools pull-right">
                                    </div>
                                </div>
                                
                                <div class="box-body">
                                    <div class="row">
                                        <div class="">
                                            <div class="col-md-6 col-xs-12">
                                                <div class="table-responsive">
                                                    <?php
                                                    
                                                    foreach ($feegroupList as $feegroup) {
                                                       
                                                    ?>
                                                        <h4>
                                                            <input type="hidden" name="fee_session_groups" value="<?php echo $feegroup->id; ?>">
                                                            <a href="#" data-toggle="popover" class="detail_popover"><?php echo $feegroup->group_name; ?></a>
                                                        </h4>

                                                        <table class="table">
                                                            <tbody>
                                                                <?php
                                                                if (empty($feegroup->feetypes)) {
                                                                ?>

                                                                    <td colspan="5" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                                                                    <?php
                                                                } else {
                                                                    $i = 0;
                                                                    foreach ($feegroup->feetypes as $feetype_key => $feetype_value) :
                                                                           
                                                                    ?>
                                                                        <tr>
                                                                            <td>
                                                                           
                                                                                <input type="hidden" name="feetype[]" value="<?php echo $feetype_value->feetype_id;  ?>" />
                                                                                <input type="hidden" name="price[]" value="<?php echo $feetype_value->amount;  ?>" />
                                                                                <input type="hidden" name="title[]" value="<?php echo sprintf('%s - %s', $feetype_value->code, $feetype_value->type);  ?>" />
                                                                                <?php echo $feetype_value->code; ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo $currency_symbol . $feetype_value->amount; ?>
                                                                            </td>
                                                                            <td style="width: 150px;">
                                                                                <div class="input-group date">
                                                                                    <input type="text" class="form-control idate" id="date_payment_<?php echo $i; ?>" name="date_payment[]" value="<?php  echo  date('d/m/Y'); ?>">
                                                                                    <div class="input-group-addon">
                                                                                        <span class="glyphicon glyphicon-th"></span>
                                                                                    </div>
                                                                                </div>

                                                                            </td>
                                                                            <td style="width: 90px;">
                                                                                <input class="form-control" name="number_multiply_payment[]" id="number_multiply_payment_<?php echo $i; ?>" value="0" />
                                                                            </td>
                                                                        </tr>
                                                                <?php
                                                                        $i += 1;
                                                                    endforeach;
                                                                }
                                                                ?>
                                                                </tr>

                                                            </tbody>
                                                        </table>
                                                    <?php
                                                    }
                                                    ?>

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class=" table-responsive">
                                                    <table class="table table-striped">
                                                        <tbody>
                                                            <tr>
                                                                <th>
                                                                    <div class="checkbox mb0 mt0">
                                                                        <label class="labelbold"><input type="checkbox" id="select_all" /> <?php echo $this->lang->line('all'); ?></label>
                                                                    </div>
                                                                    <!--  <input type="checkbox" id="select_all"/> <span><?php //echo $this->lang->line('all'); 
                                                                                                                            ?></span> -->
                                                                </th>

                                                                <th><?php echo $this->lang->line('admission_no'); ?></th>

                                                                <th><?php echo $this->lang->line('student_name'); ?></th>

                                                                <th><?php echo $this->lang->line('class'); ?></th>
                                                                <?php if ($sch_setting->father_name) {  ?>
                                                                    <th><?php echo $this->lang->line('father_name'); ?></th>
                                                                <?php }
                                                                if ($sch_setting->category) {  ?>
                                                                    <th><?php echo $this->lang->line('category'); ?></th>
                                                                <?php } ?>
                                                                <th><?php echo $this->lang->line('gender'); ?></th>

                                                            </tr>
                                                            <?php
                                                            if (empty($resultlist)) {
                                                            ?>
                                                                <tr>
                                                                    <td colspan="7" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                                                                </tr>
                                                                <?php
                                                            } else {
                                                                $count = 1;
                                                                foreach ($resultlist as $student) {
                                                                   
                                                                ?>
                                                                    <tr>

                                                                        <td>
                                                                            <?php
                                                                            if ($student['student_fees_master_id'] != 0) {
                                                                                $sel = "checked='checked'";
                                                                            } else {
                                                                                $sel = "";
                                                                            }
                                                                            ?>
                                                                            <input class="checkbox" type="checkbox" name="student_session_id[]" value="<?php echo $student['student_session_id']; ?>"  />
                                                                            <input type="hidden" name="student_fees_master_id_<?php echo $student['student_session_id']; ?>" value="<?php echo $student['student_fees_master_id']; ?>">
                                                                            <input type="hidden" name="class_id[]" value="<?php echo $student['class_id'];  ?>" />
                                                                        </td>

                                                                        <td><?php echo $student['admission_no']; ?></td>

                                                                        <td><?php echo $student['firstname'] . " " . $student['lastname']; ?></td>
                                                                        <td><?php echo $student['class'] . "(" . $student['section'] . ")" ?></td>
                                                                        <?php if ($sch_setting->father_name) {  ?>
                                                                            <td><?php echo $student['father_name']; ?></td>
                                                                        <?php }
                                                                        if ($sch_setting->category) {  ?>
                                                                            <td><?php echo $student['category']; ?></td>
                                                                        <?php } ?>
                                                                        <td><?php echo $student['gender']; ?></td>

                                                                    </tr>
                                                            <?php
                                                                }
                                                                $count++;
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>

                                                </div>
                                                <button type="submit" class="allot-fees btn btn-primary btn-sm pull-right" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait.."><?php echo $this->lang->line('save'); ?>
                                                </button>

                                                <br />
                                                <br />
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        <?php
                        }
                        ?>

                    </form>
                </div>
            </div>

        </div>

    </section>
</div>

<script type="text/javascript">

    $(document).ready(function() {

        $(".date").datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd/mm/yyyy'

        });

        $('input[name="date_payment[]"]').mask('00/00/0000');

        var $form = $('#assign_form')
        jQuery.validator.addMethod("allRequired", function(value, elem) {
            // Use the name to get all the inputs and verify them
            var name = elem.name;
            return $('input[name="' + name + '"]').map(function(i, obj) {
                return $(obj).val();
            }).get().every(function(v) {
                return v;
            });
        });
        $form.validate({
            rules: {
                'number_multiply_payment[]': {
                    number: true,
                    allRequired: true,
                },
                'date_payment[]': {
                    allRequired: true,
                }
            },
            messages: {
                'date_payment[]': 'Preencher todas as datas'
            }
        })


        $form.submit(function(e) {
            e.preventDefault();
            if (!confirm('<?php echo $this->lang->line('are_you_sure'); ?>') || !$form.valid()) return;
            var $this = $('.allot-fees');
            $this.button('loading');
           
            
            $.ajax({
                type: "POST",
                dataType: 'Json',
                url: $("#assign_form").attr('action'),
                data: $form.serialize(), // serializes the form's elements.
                complete: function() {
                    $this.button('reset');
                },
                success: function(data) {
                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function(index, value) {

                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data.message);
                    }


                }
            });


        })
    })


    //select all checkboxes
    $("#select_all").change(function() { //"select all" change 
        $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
    });

    //".checkbox" change 
    $('.checkbox').change(function() {
        //uncheck "select all", if one of the listed checkbox item is unchecked
        if (false == $(this).prop("checked")) { //if this item is unchecked
            $("#select_all").prop('checked', false); //change "select all" checked status to false
        }
        //check "select all" if all checkbox items are checked
        if ($('.checkbox:checked').length == $('.checkbox').length) {
            $("#select_all").prop('checked', true);
        }
    });

    function getSectionByClass(class_id, section_id) {
        if (class_id != "" && section_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {
                    'class_id': class_id
                },
                dataType: "json",
                success: function(data) {
                    $.each(data, function(i, obj) {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        }
    }

    $(document).ready(function() {
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id') ?>';
        getSectionByClass(class_id, section_id);
        $(document).on('change', '#class_id', function(e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {
                    'class_id': class_id
                },
                dataType: "json",
                success: function(data) {
                    $.each(data, function(i, obj) {
                        div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        });
    });

    // $("#assign_form").submit(function(e) {
    //     if (confirm('<?php echo $this->lang->line('are_you_sure'); ?>')) {
    //         var $this = $('.allot-fees');
    //         $this.button('loading');
    //         $.ajax({
    //             type: "POST",
    //             dataType: 'Json',
    //             url: $("#assign_form").attr('action'),
    //             data: $("#assign_form").serialize(), // serializes the form's elements.
    //             success: function(data) {
    //                 if (data.status == "fail") {
    //                     var message = "";
    //                     $.each(data.error, function(index, value) {

    //                         message += value;
    //                     });
    //                     errorMsg(message);
    //                 } else {
    //                     successMsg(data.message);
    //                 }

    //                 $this.button('reset');
    //             }
    //         });

    //     }
    //     e.preventDefault();

    // });
</script>