<?php
/**
 *
 * @package MediaWiki
 * @subpackage GoogleAnalytics
 * @author Jakub Kurcek
 */

if ( !defined('MEDIAWIKI') ) {
	echo "This is a MediaWiki extension.\n";
	exit(1);
}

$dir = dirname(__FILE__);

//i18n
$wgExtensionMessagesFiles['GoogleAnalyticsSampling'] = $dir . '/GoogleAnalyticsSampling.i18n.php';

// WikiaApp
$app = F::app();

// autoloaded classes
// $wgAutoloadClasses[ 'GoogleAnalyticsSampling'] =  "$dir/GoogleAnalyticsSampling.body.php" ;
$wgAutoloadClasses[ 'GoogleAnalyticsSamplingController'] =  "$dir/GoogleAnalyticsSamplingController.class.php";

