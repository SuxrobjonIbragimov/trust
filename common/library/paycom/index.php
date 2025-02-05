<?php
// Enable to debug
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require_once 'vendor/autoload.php';
require_once 'functions.php';

use Paycom\PaycomApplication;

const CONFIG_FILE = 'paycom.config.php';

// load configuration
$paycomConfig = require_once CONFIG_FILE;

$application = new PaycomApplication($paycomConfig);
$application->run();
