<?php
/**
 * This file contains all basic configuration of the portal framework.
 */

// make the path unix compatible
$directoryReplace = str_replace('\\', '/', dirname(__file__));

// define absolute portal framework directory
define('KPF_DIR', $directoryReplace);

// remove the document root
$webdirectoryReplace = str_replace($_SERVER['DOCUMENT_ROOT'], '', KPF_DIR);

// define the public web directory of the portal framework
define('KPF_WEB_DIR', $webdirectoryReplace);

// define the database host
define('DB_HOST', 'localhost');

// define the database name
define('DB_NAME', 'kpf');

// define the engine of the database
define('DB_ENGINE', 'MySQL');

// define the user of the database
define('DB_USER', '');

// define the password of the database user
define('DB_PASSWORD', '');

// define the port of the database server
define('DB_PORT', 3306);

// define the number of the installation
define('DB_N', 1);

// define the domain of the portal framework.
define('KPF_DOMAIN', 'localhost');
?>