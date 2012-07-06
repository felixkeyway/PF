<!DOCTYPE html>
<html xmlns="http://www.w.3.org/1999/xhtml">
    <head>
        <title>Keyway-Xelonis&reg; Portal Framework&trade; - Installation - Vorabüberprüfung / Keyway-Xelonis&reg; Portal Framework&trade; - Installation - Pretest</title>
        <meta name="robots" content="noindex" />
        <meta charset="utf-8" />
    </head>
    <body>
        <section>
            <hgroup>
                <h1>Vorabprüdung / Pretest</h1>
            </hgroup>
<?php
    if (version_compare(phpversion(), '5.3.4') >= 0) :
        echo '          <p>PHP 5.3.4 oder größer ist verfügbar. <a rel="nofollow" href="install.php">Sie können mit der Installation beginnen</a>.</p>' . "\n";
	echo '          <p>PHP 5.3.4 or greater is avaible. <a rel="nofollow" href="install.php">You can begining with the installation now</a>.</p>' . "\n";
    else :
	echo '          <p>PHP 5.3.4 oder größer ist nicht verfügbar. Bitte aktualisieren sie ihr System, um das Portal Framework&trade; zu installieren.</p>' . "\n";
	echo '          <p>PHP 5.3.4 or greater is not avaible. Please update your system, for installing the Portal Framework&trade;.</p>' . "\n";
    endif;
?>
            <footer>
                <p>&copy; Copyright 2011-2012 <a href="http://www.keyway-xelonis.com/">Keyway-Xelonis&reg; GmbH</a>. All rights reserved.</p>
            </footer>
        </section>
    </body>
</html>