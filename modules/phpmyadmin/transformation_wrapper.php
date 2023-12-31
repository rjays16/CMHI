<?php
/* $Id: transformation_wrapper.php,v 1.3 2005/10/29 20:08:11 kaloyan_raev Exp $ */
// vim: expandtab sw=4 ts=4 sts=4:

$is_transformation_wrapper = true;

/**
 * Get the variables sent or posted to this script and displays the header
 */
require('./libraries/grab_globals.lib.php');

/**
 * Gets a core script and starts output buffering work
 */
if (!defined('PMA_COMMON_LIB_INCLUDED')) {
    include('./libraries/common.lib.php');
}

require('./libraries/relation.lib.php'); // foreign keys
require('./libraries/transformations.lib.php'); // Transformations
$cfgRelation = PMA_getRelationsParam();

/**
 * Ensures db and table are valid, else moves to the "parent" script
 */
require('./libraries/db_table_exists.lib.php');


/**
 * Get the list of the fields of the current table
 */
PMA_mysql_select_db($db);
$table_def = PMA_mysql_query('SHOW FIELDS FROM ' . PMA_backquote($table));
if (isset($primary_key)) {
    $local_query = 'SELECT * FROM ' . PMA_backquote($table) . ' WHERE ' . $primary_key;
    $result      = PMA_mysql_query($local_query) or PMA_mysqlDie('', $local_query, '', '');
    $row         = PMA_mysql_fetch_array($result);
} else {
    $local_query = 'SELECT * FROM ' . PMA_backquote($table) . ' LIMIT 1';
    $result      = PMA_mysql_query($local_query) or PMA_mysqlDie('', $local_query, '', '');
    $row         = PMA_mysql_fetch_array($result);
}

// No row returned
if (!$row) {
    exit;
} // end if (no record returned)

$default_ct = 'application/octet-stream';

if ($cfgRelation['commwork'] && $cfgRelation['mimework']) {
    $mime_map = PMA_getMime($db, $table);
    $mime_options = PMA_transformation_getOptions((isset($mime_map[urldecode($transform_key)]['transformation_options']) ? $mime_map[urldecode($transform_key)]['transformation_options'] : ''));

    @reset($mime_options);
    while(list($key, $option) = each($mime_options)) {
        if (eregi('^; charset=.*$', $option)) {
            $mime_options['charset'] = $option;
        }
    }
}

// garvin: For re-usability, moved http-headers and stylesheets
// to a seperate file. It can now be included by header.inc.php,
// queryframe.php, querywindow.php.

include('./libraries/header_http.inc.php');
// [MIME]
$content_type = 'Content-Type: ' . (isset($mime_map[urldecode($transform_key)]['mimetype']) ? str_replace("_", "/", $mime_map[urldecode($transform_key)]['mimetype']) : $default_ct) . (isset($mime_options['charset']) ? $mime_options['charset'] : '');
header($content_type);

if (!isset($resize)) {
    echo $row[urldecode($transform_key)];
} else {
    // if image_*__inline.inc.php finds that we can resize,
    // it sets $resize to jpeg or png
   
    $srcImage = imagecreatefromstring($row[urldecode($transform_key)]);
    $srcWidth = ImageSX( $srcImage );
    $srcHeight = ImageSY( $srcImage );

    // Check to see if the width > height or if width < height
    // if so adjust accordingly to make sure the image
    // stays smaller then the $newWidth and $newHeight

    $ratioWidth = $srcWidth/$newWidth;
    $ratioHeight = $srcHeight/$newHeight;

    if( $ratioWidth < $ratioHeight){
        $destWidth = $srcWidth/$ratioHeight;
        $destHeight = $newHeight;
    }else{
        $destWidth = $newWidth;
        $destHeight = $srcHeight/$ratioWidth;
    }

    if ($resize) {
        $destImage = ImageCreateTrueColor( $destWidth, $destHeight);
    }

//    ImageCopyResized( $destImage, $srcImage, 0, 0, 0, 0, $destWidth, $destHeight, $srcWidth, $srcHeight );
// better quality but slower:
    ImageCopyResampled( $destImage, $srcImage, 0, 0, 0, 0, $destWidth, $destHeight, $srcWidth, $srcHeight );

    if ($resize == "jpeg") {
        ImageJPEG( $destImage,"",75 );
    }
    if ($resize == "png") {
        ImagePNG( $destImage);
    }
    ImageDestroy( $srcImage );
    ImageDestroy( $destImage );
}

/**
 * Close MySql non-persistent connections
 */
if (isset($GLOBALS['dbh']) && $GLOBALS['dbh']) {
    @mysql_close($GLOBALS['dbh']);
}
if (isset($GLOBALS['userlink']) && $GLOBALS['userlink']) {
    @mysql_close($GLOBALS['userlink']);
}
?>
