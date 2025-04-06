<?php namespace App\Controllers;

use CodeIgniter\Controller;

class Member extends BaseController {
	var $username;

/**
* Controller for the webmaster
*/
	public function index() {
    if($this->check_mem()) {
	  	echo view('template/header_member.php');
		$data = $this->get_mem_data();
		$msg = $this->uri->getSegment(2);
		if($msg != '') {
			if($msg == 'mem_ok') {
				$data['msg'] = '<div class="text-success"><strong>Success!</strong> Thank you for updating your membership!</div><br>';
			}
			if($msg == 'memdon_ok') {
				$data['msg'] = '<div class="text-success"><strong>Success!</strong> Thank you for updating your membership and donation!</div><br>';
			}
			if($msg == 'donation_ok') {
				$data['msg'] = '<div class="text-success"><strong>Success!</strong> Thank you for your generosity!</div><br>';
			} 			 
		} 
		else {
			$data['msg'] = '';
		}
		echo view('members/member_view.php', $data);
	}
    else {
	  	echo view('template/header');
			$this->login_mod->logout();
      $data['title'] = 'Login Error';
      $data['msg'] = 'There was an error while checking your credentials.<br><br>';
      echo view('status/status_view.php', $data);
    }
		echo view('template/footer.php');
	}

	private function get_mem_data() {
		$data['user'] = $this->login_mod->get_cur_user();
		$mem_arr = $this->mem_mod->get_mem($data['user']['id_user']);
		$data['primary'] = $mem_arr['primary'];
		$data['fam_arr'] = $this->mem_mod->get_fam_mems($data['user']['id_user']);
		$data['member_types'] = $this->master_mod->get_member_types();
		$data['lic'] = $this->data_mod->get_lic();
		return $data;
	}

	public function renew() {
		echo view('template/header_member');
		$new_usr = $this->mem_mod->get_member_by_email(strtolower($this->request->getPost('email')));
		if($new_usr['flag']) {
			$data = array();
			helper(['form', 'url']);
			$data['id_members'] = $new_usr['id_members'];
			$data['fname'] = '';
			$data['lname'] = '';
			$data['email'] = $new_usr['email'];
			$data['callsign'] = $new_usr['callsign'];
			$data['phone'] = '';
			$data['street']= '';
			$data['city'] = '';
			$data['state_cd'] = $new_usr['state'];
			$data['zip_cd'] = '';
			$data['msg'] = '';
			$data['twitter'] = '';
			$data['facebook'] = '';
			$data['linkedin'] = '';
			$data['googleplus'] = '';
			$data['cur_year'] = $new_usr['cur_year'];
			$data['mem_since'] = $new_usr['mem_since'];
			$data['type'] = $new_usr['mem_type'];
			$data['license'] = $new_usr['license'];
			$data['lic'] = $this->data_mod->get_lic();
			$data['mem_types'] = $this->master_mod->get_member_types();
			$data['id_mem_types'] = $new_usr['id_mem_types'];
			$data['states'] = $this->data_mod->get_states_array();
			echo view('members/renew_view', $data);
		}
		else {
			$data['title'] = 'Error!';
		if($new_usr['empty']) {
			$data['msg'] = 'Please, enter your email.<br><br>';
		}
		else {
				$data['msg'] = '<p class="text-danger">There was an error processing your data.</p> Most likely you either used a wrong email or you are not an MDARC member.<br><br>';
		}
			echo view('status/status_view', $data);
		}
		echo view('template/footer');
	}

