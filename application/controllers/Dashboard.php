<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->_cek_session();
    }
    public function index()
    {

        $data = [
            'title' => 'Dashboard',
            'user'  => $this->global->get_where('user', [
                'email' => $this->session->userdata('email')
            ])->row_array(),

        ];
        $this->load->view('templates/header', $data);
        $this->load->view('dashboard/dashboard', $data);
        $this->load->view('templates/footer');
    }
    private function _cek_session()
    {
        $user = $this->global->get_join('user', 'user_role',  [
            'email' => $this->session->userdata('email')
        ]);
        if ($user->num_rows() == 0) {
            redirect('auth');
        }
    }
}
