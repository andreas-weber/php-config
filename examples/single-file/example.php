<?php

/*
 * This file is part of the andreas-weber/php-config library.
 *
 * (c) Andreas Weber <code@andreas-weber.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use AndreasWeber\Config\Core\ConfigLoader;

require_once __DIR__ . '/../resources/bootstrap.php';

$configLoader = new ConfigLoader();

$config = $configLoader->load(
    array(
        BASEPATH . '/examples/resources/fixtures/config1.yml'
    )
);

var_export(
    $config->toArray()
);