	public function add_member() {
		$email = $this->uri->getSegment(2);
		echo view('template/header_light');
		$data['states'] = $this->data_mod->get_states_array();
		$data['lic'] = $this->data_mod->get_lic();
		$data['mem_types'] = $this->master_mod->get_member_types();
		echo view('public/add_mem_view', $data);
		echo view('template/footer');
	}
/**
 * Processes the new member v3
 */
	public function process_mem() {
		echo view('template/header');
		$param['email'] =  trim($this->request->getPost('email'));
		$param['callsign'] =  trim($this->request->getPost('callsign'));
		$param['address'] = $this->request->getPost('address');
		$param['city'] = $this->request->getPost('city');
		$param['state'] = $this->request->getPost('state');
		$param['zip'] = $this->request->getPost('zip');
		$param['fname'] = $this->request->getPost('fname');
		$param['lname'] = trim($this->request->getPost('lname'));
		$param['license'] = $this->request->getPost('sel_lic');
		$param['mem_since'] = trim($this->request->getPost('mem_since'));
		$param['w_phone'] = $this->request->getPost('w_phone');
		$param['h_phone'] = $this->request->getPost('h_phone');
		$param['comment'] = trim($this->request->getPost('comment'));
		$param['id_mem_types'] = $this->request->getPost('mem_types');
		$param['paym_date'] = time();
		$param['cur_year'] = date('Y', time());
		$param['timestamp'] = time();
		$strCarr = $this->request->getPost('carrier');
		$email = $this->request->getPost('email');
		if(strlen($strCarr) > 0 ) $param['hard_news'] = 'TRUE';
		//echo '<br><br><br>mem type: ' . $this->request->getPost('mem_types');
		filter_var($email, FILTER_VALIDATE_EMAIL) ? $param['email'] = $email : $param['email'] = 'none';
		$this->request->getPost('arrl') == 'on' ? $param['arrl_mem'] = 'TRUE' : $param['arrl_mem'] = 'FALSE';
		$this->request->getPost('hard_news') == 'on' ? $param['hard_news'] = 'TRUE' : $param['hard_news'] = 'FALSE';
		$this->request->getPost('dir') == 'on' ? $param['hard_dir'] = 'TRUE' : $param['hard_dir'] = 'FALSE';
		$this->request->getPost('mem_card') == 'on' ? $param['mem_card'] = 'TRUE' : $param['mem_card'] = 'FALSE';
		$this->request->getPost('dir_ok') == 'on' ? $param['ok_mem_dir'] = 'TRUE' : $param['ok_mem_dir'] = 'FALSE';
		if($this->request->getPost('howHeard') == 'arrlWeb') $param['comment'] = 'Heard about MDARC from ARRL website';
		if($this->request->getPost('howHeard') == 'mdarcTest') $param['comment'] = 'Heard about MDARC at license testing at MDARC';
		if($this->request->getPost('howHeard') == 'otherTest') $param['comment'] = 'Heard about MDARC at license testing at another club';
		if($this->request->getPost('howHeard') == 'otherReason') $param['comment'] = 'Heard about MDARC somewhere else';
		$param['comment'] .= ': ' . $this->request->getPost('txtOtherReason');

		$this->uri->setSilent();
		$param['id'] = $this->uri->getSegment(2);

		if ($this->staff_mod->edit_mem($param)) {
			$param['id_member'] = $this->mem_mod->get_last_mem()['id_members'];
			//$data['title'] = 'Member added!';
			//$data['msg'] = 'Member added no problem <br /><br />';
			//$data['msg'] .= 'ID member: ' . $param['id_member'];
			//echo view('status/status_view', $data);
			$param['repStr'] = $this->request->getPost('repeater');
			$param['mdarcStr'] = $this->request->getPost('mdarc_donation');
			$param['mdarc_mem'] = $this->request->getPost('mdarc-mem');
			$param['carrStr'] = $strCarr;
			$this->make_payment($param);
		}
		else {
			$data['title'] = 'Douplicate Entry Error!';
			$data['msg'] = 'This is duplicate entry. The member ' . $param['lname'] . ' with callsign ' . $param['callsign'] . ' is already in the database.<br><br>';
			$data['msg'] .= 'Go back to ' . anchor('members', 'members listing');
			echo view('status/status_view', $data);
		}
		echo view('template/footer');
	}

