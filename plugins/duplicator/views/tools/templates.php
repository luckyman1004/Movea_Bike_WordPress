<style>
	div.panel {padding: 20px 5px 10px 10px;}
	div.area {font-size:16px; text-align: center; line-height: 30px}
</style>

<div class="panel">

	<br/>
	<div class="area">
		<img src="<?php echo DUPLICATOR_PLUGIN_URL ?>assets/img/logo-dpro-300x50.png"  />
		<?php
			echo '<h2><i class="fa fa-clone"></i> ' .  __('This option is available in Duplicator Pro.', 'duplicator')  . '</h2>';
			_e('Templates allow you to customize what you want to include in your site and store it as a re-usable profile.', 'duplicator');
			echo '<br/>';
			_e('Save time and create a template that can be applied to a schedule or a custom package setup.', 'duplicator');
		?>
	</div>
	<p style="text-align:center">
		<a href="https://snapcreek.com/duplicator/?utm_source=duplicator_free&utm_medium=wordpress_plugin&utm_content=free_tools_templates&utm_campaign=duplicator_pro" target="_blank" class="button button-primary button-large dup-check-it-btn" >
			<?php _e('Learn More', 'duplicator') ?>
		</a>
	</p>
</div>


