<?php

class FILETRIP_Backdrop_Server {
	public function run() {
		if ( empty( $_POST['key'] ) ) {
			return new WP_Error( 'filetrip_backdrop_no_key', __( 'No key supplied', 'filetrip_backdrop' ) );
		}

		$data = get_transient( 'filetrip_backdrop-' . $_POST['key'] );
		if ( empty( $data ) ) {
			return new WP_Error( 'filetrip_backdrop_invalid_key', __( 'Supplied key was not valid', 'filetrip_backdrop' ) );
		}

		$result = call_user_func_array( $data['callback'], $data['params'] );
		delete_transient( 'filetrip_backdrop-' . $_POST['key'] );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		return true;
	}

	public static function spawn() {
		$class = get_class();
		if ( function_exists( 'get_called_class' ) ) {
			$class = get_called_class();
		}

		return call_user_func( array( $class, 'spawn_run' ), $class );
	}

	protected static function spawn_run( $class ) {
		$server = new $class();
		$server->run();
		exit;
	}
}
