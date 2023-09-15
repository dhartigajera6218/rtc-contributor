<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://#
 * @since      1.0.0
 *
 * @package    Rtc_Contributor
 * @subpackage Rtc_Contributor/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rtc_Contributor
 * @subpackage Rtc_Contributor/admin
 * @author     Dharti Gajera <dhartigajera6218@gmail.com>
 */
class Rtc_Contributor_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rtc_Contributor_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rtc_Contributor_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rtc-contributor-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rtc_Contributor_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rtc_Contributor_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rtc-contributor-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	/**
	 * Post meta box start
	 *
	 * @since    1.0.0
	 */
	

	public function rtc_add_meta_boxes() {
		global $post;
		$user = wp_get_current_user();
		$allowed_roles = array('editor', 'administrator', 'author');
		if( array_intersect($allowed_roles, $user->roles ) ) {
			add_meta_box('rtc-group', 'Contributors', array($this, 'post_meta_box_display'), 'post', 'normal');
		}
	}
	
	public function post_meta_box_display($post = array()) {
    
		wp_nonce_field( basename(__FILE__), 'mam_nonce' );

		$users = get_users( array( 'fields' => array( 'ID', 'display_name' ),'role__in' => array( 'author' ) ) );
		
		$rtc_contributor_multval_meta = maybe_unserialize( get_post_meta( $post->ID, 'rtc_contributor_multval', true ) );

		?>
		<div class="">
		<table >
			<tbody>
				<?php 
				if(!empty($users)) {
					foreach ($users as $key => $value) { 
					$checked = '';
					if ( is_array( $rtc_contributor_multval_meta ) && in_array( $value->ID, $rtc_contributor_multval_meta ) ) {
						$checked = 'checked="checked"';
					}
				?>
				<tr>
					<td>
						 <p>
							<input  type="checkbox" name="rtc_contributor_multval[]" id="rtc_contributor_label_<?php echo $value->ID;?>" value="<?php echo $value->ID;?>" <?php echo $checked; ?> />
							<label for="rtc_contributor_label_<?php echo $value->ID;?>"><?php echo $value->display_name;?></label>
						</p>
					</td>
				</tr>    
				<?php } 
				} else {
				?>
				<tr>
					<td>
						 <p>
							Data is empty.
						</p>
					</td>
				</tr>  
				<?php } ?>
				
			</tbody>
		</table>
		</div>
		<?php
	}

	public function rtc_contributor_meta_box_save($post_id) {
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST[ 'rtc_contributor_nonce' ] ) && wp_verify_nonce( $_POST[ 'rtc_contributor_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

		if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
			return;
		}

		// If the checkbox was not empty, save it as array in post meta
		if ( ! empty( $_POST['rtc_contributor_multval'] ) ) {
			update_post_meta( $post_id, 'rtc_contributor_multval', $_POST['rtc_contributor_multval'] );
		} else {
			delete_post_meta( $post_id, 'rtc_contributor_multval' );
		}
	}



}
