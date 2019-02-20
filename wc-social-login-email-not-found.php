<?php
/**
 * Plugin Name: WooCommerce Social Login: Email Check
 * Description: Checks if the email address associated to the social provider is known in the site before creating a new user.
 * Author: Sébastien Dumont
 * Author URI: https://sebastiendumont.com
 * Version: 1.2.0
 * Text Domain: woocommerce-social-login-email-check
 *
 * Copyright: © 2019 Sébastien Dumont, (mailme@sebastiendumont.com)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

/**
 * Checks if the email address associated to the Social provider is known in the site.
 * If the user email address is not found then don't create an account or login.
 * Simply return an error message.
 *
 * @since   1.0.0
 * @version 1.2.0
 * @global  $wpdb
 * @param   WC_Social_Login_Provider_profile $profile
 * @param   string $provider_id Social Login provider ID
 * @return  void
 */
function wc_social_login_email_check( $profile, $provide_id ) {
  global $wpdb;

  $email = null;

  // First, check if the user exists based on the email associated to the social profile.
  $email = $wpdb->get_var( $wpdb->prepare( "SELECT `user_email` FROM $wpdb->users WHERE `user_email` = %s", $profile->get_email() ) );

  // If no email is found then return the error message to the user.
  if ( ! $email ) {
    wc_add_notice( __( 'Oops, your email address was unknown to us&hellip; please login to your account first then connect your social media account from your profile.', 'woocommerce-social-login-email-check' ), 'error' );

    // Redirect back to "My Account" page or any other page set by the filter.
    wp_safe_redirect( apply_filters( 'woocommerce_social_login_email_check_redirect', wc_get_page_permalink( 'myaccount' ) ) );
    exit();
  }
} // END wc_social_login_email_check()
add_action( 'wc_social_login_before_create_user', 'wc_social_login_email_check', 10, 2 );