	public function do_renew() {
		$id = $this->uri->getSegment(2);
		echo view('template/header');
		$param['email'] =  trim($this->request->getPost('email'));
		$param['callsign'] =  trim($this->request->getPost('callsign'));
		$param['address'] = $this->request->getPost('address');
		$param['city'] = $this->request->getPost('city');
		$param['state'] = $this->request->getPost('state_cd');
		$param['zip'] = $this->request->getPost('zip_cd');
		$param['fname'] = $this->request->getPost('fname');
		$param['lname'] = trim($this->request->getPost('lname'));
		$param['license'] = $this->request->getPost('sel_lic');
		$param['mem_since'] = $this->request->getPost('mem_since');
		$param['w_phone'] = $this->request->getPost('phone');
		$param['h_phone'] = $this->request->getPost('phone');
		$param['mem_type'] = $this->request->getPost('mem_type');
		$param['id_mem_types'] = $this->request->getPost('mem_types');
		$param['cur_year'] = $this->request->getPost('cur_year');
		$param['paym_date'] = 864001;
		$param['timestamp'] = time();
		$strCarr = $this->request->getPost('carrier');
		$email = $this->request->getPost('email');
		if(strlen($strCarr) > 0 ) $param['hard_news'] = 'TRUE';
		//echo '<br><br><br>mem type: ' . $this->request->getPost('mem_types');
		filter_var($email, FILTER_VALIDATE_EMAIL) ? $param['email'] = $email : $param['email'] = 'none';
		$this->request->getPost('arrl') == 'on' ? $param['arrl_mem'] = 'TRUE' : $param['arrl_mem'] = 'FALSE';
		$this->request->getPost('hard_news') == 'on' ? $param['hard_news'] = 'TRUE' : $param['hard_news'] = 'FALSE';
		$this->request->getPost('dir') == 'on' ? $param['hard_dir'] = 'TRUE' : $param['hard_dir'] = 'FALSE';
		$this->request->getPost('mem_card') == 'on' ? $param['mem_card'] = 'TRUE' : $param['mem_card'] = 'FALSE';
		$this->request->getPost('dir_ok') == 'on' ? $param['ok_mem_dir'] = 'TRUE' : $param['ok_mem_dir'] = 'FALSE'; 

		$this->uri->setSilent();
		$param['id'] = $this->uri->getSegment(2);

		if ($this->staff_mod->edit_mem($param)) {
			$param['id_member'] = $id;
			$data['title'] = 'Member added!';
			$data['msg'] = 'Member added no problem <br /><br />';
			$data['msg'] .= 'ID member: ' . $param['id_member'];
			//echo view('status/status_view', $data);
			$param['repStr'] = $this->request->getPost('repeater');
			$param['mdarcStr'] = $this->request->getPost('mdarc_donation');
			$param['mdarc_mem'] = $this->request->getPost('mdarc-mem');
			$param['carrStr'] = $strCarr;
			$this->make_payment($param);
		}
		else {
			$data['title'] = 'Douplicate Entry Error!';
			$data['msg'] = 'This is duplicate entry. The member ' . $param['lname'] . ' with callsign ' . $param['callsign'] . ' is already in the database.<br><br>';
			$data['msg'] .= 'Go back to ' . anchor('members', 'members listing');
			echo view('status/status_view', $data);
		}
		echo view('template/footer');
	}

/*
* To pass the user data we will use encrytion as per:
* https://www.geeksforgeeks.org/how-to-encrypt-and-decrypt-a-php-string/
*/
	public function go_pay() {
		$param['id_member'] = $this->uri->getSegment(2);
		$param['repStr'] = $this->request->getPost('repeater');
		$param['mdarcStr'] = $this->request->getPost('mdarc_donation');
		$param['mdarc_mem'] = $this->request->getPost('mdarc-mem');
		$param['carrStr'] = $this->request->getPost('carrier');
		$this->make_payment($param);
	}

	private function make_payment($param) {
		// retrieve payment data and insert them in db 		
		
		if(substr($param['repStr'], 0, 1) == '$') {
			$rep_donation = floatval(substr($param['repStr'], 1, strlen($param['repStr']) - 1));
		} else {
			$rep_donation = floatval($param['repStr']);
		}

		if(substr($param['mdarcStr'], 0, 1) == '$') {
			$mdarc_donation = floatval(substr($param['mdarcStr'], 1, strlen($param['mdarcStr']) - 1));
		} else {
			$mdarc_donation = floatval($param['mdarcStr']);
		}

		if($param['carrStr'] == 'add_carrier') {
			$carrAmount = $this->mem_mod->get_carr_amnt();
			//$carrAmount = 18;
		}
		else {
			$carrAmount = 0;
		}
		//get random string for payment validation
		$paym_data['val_string'] = bin2hex(openssl_random_pseudo_bytes(12));
		$paym_data['id_member'] = $param['id_member'];
		if( $rep_donation != 0) {
			$paym_data['id_payaction'] = 5;
			$paym_data['amount'] = $rep_donation;
			$this->mem_mod->do_payment($paym_data);
		}
		if($mdarc_donation != 0) {
			$paym_data['amount'] = $mdarc_donation;
			$paym_data['id_payaction'] = 7;
			$this->mem_mod->do_payment($paym_data);
		}
		if(strlen($param['mdarc_mem']) != 0) {
			if($param['mdarc_mem'] == 'new_mem') $paym_data['id_payaction'] = $this->mem_mod->get_new_mem_payaction();
			if($param['mdarc_mem'] == 'renewal') $paym_data['id_payaction'] = 1;
			if($param['mdarc_mem'] == 'public_renew') $paym_data['id_payaction'] = 12;
			$paym_data['amount'] = $this->mem_mod->mem_paym_amnt($paym_data);
			//$paym_data['amount'] = 45;
			$this->mem_mod->do_payment($paym_data);
			$locStr = "https://pay-v1.jlkconsulting.info/index.php/mdarc-payment/". $param['mdarc_mem'] . "/";
		}
		else {
			$locStr = "https://pay-v1.jlkconsulting.info/index.php/mdarc-payment/donation/";
		}

		if(strlen($param['carrStr']) > 0) {
			$paym_data['id_payaction'] = 10;
			$paym_data['amount'] = $carrAmount;
			$this->mem_mod->do_payment($paym_data);
		}
		header("Location: " . $locStr . $paym_data['val_string']);
		exit;
	}

