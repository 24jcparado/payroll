<?php
class MY_Controller extends CI_Controller
{
    function checkSession($required_user_type = null)
    {
        if ($this->session->userdata('logged_in') !== TRUE) {
            $this->session->set_flashdata('error', 'Please login first.');
            redirect('welcome');
            exit;
        }

        if ($required_user_type !== null &&
            $this->session->userdata('user_type') !== $required_user_type) {    
            $this->session->set_flashdata('error', 'You do not have permission to access this page.');
            redirect('welcome');
            exit;
        }
    }
}
?>