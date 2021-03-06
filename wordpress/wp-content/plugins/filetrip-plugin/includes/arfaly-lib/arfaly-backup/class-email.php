<?php

/**
 * Email notifications for backups
 *
 * @extends FILETRIP_BKP_Service
 */
class FILETRIP_BKP_Email_Service extends FILETRIP_BKP_Service {

	/**
	 * Human readable name for this service
	 * @var string
	 */
	public $name = 'Email';

	/**
	 * Output the email form field
	 *
	 * @access  public
	 */
	public function field() { ?>

		<tr>

			<th scope="row">
				<label for="<?php echo esc_attr( $this->get_field_name( 'email' ) ); ?>"><?php _e( 'Email notification', 'filetrip-plugin' ); ?></label>
			</th>

			<td>
				<input type="email" id="<?php echo esc_attr( $this->get_field_name( 'email' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'email' ) ); ?>" value="<?php echo esc_attr( $this->get_field_value( 'email' ) ); ?>" placeholder="name@youremail.com" />

				<p class="description"><?php printf( __( 'Receive a notification email when a backup completes, if the backup is small enough (&lt; %s) then it will be attached to the email. Separate multiple email addresses with a comma.', 'filetrip-plugin' ), '<code>' . size_format( filetrip_bkp_get_max_attachment_size() ) . '</code>' ); ?></p>
			</td>

		</tr>

	<?php }

	/**
	 * Not used as we only need a field
	 *
	 * @see  field
	 * @return string Empty string
	 */
	public function form() {
		return '';
	}

	public static function constant() { ?>

		<tr<?php if ( defined( 'FILETRIP_BKP_ATTACHMENT_MAX_FILESIZE' ) ) { ?> class="filetrip_bkp_active"<?php } ?>>

			<td><code>FILETRIP_BKP_ATTACHMENT_MAX_FILESIZE</code></td>

			<td>

				<?php if ( defined( 'FILETRIP_BKP_ATTACHMENT_MAX_FILESIZE' ) ) { ?>
				<p><?php printf( __( 'You\'ve set it to: %s', 'filetrip-plugin' ), '<code>' . FILETRIP_BKP_ATTACHMENT_MAX_FILESIZE . '</code>' ); ?></p>
				<?php } ?>

				<p><?php printf( __( 'The maximum filesize of your backup that will be attached to your notification emails . Defaults to %s.', 'filetrip-plugin' ), '<code>10MB</code>' ); ?> <?php _e( 'e.g.', 'filetrip-plugin' ); ?> <code>define( 'FILETRIP_BKP_ATTACHMENT_MAX_FILESIZE', '25MB' );</code></p>

			</td>

		</tr>

	<?php }

	/**
	 * The sentence fragment that is output as part of the schedule sentence
	 *
	 * @return string
	 */
	public function display() {

		if ( $emails = $this->get_email_address_array() ) {

			$email = '<code>' . implode( '</code>, <code>', array_map( 'esc_html', $emails ) ) . '</code>';

			return sprintf( __( 'Send an email notification to %s', 'filetrip-plugin' ), $email );

		}

		return '';

	}

	/**
	 * Used to determine if the service is in use or not
	 */
	public function is_service_active() {
		return (bool) $this->get_email_address_array();
	}

	/**
	 * Validate the email and return an error if validation fails
	 *
	 * @param  array  &$new_data Array of new data, passed by reference
	 * @param  array  $old_data  The data we are replacing
	 * @return null|array        Null on success, array of errors if validation failed
	 */
	public function update( &$new_data, $old_data ) {

		$errors = array();

		if ( isset( $new_data['email'] ) ) {

			if ( ! empty( $new_data['email'] ) ) {

				foreach ( explode( ',', $new_data['email'] ) as $email ) {

					$email = trim( $email );

					if ( ! is_email( $email ) ) {
						$errors['email'] = sprintf( __( '%s isn\'t a valid email',  'filetrip-plugin' ), $email );
					}

				}

			}

			if ( ! empty( $errors['email'] ) ) {
				$new_data['email'] = '';
			}

			return $errors;

		}

	}