	public function search() {
		if($this->check_mem()) {
	  	echo view('template/header_member.php');
			$search_str = $this->request->getPost('search');
			//$data = $this->mem_mod->search($search_str);
			$data = $this->master_mod->search($search_str);
			$data['states'] = $this->data_mod->get_states_array();
			$data['lic'] = $this->data_mod->get_lic();
			$data['mem_types'] = $this->staff_mod->get_mem_types();
			echo view('members/search_res_view.php', $data);
	    }
		else {
			echo view('template/header');
			$this->login_mod->logout();
			$data['title'] = 'Login Error';
			$data['msg'] = 'There was an error while checking your credentials.<br><br>';
			echo view('status/status_view.php', $data);
		}
		echo view('template/footer.php');
	}

/**
* Loads personal data into the form and displays it
*/
  public function pers_data() {
    if($this->check_mem()) {
	  	echo view('template/header_member.php');
			$data = $this->get_pers_data();
			$data['msg'] = NULL;
			$data['errors'] = array();
			$data['errors']['cell'] = NULL;
			$data['errors']['phone'] = NULL;
			$data['errors']['email'] = NULL;
			$data['errors']['callsign'] = NULL;
			echo view('members/pers_data_view.php', $data);
	   }
    else {
	  	echo view('template/header');
			$this->login_mod->logout();
      $data['title'] = 'Login Error';
      $data['msg'] = 'There was an error while checking your credentials.<br><br>';
      echo view('status/status_view.php', $data);
    }
		echo view('template/footer.php');
  }

	private function get_pers_data() {
		$data['user'] = $this->login_mod->get_cur_user();
		$mem_arr = $this->mem_mod->get_mem($data['user']['id_user']);
		$data['mem'] = $mem_arr['primary'];
		$data['fam_arr'] = $this->mem_mod->get_fam_mems($data['user']['id_user']);
		$data['member_types'] = $this->master_mod->get_member_types();
		$data['states'] = $this->data_mod->get_states_array();
		$data['lic'] = $this->data_mod->get_lic();
		$data['errors']['cell'] = NULL;
		$data['errors']['phone'] = NULL;
		$data['errors']['email'] = NULL;
		$data['errors']['callsign'] = NULL;
		return $data;
	}

	public function update_mem() {
		if($this->check_mem()) {
			$this->uri->setSilent();
			$param['id'] = $this->uri->getSegment(2);
			$param['email'] =  strtolower(trim($this->request->getPost('email')));
			$param['callsign'] = strtoupper(trim($this->request->getPost('callsign')));
			$param['address'] = $this->request->getPost('address');
			$param['city'] = $this->request->getPost('city');
			$param['state'] = $this->request->getPost('state');
			$param['zip'] = $this->request->getPost('zip');
			$param['fname'] = $this->request->getPost('fname');
			$param['lname'] = trim($this->request->getPost('lname'));
			$param['license'] = $this->request->getPost('sel_lic');
			$param['w_phone'] = $this->request->getPost('w_phone');
			$param['h_phone'] = $this->request->getPost('h_phone');
			$email = $this->request->getPost('email');
			$this->request->getPost('dir_ok') == 'on' ? $param['ok_mem_dir'] = 'TRUE' : $param['ok_mem_dir'] = 'FALSE';
			filter_var($email, FILTER_VALIDATE_EMAIL) ? $param['email'] = $email : $param['email'] = 'none';
			$this->request->getPost('arrl') == 'on' ? $param['arrl_mem'] = 'TRUE' : $param['arrl_mem'] = 'FALSE';

			$update_arr = $this->mem_mod->update_mem($param);
			echo view('template/header_member.php');

			if(!$update_arr['flag']) {
				$val_str = '';
				foreach ($update_arr['msg'] as $key => $value) {
					if($value != NULL) {$val_str .= $value . '<br>';}
				}
				$data = $this->get_pers_data();
				$data['msg'] = '<p class="text-danger"><strong>Errors!</strong> ';
				$data['msg'] .= $val_str . '</p>';
				$data['errors'] = $update_arr['msg'];
	      		echo view('members/pers_data_view.php', $data);
			}
			else {
				$data = $this->get_mem_data();
				$data['msg'] = '<div class="text-success"><strong>Success!</strong> Your changes have been saved</div><br>';
				echo view('members/member_view.php', $data);
			}
		}
		else {
			echo view('template/header');
			$this->login_mod->logout();
      $data['title'] = 'Login Error';
      $data['msg'] = 'There was an error while checking your credentials.<br><br>';
      echo view('status/status_view.php', $data);
		}
		echo view('template/footer.php');
	}

