<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use Spipu\Html2Pdf\Html2Pdf;
// use mikehaertl\wkhtmlto\Pdf;
use Dompdf\Dompdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter; #alterado

class Site extends Public_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_installation();
        if ($this->config->item('installed') == true) {
            $this->db->reconnect();
        }

        $this->load->model("staff_model");
        $this->load->library('Auth');
        $this->load->library('Enc_lib');
        $this->load->library('customlib');
        $this->load->library('mailsmsconf');
        $this->load->library('mailer');
        $this->load->config('ci-blog');
        $this->mailer;
    }

    private function check_installation() {
        if ($this->uri->segment(1) !== 'install') {
            $this->load->config('migration');
            if ($this->config->item('installed') == false && $this->config->item('migration_enabled') == false) {
                redirect(base_url() . 'install/start');
            } else {
                if (is_dir(APPPATH . 'controllers/install')) {
                    echo '<h3>Delete the install folder from application/controllers/install</h3>';
                    die;
                }
            }
        }
    }

    function login() {

        $app_name = $this->setting_model->get();
        $app_name = $app_name[0]['name'];

        if ($this->auth->logged_in()) {
            $this->auth->is_logged_in(true);
        }

        $data = array();
        $data['title'] = 'Login';
        $school = $this->setting_model->get();

        $data['name'] = $app_name;

        $notice_content = $this->config->item('ci_front_notice_content');
        $notices = $this->cms_program_model->getByCategory($notice_content, array('start' => 0, 'limit' => 5));
        $data['notice'] = $notices;
        $data['school'] = $school[0];
        $this->form_validation->set_rules('username', $this->lang->line('username'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', $this->lang->line('password'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $data['name'] = $app_name;
            $this->load->view('admin/login', $data);
        } else {
            $login_post = array(
                'email' => $this->input->post('username'),
                'password' => $this->input->post('password')
            );
            $setting_result = $this->setting_model->get();
            $result = $this->staff_model->checkLogin($login_post);

            if (!empty($result->language_id)) {
                $lang_array = array('lang_id' => $result->language_id, 'language' => $result->language);
            } else {
                $lang_array = array('lang_id' => $setting_result[0]['lang_id'], 'language' => $setting_result[0]['language']);
            }

            if ($result) {
                if ($result->is_active) {
                    if ($result->surname != "") {
                        $logusername = $result->name . " " . $result->surname;
                    } else {
                        $logusername = $result->name;
                    }

                    $setting_result = $this->setting_model->get();
                    $session_data = array(
                        'id' => $result->id,
                        'username' => $logusername,
                        'email' => $result->email,
                        'roles' => $result->roles,
                        'date_format' => $setting_result[0]['date_format'],
                        'currency_symbol' => $setting_result[0]['currency_symbol'],
                        'currency_place' => $setting_result[0]['currency_place'],
                        'start_month' => $setting_result[0]['start_month'],
                        'school_name' => $setting_result[0]['name'],
                        'timezone' => $setting_result[0]['timezone'],
                        'sch_name' => $setting_result[0]['name'],
                        'language' => $lang_array,
                        'is_rtl' => $setting_result[0]['is_rtl'],
                        'theme' => $setting_result[0]['theme'],
                        'gender' => $result->gender,
                    );
                    $language_result1 = $this->language_model->get($lang_array['lang_id']);
                    if ($this->customlib->get_rtl_languages($language_result1['short_code'])) {
                        $session_data['is_rtl'] = 'enabled';
                    }

                    $this->session->set_userdata('admin', $session_data);

                    $role = $this->customlib->getStaffRole();
                    $role_name = json_decode($role)->name;
                    $this->customlib->setUserLog($this->input->post('username'), $role_name);

                    if (isset($_SESSION['redirect_to']))
                        redirect($_SESSION['redirect_to']);
                    else
                        redirect('admin/admin/dashboard');
                }else {
                    $data['name'] = $app_name;
                    $data['error_message'] = $this->lang->line('your_account_is_disabled_please_contact_to_administrator');

                    $this->load->view('admin/login', $data);
                }
            } else {
                $data['name'] = $app_name;
                $data['error_message'] = $this->lang->line('invalid_username_or_password');
                $this->load->view('admin/login', $data);
            }
        }
    }

    function logout() {
        $admin_session = $this->session->userdata('admin');
        $student_session = $this->session->userdata('student');
        $this->auth->logout();
        if ($admin_session) {
            redirect('site/login');
        } else if ($student_session) {
            redirect('site/userlogin');
        } else {
            redirect('site/userlogin');
        }
    }

    function forgotpassword() {

        $app_name = $this->setting_model->get();
        $data['name'] = $app_name[0]['name'];
        $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|valid_email|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('admin/forgotpassword', $data);
        } else {
            $email = $this->input->post('email');

            $result = $this->staff_model->getByEmail($email);


           

            if ($result && $result->email != "") {
                if ($result->is_active == '1') {
                    $verification_code = $this->enc_lib->encrypt(uniqid(mt_rand()));
                    $update_record = array('id' => $result->id, 'verification_code' => $verification_code);
                    $this->staff_model->add($update_record);
                    $name = $result->name;
                    $resetPassLink = site_url('admin/resetpassword') . "/" . $verification_code;
                    $sender_details = array('resetPassLink' => $resetPassLink, 'name' => $name, 'email' => $email);
                    $this->mailsmsconf->mailsms('forgot_password', $sender_details);
                    $this->session->set_flashdata('message', $this->lang->line('please_check_your_email_to_recover_your_password'));
                } else {
                    $this->session->set_flashdata('disable_message', $this->lang->line('your_account_is_disabled_please_contact_to_administrator'));
                }

                redirect('site/login', 'refresh');

                // die();
            } else {

                $data = array(
                    'error_message' => $this->lang->line('incorrect') . " " . $this->lang->line('email')
                );
            }
            $this->load->view('admin/forgotpassword', $data);
        }
    }

    //reset password - final step for forgotten password
    public function admin_resetpassword($verification_code = null) {
        $app_name = $this->setting_model->get();
        $data['name'] = $app_name[0]['name'];
        if (!$verification_code) {
            show_404();
        }

        $user = $this->staff_model->getByVerificationCode($verification_code);

        if ($user) {
            //if the code is valid then display the password reset form
            $this->form_validation->set_rules('password', $this->lang->line('password'), 'required');
            $this->form_validation->set_rules('confirm_password', $this->lang->line('confirm_password'), 'required|matches[password]');
            if ($this->form_validation->run() == false) {


                $data['verification_code'] = $verification_code;
                //render
                $this->load->view('admin/admin_resetpassword', $data);
            } else {

                // finally change the password
                $password = $this->input->post('password');
                $update_record = array(
                    'id' => $user->id,
                    'password' => $this->enc_lib->passHashEnc($password),
                    'verification_code' => ""
                );

                $change = $this->staff_model->update($update_record);
                if ($change) {
                    //if the password was successfully changed
                    $this->session->set_flashdata('message', $this->lang->line("password_reset_successfully"));
                    redirect('site/login', 'refresh');
                } else {
                    $this->session->set_flashdata('message', $this->lang->line("something_went_wrong"));
                    redirect('admin_resetpassword/' . $verification_code, 'refresh');
                }
            }
        } else {
            //if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', $this->lang->line('invalid_link'));
            redirect("site/forgotpassword", 'refresh');
        }
    }

    //reset password - final step for forgotten password
    public function resetpassword($role = null, $verification_code = null) {
        $app_name = $this->setting_model->get();
        $data['name'] = $app_name[0]['name'];
        if (!$role || !$verification_code) {
            show_404();
        }

        $user = $this->user_model->getUserByCodeUsertype($role, $verification_code);

        if ($user) {
            //if the code is valid then display the password reset form
            $this->form_validation->set_rules('password', $this->lang->line('password'), 'required');
            $this->form_validation->set_rules('confirm_password', $this->lang->line('confirm_password'), 'required|matches[password]');
            if ($this->form_validation->run() == false) {

                $data['role'] = $role;
                $data['verification_code'] = $verification_code;
                //render
                $this->load->view('resetpassword', $data);
            } else {

                // finally change the password

                $update_record = array(
                    'id' => $user->user_tbl_id,
                    'password' => $this->input->post('password'),
                    'verification_code' => ""
                );

                $change = $this->user_model->saveNewPass($update_record);
                if ($change) {
                    //if the password was successfully changed
                    $this->session->set_flashdata('message', $this->lang->line('password_reset_successfully'));
                    redirect('site/userlogin', 'refresh');
                } else {
                    $this->session->set_flashdata('message', $this->lang->line("something_went_wrong"));
                    redirect('user/resetpassword/' . $role . '/' . $verification_code, 'refresh');
                }
            }
        } else {
            //if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', $this->lang->line('invalid_link'));
            redirect("site/ufpassword", 'refresh');
        }
    }

    function ufpassword() {

        $app_name = $this->setting_model->get();
        $data['name'] = $app_name[0]['name'];
        $this->form_validation->set_rules('username', $this->lang->line('email'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('user[]', $this->lang->line('user_type'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {

            $this->load->view('ufpassword', $data);
        } else {
            $email = $this->input->post('username');
            $usertype = $this->input->post('user[]');

            $result = $this->user_model->forgotPassword($usertype[0], $email);

            if ($result && $result->email != "") {

                $verification_code = $this->enc_lib->encrypt(uniqid(mt_rand()));
                $update_record = array('id' => $result->user_tbl_id, 'verification_code' => $verification_code);
                $this->user_model->updateVerCode($update_record);
                if ($usertype[0] == "student") {
                    $name = $result->firstname . " " . $result->lastname;
                } else {
                    $name = $result->guardian_name;
                }
                $resetPassLink = site_url('user/resetpassword') . '/' . $usertype[0] . "/" . $verification_code;

                $sender_details = array('resetPassLink' => $resetPassLink, 'name' => $name, 'email' => $email);
                $this->mailsmsconf->mailsms('forgot_password', $sender_details);

                $this->session->set_flashdata('message', $this->lang->line("please_check_your_email_to_recover_your_password"));
                redirect('site/userlogin', 'refresh');
            } else {
                $data = array(
                    'name' => $app_name[0]['name'],
                    'error_message' => $this->lang->line('invalid_email_or_user_type')
                );
            }

            $this->load->view('ufpassword', $data);
        }
    }

    function userlogin() {
        if ($this->auth->user_logged_in()) {
            $this->auth->user_redirect();
        }
        $data = array();
        $data['title'] = 'Login';
        $school = $this->setting_model->get();
        $data['name'] = $school[0]['name'];
        $notice_content = $this->config->item('ci_front_notice_content');
        $notices = $this->cms_program_model->getByCategory($notice_content, array('start' => 0, 'limit' => 5));
        $data['notice'] = $notices;
        $data['school'] = $school[0];
        $this->form_validation->set_rules('username', $this->lang->line('username'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', $this->lang->line('password'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('userlogin', $data);
        } else {
            $login_post = array(
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password')
            );
            $login_details = $this->user_model->checkLogin($login_post);

            if (isset($login_details) && !empty($login_details)) {
                $user = $login_details[0];
                if ($user->is_active == "yes") {
                    if ($user->role == "student") {
                        $result = $this->user_model->read_user_information($user->id);
                    } else if ($user->role == "parent") {
                        $result = $this->user_model->checkLoginParent($login_post);
                    }

                    if ($result != false) {
                        $setting_result = $this->setting_model->get();
                        if ($result[0]->lang_id == 0) {
                            $language = array('lang_id' => $setting_result[0]['lang_id'], 'language' => $setting_result[0]['language']);
                        } else {
                            $language = array('lang_id' => $result[0]->lang_id, 'language' => $result[0]->language);
                        }
                        if ($result[0]->role == "parent") {
                            $username = $result[0]->guardian_name;
                            if ($result[0]->guardian_relation == "Father") {
                                $image = $result[0]->father_pic;
                            } else if ($result[0]->guardian_relation == "Mother") {
                                $image = $result[0]->mother_pic;
                            } else if ($result[0]->guardian_relation == "Other") {
                                $image = $result[0]->guardian_pic;
                            }
                        } elseif ($result[0]->role == "student") {
                            $image = $result[0]->image;
                            $username = ($result[0]->lastname != "") ? $result[0]->firstname . " " . $result[0]->lastname : $result[0]->firstname;
                        }
                        $session_data = array(
                            'id' => $result[0]->id,
                            'login_username' => $result[0]->username,
                            'student_id' => $result[0]->user_id,
                            'role' => $result[0]->role,
                            'username' => $username,
                            'date_format' => $setting_result[0]['date_format'],
                            'currency_symbol' => $setting_result[0]['currency_symbol'],
                            'timezone' => $setting_result[0]['timezone'],
                            'sch_name' => $setting_result[0]['name'],
                            'language' => $language,
                            'is_rtl' => $setting_result[0]['is_rtl'],
                            'theme' => $setting_result[0]['theme'],
                            'image' => $result[0]->image,
                            'gender' => isset($result[0]->gender) ? $result[0]->gender : 'male',
                        );
                        $language_result1 = $this->language_model->get($language['lang_id']);
                        if ($this->customlib->get_rtl_languages($language_result1['short_code'])) {
                            $session_data['is_rtl'] = 'enabled';
                        }
                        $this->session->set_userdata('student', $session_data);
                        if ($result[0]->role == "parent") {
                            $this->customlib->setUserLog($result[0]->username, $result[0]->role);
                        }
                        redirect('user/user/choose');
                    } else {
                        $data['error_message'] = 'Account Suspended';
                        $this->load->view('userlogin', $data);
                    }
                } else {
                    $data['error_message'] = $this->lang->line('your_account_is_disabled_please_contact_to_administrator');
                    $this->load->view('userlogin', $data);
                }
            } else {
                $data['error_message'] = $this->lang->line('invalid_username_or_password');
                $this->load->view('userlogin', $data);
            }
        }
    }

    public function savemulticlass() {

        $student_id = '';
        $this->form_validation->set_rules('student_id', $this->lang->line('student'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'student_id' => form_error('student_id')
            );

            $array = array('status' => '0', 'error' => $msg, 'message' => '');
        } else {

            $data = array(
                'student_id' => date('Y-m-d', strtotime($this->input->post('student_id'))),
            );


            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }
    
     public function orcamento($token=null){#alterado
       try{
           
           $this->orcamento_model->getByToken(trim($token));
           $data['orcamento'] = $this->orcamento_model;
           $data['itens'] = $this->orcamento_item_model->getAll(['idOrcamento'=> $this->orcamento_model->idOrcamento]);
           
            /*$img = (FCPATH.'uploads/school_content/logo/'.$this->sch_setting_detail->admin_logo);
            $type = pathinfo($img, PATHINFO_EXTENSION);
            $datax = file_get_contents($img);*/
            
            $class = $this->class_model->get($this->orcamento_model->class_id);
            $section = $this->section_model->get($this->orcamento_model->section_id);
            $staff = $this->staff_model->get($this->orcamento_model->staff_id);
           
           $data['escola'] = [
               /*'nome' => $this->sch_setting_detail->name,
               'email' => $this->sch_setting_detail->email,
               'telefone' => $this->sch_setting_detail->phone,
               'endereco' => $this->sch_setting_detail->address,
               'logo' => 'data:image/' . $type . ';base64,' . base64_encode($datax),*/
               'turma'=> isset($class['class']) ? $class['class'] : '---',
               'periodo'=> isset($section['section']) ? $section['section'] : '---',
               'usuario'=> isset($staff['name']) ? $staff['name'].' '.$staff['surname'] : '---'
           ];
          
           
           $page = $this->load->view('admin/orcamento/print', $data,true);
          
          // echo $page;
          // die('');
           
           $html2pdf = new Html2Pdf('P', 'A4', 'pt', true, 'UTF-8', 5);
            // $html2pdf->setDefaultFont('dejavusans');
           $html2pdf->pdf->SetDisplayMode('real');
            $html2pdf->writeHTML($page);
            $html2pdf->output('Orcamento_'.$this->orcamento_model->idOrcamento.'.pdf');
            die();
           
       } catch (\Exception $e){
          echo 'Erro: '.$e->getMessage();
       }
    }


}