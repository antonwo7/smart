<?php
	
	if(isset($_POST['hsf_request'])){
		$data = array(
			'hsf_firstname' => e($_POST['hsf_firstname']),
			'hsf_lastname' => e($_POST['hsf_lastname']),
			'hsf_email' => e($_POST['hsf_email']),
			'hsf_subject' => e($_POST['hsf_subject']),
			'hsf_message' => e($_POST['hsf_message']),
			'hsf_link' => $hsf_link,
			'hsf_key' => $hsf_key,
			'hsf_email_admin' => $hsf_email_admin
		);
		
		$hsform = new HSForm($data);
		$messages = $hsform->submit();
		if(!empty($messages['success'])){
			foreach($messages['success'] as $success){
				echo '<div class="__submit-success">' . $success . '</div>';
			}
		}
		if(!empty($messages['errors'])){
			foreach($messages['errors'] as $error){
				echo '<div class="__submit-error">' . $error . '</div>';
			}
		}
	}
	
?>

<form class="shform" action="" method="POST">
	<input type="hidden" name="hsf_request" value="">
	<input type="text" name="hsf_firstname" placeholder="First Name" value="">
    <input type="text" name="hsf_lastname" placeholder="Last Name" value="">
	<input type="text" name="hsf_email" placeholder="Email" value="">
	<input type="text" name="hsf_subject" placeholder="Subject" value="">
	<textarea name="hsf_message" placeholder="Message"></textarea>
    <input type="submit" class="<?=$class; ?>" value="<?=$button_text; ?>">
</form>