	public function add_fam_mem() {
		if($this->check_mem()) {
			$this->uri->setSilent();
			$param['parent_primary'] = $this->uri->getSegment(2);
			$param['callsign'] =  strtoupper(trim($this->request->getPost('callsign')));
			$param['fname'] = $this->request->getPost('fname');
			$param['lname'] = trim($this->request->getPost('lname'));
			$param['license'] = $this->request->getPost('sel_lic');
			$param['w_phone'] = $this->request->getPost('w_phone');
			$param['h_phone'] = $this->request->getPost('h_phone');
			$param['id_mem_types'] = $this->request->getPost('mem_types');
			$param['mem_type'] = $this->staff_mod->get_mem_types()[$param['id_mem_types']];
			$param['active'] = TRUE;
			$param['mem_since'] = date('Y', time());
			$param['comment'] = $this->request->getPost('comment');
			$email = strtolower($this->request->getPost('email'));
			filter_var($email, FILTER_VALIDATE_EMAIL) ? $param['email'] = $email : $param['email'] = 'none';
			$this->request->getPost('arrl') == 'on' ? $param['arrl_mem'] = 'TRUE' : $param['arrl_mem'] = 'FALSE';
			$this->request->getPost('dir_ok') == 'on' ? $param['ok_mem_dir'] = 'TRUE' : $param['ok_mem_dir'] = 'FALSE';
			$ret_str = $this->mem_mod->add_fam_mem($param);

			echo view('template/header_member');
			if($ret_str == NULL) {
				$data = $this->get_mem_data();
				$data['msg'] = '<p class="text-success fw-bold">Your family member was added!<br>';
				echo view('members/member_view.php', $data);
			}
			else {
	      $data = $this->get_pers_data();
	      $data['msg'] = $ret_str;
				echo view('members/pers_data_view.php', $data);
			}
			echo view('template/footer');
		}
		else {
			echo view('template/header');
			$this->login_mod->logout();
			$data['title'] = 'Login Error';
			$data['msg'] = 'There was an error while checking your credentials.<br><br>';
			echo view('status/status_view.php', $data);
			echo view('template/footer');
		}
	}
		public function edit_fam_mem() {
			if($this->check_mem()) {
				$this->uri->setSilent();
				$param['id'] = $this->uri->getSegment(2);
				$param['callsign'] = strtoupper(trim($this->request->getPost('callsign')));
				$param['fname'] = $this->request->getPost('fname');
				$param['lname'] = trim($this->request->getPost('lname'));
				$param['license'] = $this->request->getPost('sel_lic');
				$w_phone = $this->mem_mod->do_phone($this->request->getPost('w_phone'));
				$h_phone = $this->mem_mod->do_phone($this->request->getPost('h_phone'));
				$param['w_phone'] = $w_phone['phone'];
				$param['h_phone'] = $h_phone['phone'];
				$param['id_mem_types'] = $this->request->getPost('mem_types');
				$param['mem_type'] = $this->staff_mod->get_mem_types()[$param['id_mem_types']];
				$param['active'] = TRUE;
				$param['comment'] = $this->request->getPost('comment');
				$email = strtolower($this->request->getPost('email'));
				filter_var($email, FILTER_VALIDATE_EMAIL) ? $param['email'] = $email : $param['email'] = 'none';
				$this->request->getPost('arrl') == 'on' ? $param['arrl_mem'] = 'TRUE' : $param['arrl_mem'] = 'FALSE';
				$this->request->getPost('dir_ok') == 'on' ? $param['ok_mem_dir'] = 'TRUE' : $param['ok_mem_dir'] = 'FALSE';
				$ret_str = $this->mem_mod->edit_fam_mem($param);

				$data = $this->get_pers_data();
				$data['msg'] = '';

				$flag = TRUE;
				echo view('template/header_member');
				if(!$w_phone['flag']){
					$data['msg'] .= '<br><span class="text-danger">Family member cell phone was in wrong format and was not saved.</span>';
					$flag = FALSE;
				}

				if(!$h_phone['flag']) {
					$data['msg'] .= '<br><span class="text-danger">Family member other phone number was in wrong format and was not saved.</span>';
					$flag = FALSE;
				}

				if($ret_str != NULL) {
					$data['msg'] .= '<br><span class="text-danger">Family member error(s): ' . $ret_str . '</span>';
					$flag = FALSE;
				}

				if($flag) {
					$data['msg'] = '<p class="text-success"><strong>Success!</strong> ';
					$data['msg'] .= 'Your changes to family member data have been saved</p>';
				}

				echo view('members/pers_data_view.php', $data);
				echo view('template/footer');

			}
			else {
				echo view('template/header');
				$this->login_mod->logout();
	      $data['title'] = 'Login Error';
	      $data['msg'] = 'There was an error while checking your credentials.<br><br>';
	      echo view('status/status_view.php', $data);
			}
		}

