<?php
// constants.php: define common constants

/* How many digits should every suffix have? (default is three digits, e.g. .001, .002...)
 * Three is recommended because of many splitting tools' default setting. ;) */
define('DIGITS', 3);
/* used in index.php. Change this if you have a pretty mod_rewrite setup
 * (see .htaccess for an example) */
define('GET_PREFIX', 'get.php?f=');

// suffix for glob()-ing files
$patternsufix = '.'; for ($i = 0; $i < constant('DIGITS'); $i++) $patternsufix .= '[0-9]';
// suffix of first chunk
$firstsuffix = '.'; for ($i = 1; $i < constant('DIGITS'); $i++) $firstsuffix .= '0'; $firstsuffix .= '1';

// TODO?: do a full-flexed mime.types lookup
// Following extension to MIME translation array just lists what I use most. ;)
$contenttypes = array(
	'.7z'  => 'application/x-7z-compressed',
	'.bz2' => 'application/x-bzip2',
	'.gz'  => 'application/x-gzip',
	'.mp3' => 'audio/mpeg',
	'.ogg' => 'application/ogg',
	'.pdf' => 'application/pdf',
	'.rar' => 'application/x-rar-compressed',
	'.xz'  => 'application/x-xz',
	'.zip' => 'application/zip');
?>
