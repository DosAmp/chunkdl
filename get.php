<?php
// get.php: actually concat chunks and send a file to the user
require('constants.php');

if (isset($_GET['f']) && !empty($_GET['f'])) {
	$zieldatei = preg_replace('[^A-Za-z0-9.\-_]', '', $_GET['f']);
	if ($zieldatei == 'index.php')
			header('HTTP/1.0 403 Forbidden');
	elseif (empty($zieldatei)) {
	}
	else {
		$filesize = 0;
		$quellanzahl = count(glob($zieldatei . $suffix));
		if ($quellanzahl > 0 && $quellanzahl < pow(10, constant('DIGITS'))) {
			// remember, use only for files < 4 GiB
			for ($i = 1; $i <= $quellanzahl; $i++) {
				$quelldatei = $zieldatei . sprintf('.%0' . strval(constant('DIGITS')) . 'u', $i);
				if (file_exists($quelldatei) && filesize($quelldatei)) $filesize += filesize($quelldatei);
			}
			header('Content-Length: ' . strval($filesize));

			if (strrpos($zieldatei, '.') === null)
				// don't really know what to do with extensionless files
				header('Content-Type: application/force-download');
			else {
				$ext = strtolower(substr($zieldatei, strrpos($zieldatei, '.')));
				if (isset($contenttypes[$ext])) header('Content-Type: ' . $contenttypes[$ext]);
				// explicit fallback also for executable files (.exe, .class etc.)
				else header('Content-Type: application/octet-stream');
			}

			header('Content-Disposition: attachment; filename="' . $zieldatei . '"');
			for ($i = 1; $i <= $quellanzahl; $i++) {
				$quelldatei = $zieldatei . sprintf('.%0' . strval(constant('DIGITS')) . 'u', $i);
				if (file_exists($quelldatei)) @readfile($quelldatei);
			}
		}
		else {
			header('HTTP/1.0 404 Not Found');
			header('Content-Type: text/plain; charset=utf-8');
			echo "ERROR: File not found\n";
		}
	}
}
else {
	header('HTTP/1.0 404 Not Found');
	header('Content-Type: text/plain; charset=utf-8');
	echo "ERROR: No file specified";
}
?>