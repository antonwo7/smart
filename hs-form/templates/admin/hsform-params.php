<div class="wrap">
	<h2><?=__('HS Form options');?></h2>
	<form method="post" action="options.php">
		<h3><?=__('Main options');?></h3>
		<?php settings_fields( 'hsf_params_group' ); ?>
		<?php $hsf_params = get_option( 'hsf_params' ); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?=__('HAPI Link');?></th>
				<td><input type="text" name="hsf_params[hsf_link]" value="<?php echo esc_attr( $hsf_params['hsf_link'] ); ?>" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><?=__('HAPI Key');?></th>
				<td><input type="text" name="hsf_params[hsf_key]" value="<?php echo esc_attr( $hsf_params['hsf_key'] ); ?>" /></td>
			</tr>
			
			<tr valign="top">
				<th scope="row"><?=__('Email for messages');?></th>
				<td><input type="text" name="hsf_params[hsf_email_admin]" value="<?php echo esc_attr( $hsf_params['hsf_email_admin'] ); ?>" /></td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?=__('Save');?>" />
		</p>
	</form>
</div>