	/**
	 * Get an array or validated email address's
	 * @return array An array of validated email address's
	 */
	private function get_email_address_array() {

		$emails = array_map( 'trim', explode( ',', $this->get_field_value( 'email' ) ) );

		return array_filter( array_unique( $emails ), 'is_email' );

	}

	/**
	 * Fire the email notification on the filetrip_bkp_backup_complete
	 *
	 * @see  FILETRIP_Backup::do_action
	 * @param  string $action The action received from the backup
	 * @return void
	 */
	public function action( $action ) {

		if ( $action === 'filetrip_bkp_backup_complete' && $this->get_email_address_array() ) {

			$file = $this->schedule->get_archive_filepath();

			$sent = false;

			$download = esc_url(add_query_arg( 'filetrip_bkp_download', base64_encode( $file ), FILETRIP_BKP_ADMIN_URL ));
			$domain   = parse_url( home_url(), PHP_URL_HOST ) . parse_url( home_url(), PHP_URL_PATH );

			$headers  = 'From: BackUpWordPress <' . apply_filters( 'filetrip_bkp_from_email', get_bloginfo( 'admin_email' ) ) . '>' . "\r\n";

			// The backup failed, send a message saying as much
			if ( ! file_exists( $file ) && ( $errors = array_merge( $this->schedule->get_errors(), $this->schedule->get_warnings() ) ) ) {

				$error_message = '';

				foreach ( $errors as $error_set )
					$error_message .= implode( "\n - ", $error_set );

				if ( $error_message )
					$error_message = ' - ' . $error_message;

				$subject = sprintf( __( 'Backup of %s Failed', 'filetrip-plugin' ), $domain );

				$message = sprintf( __( 'BackUpWordPress was unable to backup your site %1$s.', 'filetrip-plugin' ) . "\n\n" . __( 'Here are the errors that we\'re encountered:', 'filetrip-plugin' ) . "\n\n" . '%2$s' . "\n\n" . __( 'If the errors above look like Martian, forward this email to %3$s and we\'ll take a look', 'filetrip-plugin' ) . "\n\n" . __( "Kind Regards,\nThe Apologetic BackUpWordPress Backup Emailing Robot", 'filetrip-plugin' ), home_url(), $error_message, 'support@hmn.md' );

				wp_mail( $this->get_email_address_array(), $subject, $message, $headers );

				return;

			}

			$subject = sprintf( __( 'Backup of %s', 'filetrip-plugin' ), $domain );

			// If it's larger than the max attachment size limit assume it's not going to be able to send the backup
			if ( @filesize( $file ) < filetrip_bkp_get_max_attachment_size() ) {

				$message = sprintf( __( 'BackUpWordPress has completed a backup of your site %1$s.', 'filetrip-plugin' ) . "\n\n" . __( 'The backup file should be attached to this email.', 'filetrip-plugin' ) . "\n\n" . __( 'You can download the backup file by clicking the link below:', 'filetrip-plugin' ) . "\n\n" . '%2$s' . "\n\n" . __( "Kind Regards,\nThe Happy BackUpWordPress Backup Emailing Robot", 'filetrip-plugin' ), home_url(),  $download );

				$sent = wp_mail( $this->get_email_address_array(), $subject, $message, $headers, $file );

			}

			// If we didn't send the email above then send just the notification
			if ( ! $sent ) {

				$message = sprintf( __( 'BackUpWordPress has completed a backup of your site %1$s.', 'filetrip-plugin' ) . "\n\n" . __( 'Unfortunately the backup file was too large to attach to this email.', 'filetrip-plugin' ) . "\n\n" . __( 'You can download the backup file by clicking the link below:', 'filetrip-plugin' ) . "\n\n" . '%2$s' . "\n\n" . __( "Kind Regards,\nThe Happy BackUpWordPress Backup Emailing Robot", 'filetrip-plugin' ), home_url(), $download );

				wp_mail( $this->get_email_address_array(), $subject, $message, $headers );

			}

		}

	}

	public static function intercom_data() { return array(); }

	public static function intercom_data_html() {}

}

// Register the service
FILETRIP_BKP_Services::register( __FILE__, 'FILETRIP_BKP_Email_Service' );
