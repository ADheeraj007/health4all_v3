<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Helpline extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('masters_model');
		$this->load->model('staff_model');
		$this->load->model('helpline_model');
		if($this->session->userdata('logged_in')){
		$userdata=$this->session->userdata('logged_in');
		$user_id=$userdata['user_id'];
		$this->data['hospitals']=$this->staff_model->user_hospital($user_id);
		$this->data['functions']=$this->staff_model->user_function($user_id);
		$this->data['departments']=$this->staff_model->user_department($user_id);
		}
		$this->data['op_forms']=$this->staff_model->get_forms("OP");
		$this->data['ip_forms']=$this->staff_model->get_forms("IP");
	}

	function detailed_report(){
		if(!$this->session->userdata('logged_in')){
			show_404();
		}
		$access=0;
		foreach($this->data['functions'] as $function){
			if($function->user_function=="Helpline Reports"){
					$access=1;
			}
		}
		if($access==1){
			$this->load->helper('form');
			$this->load->library('form_validation');
			$user=$this->session->userdata('logged_in');
			$this->data['user_id']=$user['user_id'];
			$this->data['title']="HelpLine Calls - Detailed Report";
			$this->load->view('templates/header',$this->data);
			$this->data['calls']=$this->helpline_model->get_detailed_report();
			$this->data['caller_type']=$this->helpline_model->get_caller_type();
			$this->data['call_category']=$this->helpline_model->get_call_category();
			$this->data['resolution_status']=$this->helpline_model->get_resolution_status();
			$this->data['helpline']=$this->helpline_model->get_helpline("report");
			$this->data['all_hospitals']=$this->staff_model->get_hospital();
			$this->data['emails_sent']=$this->helpline_model->get_emails();
			$this->load->view('pages/helpline/report_detailed',$this->data);
			$this->load->view('templates/footer');
		}
		else show_404();
	}

	function voicemail_detailed_report(){
		if(!$this->session->userdata('logged_in')){
			show_404();
		}
		$access=0;
		foreach($this->data['functions'] as $function){
			if($function->user_function=="Helpline Reports"){
					$access=1;
			}
		}
		if($access==1){
		$this->load->helper('form');
		$this->load->library('form_validation');
		$user=$this->session->userdata('logged_in');
		$this->data['user_id']=$user['user_id'];
		$this->data['title']="HelpLine Voicemail Calls - Detailed Report";
		$this->load->view('templates/header',$this->data);
		$this->data['calls']=$this->helpline_model->get_voicemail_detailed_report();
		$this->data['caller_type']=$this->helpline_model->get_caller_type();
		$this->data['call_category']=$this->helpline_model->get_call_category();
		$this->data['resolution_status']=$this->helpline_model->get_resolution_status();
		$this->data['helpline']=$this->helpline_model->get_helpline("report");
		$this->data['all_hospitals']=$this->staff_model->get_hospital();
		$this->data['emails_sent']=$this->helpline_model->get_emails();
		$this->load->view('pages/helpline/report_voicemail_detailed',$this->data);
		$this->load->view('templates/footer');
		}
		else show_404();
	}

	function report_groupwise(){
		if(!$this->session->userdata('logged_in')){
			show_404();
		}
		$access=0;
		foreach($this->data['functions'] as $function){
			if($function->user_function=="Helpline Reports"){
					$access=1;
			}
		}
		if($access==1){
		$this->load->helper('form');
		$this->load->library('form_validation');
		$user=$this->session->userdata('logged_in');
		$this->data['user_id']=$user['user_id'];
		$this->data['title']="HelpLine Calls - Group Wise Report";
		$this->load->view('templates/header',$this->data);
		$this->data['calls']=$this->helpline_model->get_detailed_report();
		$this->data['caller_type']=$this->helpline_model->get_caller_type();
		$this->data['call_category']=$this->helpline_model->get_call_category();
		$this->data['resolution_status']=$this->helpline_model->get_resolution_status();
		$this->data['helpline']=$this->helpline_model->get_helpline("report");
		$this->data['all_hospitals']=$this->staff_model->get_hospital();
		$this->data['groups']=$this->helpline_model->get_groups();
		$this->data['emails_sent']=$this->helpline_model->get_emails();
		$this->load->view('pages/helpline/report_group_wise',$this->data);
		$this->load->view('templates/footer');
		}
		else show_404();
	}

	function update_call(){
		if(!$this->session->userdata('logged_in')){
			show_404();
		}
		$access=0;
		foreach($this->data['functions'] as $function){
			if($function->user_function=="Helpline Update"){
					$access=1;
			}
		}
		if($access==1){
		$this->load->helper('form');
		$this->load->library('form_validation');
		$user=$this->session->userdata('logged_in');
		$this->data['user_id']=$user['user_id'];
		$this->data['title']="Update HelpLine Calls";
		$this->load->view('templates/header',$this->data);
		$this->form_validation->set_rules('call[]','Call','trim|xss_clean');
		$this->data['helpline']=$this->helpline_model->get_helpline("update");
		$this->data['caller_type']=$this->helpline_model->get_caller_type();
		$this->data['call_category']=$this->helpline_model->get_call_category();
		$this->data['resolution_status']=$this->helpline_model->get_resolution_status();
		$this->data['all_hospitals']=$this->staff_model->get_hospital();
		if ($this->form_validation->run() === FALSE){
			$this->load->view('pages/helpline/update_calls',$this->data);
		}
		else{
			$this->data['calls']=$this->helpline_model->get_calls();
			if(($this->input->post('submit'))) {
				if(!!$this->input->post('call')){
					if($this->helpline_model->update_call()){
					$this->data['msg']="Calls Updated successfully";
					$this->data['calls']=$this->helpline_model->get_calls();
					}
					else{
						$this->data['msg']="Calls could not be updated. Please try again.";
					}
				}
				else {
						$this->data['msg']="Please select at least one call to update.";
				}
				$this->load->view('pages/helpline/update_calls',$this->data);
			}
			else if($this->input->post('send_email')){
				$result = $this->helpline_model->send_email();
				$this->data['calls']=$this->helpline_model->get_calls();
				if($result==1){
					$this->data['msg']="Email sent successfully.";
				}
				else if($result==2){
					$this->data['msg']="Email not sent, please check the email address(es) entered and try again.";
				}
				else{
					$this->data['msg']="Email not sent.";
				}
				$this->load->view('pages/helpline/update_calls',$this->data);
			}
			else{
			$this->load->view('pages/helpline/update_calls',$this->data);
			}
		}
		$this->load->view('templates/footer');
		}
		else show_404();
	}

	function update_voicemail_calls(){
		if(!$this->session->userdata('logged_in')){
			show_404();
		}
		$access=0;
		foreach($this->data['functions'] as $function){
			if($function->user_function=="Helpline Update"){
					$access=1;
			}
		}
		if($access==1){
		$this->load->helper('form');
		$this->load->library('form_validation');
		$user=$this->session->userdata('logged_in');
		$this->data['user_id']=$user['user_id'];
		$this->data['title']="Update HelpLine Voicemail Calls";
		$this->load->view('templates/header',$this->data);
		$this->form_validation->set_rules('call[]','Call','trim|xss_clean');
		$this->data['calls']=$this->helpline_model->get_voicemail_calls();
		$this->data['caller_type']=$this->helpline_model->get_caller_type();
		$this->data['call_category']=$this->helpline_model->get_call_category();
		$this->data['resolution_status']=$this->helpline_model->get_resolution_status();
		$this->data['helpline']=$this->helpline_model->get_helpline("update");
		$this->data['all_hospitals']=$this->staff_model->get_hospital();
		if ($this->form_validation->run() === FALSE){
			$this->load->view('pages/helpline/update_voicemail_calls',$this->data);
		}
		else{
			if(($this->input->post('submit'))) {
				if(!!$this->input->post('call')){
					if($this->helpline_model->update_call()){
					$this->data['msg']="Calls Updated successfully";
					$this->data['calls']=$this->helpline_model->get_voicemail_calls();
					}
					else{
						$this->data['msg']="Calls could not be updated. Please try again.";
					}
				}
				else {
						$this->data['msg']="Please select at least one call to update.";
				}
				$this->load->view('pages/helpline/update_voicemail_calls',$this->data);
			}
			else if($this->input->post('send_email')){
				$result = $this->helpline_model->send_email();
				$this->data['calls']=$this->helpline_model->get_voicemail_calls();
				if($result==1){
					$this->data['msg']="Email sent successfully.";
				}
				else if($result==2){
					$this->data['msg']="Email not sent, please check the email address(es) entered and try again.";
				}
				else{
					$this->data['msg']="Email not sent.";
				}
				$this->load->view('pages/helpline/update_voicemail_calls',$this->data);
			}
			else{
			$this->load->view('pages/helpline/update_voicemail_calls',$this->data);
			}
		}
		$this->load->view('templates/footer');
		}
		else show_404();
	}

	function insert_call(){
		if(!!$this->input->get('CallSid')) {
			$this->helpline_model->insert_call();
		}
		else show_404();
	}

	function search_call_groups(){
		if($groups = $this->helpline_model->get_call_groups()){
			$list=array(
				'groups'=>$groups
			);

				echo json_encode($list);
		}
		else return false;
	}

	function helpline_receivers(){
		if($this->session->userdata('logged_in')){
			$this->load->helper('form');
			$this->data['title']="User Helpline Receivers";
			$this->data['userdata']=$this->session->userdata('logged_in');
			$this->data['user_functions']=$this->staff_model->get_user_function();
			$this->data['receivers'] = $this->helpline_model->getHelplineReceivers();
			$this->load->view('templates/header',$this->data);
			$this->load->view('templates/leftnav',$this->data);			
			$this->load->view('pages/helpline_receiver_list',$this->data);
			$this->load->view('templates/footer');
		}
		else{
			show_404();
		}
	}

	function helpline_receivers_form($receiver_id=''){
		if($this->session->userdata('logged_in')){  						
            $this->data['userdata']=$this->session->userdata('logged_in');  
		} else{
            show_404(); 													
        } 

		/*$access = -1;
		foreach($this->data['functions'] as $function){
            if($function->user_function=="Helpline - Receivers"){
                $access = 1;
				break;
            }
		}
		if($access != 1){
			show_404();
		}*/
		
		$this->load->helper('form');										
		$this->load->library('form_validation'); 							
		$this->data['title']="Add Helpline Receiver";										
		$this->load->view('templates/header', $this->data);				
		$this->load->view('templates/leftnav');	
		$config=array(
           array( 'field'   => 'full_name', 'label'   => 'Full Name', 'rules'   => 'required|trim|xss_clean' ),
           array( 'field'   => 'short_name', 'label'   => 'Short Name', 'rules'   => 'required|trim|xss_clean' ),
           array( 'field'   => 'email', 'label'   => 'Email', 'rules'   => 'required|trim|xss_clean' ),
           array( 'field'   => 'category', 'label'   => 'Category', 'rules'   => 'required|trim|xss_clean' ),
           array( 'field'   => 'app_id', 'label'   => 'App ID', 'rules'   => 'required|trim|xss_clean' )
		);
		if(!$receiver_id){
			$config[] = array( 'field'   => 'phone', 'label'   => 'Phone', 'rules'   => 'required|trim|xss_clean' );
		}
		$this->form_validation->set_rules($config);

		$this->data['users']=$this->masters_model->get_users();
		$this->data['helplines']=$this->helpline_model->get_helplines();
		
		$existing_receivers = false;
		if($this->input->post('phone')){
			$this->data['receivers_exists_msg'] = "The receiver with this phone already exists";
			$this->data['receivers'] = $existing_receivers = $this->helpline_model->getHelplineReceivers(array('phone' => $this->input->post('phone')));
			if($existing_receivers){
				$this->load->view('pages/helpline_receiver_list', $this->data);
			}
        }

        if(!$existing_receivers){
			if($this->form_validation->run()===FALSE) {	

			} else {
				if($this->helpline_model->save_helpline_receiver($receiver_id)){
					$this->data['msg']="Helpline Receiver Saved Succesfully";					
				}
			}
        }

        $this->data['submitLink'] = "helpline/helpline_receivers_form";
        $this->data['usersList'] = array();
        if($receiver_id){
        	$helpline_receiver = $this->helpline_model->getHelplineReceiverById($receiver_id)[0];
        	$this->data['edit_data'] = json_encode(['helpline_receiver' => $helpline_receiver, 'helpline_receiver_link' => $this->helpline_model->getHelplineReceiverLinksById($receiver_id), 'userList' => $this->staff_model->search_staff_user("", $helpline_receiver->user_id)]);
        	$this->data['submitLink'] = "helpline/helpline_receivers_form/" . $receiver_id;
        }

		$this->load->view('pages/helpline_receiver_form',$this->data);							
		$this->load->view('templates/footer');								
    }	

	function search_staff_user(){
		if($results = $this->staff_model->search_staff_user($this->input->post('query'))){			
			echo json_encode($results);
		}
		else return false;
	}
}
