<?php

ob_start();
include(get_template_directory() . '/inc/SpinPapiConf.inc.php');

$sp = new SpinPapiClient($mySpUserID, $mySpSecret, $myStation, true, $papiVersion);
$sp->fcInit(get_template_directory() . '/.fc');

$current = $sp->query(array(
    'method' => 'getRegularShowsInfo',
    'When' => 'now'
));
ob_end_clean();
wp_send_json($current);

?>
