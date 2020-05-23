<?php 
class HSForm{
	
	private $messages;
	private $data;
	
	function __construct($data){
		$this->data = $data;
	}
	
	public function submit(){
		if($this->validate()){
			$this->send_mail();
			$this->create_hubspot_contact();
		}
		
		return $this->messages;
	}
	
	public function send_mail(){
		//$to = $this->data['hsf_firstname'] . ' <' . $this->data['hsf_email'] . '>';
		$to = $this->data['hsf_email_admin'];
		$subject = $this->data['hsf_subject'];
		
		$message = 'Subject: ' . $this->data['hsf_subject'] . '\r\nMessage: ' . $this->data['hsf_message'];
		
		$headers = 'MIME-Version: 1.0\r\n';
		$headers .= 'Content-type: text/html; charset=iso-8859-1\r\n';
		$headers .= 'From: Good site <goodsite@gmail.com>\r\n';
		
		if(!mail($to, $subject, $message, $headers))
			$this->messages['errors'][] = __('Error occurred while sending email');
		else{
			$this->messages['success'][] = __('Email was sent!');
			error_log(__('Mail sent. Email: ') . $this->data['hsf_email'], 0);
		}
	}
	
	public function create_hubspot_contact(){
		extract($this->data);
		
		$url = $hsf_link . $hsf_key;
		
		$post_array = array(
            'properties' => array(
                array(
                    'property' => 'email',
                    'value' => $hsf_email
                ),
                array(
                    'property' => 'firstname',
                    'value' => $hsf_firstname
                ),
                array(
                    'property' => 'lastname',
                    'value' => $hsf_lastname
                )
            )
        );
		
		$post_json = json_encode($post_array);
		
		$ch = @curl_init();
        
		@curl_setopt($ch, CURLOPT_POST, true);
        @curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);
        @curl_setopt($ch, CURLOPT_URL, $url);
        @curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		@curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		@curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        
		$response = @curl_exec($ch);
        $status_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errors = curl_error($ch);
        @curl_close($ch);
		
		$response = json_decode($response);
		
		if($status_code == '409'){
			$this->messages['errors'][] = __('Contact already exists at HubSpot');
		}elseif($status_code == '400'){
			$this->messages['errors'][] = __('Error! Data sent is incorrect');
		}elseif($status_code == '200'){
			$this->messages['success'][] = __('Data was sent at HubSpot');
		}
	}
	
	private function validate(){
		$this->messages = array();
		
		$email = $this->data['hsf_email'];
		
		if(strlen($this->data['hsf_firstname']) < 2){
			$this->messages['errors'][] = __('Field Firstname less than 2 characters');
		}
		if(strlen($this->data['hsf_firstname']) > 60){
			$this->messages['errors'][] = __('Field Firstname more than 60 characters');
		}
		if(strlen($this->data['hsf_lastname']) < 2){
			$this->messages['errors'][] = __('Field Lastname less than 2 characters');
		}
		if(strlen($this->data['hsf_lastname']) > 60){
			$this->messages['errors'][] = __('Field Lastname more than 60 characters');
		}
		if(strlen($this->data['hsf_subject']) < 2){
			$this->messages['errors'][] = __('Field Subject less than 2 characters');
		}
		if(strlen($this->data['hsf_subject']) > 60){
			$this->messages['errors'][] = __('Field Subject more than 60 characters');
		}
		if(strlen($this->data['hsf_message']) < 5){
			$this->messages['errors'][] = __('Field Message less than 5 characters');
		}
		if(strlen($this->data['hsf_message']) > 400){
			$this->messages['errors'][] = __('Field Message more than 400 characters');
		}
		
		
		if(!preg_match('/ .+@.+\..+ /xsi', $this->data['hsf_email'])) 
			$this->messages['errors'][] = __('Field Email is not an email');
		
		if(empty($this->messages)) return true;
		return false;
	}
}
