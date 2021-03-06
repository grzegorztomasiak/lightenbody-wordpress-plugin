<?php

/**
 * Class Lightenbody
 */
class Lightenbody
{
	protected $loader;
	protected $plugin_name;
	protected $version;

	public function __construct()
    {
		$this->plugin_name = 'lightenbody';
		$this->version = '3.0.0';

		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	private function load_dependencies()
    {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/lightenbody-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/lightenbody-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/lightenbody-public.php';

		$this->loader = new Lightenbody_Loader();
	}

	private function define_admin_hooks()
    {
		$plugin_admin = new Lightenbody_Admin($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
		$this->loader->add_action('admin_init', $plugin_admin, 'settings_update');
		$this->loader->add_action('admin_menu', $plugin_admin, 'add_plugin_admin_menu');

		$plugin_basename = plugin_basename(plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php');
		$this->loader->add_filter('plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links');
	}

	private function define_public_hooks()
    {
		$plugin_public = new Lightenbody_Public($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
		$this->loader->add_action('init', $plugin_public, 'register_shortcodes');
	}

	public function bootstrap()
    {
		$this->loader->bootstrap();
	}

	public function get_plugin_name()
    {
		return $this->plugin_name;
	}

	public function get_loader()
    {
		return $this->loader;
	}

	public function get_version()
    {
		return $this->version;
	}
}
