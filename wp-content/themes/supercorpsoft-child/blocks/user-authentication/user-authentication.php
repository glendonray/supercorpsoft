<?php
/**
 * Block template for User Authentication
 */

// ACF block rendering
$block_id = 'block-' . $block['id'];
$block_classes = 'acf-user-authentication ' . (isset($block['className']) ? $block['className'] : '');
$block_style = '';

if (!empty($block['align'])) {
    $block_classes .= ' align' . $block['align'];
}

if (!empty($attributes['backgroundColor'])) {
    $block_style .= 'background-color: ' . $attributes['backgroundColor'] . ';';
}

if (!empty($attributes['textColor'])) {
    $block_style .= ' color: ' . $attributes['textColor'] . ';';
}
?>

<div id="<?php echo esc_attr($block_id); ?>" class="<?php echo esc_attr($block_classes); ?>" style="<?php echo esc_attr($block_style); ?>">
    <div class="content">
        <?php
        if (!empty($block['content'])) {
            echo apply_filters('the_content', $block['content']);
        }
        ?>
    </div>
    <div class="user-authentication-block">
      <div id="user-login-form-wrapper" style="display:none;">
		<h2>Login</h2>
		<form id="user-login">
		<label for="username">Username <span class="required-asterisk">*</span></label>
		<input type="text" name="log" id="username" required>
		<label for="password">Password <span class="required-asterisk">*</span></label>
		<input type="password" name="pwd" id="password" required>
		<label>
			<input type="checkbox" name="rememberme" id="rememberme"> Remember me
		</label>
		<input type="submit" value="Login" <?php echo (is_admin()) ? 'disabled' : null; ?>>
		</form>
		<p class="toggle-text">Don't have an account? <button id="show-registration" type="button">Create one here</button>.</p>
		</div>
		<div id="login-error" class="form-error-wrapper" style="display:none;"></div>

		<div id="user-registration-form-wrapper">
			<h2>Create Account</h2>
			<form id="user-registration">
				<label for="reg-username">Username <span class="required-asterisk">*</span></label>
				<input type="text" name="user_login" id="reg-username" required>
				<label for="reg-email">Email <span class="required-asterisk">*</span></label>
				<input type="email" name="user_email" id="reg-email" required>
				<input type="submit" value="Register" <?php echo (is_admin()) ? 'disabled' : null; ?>>
			</form>
			<p class="toggle-text">Already have an account? <button id="show-login" type="button">Login here</button>.</p>
		</div>
		<div id="registration-error" class="form-error-wrapper" style="display:none;"></div>
    </div>
</div>
