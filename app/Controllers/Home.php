<?php

namespace App\Controllers;
use App\Libraries\MailService;

class Home extends BaseController {
  public function index() {
	// load public header first
		echo view('template/header');

	// if not logged in load main public page
		if(!($this->login_mod->is_logged())) {
			echo view('public/main_view', array('msg' => '', 'map_key' => getenv('GOOGLE_MAP_API_KEY')));
		}
		else {
	
	// if logged in then redirect to a proper page according to user level (every level has its own controller)
			return redirect()->route($this->login_mod->get_cur_user()['controller']);
		}

	// load footer - it is the same for every user level
		echo view('template/footer');
  }

  public function terms() {
	echo view('public/terms');
  }

  public function send_mail() {
	// $email = service('email');

	// $config['protocol'] = 'smtp';
	// $config['SMTPHost'] = 'smtp.ionos.com';
	// $config['SMTPUser'] = 'mdarc-memberships@arrleb.org';
	// $config['SMTPPass'] = 'CKPrb56yg!4Q$vC';
	// $config['SMTPPort'] = 587;
	// $config['mailType'] = 'html';

	// $email->initialize($config);

	// $email->setFrom('mdarc-memberships@arrleb.org', 'MDARC Membership Chair');
	// $email->setTo('mdarc-memberships@arrleb.org');
	
	// $email->setSubject('Email Test');
	// $email->setMessage('<h1>Send HTML Email using SMTP in CodeIgniter</h1>
    //         <p>This is a test email sending using SMTP mail server with PHPMailer.</p>');

	$mail = new MailService();

	$to = 'mdarc-memberships@arrleb.org';
	$subject = 'Test Email via PHPMailer';
	$message = '<p>This is a <b>test email</b> sent from PHPMailer without Composer.</p>';

	$result = $mail->sendMail($to, $subject, $message);

	$data['msg'] = $result;

	$data['title'] = 'Email msg status';
	echo view('status/status_view.php', $data);
  }

