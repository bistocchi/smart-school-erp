<?php

use Application\Core\BankInterPayment;
use Application\Core\Billet;
use Application\Core\JsonResponse;
use Illuminate\Database\Capsule\Manager as DB;


if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}



class Studentfee extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->search_type = $this->config->item('search_type');
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', $this->lang->line('fees_collection'));
        $this->session->set_userdata('sub_menu', 'studentfee/index');
        $data['title'] = 'student fees';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/studentfeeSearch', $data);
        $this->load->view('layout/footer', $data);
    }

    public function collection_report()
    {

        if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
            access_denied();
        }

        $data['collect_by'] = $this->studentfeemaster_model->get_feesreceived_by();

        $data['searchlist'] = $this->customlib->get_searchtype();
        $data['group_by'] = $this->customlib->get_groupby();

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/collection_report');

        if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

            $dates = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        } else {

            $dates = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        }

        if (isset($_POST['collect_by']) && $_POST['collect_by'] != '') {

            $data['received_by'] = $received_by = $_POST['collect_by'];
        } else {

            $data['received_by'] = $received_by = '';
        }

        if (isset($_POST['group']) && $_POST['group'] != '') {

            $data['group_byid'] = $group = $_POST['group'];
        } else {

            $data['group_byid'] = $group = '';
        }

        $collect_by = array();
        $collection = array();
        $start_date = date('Y-m-d', strtotime($dates['from_date']));
        $end_date = date('Y-m-d', strtotime($dates['to_date']));
        //echo $start_date." ".$end_date;die;//2019-01-01 2019-12-31
        $data['collectlist'] = $this->studentfeemaster_model->getFeeCollectionReport($start_date, $end_date);
        // echo $this->db->last_query();die;
        $this->form_validation->set_rules('search_type', $this->lang->line('search') . " " . $this->lang->line('type'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('collect_by', $this->lang->line('collect') . " " . $this->lang->line('by'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('group', $this->lang->line('group') . " " . $this->lang->line('by'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {

            $data['results'] = array();
        } else {

            $data['results'] = $this->studentfeemaster_model->getFeeCollectionReport($start_date, $end_date, $received_by, $group);

            if ($group != '') {

                if ($group == 'class') {

                    $group_by = 'class_id';
                } elseif ($group == 'collection') {

                    $group_by = 'received_by';
                } elseif ($group == 'mode') {

                    $group_by = 'payment_mode';
                }

                foreach ($data['results'] as $key => $value) {

                    $collection[$value[$group_by]][] = $value;
                }
            } else {

                $s = 0;
                foreach ($data['results'] as $key => $value) {

                    $collection[$s++] = array($value);
                }
            }

            $data['results'] = $collection;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/collection_report', $data);
        $this->load->view('layout/footer', $data);
    }

    public function pdf()
    {
        $this->load->helper('pdf_helper');
    }

    public function search()
    {
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
            access_denied();
        }
        $data['title'] = 'Student Search';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $button = $this->input->post('search');
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $data['sch_setting'] = $this->sch_setting_detail;
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/studentfeeSearch', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $search = $this->input->post('search');
            $search_text = $this->input->post('search_text');
            if (isset($search)) {
                if ($search == 'search_filter') {
                    $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
                    if ($this->form_validation->run() == false) {
                    } else {
                        $resultlist = $this->student_model->searchByClassSection($class, $section);
                        $data['resultlist'] = $resultlist;
                    }
                } else if ($search == 'search_full') {
                    $resultlist = $this->student_model->searchFullText($search_text);
                    $data['resultlist'] = $resultlist;
                }
                $this->load->view('layout/header', $data);
                $this->load->view('studentfee/studentfeeSearch', $data);
                $this->load->view('layout/footer', $data);
            }
        }
    }

    public function feesearch()
    {
        if (!$this->rbac->hasPrivilege('search_due_fees', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'studentfee/feesearch');
        $data['title'] = 'student fees';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $feesessiongroup = $this->feesessiongroup_model->getFeesByGroup();

        $data['feesessiongrouplist'] = $feesessiongroup;
        $data['fees_group'] = "";
        if (isset($_POST['feegroup_id']) && $_POST['feegroup_id'] != '') {
            $data['fees_group'] = $_POST['feegroup_id'];
        }

        $this->form_validation->set_rules('feegroup_id', $this->lang->line('fee_group'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/studentSearchFee', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data['student_due_fee'] = array();
            $feegroup_id = $this->input->post('feegroup_id');
            $feegroup = explode("-", $feegroup_id);
            $feegroup_id = $feegroup[0];
            $fee_groups_feetype_id = $feegroup[1];
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $student_due_fee = $this->studentfee_model->getDueStudentFees($feegroup_id, $fee_groups_feetype_id, $class_id, $section_id);
            if (!empty($student_due_fee)) {
                foreach ($student_due_fee as $student_due_fee_key => $student_due_fee_value) {
                    $amt_due = $student_due_fee_value['amount'];
                    $student_due_fee[$student_due_fee_key]['amount_discount'] = 0;
                    $student_due_fee[$student_due_fee_key]['amount_fine'] = 0;
                    $a = json_decode($student_due_fee_value['amount_detail']);
                    if (!empty($a)) {
                        $amount = 0;
                        $amount_discount = 0;
                        $amount_fine = 0;

                        foreach ($a as $a_key => $a_value) {
                            $amount = $amount + $a_value->amount;
                            $amount_discount = $amount_discount + $a_value->amount_discount;
                            $amount_fine = $amount_fine + $a_value->amount_fine;
                        }
                        if ($amt_due <= $amount) {
                            unset($student_due_fee[$student_due_fee_key]);
                        } else {

                            $student_due_fee[$student_due_fee_key]['amount_detail'] = $amount;
                            $student_due_fee[$student_due_fee_key]['amount_discount'] = $amount_discount;
                            $student_due_fee[$student_due_fee_key]['amount_fine'] = $amount_fine;
                        }
                    }
                }
            }

            $data['student_due_fee'] = $student_due_fee;
            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/studentSearchFee', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function reportbyname()
    {
        if (!$this->rbac->hasPrivilege('fees_statement', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/reportbyname');
        $data['title'] = 'student fees';
        $data['title'] = 'student fees';
        $class = $this->class_model->get();
        $data['classlist'] = $class;

        if ($this->input->server('REQUEST_METHOD') == "GET") {

            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/reportByName', $data);
            $this->load->view('layout/footer', $data);
        } else {

            $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('student_id', $this->lang->line('student'), 'trim|required|xss_clean');

            if ($this->form_validation->run() == false) {

                $this->load->view('layout/header', $data);
                $this->load->view('studentfee/reportByName', $data);
                $this->load->view('layout/footer', $data);
            } else {

                $data['student_due_fee'] = array();
                $class_id = $this->input->post('class_id');
                $section_id = $this->input->post('section_id');
                $student_id = $this->input->post('student_id');
                $student = $this->student_model->get($student_id);
                $data['student'] = $student;
                $student_due_fee = $this->studentfeemaster_model->getStudentFees($student['student_session_id']);
                $student_discount_fee = $this->feediscount_model->getStudentFeesDiscount($student['student_session_id']);
                $data['student_discount_fee'] = $student_discount_fee;
                $data['student_due_fee'] = $student_due_fee;
                $data['class_id'] = $class_id;
                $data['section_id'] = $section_id;
                $data['student_id'] = $student_id;
                $category = $this->category_model->get();
                $data['categorylist'] = $category;
                $this->load->view('layout/header', $data);
                $this->load->view('studentfee/reportByName', $data);
                $this->load->view('layout/footer', $data);
            }
        }
    }

    public function reportbyclass()
    {
        $data['title'] = 'student fees';
        $data['title'] = 'student fees';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/reportByClass', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $student_fees_array = array();
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $student_result = $this->student_model->searchByClassSection($class_id, $section_id);
            $data['student_due_fee'] = array();
            if (!empty($student_result)) {
                foreach ($student_result as $key => $student) {
                    $student_array = array();
                    $student_array['student_detail'] = $student;
                    $student_session_id = $student['student_session_id'];
                    $student_id = $student['id'];
                    $student_due_fee = $this->studentfee_model->getDueFeeBystudentSection($class_id, $section_id, $student_session_id);
                    $student_array['fee_detail'] = $student_due_fee;
                    $student_fees_array[$student['id']] = $student_array;
                }
            }
            $data['class_id'] = $class_id;
            $data['section_id'] = $section_id;
            $data['student_fees_array'] = $student_fees_array;
            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/reportByClass', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function view($id)
    {
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
            access_denied();
        }
        $data['title'] = 'studentfee List';
        $studentfee = $this->studentfee_model->get($id);
        $data['studentfee'] = $studentfee;
        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/studentfeeShow', $data);
        $this->load->view('layout/footer', $data);
    }

    public function deleteFee()
    {

        if (!$this->rbac->hasPrivilege('collect_fees', 'can_delete')) {
            access_denied();
        }
        $invoice_id = $this->input->post('main_invoice');
        $sub_invoice = $this->input->post('sub_invoice');
        if (!empty($invoice_id)) {
            $this->studentfee_model->remove($invoice_id, $sub_invoice);
        }
        $array = array('status' => 'success', 'result' => 'success');
        echo json_encode($array);
    }

    public function deleteStudentDiscount()
    {

        $discount_id = $this->input->post('discount_id');
        if (!empty($discount_id)) {
            $data = array('id' => $discount_id, 'status' => 'assigned', 'payment_id' => "");
            $this->feediscount_model->updateStudentDiscount($data);
        }
        $array = array('status' => 'success', 'result' => 'success');
        echo json_encode($array);
    }




    public function getcollectfee()
    {
        $setting_result = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        $record =   $this->input->post('data');
        // '[{"fee_session_group_id":2,"fee_master_id":4,"fee_groups_feetype_id":""}]';
        $record_array = json_decode($record);

        $fees_array = array();
        $this->load->model(['eloquent/Student_eloquent', 'eloquent/Student_fee_item_eloquent']);

        foreach ($record_array as $key => $value) {
            // $fee_groups_feetype_id = $value->fee_groups_feetype_id;
            $fee_master_id = $value->fee_master_id;
            // $fee_session_group_id = $value->fee_session_group_id;
            // $feeList = $this->studentfeemaster_model->getDueFeeByFeeSessionGroupFeetype($fee_session_group_id, $fee_master_id, $fee_groups_feetype_id);
            $fees_array[] = $value->fee_master_id;
        }
        $data['listOfFees'] = Student_fee_item_eloquent::whereIn('id', $fees_array)->get();

        $result = array(
            'view' => $this->load->view('studentfee/getcollectfee', $data, true),
        );

        $this->output->set_output(json_encode($result));
    }

    public function addfee($id)
    {
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_add')) {
            access_denied();
        }
        $data['sch_setting'] = $this->sch_setting_detail;

        $data['title'] = 'Student Detail';

        $student = $this->student_model->getByStudentSession($id);
        
        $data['student'] = $student;
        $this->load->model(['eloquent/Student_eloquent', 'eloquent/Student_fee_item_eloquent']);
        $student_due_fee = $this->studentfeemaster_model->getStudentFees2($id);
    //   dump($student_due_fee);
        // $dsd = Student_eloquent::where('id', $id)->with('fees')->get('id');
        $due_date = $this->input->get('due_date') ? $this->input->get('due_date') :  date('Y');

        $options = Student_fee_item_eloquent::select(
            DB::raw('DISTINCT(YEAR(`due_date`)) AS year')
        )->get()->pluck('year');
        $years = [];
        foreach ($options->toArray() as $v) {
            $years[$v] = $v;
        }
        $data['optionsYear'] = $years;
        $data['current_year'] = $due_date;
      
        foreach ($student_due_fee as $row) {
            $row->fees = Student_fee_item_eloquent::where('student_session_id', $row->student_session_id)
                // ->whereYear('due_date', $due_date)
                ->with(['deposite', 'billet'])
                ->orderBy('due_date','ASC')
                ->get();
        }
        // dump($student_due_fee);
        // die();
        // dump($dsd->toArray() );


        // exit();

        $student_discount_fee = $this->feediscount_model->getStudentFeesDiscount($id);

        $data['student_discount_fee'] = $student_discount_fee;
        $data['student_due_fee'] = $student_due_fee;
        $category = $this->category_model->get();
        $data['categorylist'] = $category;
        $class_section = $this->student_model->getClassSection($student["class_id"]);
        $data["class_section"] = $class_section;
        $session = $this->setting_model->getCurrentSession();
        $studentlistbysection = $this->student_model->getStudentClassSection($student["class_id"], $session);
        $data["studentlistbysection"] = $studentlistbysection;



        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/studentAddfee', $data);
        $this->load->view('layout/footer', $data);
        // /studentAddfee
    }

    public function deleteTransportFee()
    {
        $id = $this->input->post('feeid');
        $this->studenttransportfee_model->remove($id);
        $array = array('status' => 'success', 'result' => 'success');
        echo json_encode($array);
    }

    public function delete($id)
    {
        $data['title'] = 'studentfee List';
        $this->studentfee_model->remove($id);
        redirect('studentfee/index');
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
            access_denied();
        }
        $data['title'] = 'Add studentfee';
        $this->form_validation->set_rules('category', $this->lang->line('category'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/studentfeeCreate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'category' => $this->input->post('category'),
            );
            $this->studentfee_model->add($data);
            $this->session->set_flashdata('msg', '<div studentfee="alert alert-success text-center">' . $this->lang->line('success_message') . '</div>');
            redirect('studentfee/index');
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit studentfees';
        $data['id'] = $id;
        $studentfee = $this->studentfee_model->get($id);
        $data['studentfee'] = $studentfee;
        $this->form_validation->set_rules('category', $this->lang->line('category'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('studentfee/studentfeeEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id' => $id,
                'category' => $this->input->post('category'),
            );

         
            $this->studentfee_model->add($data);
            $this->session->set_flashdata('msg', '<div studentfee="alert alert-success text-center">' . $this->lang->line('update_message') . '</div>');
            redirect('studentfee/index');
        }
    }

    public function addstudentfee()
    {

        $this->form_validation->set_rules('student_fees_master_id', $this->lang->line('fee_master'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('fee_groups_feetype_id', $this->lang->line('student'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'required|trim|xss_clean|callback_check_deposit');
        $this->form_validation->set_rules('amount_discount', $this->lang->line('discount'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('amount_fine', $this->lang->line('fine'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('payment_mode', $this->lang->line('payment_mode'), 'required|trim|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'amount' => form_error('amount'),
                'student_fees_master_id' => form_error('student_fees_master_id'),
                'fee_groups_feetype_id' => form_error('fee_groups_feetype_id'),
                'amount_discount' => form_error('amount_discount'),
                'amount_fine' => form_error('amount_fine'),
                'payment_mode' => form_error('payment_mode'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $staff_record = $this->staff_model->get($this->customlib->getStaffID());

            $collected_by = " Collected By: " . $this->customlib->getAdminSessionUserName() . "(" . $staff_record['employee_id'] . ")";
            $student_fees_discount_id = $this->input->post('student_fees_discount_id');
            $json_array = array(
                'amount' => $this->input->post('amount'),
                'date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'amount_discount' => $this->input->post('amount_discount'),
                'amount_fine' => $this->input->post('amount_fine'),
                'description' => $this->input->post('description') . $collected_by,
                'payment_mode' => $this->input->post('payment_mode'),
                'received_by' => $staff_record['id'],
            );
            $data = array(
                'student_fees_master_id' => $this->input->post('student_fees_master_id'),
                'fee_groups_feetype_id' => $this->input->post('fee_groups_feetype_id'),
                'amount_detail' => $json_array,
            );

            $action = $this->input->post('action');
            $send_to = $this->input->post('guardian_phone');
            $email = $this->input->post('guardian_email');
            $parent_app_key = $this->input->post('parent_app_key');
            $student_session_id = $this->input->post('student_session_id');
            $inserted_id = $this->studentfeemaster_model->fee_deposit($data, $send_to, $student_fees_discount_id);
            $mailsms_array = $this->feegrouptype_model->getFeeGroupByID($this->input->post('fee_groups_feetype_id'));
            $print_record = array();
            if ($action == "print") {
                $receipt_data = json_decode($inserted_id);
                $setting_result = $this->setting_model->get();
                $data['settinglist'] = $setting_result;
                $fee_record = $this->studentfeemaster_model->getFeeByInvoice($receipt_data->invoice_id, $receipt_data->sub_invoice_id);
                $student = $this->studentsession_model->searchStudentsBySession($student_session_id);
                $data['student'] = $student;
                $data['sub_invoice_id'] = $receipt_data->sub_invoice_id;
                $data['feeList'] = $fee_record;
                $print_record = $this->load->view('print/printFeesByName', $data, true);
            }
            $mailsms_array->invoice = $inserted_id;
            $mailsms_array->contact_no = $send_to;
            $mailsms_array->email = $email;
            $mailsms_array->parent_app_key = $parent_app_key;

            $this->mailsmsconf->mailsms('fee_submission', $mailsms_array);

            $array = array('status' => 'success', 'error' => '', 'print' => $print_record);
            echo json_encode($array);
        }
    }



    public function printFeesByName()
    {
        $data = array('payment' => "0");
        $record = $this->input->post('data');
        $invoice_id = $this->input->post('main_invoice');
        $sub_invoice_id = $this->input->post('sub_invoice');
        $student_session_id = $this->input->post('student_session_id');
        $setting_result = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        $student = $this->studentsession_model->searchStudentsBySession($student_session_id);

        $fee_record = $this->studentfeemaster_model->getFeeByInvoice($invoice_id, $sub_invoice_id);
        $data['student'] = $student;
        $data['sub_invoice_id'] = $sub_invoice_id;
        $data['feeList'] = $fee_record;
        $this->load->view('print/printFeesByName', $data);
    }
    
     public function printComprovantePagamento(){
        
        
        $student_session_id = $this->input->post('student_session_id');
        $setting_result = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        $student = $this->studentsession_model->searchStudentsBySession($student_session_id);
        $data['student'] = $student;
        
        $student_fee_id = $this->input->post('student_fee_id');
        $this->load->model(['eloquent/Student_eloquent', 'eloquent/Student_fee_item_eloquent']);
        $data['fee_value'] = Student_fee_item_eloquent::where('id', $student_fee_id)
                // ->whereYear('due_date', $due_date)
                ->with(['deposite', 'billet'])
                ->orderBy('due_date','ASC')
                ->get()[0];
       
       // echo '<pre>';
       // print_r($data['fee_value']->deposite);
        
        
        $this->load->view('print/printComprovantePagamento', $data);
    }
    

    public function printFeesByGroup()
    {
        $fee_groups_feetype_id = $this->input->post('fee_groups_feetype_id');
        $fee_master_id = $this->input->post('fee_master_id');
        $fee_session_group_id = $this->input->post('fee_session_group_id');
        $setting_result = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
		
		//die($fee_groups_feetype_id);
        $data['feeList'] = 
		$this->studentfeemaster_model->getDueFeeByFeeSessionGroupFeetype($fee_session_group_id, $fee_master_id, $fee_groups_feetype_id);

        $this->load->view('print/printFeesByGroup', $data);
    }

    public function printFeesByGroupArray()
    {
        $setting_result = $this->setting_model->get();

        $data['settinglist'] = $setting_result;
        $record = $this->input->post('data');
        $record_array = json_decode($record);
        $fees_array = array();
        foreach ($record_array as $key => $value) {
            $fee_groups_feetype_id = $value->fee_groups_feetype_id;
            $fee_master_id = $value->fee_master_id;
            $fee_session_group_id = $value->fee_session_group_id;
            $feeList = $this->studentfeemaster_model->getDueFeeByFeeSessionGroupFeetype($fee_session_group_id, $fee_master_id, $fee_groups_feetype_id);
            $fees_array[] = $feeList;
        }
        $data['feearray'] = $fees_array;
        $this->load->view('print/printFeesByGroupArray', $data);
    }

    public function searchpayment()
    {
        if (!$this->rbac->hasPrivilege('search_fees_payment', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'studentfee/searchpayment');
        $data['title'] = 'Edit studentfees';

        $this->form_validation->set_rules('paymentid', $this->lang->line('payment_id'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
        } else {
            $paymentid = $this->input->post('paymentid');
            $invoice = explode("/", $paymentid);

            if (array_key_exists(0, $invoice) && array_key_exists(1, $invoice)) {
                $invoice_id = $invoice[0];
                $sub_invoice_id = $invoice[1];
                $feeList = $this->studentfeemaster_model->getFeeByInvoice($invoice_id, $sub_invoice_id);
                $data['feeList'] = $feeList;
                $data['sub_invoice_id'] = $sub_invoice_id;
            } else {
                $data['feeList'] = array();
            }
        }
        $this->load->view('layout/header', $data);
        $this->load->view('studentfee/searchpayment', $data);
        $this->load->view('layout/footer', $data);
    }

    public function addfeegroup()
    {
        $this->form_validation->set_rules('fee_session_groups', $this->lang->line('fee_group'), 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'fee_session_groups' => form_error('fee_session_groups'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $student_session_id = $this->input->post('student_session_id');
            $fee_session_groups = $this->input->post('fee_session_groups');
            $student_sesssion_array = isset($student_session_id) ? $student_session_id : array();
            $student_ids = $this->input->post('student_ids');
            $delete_student = array_diff($student_ids, $student_sesssion_array);

            $preserve_record = array();
            if (!empty($student_sesssion_array)) {
                foreach ($student_sesssion_array as $key => $value) {
                    $insert_array = array(
                        'student_session_id' => $value,
                        'fee_session_group_id' => $fee_session_groups,
                    );
                    $inserted_id = $this->studentfeemaster_model->add($insert_array);

                    $preserve_record[] = $inserted_id;
                }
            }
            if (!empty($delete_student)) {
                $this->studentfeemaster_model->delete($fee_session_groups, $delete_student);
            }

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    /**
     * array:8 [
  "fee_session_groups" => "2"
  "feetype" => array:2 [
    0 => "4"
    1 => "15"
  ]
  "price" => array:2 [
    0 => "550.00"
    1 => "250.55"
  ]
  "date_payment" => array:2 [
    0 => "11/05/2020"
    1 => "11/16/2020"
  ]
  "number_multiply_payment" => array:2 [
    0 => "12"
    1 => "1"
  ]
  "student_session_id" => array:1 [
    0 => "1"
  ]
  "student_fees_master_id_1" => "1"
  "student_ids" => array:1 [
    0 => "1"
  ]
]
     */
    public function assign()
    {



        $data = [];
        $listOfDatePayment = $this->input->post('date_payment');
        $listOfType = $this->input->post('feetype');
        foreach ($this->input->post('number_multiply_payment') as $key => $v) {
            $datePaymentDefault = (implode('-', array_reverse(explode('/', $listOfDatePayment[$key]))));
            $type_id = $listOfType[$key];
            $price  = $this->input->post('price')[$key];
            $title =  $this->input->post('title')[$key];
            if($v == 0) continue;

            for ($i = 0; $i < $v; $i += 1) {
                $datePayment = new DateTime($datePaymentDefault);
                $datePayment->modify("+ {$i}month");
                $data[] = [
                    'title' => sprintf('%s %s/%s', $title, $i + 1, $v),
                    'feetype_id' => $type_id,
                    'amount' => $price,

                    'due_date' => $datePayment->format('Y-m-d'),
                    'fee_session_group_id' => $this->input->post('fee_session_groups')
                ];
            }
        }
        $this->load->model(['eloquent/Student_fee_item_eloquent', 'eloquent/Student_fee_master_eloquent']);
        foreach ($this->input->post('student_session_id') as $k => $v) {
            $class_id = $this->input->post('class_id')[$k];
            foreach ($data as $row) {
                $register = array_merge($row, ['user_id' => $v, 'student_session_id' => $v, 'class_id' => $class_id]);
                // dump($register);
                Student_fee_item_eloquent::updateOrCreate(
                    [
                        'user_id' => $v,
                        'due_date' => $row['due_date'],
                        'feetype_id' => $row['feetype_id'],
                        'student_session_id' => $v,
                    ],
                    $register
                );
                $sessionMaster = [
                    'student_session_id' => $v,
                    'fee_session_group_id' => $this->input->post('fee_session_groups'),


                ];
                // dump($register);
                Student_fee_master_eloquent::updateOrCreate(
                    $sessionMaster,
                    $sessionMaster
                );
            }
        }
    }

    public function geBalanceFee()
    {
        $this->form_validation->set_rules('fee_groups_feetype_id', $this->lang->line('fee_groups_feetype_id'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('student_fees_master_id', 'student_fees_master_id', 'required|trim|xss_clean');
        $this->form_validation->set_rules('student_session_id', 'student_session_id', 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'fee_groups_feetype_id' => form_error('fee_groups_feetype_id'),
                'student_fees_master_id' => form_error('student_fees_master_id'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $data = array();
            $student_session_id = $this->input->post('student_session_id');
            $fee_groups_feetype_id = $this->input->post('fee_groups_feetype_id');
            $student_fees_master_id = $this->input->post('student_fees_master_id');
            $remain_amount_object = $this->getStuFeetypeBalance($fee_groups_feetype_id, $student_fees_master_id);
            $discount_not_applied = $this->getNotAppliedDiscount($student_session_id);
            $remain_amount = json_decode($remain_amount_object)->balance;
            $remain_amount_fine = json_decode($remain_amount_object)->fine_amount;

            $array = array('status' => 'success', 'error' => '', 'balance' => $remain_amount, 'discount_not_applied' => $discount_not_applied, 'remain_amount_fine' => $remain_amount_fine);
            echo json_encode($array);
        }
    }

    public function getStuFeetypeBalance($fee_groups_feetype_id, $student_fees_master_id)
    {
        $data = array();
        $data['fee_groups_feetype_id'] = $fee_groups_feetype_id;
        $data['student_fees_master_id'] = $student_fees_master_id;
        $result = $this->studentfeemaster_model->studentDeposit($data);
        $amount_balance = 0;
        $amount = 0;
        $amount_fine = 0;
        $amount_discount = 0;
        $fine_amount = 0;
        $fee_fine_amount = 0;
        $due_amt = $result->amount;
        if (strtotime($result->due_date) < strtotime(date('Y-m-d'))) {
            $fee_fine_amount = $result->fine_amount;
        }

        if ($result->is_system) {
            $due_amt = $result->student_fees_master_amount;
        }

        $amount_detail = json_decode($result->amount_detail);
        if (is_object($amount_detail)) {

            foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                $amount = $amount + $amount_detail_value->amount;
                $amount_discount = $amount_discount + $amount_detail_value->amount_discount;
                $amount_fine = $amount_fine + $amount_detail_value->amount_fine;
            }
        }

        $amount_balance = $due_amt - ($amount + $amount_discount);
        $fine_amount = abs($amount_fine - $fee_fine_amount);
        $array = array('status' => 'success', 'error' => '', 'balance' => $amount_balance, 'fine_amount' => $fine_amount);
        return json_encode($array);
    }

    public function check_deposit($amount)
    {
        if ($this->input->post('amount') != "" && $this->input->post('amount_discount') != "") {
            if ($this->input->post('amount') < 0) {
                $this->form_validation->set_message('check_deposit', $this->lang->line('deposit_amount_can_not_be_less_than_zero'));
                return false;
            } else {
                $student_fees_master_id = $this->input->post('student_fees_master_id');
                $fee_groups_feetype_id = $this->input->post('fee_groups_feetype_id');
                $deposit_amount = $this->input->post('amount') + $this->input->post('amount_discount');
                $remain_amount = $this->getStuFeetypeBalance($fee_groups_feetype_id, $student_fees_master_id);
                $remain_amount = json_decode($remain_amount)->balance;
                if ($remain_amount < $deposit_amount) {
                    $this->form_validation->set_message('check_deposit', $this->lang->line('deposit_amount_can_not_be_greater_than_remaining'));
                    return false;
                } else {
                    return true;
                }
            }
            return true;
        }
        return true;
    }

    public function getNotAppliedDiscount($student_session_id)
    {
        return $this->feediscount_model->getDiscountNotApplied($student_session_id);
    }

    public function listBillet()
    {
        $this->load->library('bank_payment_inter');
        // $this->bank_payment_inter->cancel(['number' => '00635734066', 'motive' => 'ACERTOS'] , function(){});
        $data = $this->bank_payment_inter->find('00639157132');

        return new JsonResponse(compact('data'));
    }
    public function getBillet($id)
    {   
        try{
            
            $this->load->library('bank_payment_inter');
            $this->bank_payment_inter->show($id);
           
        }catch(Exception $e){
            return new JsonResponse(json_decode($e->getMessage(), true), 404);
        }
       
    }
    public function generateBillet()
    {
        $staff_record = $this->session->userdata('admin');

        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('row_counter[]', 'Fees List', 'required|trim|xss_clean');
        try {


            DB::beginTransaction();
            $data = json_decode($this->input->post('data'), true);

            if ($this->form_validation->run()) {
                $data = array(
                    'row_counter' => form_error('row_counter'),
                );
                $array = array('status' => 0, 'error' => $data);
                return new JsonResponse($array);
            }

            $this->load->model(['eloquent/Billet_eloquent', 'eloquent/Student_deposite_eloquent']);
            $this->load->library('bank_payment_inter');
            $listOfIds = [];
            $student = (object) $this->student_model->getByStudentSession($this->input->post('user_id'));
            $errors = [];
            //   return new JsonResponse((array) $student);
            $ids  = [];
            foreach ($data as $k => $values) {

                // if (Student_deposite_eloquent::where('fee_groups_feetype_id', $values['fee_groups_feetype_id'])->where('student_fees_master_id', $values['fee_master_id'])->count() > 0) continue;
                // if (
                //     Billet_eloquent::where('fee_item_id',  $values['fee_master_id'])

                //     ->count() > 0
                // ) continue;
                $data[$k]['fee_item_id'] =  $values['fee_master_id'];
                $data[$k]['due_date'] = $values['fee_date_payment'];
                

                // $billet = new Billet_eloquent;
                // $billet->body = json_encode($values);
                // $billet->price = ($values['fee_amount'] + $values['fee_fine']) - $values['fee_discount'];
                // $billet->fee_item_id = $values['fee_master_id'];
                // $billet->user_id = $student->id;

                // //$billet->fill($values);
                // //create billet
                // $billet->save();
                // $listOfIds[] = $billet->id;
                // // $billet->received_at = date('Y-m-d H:i:s');
                // $address = preg_split('#,#', $student->guardian_address);
                // $payment = new BankInterPayment;
                // $payment->user =  $student->guardian_name;
                // $payment->user_document =  $student->guardian_document;
                // $payment->price = $billet->price;
                // $payment->address = $student->guardian_address;
                // $payment->address_state = $student->guardian_state;
                // $payment->address_district = $student->guardian_district;
                // $payment->address_city = $student->guardian_city;
                // $payment->address_number = $student->guardian_address_number;
                // $payment->address_postal_code = $student->guardian_postal_code;
                // $payment->date_payment = $values['fee_date_payment'];
                // $payment->your_number =  str_pad($billet->id, 10, "0", STR_PAD_LEFT);
                // $payment->description = implode(PHP_EOL, [$values['fee_line_1'], $values['fee_line_2']]);
                // //  $errors[] = $payment;
                // $this->bank_payment_inter->create($payment, function ($opt) use (&$billet, &$errors) {
                //     if (!$opt->success) {
                //         $errors[] = sprintf('%s - %s', $opt->status, (string) $opt->body);
                //         return false;
                //     }
                //     $billet->bank_bullet_id = $opt->billet->number;
                //     $billet->save();
                //     DB::commit();
                // });
            }
         
            $ids =  array_merge($ids, (new Billet)->create($data, $this->input->post('user_id'), true));
            DB::commit();
            //    return new JsonResponse(['message' => $errors]);
            if (count($errors) > 0)
                throw new Exception(implode('<br/>', $errors));
            new JsonResponse(['message' =>  'successful']);
        } catch (Exception $e) {
            return  new JsonResponse(['message' => $e->getMessage()], 400);
        }
        //    $this->bank_payment_inter->create($payment, function($o) use (& $response){
        //             array_push($response, $o);
        //    });


    }

    public function cancelBillet()
    {
        $staff_record = $this->session->userdata('admin');

        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('row_counter[]', 'Fees List', 'required|trim|xss_clean');
        $this->load->library('bank_payment_inter');

        $data = json_decode($this->input->post('data'), true);

        if ($this->form_validation->run()) {
            $data = array(
                'row_counter' => form_error('row_counter'),
            );
            $array = array('status' => 0, 'error' => $data);
            return new JsonResponse($array);
        }

        $this->load->model('eloquent/Billet_eloquent');
        $saved = [];

        foreach ($data as $values) {
            $billet = Billet_eloquent::find($values['billet_id']);
            \Billet_eloquent::where('bank_bullet_id', $billet->bank_bullet_id)
                       ->update([
                           'status' => $this->input->post('motive'),
                           'deleted_at' => date('Y-m-d H:i:s'),
                       ]);
        }


        return new JsonResponse(['inputs' => $saved]);
    }

    public function destroyItem($id)
    {
        $staff_record = $this->session->userdata('admin');
        $this->load->model('eloquent/Student_fee_item_eloquent');

        try {

            Student_fee_item_eloquent::where('id', $id)->delete();
        } catch (Exception $e) {
            return new JsonResponse(['message' => 'failure'], 400);
        }



        return new JsonResponse(['message' => 'successful']);
    }

    public function addfeegrp()
    {

        $staff_record = $this->session->userdata('admin');

        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('row_counter[]', 'Fees List', 'required|trim|xss_clean');
        $this->form_validation->set_rules('collected_date', 'Date', 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'row_counter' => form_error('row_counter'),
                'collected_date' => form_error('collected_date'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {
            $collected_array = array();
            $collected_by = " Collected By: " . $this->customlib->getAdminSessionUserName();

            $total_row = $this->input->post('row_counter');
            foreach ($total_row as $total_row_key => $total_row_value) {

                $this->input->post('student_fees_master_id_' . $total_row_value);
                $this->input->post('fee_groups_feetype_id_' . $total_row_value);

                $json_array = array(
                    'amount' => $this->input->post('fee_amount_' . $total_row_value),
                    'date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('collected_date'))),
                    'description' => $this->input->post('fee_gupcollected_note') . $collected_by,
                    'amount_discount' => 0,
                    'amount_fine' => 0,
                    'payment_mode' => $this->input->post('payment_mode_fee'),
                    'received_by' => $staff_record['id'],
                );
                $collected_array[] = array(
                    'student_fees_master_id' => $this->input->post('student_fees_master_id_' . $total_row_value),
                    'fee_groups_feetype_id' => $this->input->post('fee_groups_feetype_id_' . $total_row_value),
                    'student_fees_id' => $this->input->post('student_fees_master_id_' . $total_row_value),
                    'amount_detail' => $json_array,
                );
            }

            $inserted_id = $this->studentfeemaster_model->fee_deposit_collections($collected_array, $this->input->post('generate_invoice') == 1);
            $array = array('status' => 1, 'error' => '');
            echo json_encode($array);
        }
    }
}