  public function check_mem() {
		$retval = FALSE;
		$user_arr = $this->login_mod->get_cur_user();
		if($user_arr == NULL) {
			$retval = FALSE;
		}
		elseif((($user_arr['type_code'] == 99)) || (($user_arr['authorized'] == 1) && ($user_arr['type_code'] < 90))) {
			$retval = TRUE;
		}
		return $retval;
	}

	public function delete_fam_mem() {
		if($this->check_mem()) {
			$this->uri->setSilent();
			$this->mem_mod->delete_fam_mem($this->uri->getSegment(2));
			echo view('template/header_member');
			$data = $this->get_mem_data();
			$data['msg'] = '<p class="text-danger fw-bold">Your family member was deleted!<br>';
			echo view('members/member_view.php', $data);
		}
		else {
			echo view('template/header');
			$data['title'] = 'Authorization Error';
			$data['msg'] = 'You may not be authorized to view this page.<br><br>';
			echo view('status/status_view', $data);
		}
		echo view('template/footer');
	}

/**
* Show form to update username and password
*/
	public function show_update() {
		if($this->check_mem()) {
			echo view('template/header_member.php');
			$data = $this->get_mem_data();
			//$this->user_mod->update_acc($param);
			$data['msg'] = '';
			echo view('members/change_login_view', $data);
		}
		else {
			echo view('template/header');
			$data['title'] = 'Authorization Error';
			$data['msg'] = 'You may not be authorized to view this page.<br><br>';
			echo view('status/status_view', $data);
		}
		echo view('template/footer');
	}

	/**
	* Do the update of username and password
	*/
	public function do_update() {
		if($this->check_mem()) {
			echo view('template/header_member.php');
			$data = $this->get_mem_data();
			$param['id'] = $data['user']['id_user'];
			$param['pass'] = $this->request->getPost('pass');
			$param['pass2'] = $this->request->getPost('pass2');
			$param['username'] = strtolower($this->request->getPost('username'));
			$param['cur_username'] = $data['user']['username'];
			$flags = $this->user_mod->do_update($param);
			if($flags['flag']) {
				$data['msg'] = '<p class="text-success"><strong>Success!</strong> ';
				$data['msg'] .= 'Your changes were saved!</p>';
				echo view('members/member_view', $data);
			}
			else {
				$data['msg'] = '<p class="text-danger"><strong>Error(s)!</strong> ';
				if(!$flags['pass_comp']) {
					$data['msg'] .= '<br>The pasword requirements not met. It must be at least 12 characters long, 2 upper case, 2 lower case, 2 numbers and 2 special characters such as !@#$%^&*()\-_+.<br>'; }
				if(!$flags['pass_match']) {
					$data['msg'] .= 'The paswords did not match<br>';
				}
				if(!$flags['username']) {
					$data['msg'] .= 'The username is already taken. Please, use different username<br>';
				}
				$data['msg'] .= '</p>';
				echo view('members/change_login_view', $data);			}
		}
		else {
			echo view('template/header');
			$data['title'] = 'Authorization Error';
			$data['msg'] = 'You may not be authorized to view this page.<br><br>';
			echo view('status/status_view', $data);
		}
		echo view('template/footer');
	}

}
