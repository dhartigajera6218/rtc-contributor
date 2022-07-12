<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       
 * @since      1.0.0
 *
 * @package    rtc-contributor
 * @subpackage rtc-contributor/includes
 */


function rtc_contributor_activate_deactivate_plugin() {

    register_activation_hook(RTC_Contributor_PLUGIN_FILE, array("RTCContributorActivation", "rtc_contributor_activate"));
    register_uninstall_hook(RTC_Contributor_PLUGIN_FILE, array("RTCContributorActivation", "rtc_contributor_uninstall"));
}


/* plugin active function */

function rtc_contributor_activate() {
    
}


/* plugin deactive function */

function rtc_contributor_uninstall() {
    
}


/* front css and js */
function rtc_contributor_register_scripts() {
    wp_enqueue_style('rtc-contributor-style', plugins_url() . '/rtc-contributor/assets/css/style.css');
}

add_action('wp_enqueue_scripts', 'rtc_contributor_register_scripts', 10);

  
/* Post meta box start  */ 

add_action('add_meta_boxes', 'rtc_add_meta_boxes');

function rtc_add_meta_boxes() {
    $user = wp_get_current_user();
    $allowed_roles = array('editor', 'administrator', 'author');
    if( array_intersect($allowed_roles, $user->roles ) ) {
        add_meta_box('rtc-group', 'Contributors', 'post_meta_box_display', 'post', 'normal' );
    }
}

function post_meta_box_display($post) {
    
    wp_nonce_field( basename(__FILE__), 'mam_nonce' );

    $users = get_users( array( 'fields' => array( 'ID', 'display_name' ),'role__in' => array( 'author' ) ) );
    
    $rtc_contributor_multval_meta = maybe_unserialize( get_post_meta( $post->ID, 'rtc_contributor_multval', true ) );

    ?>
    <div class="">
    <table >
        <tbody>
            <?php foreach ($users as $key => $value) { 
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
            <?php } ?>
            
        </tbody>
    </table>
    </div>
    <?php
}

add_action('save_post', 'rtc_contributor_meta_box_save');

function rtc_contributor_meta_box_save($post_id) {
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

/* meta box end  */ 



/* meta box content show in after post content */

function rtc_post_meta_contentshow( $content ) {
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
add_filter( 'the_content', 'rtc_post_meta_contentshow' );