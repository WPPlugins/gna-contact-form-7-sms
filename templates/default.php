<div class="postbox">
	<h3 class="hndle"><label for="title"><?php _e('GNA Contact Form 7 SMS', 'gna-contact-form-7-sms'); ?></label></h3>
	<div class="inside">
		<p><?php _e('Thank you for using our GNA Contact Form 7 SMS plugin.', 'gna-contact-form-7-sms'); ?></p>
	</div>
</div> <!-- end postbox-->

<div class="postbox">
	<h3 class="hndle"><label for="title"><?php _e('Template', 'gna-contact-form-7-sms'); ?></label></h3>
	<div class="inside">
		<fieldset>
			<div class="gna_blue_box">
				<p>In the following fields, you can use these tags:</p>
				<p>
					<?php $data['form']->suggest_mail_tags(); ?>
				</p>
			</div>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label for="g_cfs_receive_number"><?php _e('Receive Number:', 'gna-contact-form-7-sms'); ?></label>
						</th>
						<td>
							<input type="text" id="g_cfs_receive_number" name="g_cfs_receive_number" class="wide" size="70" value="<?php echo $data['receive_number']; ?>">
							<p><?php _e('Do not include plus(+) symbol. For instance, 614xxxxxxxx', 'gna-contact-form-7-sms'); ?></p>
						</td>
					</tr>

					<tr>
						 <th scope="row">
							<label for="g_cfs_mail_body">Message body:</label>
						</th>
						<td>
							<textarea id="g_cfs_mail_body" name="g_cfs_mail_body" cols="100" rows="6" class="large-text code"><?php echo $data['message']; ?></textarea>
						</td>
					</tr>
				</tbody>
			</table>
		</fieldset>
	</div>
</div>
