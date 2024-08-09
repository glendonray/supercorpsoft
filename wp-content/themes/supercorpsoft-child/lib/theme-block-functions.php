<?php
function localize_user_authentication_script() {
    $is_logged_in = is_user_logged_in();
    $localized_data = array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'loginNonce' => wp_create_nonce('login_ajax_nonce'),
        'logoutNonce' => wp_create_nonce('logout_ajax_nonce'),
        'isLoggedIn' => $is_logged_in ? 'true' : 'false',
    );

    wp_localize_script('user-authentication-script', 'authData', $localized_data);
}
add_action('enqueue_block_assets', 'localize_user_authentication_script');

function handle_ajax_login() {
    check_ajax_referer('login_ajax_nonce', 'ajax_nonce');

    $creds = array(
        'user_login'    => $_POST['log'],
        'user_password' => $_POST['pwd'],
        'remember'      => isset($_POST['rememberme']) ? true : false,
    );

    $user = wp_signon($creds, is_ssl());

    if (is_wp_error($user)) {
        wp_send_json_error(array('message' => $user->get_error_message()));
    } else {
        wp_send_json_success(array('redirect_url' => admin_url()));
    }
}
add_action('wp_ajax_nopriv_user_login_action', 'handle_ajax_login');

function handle_ajax_registration() {
    check_ajax_referer('login_ajax_nonce', 'ajax_nonce');

    $username = sanitize_user($_POST['user_login']);
    $email = sanitize_email($_POST['user_email']);

    if (username_exists($username)) {
        wp_send_json_error(array('message' => 'This username is already taken.'));
    }

    if (email_exists($email)) {
        wp_send_json_error(array('message' => 'This email is already registered.'));
    }

    $user_id = wp_create_user($username, wp_generate_password(), $email);

    if (is_wp_error($user_id)) {
        wp_send_json_error(array('message' => $user_id->get_error_message()));
    } else {
        wp_send_json_success(array('success_message' => '<p class="success-title">Registration Complete!</p><p>Registration complete. Please check your email.</p>'));
    }
}
add_action('wp_ajax_nopriv_user_registration_action', 'handle_ajax_registration');

function handle_ajax_logout() {
    check_ajax_referer('logout_ajax_nonce', 'ajax_nonce');

    wp_logout();

    wp_send_json_success(array('message' => 'Successfully logged out.'));
}
add_action('wp_ajax_user_logout_action', 'handle_ajax_logout');
