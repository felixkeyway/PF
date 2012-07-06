<?php
$setupConfig = array();

/* General Settings */

$setupConfig['general'] = array();

// set to false, if will be installed a user application.
$setupConfig['general']['is_standalone'] = true;

// contains the name of the user application, they will be installed (let it empty, if the framework will be installed
// as standalone).
$setupConfig['general']['application_name'] = '';

/* Archive settings */

$setupConfig['archive'] = array();

// set to true, if the Install-Archive already is unpack.
$setupConfig['archive']['is_unpack'] = false;

// set the the name of the directory, if you have unpack the archive.
$setupConfig['archive']['unpack_files_directory'] = '';

// set the type of the first archive (!DO NOT CHANGE THIS VALUE!)
$setupConfig['archive']['first_archive_type'] = 'gz';

// set the type of the second archive (it is contain in the first archive) (!DO NOT CHANGE THIS VALUE!)
$setupConfig['archive']['second_archive_type'] = 'tar';

/* User settings */

$setupConfig['user'] = array();

// set this value to false, if you want to create the user in the setup.
$setupConfig['user']['create_user_automatically'] = false;

// let this value empty, if you create your adminestrator user in the setup. Set the name of the first account.
$setupConfig['user']['account_name'] = 'root';

// let this value empty, if you create your adminestrator user in the setup. Set the password of the first account.
$setupConfig['user']['account_password'] = 'password';

// let this value empty, if you create your adminestrator user in the setup. Set the email of the first account.
$setupConfig['user']['account_email'] = 'letterbox@domain.tld';

/* Session settings */

$setupConfig['session'] = array();

// set the prefix of the session variables
$setupConfig['session']['variable_prefix'] = 'kpfsetup_';
?>