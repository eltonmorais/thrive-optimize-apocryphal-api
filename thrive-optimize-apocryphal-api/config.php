<?php

$config = array(

  /**
   * Plugin Slug
   * This is used in various place, like saving options and so on
   */
  'slug' => 'flitint',

  /**
   * Plugin Full Slug (optional)
   * This is used in various place, like in updates modules and so on.
   * Must be EXACTLY like the file and directory name
   */
  'fullSlug' => 'thrive_optimize_apocryphal_api',

  /**
   * Plugin Name
   * This is used in various place
   */
  'name' => 'Thrive Optimize Apocryphal API',

  /**
   * Text Domain to be used in the plugin
   */
  'textDomain' => 'thrive_optimize_apocryphal_api',

  /**
   * Is this a plugin or is this a theme?
   */
  'type' => 'plugin',

  /**
   * Lang directory
   */
  'lang' => 'lang',

  /**
   * true shows ACF Options
   */
  'debug' => true,

  /**
   * Enables the loading of titan framework to serve options
   */
  'acf' => true,

  /**
   * Uses custom Menu
   */
  'menu' => true,

  /**
   * DANGER ZONE: ADVANCED SETTINGS
   * Do not change anything beyond this point if you don't know exactly what it does!
   */

  /**
   * It is important to us to pass a variable of some file contained in the root of the plugin
   * Like this very own config file, so that's what we do here.
   */
  'file' => __FILE__,
  'dir' => __DIR__,

);
