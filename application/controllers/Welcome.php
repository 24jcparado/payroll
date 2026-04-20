<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {

	function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$this->session->set_userdata('reg_token', bin2hex(random_bytes(32)));
		$this->session->set_userdata('receiver_token', bin2hex(random_bytes(32)));

		$data['reg_token'] = $this->session->userdata('reg_token');
		$data['receiver_token'] = $this->session->userdata('receiver_token');
		$data['title'] = 'Login';
		$this->load->view('auth/login', $data);
	}

	public function login()
	{
		$this->load->library(['session', 'form_validation']);
		$this->load->helper('security');

		/* ================= BASIC INPUT ================= */
		$email    = trim($this->input->post('email', TRUE));
		$password = $this->input->post('password', TRUE);

		$ip         = $this->input->ip_address();
		$user_agent = $this->input->user_agent();

		/* ================= TOKEN CHECK ================= */
		$form_token    = $this->input->post('reg_token', TRUE);
		$session_token = $this->session->userdata('reg_token');

		if (!$form_token || $form_token !== $session_token) {

			$this->_log_attempt($email, $ip, $user_agent, 'error', 'Invalid or missing registration token');
			show_error('Invalid request token.', 403);
			return;
		}

		$this->session->unset_userdata('reg_token');

		/* ================= USER LOOKUP BY EMAIL ================= */
		$user = $this->Get_model->getUserByEmail($email);

		if (!$user) {

			$this->_log_attempt($email, $ip, $user_agent, 'error', 'User not found');
			$this->session->set_flashdata('error', 'Invalid login credentials.');
			redirect('welcome');
			return;
		}

		$user = $user[0];

		/* ================= ACCOUNT STATUS CHECK ================= */
		if (empty($user['active'])) {

			$this->_log_attempt($email, $ip, $user_agent, 'error', 'Inactive account');
			$this->session->set_flashdata('error', 'Account is inactive. Contact administrator.');
			redirect('welcome');
			return;
		}

		/* ================= PASSWORD VERIFY ================= */
		if (!password_verify($password, $user['password'])) {

			$secret = $this->config->item('encryption_key');
			$attempt_hash = hash_hmac('sha256', $password, $secret);

			$this->_log_attempt($email, $ip, $user_agent, 'error', 'Incorrect password', $attempt_hash);

			$this->session->set_flashdata('error', 'Invalid login credentials.');
			redirect('welcome');
			return;
		}

		/* ================= FORCE PASSWORD RESET ================= */
		if (!empty($user['force_reset']) && $user['force_reset'] == 0) {

			$this->session->set_userdata('temp_user_id', $user['user_id']);
			$this->session->set_flashdata('info', 'Please reset your password before continuing.');
			redirect('auth/force_reset');
			return;
		}

		/* ================= PRIVILEGE DECODE ================= */
		$privileges = !empty($user['privilege'])
			? json_decode($user['privilege'], TRUE)
			: [];

		foreach ($privileges as $k => $v) {
			$privileges[$k] = (bool) $v;
		}

		/* ================= SESSION REGENERATE ================= */
		$this->session->sess_regenerate(TRUE);

		/* ================= SET SESSION ================= */
		$this->session->sess_regenerate(TRUE);

		$this->session->set_userdata([
			'user_id'   => $user['user_id'],
			'email'     => $user['email'],
			'username'  => $user['username'],
			'name'      => $user['name'],
			'user_type' => $user['user_type'],
			'campus' => $user['campus'],
			'privilege' => $privileges,
			'logged_in' => TRUE
		]);
		

		$this->Update_model->lastLogin($user['user_id']);

		$this->_log_attempt($email, $ip, $user_agent, 'success', 'Login successful');

		/* ================= ROLE REDIRECT ================= */
		switch ($user['user_type']) {

			case 'Admin':
				redirect('admin');
				break;

			case 'Payroll':
				redirect('payroll');
				break;

			case 'External':
				redirect('payroll');
				break;

			default:
				redirect('welcome');
		}
	}

	private function _log_attempt($username, $ip, $agent, $status, $message, $password_hash = null)
	{
		$this->Login_log_model->add_log([
			'username'              => $username,
			'ip_address'            => $ip,
			'user_agent'            => $agent,
			'status'                => $status,
			'message'               => $message,
			'entered_password_hash' => $password_hash
		]);
	}

    public function change_password()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('welcome/login');
        }

        if ($this->input->post()) {

            $this->form_validation->set_rules('password', 'New Password', 
                'required|min_length[8]');

            if ($this->form_validation->run()) {

                $user_id = $this->session->userdata('user_id');
                $new_password = $this->input->post('password');

                $this->Update_model->update_password($user_id, $new_password);

                $this->session->set_flashdata('success', 'Password updated successfully.');
                redirect('welcome/login');
            }
        }

        $this->load->view('auth/change_password');
    }

    public function logout()
	{
		$this->session->sess_destroy();
		redirect('welcome');
		exit;
	}




	// Verify Token for Payroll Verification
	public function verify_token($token = null)
	{
		if ($this->input->post('token_id')) {
			$token = strtoupper(trim($this->input->post('token_id')));
		}
		if (empty($token)) {
			show_error('Invalid Payroll Token.', 400);
		}
		$payroll = $this->Get_model->get_payroll_by_token($token);

		if (!$payroll) {
			show_error('Payroll record not found or invalid token.', 404);
		}
		$type = strtoupper(trim($payroll->payroll_type));

		$data['payroll'] = $payroll;

		if ($type === 'GENERAL PAYROLL') {
			$this->load->view('receiver/general_payroll', $data);
		} elseif ($type === 'OVERLOAD' || $type === 'PART_TIME') {
			$this->load->view('receiver/overload_parttime', $data);
		} else {
			show_error('Unsupported payroll type.', 400);
		}
	}

	public function receiver_login()
	{
		$this->load->library(['session']);
		$this->load->helper('security');

		/* ================= INPUT ================= */
		$username = trim($this->input->post('username', TRUE));
		$password = $this->input->post('password', TRUE);

		$ip         = $this->input->ip_address();
		$user_agent = $this->input->user_agent();

		/* ================= TOKEN CHECK ================= */
		$form_token    = $this->input->post('receiver_token', TRUE);
		$session_token = $this->session->userdata('receiver_token');

		if (!$form_token || $form_token !== $session_token) {

			$this->_log_attempt($username, $ip, $user_agent, 'error', 'Invalid token (receiver)');
			show_error('Invalid request token.', 403);
			return;
		}

		$this->session->unset_userdata('receiver_token');

		/* ================= USER LOOKUP ================= */
		$user = $this->Get_model->get_user_receiver($username);

		if (!$user) {

			$this->_log_attempt($username, $ip, $user_agent, 'error', 'Receiver not found');
			$this->session->set_flashdata('error', 'Invalid login credentials.');
			redirect('welcome');
			return;
		}

		/* ================= STATUS CHECK ================= */
		if ($user->status !== 'active') {

			$this->_log_attempt($username, $ip, $user_agent, 'error', 'Receiver inactive');
			$this->session->set_flashdata('error', 'Account is inactive.');
			redirect('welcome');
			return;
		}

		/* ================= PASSWORD VERIFY ================= */
		if (!password_verify($password, $user->password_hash)) {

			$secret = $this->config->item('encryption_key');
			$attempt_hash = hash_hmac('sha256', $password, $secret);

			$this->_log_attempt($username, $ip, $user_agent, 'error', 'Incorrect password (receiver)', $attempt_hash);

			$this->session->set_flashdata('error', 'Invalid login credentials.');
			redirect('welcome');
			return;
		}

		/* ================= SESSION SECURITY ================= */
		$this->session->sess_regenerate(TRUE);

		/* ================= SET SESSION ================= */

		$middle = !empty($user->middle_name) 
			? ' ' . strtoupper(substr($user->middle_name, 0, 1)) . '.' 
			: '';

		$full_name = $user->first_name . $middle . ' ' . $user->last_name;
		$this->session->set_userdata([
			'receiver_id'   => $user->receiver_id,
			'receiver_name' => $full_name,
			'receiver_role' => $user->role,
			'receiver_logged_in' => TRUE
		]);

		/* ================= UPDATE LAST LOGIN ================= */
		$this->Update_model->update_login_receiver($user->receiver_id);

		$this->_log_attempt($username, $ip, $user_agent, 'success', 'Receiver login success');

		/* ================= REDIRECT ================= */
		redirect('receiver/dashboard');
	}
}
