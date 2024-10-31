<?php

use Whodunit\MywpLoginForm\Utility\Helpers;

if ( isset( $attributes['shortcode'] ) && true === $attributes['shortcode'] ) {
	wp_enqueue_style( 'block-' . $attributes['slug'] );
}

/// Form settings
/// ============================
if ( isset( $attributes['url_redirect'] ) && ! empty( $attributes['url_redirect'] ) ) {
	$url_redirect = $attributes['url_redirect'];
} else {
	$url_redirect = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

$is_stylised               = ( isset( $attributes[ 'is_stylised' ] ) && false !== $attributes[ 'is_stylised' ] ) ? true : false;
$block_style               = ( $is_stylised ) ? Helpers::check_input( $attributes, 'block_style', '' ) : '';
$button_style              = ( $is_stylised ) ? Helpers::check_input( $attributes, 'button_style', '' ) : '';
$fields_border_radius      = Helpers::check_input( $attributes, 'fields_border_radius', 0 );
$fields_border_color       = Helpers::check_input( $attributes, 'fields_border_color', '' );
$button_border_radius      = Helpers::check_input( $attributes, 'button_border_radius', 0 );
$font_size                 = Helpers::check_input( $attributes, 'font_size', '' );
$text_color_value          = Helpers::check_input( $attributes, 'text_color_value', '' );
$background_color_value    = Helpers::check_input( $attributes, 'background_color_value', '' );
$background_gradient_value = Helpers::check_input( $attributes, 'background_gradient_value', '' );
$button_border_width       = Helpers::check_input( $attributes, 'button_border_width', '' );
$unique_id                 = uniqid();

$mwp_login_form_args = [
	'echo'           => true,
	'redirect'       => $url_redirect,
	'form_id'        => 'mwplf-login-form_' . esc_attr( $unique_id ),
	'label_username' => Helpers::check_input( $attributes, 'username_text', esc_html__( 'Username or Email Address', 'mywp-login-form' ) ),
	'label_password' => Helpers::check_input( $attributes, 'password_text', esc_html__( 'Password', 'mywp-login-form' ) ),
	'label_remember' => esc_html__( 'Remember Me', 'mywp-login-form' ),
	'label_log_in'   => Helpers::check_input( $attributes, 'button_text', esc_html__( 'Login', 'mywp-login-form' ) ),
	'id_username'    => 'mwplf-user_login_' . esc_attr( $unique_id ),
	'id_password'    => 'mwplf-user_pass_' . esc_attr( $unique_id ),
	'id_remember'    => 'mwplf-remember_me_' . esc_attr( $unique_id ),
	'id_submit'      => 'mwplf-submit_' . esc_attr( $unique_id ),
	'remember'       => Helpers::check_boolean( $attributes, 'remember_me' ),
	'value_username' => '',
	'value_remember' => false,
];

if( $is_stylised ):
	$button_default_style_txt_color = '#FFFFFF';
	$button_default_style_bg_color  = '#2271b1';
	$button_default_style_color     = '#2271b1';
	$button_default_style_border    = '#2271b1';
?>
<style>
    #mwplf-login-form__wrapper_<?php echo esc_attr( $unique_id ); ?> input[type=text],
    #mwplf-login-form__wrapper_<?php echo esc_attr( $unique_id ); ?> input[type=email],
    #mwplf-login-form__wrapper_<?php echo esc_attr( $unique_id ); ?> input[type=password] {

		<?php if( $fields_border_radius ) : ?>
    		border-radius: <?php echo esc_attr( $fields_border_radius ); ?>px;
        <?php endif; ?>

		<?php if( $fields_border_color ) : ?>
			border-width:1px;
			border-style:solid;
			border-color: <?php echo esc_attr( $fields_border_color ); ?>;
		<?php endif; ?>
    }

    #mwplf-login-form__wrapper_<?php echo esc_attr( $unique_id ); ?> input[type=submit] {

		<?php if( $button_border_radius ) : ?>
       		border-radius: <?php echo esc_attr( $button_border_radius ); ?>px;
		<?php endif; ?>

        <?php if( $font_size ) : ?>
			font-size: <?php echo esc_attr( $font_size ); ?>;
		<?php endif; ?>

        <?php if( $text_color_value ) :
			$button_default_style_txt_color = $text_color_value ;
		endif; ?>
		color: <?php echo esc_attr( $button_default_style_txt_color ); ?>;

		<?php if( $background_color_value ) :
			$button_default_style_bg_color = $background_color_value ;
		endif; ?>
		background: <?php echo esc_attr( $button_default_style_bg_color ); ?>;

		<?php if( $background_gradient_value ) : ?>
			background: <?php echo esc_attr( $background_gradient_value ); ?>;
		<?php endif; ?>

		<?php if( $button_style == 'fill' ) : ?>

			<?php if( $background_color_value ) : ?>
				border-color : <?php echo esc_attr( $background_color_value ); ?>;
			<?php endif; ?>

		<?php endif; ?>

		<?php if( $button_style == 'outline' ) : ?>
			background: none !important;
			<?php

			if( $background_color_value ){ $button_default_style_border = $background_color_value ; }
			if( $text_color_value ){ $button_default_style_color = $text_color_value ; }
			
			?>
			color : <?php echo esc_attr( $button_default_style_color ) ; ?>;
			border-width:1px;
			border-style:solid;
			border-color: <?php echo esc_attr( $button_default_style_border ); ?>;
		<?php endif; ?>

		<?php
		$button_default_style_border_width = '1px' ;
		
		if( $button_border_width ) : 
			$button_default_style_border_width = $button_border_width . 'px' ;
		endif; ?>
		border-width:<?php echo esc_attr( $button_default_style_border_width ) ; ?>;
    }
</style>
<?php
endif;
?>
<div id="mwplf-login-form__wrapper_<?php echo esc_attr( $unique_id ) ?>" class="mwplf-login-form <?php echo esc_attr( $block_style ); ?> <?php echo esc_attr( $button_style ); ?>">
	<?php wp_login_form( $mwp_login_form_args ); ?>

	<?php if ( true === Helpers::check_boolean( $attributes, 'lost_password' ) ): ?>
		<a href="<?php echo esc_url( wp_lostpassword_url() ) ?>" class="mwplf-login-form-lost-password"><?php esc_html_e( 'Lost password?', 'mywp-login-form' ) ?></a>
	<?php endif; ?>
</div>