  public function test_email() {	

	$email = service('email');

	$config['protocol'] = 'smtp';
	$config['SMTPHost'] = 'smtp.ionos.com';
	$config['SMTPUser'] = 'mdarc-memberships@arrleb.org';
	$config['SMTPPass'] = 'CKPrb56yg!4Q$vC';
	$config['SMTPPort'] = 587;
	$config['mailType'] = 'html';

	$email->initialize($config);

	$email->setFrom('mdarc-memberships@arrleb.org', 'MDARC Membership Chair');
	$email->setTo('jkulisek.us@gmail.com');
	
	$email->setSubject('Email Test');
	$email->setMessage('<h1>Send HTML Email using SMTP in CodeIgniter</h1>
            <p>This is a test email sending using SMTP mail server with PHPMailer.</p>');

	if($email->send()) {
		$data['status']='OK!';
	}
	else {
		$data['status'] = 'Error!';
	}

	echo view('email_send_view.php', $data);
  }

  public function contact() {

// if not logged in load generic header for contact page
    if(!($this->login_mod->is_logged())) {
      echo view('template/header_light');
    }
    else {
	
// if logged in then redirect to a proper page according to user level (every level has its own controller)
      echo view('template/header_' . $this->login_mod->get_cur_user()['controller']);
    }

// load contact view with the form
    echo view('public/contact_view');

// load footer - it is the same for every user level
    echo view('template/footer');
  }

  public function faqs() {
	echo view('template/header_light');
    $data = $this->staff_mod->get_faqs();
    echo view('public/faqs_view', $data);
    echo view('template/footer');
  }

  public function send_contact() {

    echo view('template/header_contact');
    $param['fname'] = $this->request->getPost('fname');
    $param['lname'] = $this->request->getPost('lname');
    $param['email'] = $this->request->getPost('email');
    $param['msg'] = $this->request->getPost('msg');

	// Validation
// 	$input = $this->validate([
// 		'recaptcha_response' => 'required|verifyrecaptchaV3',
// 	  ],[
// 		'recaptcha_response' => [
// 			  'required' => 'Please verify captcha',
// 		],
//    ]);

//	For now before reCAPTCHA gets fully implemented
		$input = true;

   if (!$input) { // Not valid

		//$data['validation'] = $this->validator;

		$data['title'] = 'Error in reCAPTCHA validation!';
		$data['msg'] = '<p class="text-danger">There was an error.</p> Thank you for visiting our web portal!.<br><br>';
		$data['msg'] .= 'Validation: ' . $data['validation'];
		echo view('status/status_view', $data);

   }
   else { 
		
		$flag = $this->home_mod->contact($param);
		if($flag) {
			$data['title'] = 'Message was sent!';
			$data['msg'] = '<p class="text-danger">Your message was sent.</p> Thank you for visiting our web portal!.<br><br>';
			echo view('status/status_view', $data);
		}
		else {
			$data['title'] = 'Error!';
			$data['msg'] = '<p class="text-danger">There was an error.</p> Thank you for visiting our web portal!.<br><br>';
			$data['msg'] .= 'fname: ' . $param['fname'];
			echo view('status/status_view', $data);
		}
   }
	echo view('template/footer');
	
  }

/**
* Loads up the blank registration form
*/
  public function register() {
  	echo view('template/header_light');
	$email = $this->request->getPost('email') ?? 'none';
	if(!$this->user_mod->check_email($email)){
		$new_usr = $this->mem_mod->get_member_by_email(strtolower($email));
		if($new_usr['flag']) {
			$data = array();
			helper(['form', 'url']);
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
			$data['user_types'] = $this->user_mod->get_user_types();
			$data['states'] = $this->data_mod->get_states_array();
			echo view('public/register_view', $data);
		}
		else {
			$data['title'] = 'Not MDARC Member!';
		if($new_usr['empty']) {
			$data['msg'] = 'Please, enter your MDARC email.<br><br>';
		}
		else {
				$data['msg'] = '<p class="text-danger">You need to be MDARC member.</p> Your email is not listed in the MDARC database. Please, enter your MDARC email to register as a new user.<br><br>';
		}
			echo view('status/status_view', $data);
		}
	}
	else {
		$data['title'] = "Already a User!";
		$data['msg'] = "You already are registered as a user on this system.";
		echo view('status/status_view', $data);
	}
  	
  	echo view('template/footer');
  }

/**
* The first step of user registration when the user sends the initial data. The second step will include setting the username and password
* via the confirm_reg() below
*/
	public function send_reg() {
		helper(['form', 'url']);
		$param = array();
		$param['fname'] = $this->request->getPost('fname');
		$param['lname'] = $this->request->getPost('lname');
		$param['email'] = $this->request->getPost('email');
		$param['street'] = $this->request->getPost('street');
		$param['city'] = $this->request->getPost('city');
		$param['state_cd'] = $this->request->getPost('state_cd');
		$param['zip_cd'] = $this->request->getPost('zip_cd');
		$param['phone'] = $this->request->getPost('phone');
		$param['callsign'] = $this->request->getPost('callsign');
		$param['facebook'] = $this->request->getPost('facebook');
		$param['twitter'] = $this->request->getPost('twitter');
		$param['linkedin'] = $this->request->getPost('linkedin');
		$param['id_user_type'] = 0;

		$email_flag = TRUE;
		if (!filter_var($param['email'], FILTER_VALIDATE_EMAIL)) {
  		$email_flag = FALSE;
		}

		$isPhoneNum = FALSE;
		//eliminate every char except 0-9
		$justNums = preg_replace("/[^0-9]/", '', $param['phone']);

		//eliminate leading 1 if its there
		if (strlen($justNums) == 11) $justNums = preg_replace("/^1/", '',$justNums);

		//if we have 10 digits left, it's probably valid.
		if (strlen($justNums) == 10) $isPhoneNum = TRUE;

		$param['ip'] = $this->get_ip();

		//echo 'ip: ' . $param['ip'];
		echo view('template/header');
		if($param['lname'] == '' || $param['fname'] == '' || $email_flag == FALSE || $param['street'] == '' || $param['city'] == '' || $param['zip_cd'] == ''
				|| $isPhoneNum == FALSE || $param['fname'] == $param['lname']) {
            $data = $param;
            $data['state_cd'] = $param['state_cd'];
            $data['zip_cd'] = $param['zip_cd'];
            $data['phone'] = $param['phone'];
            $data['title'] = 'Error!';
            $data['msg'] = '<span style="color: red">Please, fill all the required information marked by *. Thank you!</span>';
            if(!$email_flag) {
              $data['msg'] .= ' Note: Your email is wrong';
            }
            elseif(!$isPhoneNum) {
              $data['msg'] .= ' Note: Your email is wrong';
            }
				$data['states'] = $this->data_mod->get_states_array();
				$data['user_types'] = $this->user_mod->get_user_types();
            	echo view('public/register_view', $data);
        }
        else {
			$retarr = $this->user_mod->register($param);
          	if($retarr['flag']) {

              $data['title'] = 'Thank you!';

							$msg_str = 'Your registration has been sent. You will get an email with further instructions within 72 hours.<p class="text-danger"> Please, also check your spam messages since this email can be wrongly flagged as spam by your email server.</p> Thank you! <br><br>';

							//$msg_str = 'Still working on it. Check again later. Click to go back to ' . anchor(base_url(), 'home page here') . '<br><br>';

              $data['msg'] = $msg_str;
          }
          else {
              $data['title'] = 'Error!';
              $data['msg'] = '<span style="color: red">' . $retarr['msg'];
          }
          echo view('status/status_view', $data);
        }
        echo view('template/footer');
    	}

    /**
    * This is called by clicking on the emailed URL to confirm registration by setting up username and password
    */
    	public function set_pass() {
    			$this->uri->setSilent();
    	    echo view('template/header');
    			$data['email_key'] = $this->uri->getSegment(2);
    			$data['msg'] = '';
    			echo view('public/set_pass_view', $data);
    	    echo view('template/footer');
    	}

      public function change_pass() {
    			$this->uri->setSilent();
    	    echo view('template/header');
    			$data['email_key'] = $this->uri->getSegment(2);
    			$data['msg'] = '';
    			echo view('public/change_pass_view', $data);
    	    echo view('template/footer');
    	}

    	/**
    	* This is called by clicking on the emailed URL to confirm registration by setting up username and password
    	*/
    		public function reset_password() {
    				$this->uri->setSilent();
    		    echo view('template/header');
    				$data['email_key'] = '';
    				$data['msg'] = '';
    				echo view('public/reset_pass_view', $data);
    		    echo view('template/footer');
    		}

    	/**
    	* This is the final step for the user registration when he will send his username and password.
    	* When these are saved the master user will approve him and only then the user will gain
    	* appropriate access according his user profile
    	* Also can be used for username and password reset (still todo)
    	*/
    	public function set_user() {
    		echo view('template/header');
    		$param['flag'] = TRUE;
    		$param['email_key'] = $this->uri->getSegment(2);
    		$param['pass'] = $this->request->getPost('pass');
    		$param['pass2'] = $this->request->getPost('pass2');
			$username = $this->request->getPost('username') ?? '';
    		$param['username'] = strtolower($username);
    		$flags_arr = $this->user_mod->set_user($param);
    		if($flags_arr['flag']) {
    			$data['title'] = 'Username and Password Set!';
    			$data['msg'] = '<strong style="color: red">You still need to be authorized by the system admin</strong> and will be notified soon.
    			<br><br>Thank you for your interest in MDARC Members Portal! <br><br>You can go back to home page by clicking '
    			 		. anchor('Home', 'here'). '<br><br>';
    			echo view('status/status_view', $data);
    		}
    		else {
    			$data['msg'] = 'Please, fix the following error(s):<br>';
    			$data['id_user'] = $flags_arr['id_user'];
    			if($data['id_user'] > 0) {
    				if($flags_arr['usr_dup']) $data['msg'] .= '<p style="color:red;">Duplicate username</p>';
    				if(!($flags_arr['pass_match'])) $data['msg'] .= '<p style="color:red;">Passwords do not match</p>';
    				if(!($flags_arr['pass_comp'])) $data['msg'] .= '<p style="color:red;">Password complexity requirement not met. Password needs to contain at least two uppercase letters, two lowercase letters, two numbers and two special characters (!@#$%^&*()\-_+.]) in addition to be at least 12 characters long.</p>';
    				$data['email_key'] = $flags_arr['email_key'];
    				echo view('public/set_pass_view', $data);
    			}
    			else {
    				$data['title'] = 'Error!';
    				$data['msg'] = 'There was an error processing your data. <br><br>You can go back to home page by clicking '
    				 		. anchor('Home', 'here'). '<br><br>';
    				echo view('status/status_view', $data);
    			}
    		}

    		echo view('template/footer');
    	}

      public function change_user_pass() {
    		echo view('template/header');
    		$param['flag'] = TRUE;
    		$param['email_key'] = $this->uri->getSegment(2);
    		$param['pass'] = $this->request->getPost('pass');
    		$param['pass2'] = $this->request->getPost('pass2');
			$username = $this->request->getPost('username') ?? '';
    		$param['username'] = strtolower($username);
    		$flags_arr = $this->user_mod->change_user_pass($param);
    		if($flags_arr['flag']) {
    			$data['title'] = 'Your Password Was Successfully Changed!';
    			$data['msg'] = 'Now you can go back to home page and login with your new password.';
    			echo view('status/status_view', $data);
    		}
    		else {
    			$data['msg'] = 'Please, fix the following error(s):<br>';
    			$data['id_user'] = $flags_arr['id_user'];
    			if($data['id_user'] > 0) {
    				if(!($flags_arr['pass_match'])) $data['msg'] .= '<p style="color:red;">Passwords do not match</p>';
      			if(!($flags_arr['usr_chk'])) $data['msg'] .= '<p style="color:red;">You entered a wrong username. Please, enter your correct username.</p>';
    				if(!($flags_arr['pass_comp'])) $data['msg'] .= '<p style="color:red;">Password complexity requirement not met. Password needs to contain at least two uppercase letters, two lowercase letters, two numbers and two special characters (!@#$%^&*()\-_+.]) in addition to be at least 8 characters long.</p>';
    				$data['email_key'] = $flags_arr['email_key'];
    				echo view('public/change_pass_view', $data);
    			}
    			else {
    				$data['title'] = 'Error!';
    				$data['msg'] = 'There was an error processing your data. <br><br>You can check your username and try again or contact the administrator. <br><br>';
            foreach($flags_arr as $key => $value) {
              //$value ? $data['msg'] .= $key . ' ' . 'OK<br>' : $data['msg'] .= $key . ' ' . 'Not OK<br>';
            }
    				echo view('status/status_view', $data);
    			}
    		}

    		echo view('template/footer');
    	}

/**
* Recovers lost password
*/
    public function load_password() {
    	echo view('template/header');
    	$param['email'] = $this->request->getPost('email');
    	$flags = $this->user_mod->load_password($param);
    	if(!(filter_var($param['email'], FILTER_VALIDATE_EMAIL))) {
        $data['title'] = 'Email Error!';
        $data['msg'] = '<p class="text-danger">Enter a valid email</p>. Please, go back and try again. Thank you!<br><br>';
        echo view('status/status_view', $data);
    	}
    	elseif(!$flags['flag']) {
        $data['title'] = 'Email Error!';
        $data['msg'] = '<p class="text-danger">You entered a wrong email. Please, go back and try again.</p> Thank you!<br><br>';
        echo view('status/status_view', $data);
    	}
    	elseif(!$flags['db_flag']) {
        $data['title'] = 'DB Error!';
        $data['msg'] = '<p class="text-danger">There was a DB error</p>. Please, try again later. Thank you!<br><br>';
        echo view('status/status_view', $data);
    	}
      else {
        $data['title'] = 'Password Change Request Sent!';
    		$data['msg'] = 'Change password link was sent to your email. Thank you for using our Membership Portal!<br>';
    		echo view('status/status_view', $data);
      }
    	echo view('template/footer');
    }

    /**
    * Self-explanatory: calls the logout function from Loging_model
    */
	public function logout() {
		$this->login_mod->logout();
		echo view('template/header');
		echo view('public/main_view', array('msg' => '<p class="text-success lead"><i class="bi bi-check-circle"></i> You have succesfuly logged out. Thank you and have a great day!</p>', 'map_key' => getenv('GOOGLE_MAP_API_KEY')));
		echo view('template/footer');
	}

	public function welcome_new() {
		echo view('template/header');
		echo view('public/main_view', array('msg' => '<p class="text-success lead"><i class="bi bi-check-circle"></i> Welcome to the MDARC community as a new member! Please, check your confirmation email. Thank you!</p>'));
		echo view('template/footer');
	}
	public function public_renew() {
		echo view('template/header');
		echo view('public/main_view', array('msg' => '<p class="text-success lead"><i class="bi bi-check-circle"></i> Thank you for renewing your membership! Please, check your confirmation email. Thank you!</p>'));
		echo view('template/footer');
	}
	public function public_renewdon() {
		echo view('template/header');
		echo view('public/main_view', array('msg' => '<p class="text-success lead"><i class="bi bi-check-circle"></i> Thank you for renewing your membership and generous donation! Please, check your confirmation email. Thank you!</p>'));
		echo view('template/footer');
	}
	public function donate() {
		echo view('template/header');
		echo view('public/main_view', array('msg' => '<p class="text-success lead"><i class="bi bi-check-circle"></i> Thank you for your generous donation! Please, check your confirmation email. Thank you!</p>'));
		echo view('template/footer');
	}

    public function new_username() {
    	echo view('template/header');
    	echo view('public/change_username_view', array('msg' => ''));
    	echo view('template/footer');
    }

    public function email_username() {
    echo view('template/header');
    echo view('public/email_username_view', array('msg' => ''));
    echo view('template/footer');
    }

    public function set_username() {
    	$id = $this->user_mod->get_id_by_email($this->request->getPost('email'));
    	echo view('template/header');
    	if($id > 0) {
    	  echo view('public/set_username_view', array('id' => $id, 'msg' => '', 'id' => $id));
    	}
    	else {
    		$data['title'] = 'Error!';
    		$data['msg'] = 'There was an error processing your data. <br><br>You can go back to home page by clicking '
    				. anchor('Home', 'here'). '<br><br>';
    		echo view('status/status_view', $data);
    	}
    	echo view('template/footer');
    }

    public function load_username() {
    	echo view('template/header');
    	$param['username'] = $this->request->getPost('username');
    	$param['username2'] = $this->request->getPost('username2');
    	$param['id_user'] = $this->uri->getSegment(2);
    	if($this->user_mod->load_username($param)) {
    		$data['title'] = 'Username Set!';
    		$data['msg'] = 'Your username was reset. You may go back to home page by clicking '
    				. anchor('Home', 'here'). '<br><br>';
    	  echo view('status/status_view', $data);
    	}
    	else {
    		$data['title'] = 'Error!';
    		$data['msg'] = 'There was an error processing your data. <br><br>You can go back to home page by clicking '
    				. anchor('Home', 'here'). '<br><br>';
    		echo view('status/status_view', $data);
    	}
    	echo view('template/footer');
    }

    public function lost_username() {
    	$flag = $this->user_mod->lost_username($this->request->getPost('email'));
    	echo view('template/header');
    	if($flag) {
    		$data['title'] = 'Username Sent!';
    		$data['msg'] = 'Your username was sent to your email. You may go back to home page by clicking '
    				. anchor('Home', 'here'). '<br><br>';
    	  echo view('status/status_view', $data);
    	}
    	else {
    		$data['title'] = 'Error!';
    		$data['msg'] = 'There was an error processing your data. <br><br>You can go back to home page by clicking '
    				. anchor('Home', 'here'). '<br><br>';
    		echo view('status/status_view', $data);
    	}
    	echo view('template/footer');
    }

    /**
    * Inspired by: https://www.w3resource.com/php-exercises/php-basic-exercise-5.php
    */
    	private function get_ip() {
    		$ip_address = NULL;
    		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        	$ip_address = $_SERVER['HTTP_CLIENT_IP'];
      	}
    	//whether ip is from proxy
    		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    		  $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    		}
    	//whether ip is from remote address
    		else {
    		  $ip_address = $_SERVER['REMOTE_ADDR'];
    		}
    		return $ip_address;
    	}

      public function test_foo() {
        echo "OK!<br>";
		$username = $this->request->getPost('username') ?? '';
      	$param['username'] = strtolower($username ?? '');
        echo '<br><br><br>username: ' . $param['username'];
      }

    }
