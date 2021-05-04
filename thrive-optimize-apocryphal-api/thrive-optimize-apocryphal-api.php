<?php

namespace apt\thewhale;

/**
 * @package Thrive Optimize Apocryphal API
 * @version 0.0.1-release-candidate
 */
/*
Plugin Name: Thrive Optimize Apocryphal API
Plugin URI: http://autopilottools.com
Description: Integrate WP with Flitshop Headless.
Author: Auto Pilot Tools
Version: 0.0.1
Author URI: http://autopilottools.com/
*/

//Insert The Whale Framework embedder
$dir = __DIR__;
require_once 'the-whale/embedder.php';

//Insert constants
require_once 'the-whale/config/constants.php';

//Insert the Config
require_once 'config.php';

/**
 * We execute our plugin, passing this file so they can find assetsour config file
 */
$plugin_file = __FILE__;
$thrive_optimize_apocryphal_api = new thrive_optimize_apocryphal_api($config);
