<?php

// rm unused files from kk_php_sdk
$files = array(
    'plugins/komtetkassa/lib/vendors/komtet-kassa-php-sdk/src/Yura.php',
    'plugins/komtetkassa/lib/vendors/komtet-kassa-php-sdk/tests/Yura.php'
);


foreach ($files as $file) {
    $path = wa()->getAppPath($file);

    if (file_exists($path)) {
        try {
            waFiles::delete($path);
        } catch (waException $e) {
           $this->writeLog('Error while delete files');
        }
    } else {
        $this->writeLog('File not exists');
    }
}
