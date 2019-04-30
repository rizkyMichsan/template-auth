<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->_cek_session();
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');
        if ($this->form_validation->run() == false) {
            $data = array('title' => 'Login Form');
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/login');
            $this->load->view('templates/auth_footer');
        } else {
            $this->_login();
        }
    }

    private function _login()
    {

        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $user = $this->global->get_join('user', 'user_role',  [
            'email' => $email
        ]);

        if ($user->num_rows() > 0) {
            $us = $user->row_array();
            if ($us['is_active'] == 1) {
                if (password_verify($password, $us['password'])) {
                    $data = [
                        'email' => $us['email'],
                        'role_id' => $us['role_id']
                    ];
                    $this->session->set_userdata($data);
                    redirect('dashboard');
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Wrong Password!
          </div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                This email has not been activated!
              </div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Email Not Registered!
          </div>');
            redirect('auth');
        }
    }

    public function registration()
    {
        $this->_cek_session();
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
            'is_unique' => 'this email has already register'
        ]);
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[6]|matches[password2]', [
            'matches' => 'password not match!',
            'min_length' => 'password to short'
        ]);
        $this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');

        if ($this->form_validation->run() == false) {
            $data = array('title' => 'Registration Form');
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/registration');
            $this->load->view('templates/auth_footer');
        } else {
            $getUser = $this->global->get_limit('user', 1)->row_array();
            $userid = $getUser['id'] + 1;
            $data = array(
                'id' => $userid,
                'name' => htmlspecialchars($this->input->post('name', true)),
                'email' => htmlspecialchars($this->input->post('email', true)),
                'image' => 'default.png',
                'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                'is_active' => 0,
                'date_created' => time()
            );
            $this->global->insert('user', $data);
            $this->global->insert('user_role', [
                'user_id' => $userid,
                'role_id' => 2
            ]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Register Success, Please Login!
          </div>');
            redirect('auth');
        }
    }
    public function logout()
    {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('role_id');

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            You have been logged out!
          </div>');
        redirect('auth');
    }
    private function _cek_session()
    {
        $user = $this->global->get_join('user', 'user_role',  [
            'email' => $this->session->userdata('email')
        ]);
        if ($user->num_rows() > 0) {
            redirect('dashboard');
        }
    }
}
