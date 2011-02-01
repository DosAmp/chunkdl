<?php
// index.php: imitate a simple Apache-style directory index
require('constants.php');

// get dirname and parent dirname
$urlpath = dirname($_SERVER['PHP_SELF']);
// doesn't work well, especially on PHP <5.2: $parentpath = realpath($urlpath . '/..');
if (strrpos($urlpath, '/') === false) $parentpath = $urlpath . '/..'; // wait, what?
elseif (strrpos($urlpath, '/') === 0) $parentpath = '/';
else $parentpath = substr($urlpath, 0, strrpos($urlpath, '/'));
// in case there are *really* special chars in the paths
$urlpath = htmlspecialchars($urlpath); $parentpath = htmlspecialchars($parentpath);

/* TODO?: Do we need a header with charset here - even all of this should be plain ASCII?
 * If so, we still could look for an Accept-Charset header and simply just that if it's
 * 7-bit compatible to ASCII. */
echo <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>Index of
EOF;
echo ' ' . $urlpath . "</title>\n";
echo <<<EOF
	</head>
	<body>
		<h1>Index of 
EOF;
echo $urlpath . "</h1>\n\t\t<ul>\n";
if ($urlpath != $parentpath) // == '/'
	echo "\t\t\t<li><a href=\"" . $parentpath . "\">Parent Directory</a></li>\n";

$thefiles = glob('*' . $firstsuffix);
foreach ($thefiles as &$file) {
	$file = substr($file, 0, strlen($file) - strlen($firstsuffix));
	// TODO: provide a shortened file name so that the line fits well on a 80-column terminal
	echo "\t\t\t<li><a href=\"" . constant('GET_PREFIX') . $file . '">' . $file . "</a></li>\n";
}
unset($file);

// Phew, we're almost done, just the footer...
// TODO: implement the "at <server hostname> port $_SERVER['SERVER_PORT']" part
echo "\t\t</ul>\n\t\t<address>" . $_SERVER['SERVER_SOFTWARE'] . "</address>\n\t</body>\n</html>";
?>