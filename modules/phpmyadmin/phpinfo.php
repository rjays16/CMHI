<?php
/* $Id: phpinfo.php,v 1.3 2005/10/29 20:08:11 kaloyan_raev Exp $ */
// vim: expandtab sw=4 ts=4 sts=4:


/**
 * Gets core libraries and defines some variables
 */
require('./libraries/grab_globals.lib.php');
require('./libraries/common.lib.php');


/**
 * Displays PHP information
 */
$is_superuser = @PMA_mysql_query('USE mysql', $userlink);
if ($is_superuser || $cfg['ShowPhpInfo']) {
    phpinfo();
}
?>
