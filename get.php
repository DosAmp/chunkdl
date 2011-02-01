<?php
// get.php: actually concat chunks and send a file to the user
require('constants.php');

if (isset($_GET['f']) && !empty($_GET['f'])) {
	$target = preg_replace('[^A-Za-z0-9.\-_]', '', $_GET['f']);
	/* XXX: it's pretty valid to have index.php.* files, this is only here
	 * to confuse script kiddies */
	if ($target == 'index.php')
		header('HTTP/1.0 403 Forbidden');
	elseif (empty($target)) {
		header('HTTP/1.0 404 Not Found');
		header('Content-Type: text/plain; charset=utf-8');
		echo "ERROR: File name invalid";
	}
	else {
		$filesize = 0;
		$destcount = count(glob($target . $patternsufix));
		if ($destcount > 0 && $destcount < pow(10, constant('DIGITS'))) {
			// XXX: preliminary solution until ranges are implemented
			header('Accept-Ranges: none');

			// remember, use only for files < 4 GiB
			for ($i = 1; $i <= $destcount; $i++) {
				$destination = $target . sprintf('.%0' . strval(constant('DIGITS')) . 'u', $i);
				if (file_exists($destination) && filesize($destination)) $filesize += filesize($destination);
			}
			header('Content-Length: ' . strval($filesize));

			if (strrpos($target, '.') === null)
				// don't really know what to do with extensionless files
				header('Content-Type: application/force-download');
			else {
				$ext = strtolower(substr($target, strrpos($target, '.')));
				if (isset($contenttypes[$ext])) header('Content-Type: ' . $contenttypes[$ext]);
				// explicit fallback also for executable files (.exe, .class etc.)
				else header('Content-Type: application/octet-stream');
			}

			header('Content-Disposition: attachment; filename="' . $target . '"');
			for ($i = 1; $i <= $destcount; $i++) {
				$destination = $target . sprintf('.%0' . strval(constant('DIGITS')) . 'u', $i);
				if (file_exists($destination)) @readfile($destination);
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
