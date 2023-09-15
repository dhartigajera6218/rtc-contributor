<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://#
 * @since      1.0.0
 *
 * @package    Rtc_Contributor
 * @subpackage Rtc_Contributor/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Rtc_Contributor
 * @subpackage Rtc_Contributor/public
 * @author     Dharti Gajera <dhartigajera6218@gmail.com>
 */
class Rtc_Contributor_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rtc-contributor-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rtc-contributor-public.js', array( 'jquery' ), $this->version, false );

	}
	
	public function rtc_post_meta_contentshow( $content ) {
		global $post;
		if ( is_single() && 'post' == get_post_type() ) {
			$custom_content = $content;
			$rtc_contributor_multvals = maybe_unserialize( get_post_meta( $post->ID, 'rtc_contributor_multval', true ) );
			if(!empty($rtc_contributor_multvals)){
				$data = '<div class="rtc_post_meta_part">
					<h3>Contributors</h3>
					<div class="rtc_post_meta_content">
				';
				foreach($rtc_contributor_multvals as $val){
					$user = get_user_by( 'id', $val );
					$name = $user->display_name;
					$img = get_avatar($val, 40);
					$data .= '<div class="rtc_post_meta_div">
						'.$img.'
						<a href="'.get_author_posts_url($val).'"><span>'.$name.'</span></a>
					</div>';
				}
				$data .= '</div></div>';
				$custom_content .= $data;
			}
			return $custom_content;
		} else {
			return $content;
		}
	}

}
