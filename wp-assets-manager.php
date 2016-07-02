<?php
/*
	Plugin Name: Assets Manager for WordPress
	Plugin URI: http://www.jackreichert.com/2014/01/12/introducing-assets-manager-for-wordpress/
	Description: Plugin creates an assets manager. Providing a self hosted file sharing platfrom.
	Version: 0.6.2
	Author: Jack Reichert
	Author URI: http://www.jackreichert.com
	Text Domain: assets-manager
	License: GPL3
*/

$wp_assets_manager = new WP_Assets_Manager();

class WP_Assets_Manager {
	private $Log_Assets_Access;
	private $Assets_Manager_Asset_Type;

	/* 
	 * Assets Manager class construct
	 */
	public function __construct() {
		$this->setup();
		$this->teardown();

		$this->include_dependencies();
		$this->instantiate_components();
	}

	/**
	 * Plugin activation
	 */
	public function setup() {
		register_activation_hook( __FILE__, array( $this, 'wp_assets_manager_activate' ) );
	}

	/**
	 * Plugin deactivation
	 */
	public function teardown() {
		register_deactivation_hook( __FILE__, array( $this, 'wp_assets_manager_deactivate' ) );
	}

	/**
	 * Include all dependencies
	 */
	public function include_dependencies() {
		require_once 'inc/Serve_Attachment.php';
		require_once 'inc/Log_Assets_Access.php';
		require_once 'inc/Check_Asset_Restrictions.php';
		require_once 'inc/Admin.php';
		require_once 'inc/Asset_Post_Type.php';
		require_once 'inc/Public.php';
		require_once 'inc/Update_Assets.php';
	}

	/**
	 * Instantiates all components of plugin
	 */
	public function instantiate_components() {
		$this->Log_Assets_Access = new Assets_Manager_Log_Assets_Access();
		$this->Log_Assets_Access->init();
		
		$this->Assets_Manager_Asset_Type = new Assets_Manager_Asset_Post_Type();
		$this->Assets_Manager_Asset_Type->init();

		$Check_Asset_Restrictions = new Check_Asset_Restrictions();
		$Check_Asset_Restrictions->init();

		$Serve_File = new Assets_Manager_Serve_Attachment();
		$Serve_File->init();
		
		$Public = new Assets_Manager_Public();
		$Public->init();
		
		$Assets_Manager_Admin = new Assets_Manager_Admin();
		$Assets_Manager_Admin->init();
		
		$Assets_Manager_Update_Asset = new Assets_Manager_Update_Asset();
		$Assets_Manager_Update_Asset->init();
	}

	/**
	 * Run this on plugin activation
	 */
	public function wp_assets_manager_activate() {
		$this->Log_Assets_Access->create_log_table();
		$this->Assets_Manager_Asset_Type->create();
		flush_rewrite_rules();
	}

	/**
	 * Clean up after deactivation
	 */
	public function wp_assets_manager_deactivate() {
		flush_rewrite_rules();
	}
}
		