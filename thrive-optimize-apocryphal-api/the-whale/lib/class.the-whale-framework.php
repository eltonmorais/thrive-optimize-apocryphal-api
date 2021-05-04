<?php

namespace apt\thewhale;

if (!class_exists('the_whale_framework')) :

	abstract class the_whale_framework
	{

		protected $main_blog_id;
		protected $settings;
		protected $path;
		protected $url;
		protected $framework_path;
		protected $framework_url;
		protected $encryptor;
		static public $file = __FILE__;

		function __construct($config)
		{

			$this->settings = $config;

			$this->setup_path();

			if ($this->settings['acf']) {
				$this->add_acf();

				add_action('acf/init', array($this, 'add_acf_fields'));

				if ($this->settings['menu']) {
					add_action('admin_menu', array($this, 'add_acf_menus'));
				}

				if (!$this->settings['debug']) {
					add_filter('acf/settings/show_admin', '__return_false');
				}
			}

			add_action('init', array(&$this, 'plugin'), 0);
		}

		public function encrypt($data)
		{

			$this->get_encryptor();

			return $this->encryptor->encrypt($data);
		}

		public function decrypt($data)
		{

			$this->get_encryptor();

			return $this->encryptor->decrypt($data);
		}

		function get_encryptor()
		{
			if (!is_object($this->encryptor)) {
				$this->encryptor = new encrypt();
			}
		}

		public function get_main_blog_id()
		{
			$network_url = network_site_url();
			$network_url = str_replace("http://", "", $network_url);
			$network_url = str_replace("https://", "", $network_url);
			$network_url = str_replace("/", "", $network_url);
			$this->main_blog_id = get_blog_id_from_url($network_url);
		}

		protected function add_acf()
		{

			add_filter('assets/plugins/acf/plugin/settings/path', array($this, 'acf_settings_path'));
			add_filter('assets/plugins/acf/plugin/settings/dir', array($this, 'acf_settings_dir'));
			include_once($this->framework_path . 'inc/acf/plugin/acf.php');
		}

		protected function acf_hide_admin()
		{
			add_filter('acf/settings/show_admin', '__return_false');
		}

		protected function acf_settings_dir($dir)
		{

			$dir = $this->path . 'the-whale/inc/acf/plugin/';
			return $dir;
		}

		protected function acf_settings_path($path)
		{

			$path = $this->path . 'the-whale/inc/acf/';
			return $path;
		}

		function add_acf_menus()
		{
			/**
			 * If the plugin don't use custom menu, it will return false. If it uses,
			 * then we should replace that function on the child class
			 **/
			require_once($this->path . "the-whale/config/acf-menu.php");
		}

		function add_acf_fields()
		{
			/**
			 * If the plugin don't use ACF Fields, it will return false. If it uses,
			 * then we should replace that function on the child class
			 **/
			require_once($this->path . "the-whale/config/acf-fields.php");
		}

		/**
		 * This method handles the path and url setups, used on our utility belt methods
		 * @return null;
		 */
		protected final function setup_path()
		{

			// We need to check for the type of this project
			if ($this->settings['type'] === 'theme') {

				// Set paths and so on relative to themes directory
				$this->path = get_stylesheet_directory() . '/';
				$this->url  = get_stylesheet_directory_uri() . '/';

				// Do the same thing to our framework
				$this->framework_path = get_stylesheet_directory() . '/the-whale/';
				$this->framework_url  = get_stylesheet_directory_uri() . '/the-whale/';
			} // end if;

			// If this is a plugin (or anything else, for that matter), setup:
			else {
				// Setup our Plugin path
				$this->path = $this->settings['dir'] . "/";
				$this->url  = $this->settings['dir'] . "/";

				// Same for framework
				$this->framework_path = $this->settings['dir'] . "/the-whale/";
				$this->framework_url  = $this->settings['dir'] . "/the-whale/";
			} // end else;
		} // end setupPath;

		/*
	 * Accepts a CPT strings as input.
	 * Returns an array of field group IDs point at an array of their:
	 *  - meta: field group meta [ id, title, menu_order ]
	 *  - fields: array of info for each field [ key, label, name, type, order_no, instructions, required, id, class, conditional_logic[array()], etc. ]
	*/
		public function get_user_fields()
		{

			//first I need to get all the options_pages
			$posts = get_posts(array(
				'post_type' => 'acf-options-page',
			));

			foreach ($posts as $post) {
				if (get_post_meta($post->ID, '_acfop_save_to', true) == 'current_user') {
					$m = unserialize($post->post_content);
					$all_created_pages[] = $m['acf']['menu_slug'];
				}
			}

			if (count($all_created_pages) < 1) {
				return array();
			}

			$result = array();
			$acf_field_groups = acf_get_field_groups();
			foreach ($acf_field_groups as $acf_field_group) {
				foreach ($acf_field_group['location'] as $group_locations) {
					foreach ($group_locations as $rule) {
						unset($fields);
						if ($rule['param'] == 'options_page' && in_array($rule['value'], $all_created_pages)) {

							$fields = acf_get_fields($acf_field_group);

							foreach ($fields as $field) {

								if (empty($field['name'])) {
									continue;
								}
								$result[] = $field['name'];
							}
						}
					}
				}
			}

			return $result;
		}

		abstract function plugin();
	}

endif;
