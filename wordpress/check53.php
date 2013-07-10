<?php

/*
 * This file can be included by your code, and will only be used if the PHP version is less than 5.3 - which
 * would prevent the namespaced class from loading.
 */

if (version_compare(PHP_VERSION, '5.3.0', '<'))
{
	// This global path string gets overwritten, so save it
	$GLOBALS['templateSystemPluginEntryPoint'] = $GLOBALS['plugin'];

	// Run this code when the admin notice event comes up
	add_action( 'admin_notices', 'templateSystemCheckPhpVersion', 0 );

	// Only define it if it hasn't been define before
	if (!function_exists('templateSystemCheckPhpVersion'))
	{
		function templateSystemCheckPhpVersion()
		{
			global $templateSystemPluginEntryPoint;

			// This gets the entry point to the plugin
			$entryPoint = $templateSystemPluginEntryPoint;

			// Suppress activation notice
			unset($_GET['activate']);

			// Create list of errors
			$errors = array(
				'You need a minimum of PHP 5.3 to run this plugin, whereas you are running ' . PHP_VERSION,
			);

			// Retrieve the name of the plugin this library is used in
			$name = get_file_data($entryPoint, array('Plugin Name'), 'plugin');

			printf(
				'<div class="error"><p>%1$s</p>
				<p><i>%2$s</i> has been deactivated.</p></div>',
				join( '</p><p>', $errors ),
				$name[0]
			);
			deactivate_plugins($entryPoint);
		}
	}

	return false;
}

return true;