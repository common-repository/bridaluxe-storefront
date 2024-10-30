<div class="wrap">
	<h2>Bridaluxe Storefront Settings</h2>
	<form action="" method="post">
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><label for="bridaluxe-affiliate-id"><?php _e( 'Affiliate ID' ); ?></label></th>
				<td>
					<input value="<?php echo attribute_escape( $this->options[ 'affiliate-id' ] ); ?>" type="text" name="bridaluxe-affiliate-id" id="bridaluxe-affiliate-id" size="7"><br />
					<?php _e( 'Find your affiliate ID in the' ); ?> <a href="http://affiliate.bridaluxe.com/account/" title="<?php _e( 'Bridaluxe Affiliate Account' ); ?>" target="_blank"><?php _e( 'Bridaluxe account section' ); ?></a>.
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="bridaluxe-use-stylesheets"><?php _e( 'Use Bridaluxe Stylesheets?' ); ?></label></th>
				<td>
					<input <?php checked( 1, $this->options[ 'use-stylesheets' ] ); ?> value="1" type="checkbox" name="bridaluxe-use-stylesheets" id="bridaluxe-use-stylesheets" /><br />
					<?php _e( 'Uncheck this box if you wish to disable the default bridaluxe stylesheets.' ); ?>
				</td>
			</tr>
		</tbody>
	</table>
	<p class="submit">
		<input type="submit" name="bridaluxe-settings-submit" id="bridaluxe-settings-submit" value="<?php _e( 'Save Settings' ); ?>" />
	</p>
	</form>
</div>