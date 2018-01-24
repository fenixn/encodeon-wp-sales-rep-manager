<?php

/**
 * In order for PHP Unit tests to work correctly, you need to have the wordpress developer tool.
 * Run the following command in the plugin root directory (where the phpunit.xml file is located)
 * git clone git://develop.git.wordpress.org/ wordpress-develop
 */

$_tests_dir = dirname( __DIR__ ) . '../wordpress-develop/tests/phpunit';

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';

require dirname( __DIR__ ) . '../vendor/autoload.php';
