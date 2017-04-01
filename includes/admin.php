<?phpadd_filter('buddyforms_admin_tabs', 'buddyforms_easypin_admin_tab', 10, 1);function buddyforms_easypin_admin_tab($tabs){	$tabs['easypin'] = 'EasyPin Settings';	return $tabs;}add_action( 'buddyforms_settings_page_tab', 'buddyforms_easypin_settings_page_tab' );function buddyforms_easypin_settings_page_tab($tab){	if($tab != 'easypin')		return $tab;	$easypin_settings = get_option( 'buddyforms_easypin_settings' ); ?>	<div class="metabox-holder">		<div class="postbox">			<h2><?php _e( 'Load Bootstrap?', 'buddyforms' ); ?></h2>			<div class="inside">				<p>If you already load bootstrap with your theme or a plugin you can deactivate EasyPin Bootstrap.</p>				<form method="post" action="options.php">					<?php settings_fields( 'buddyforms_easypin_settings' );					$bootstrap = isset( $easypin_settings['bootstrap'] ) ? $easypin_settings['bootstrap'] : '';					?>					<table class="form-table">						<tr valign="top">							<th scope="row" valign="top">								<?php _e('Bootstrap')?>							</th>							<td>								<input <?php checked( $bootstrap, 'off' ) ?> type="checkbox" value="off" name="buddyforms_easypin_settings[bootstrap]">Turn off loading Bootstrap							</td>						</tr>					</table>					<?php submit_button(); ?>				</form>			</div><!-- .inside -->		</div><!-- .postbox -->	</div><!-- .metabox-holder -->	<?php}function buddyforms_easypin_register_option() {	// creates our settings in the options table	register_setting( 'buddyforms_easypin_settings', 'buddyforms_easypin_settings', 'buddyforms_easypin_settings_default_sanitize' );}add_action( 'admin_init', 'buddyforms_easypin_register_option' );function buddyforms_easypin_settings_default_sanitize( $new ) {	return $new;}