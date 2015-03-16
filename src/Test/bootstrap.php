<?php

/*
 * This file is part of the andreas-weber/php-config library.
 *
 * (c) Andreas Weber <code@andreas-weber.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define base path
defined('BASEPATH')
|| define('BASEPATH', realpath(dirname(__FILE__) . '/../../'));

// Autoloader
require_once BASEPATH . '/vendor/autoload.php';
