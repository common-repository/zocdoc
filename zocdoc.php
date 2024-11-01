<?php
/* 
    Plugin Name: Zocdoc
    Plugin URI: 
    Description: Integrates Zocdoc JavaScript Partner SDK into your site.
    Version: 1.0
    Author: wp-develop
    Text Domain: zocdoc
    Domain Path: /languages
    License: GPL
    License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}
if( is_admin() ) {
    require_once( dirname( __FILE__ ) . '/admin/zocdoc-settings.php' );
}

function zocdoc_frontend_script() {
    wp_enqueue_style( 'zd-font-style', 'https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700' );
    wp_enqueue_style( 'zd-front-style', plugins_url( '/public/css/zocdoc-main.css', __FILE__ ) );
    wp_enqueue_script( 'zd-front-script', plugins_url( '/public/js/zocdoc-main.js', __FILE__ ), array( 'jquery' ) );
}

add_action( 'wp_enqueue_scripts', 'zocdoc_frontend_script');

function zocdoc_footer_data() {
    $zocdoc_data = get_option('_zocdoc_data');
    $enable_floating_button = '';
    $api_token = '';
    $zd_button_title = __('Book appointment', 'zocdoc');
    $zd_popup_title = __('Book an appointment', 'zocdoc');
    $zd_close_text = __('Close', 'zocdoc');
    $zd_button_color = '#fff04b';
    $zd_hover_btn_color = '#00234b';
    $zd_text_color = '#00234b';
    $zd_hover_text_color = '#ffffff';
    $doctors = '';
    if(!empty($zocdoc_data)) {

        if(isset($zocdoc_data['enable_floating_button']) && $zocdoc_data['enable_floating_button'] == 'yes') {
            $enable_floating_button = $zocdoc_data['enable_floating_button'];
        }

        if(isset($zocdoc_data['api_token'])) {
            $api_token = $zocdoc_data['api_token'];
        }

        if(isset($zocdoc_data['zd_button_title']) && $zocdoc_data['zd_button_title'] != '') {
            $zd_button_title = $zocdoc_data['zd_button_title'];
        }

        if(isset($zocdoc_data['zd_popup_title']) && $zocdoc_data['zd_popup_title'] != '') {
            $zd_popup_title = $zocdoc_data['zd_popup_title'];
        }

        if(isset($zocdoc_data['zd_close_text']) && $zocdoc_data['zd_close_text'] != '') {
            $zd_close_text = $zocdoc_data['zd_close_text'];
        }

        if(isset($zocdoc_data['zd_button_color'])) {
            $zd_button_color = $zocdoc_data['zd_button_color'];
        }

        if(isset($zocdoc_data['zd_hover_btn_color'])) {
            $zd_hover_btn_color = $zocdoc_data['zd_hover_btn_color'];
        }

        if(isset($zocdoc_data['zd_text_color'])) {
            $zd_text_color = $zocdoc_data['zd_text_color'];
        }

        if(isset($zocdoc_data['zd_hover_text_color'])) {
            $zd_hover_text_color = $zocdoc_data['zd_hover_text_color'];
        }

        if(isset($zocdoc_data['zd_doctors'])) {
            $doctors = $zocdoc_data['zd_doctors'];
        }

    }
    if($enable_floating_button == 'yes' && $api_token != '') {
?>
        <div class="zd_overly"></div>
        <span id="zd_opener">
            <em class="open_txt"><?php echo esc_html($zd_button_title); ?></em>
            <em class="close_txt"><?php echo esc_html($zd_close_text); ?></em>
            <em class="arrow"></em>
        </span>
        <div id="am_zoc_doc_widget">
            <h3 class="widget_title"><?php echo esc_html($zd_popup_title); ?></h3>
            <span id="zd_closer"></span>
            <div>
                <?php
                if(!empty($doctors)) {
                    foreach($doctors as $key => $doctor) {
                        $full_name = '';
                        $first_name = '';
                        $last_name = '';
                        $zipcode = '';
                        $address = '';
                        if(isset($doctor['full_name'])) {
                            $full_name = $doctor['full_name'];
                        }
                        if(isset($doctor['first_name'])) {
                            $first_name = $doctor['first_name'];
                        }
                        if(isset($doctor['last_name'])) {
                            $last_name = $doctor['last_name'];
                        }
                        if(isset($doctor['zipcode'])) {
                            $zipcode = $doctor['zipcode'];
                        }
                        if(isset($doctor['address'])) {
                            $address = $doctor['address'];
                        }
                    ?>
                        <h2><?php echo esc_html($full_name); ?></h2>
                        <div data-zd-location data-zd-groupid="<?php echo esc_attr($key); ?>"></div>
                        <div data-zd-newexisting data-zd-groupid="<?php echo esc_attr($key); ?>"></div>
                        <div
                            data-zd-timesgrid
                            data-zd-firstname="<?php echo esc_attr($first_name); ?>"
                            data-zd-lastname="<?php echo esc_attr($last_name); ?>"
                            data-zd-address="<?php echo esc_attr($address); ?>"
                            data-zd-zip="<?php echo esc_attr($zipcode); ?>"
                            data-zd-groupid="<?php echo esc_attr($key); ?>"
                            data-zd-showaddress="false">
                        </div>
                    <?php
                    }
                }
                ?>
            </div>
        </div>
        <script
            src="https://static.zocdoc.com/widget/zocdoc.widget.js"
            async
            data-zd-sdk
            data-zd-token="<?php echo esc_attr( $api_token ); ?>"
            data-zd-buttonColor="<?php echo esc_attr( $zd_button_color ); ?>"
            data-zd-textColor="<?php echo esc_attr( $zd_text_color ); ?>"
            data-zd-buttonColorHover="<?php echo esc_attr( $zd_hover_btn_color ); ?>"
            data-zd-textColorHover="<?php echo esc_attr( $zd_hover_text_color ); ?>">
        </script>
<?php
    }
}

add_action('wp_footer', 'zocdoc_footer_data');