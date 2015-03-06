<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Stash Breaker Extension
 *
 * @package      ExpressionEngine
 * @subpackage   Addons
 * @category     Extension
 * @author       Dom Stubbs
 * @link         http://www.vayadesign.net
 */

class Stash_breaker_ext {

	public $settings       = array();
	public $description    = 'Automatically flush site-scoped Stashes when content is updated. Requires Stash 2.3.0 or greater.';
	public $docs_url       = '';
	public $name           = 'Stash Breaker';
	public $settings_exist = 'n';
	public $version        = '0.3';

	/**
	 * Constructor
	 *
	 * @param 	mixed	Settings array or empty string if none exist.
	 */
	public function __construct($settings = '')
	{
		$this->EE =& get_instance();
		$this->EE->load->model('addons_model');
		$this->settings = $settings;
	}

	// ----------------------------------------------------------------------

	/**
	 * Activate Extension
	 *
	 * This function enters the extension into the exp_extensions table
	 *
	 * @see http://codeigniter.com/user_guide/database/index.html for
	 * more information on the db class.
	 *
	 * @return void
	 */
	public function activate_extension()
	{
		// Setup custom settings in this array.
		$this->settings = array();

		$hooks = array(
			'entry_submission_end',
			'delete_entries_end',
			'update_multi_entries_start',
			'low_variables_post_save',
			'low_variables_delete',
			'low_reorder_post_sort',
			'structure_reorder_end',
			'deployment_hooks_post_deploy',
			'edit_wiki_article_end',
			'forum_submit_post_end',
			'insert_comment_end',
			'delete_comment_additional',
			'update_comment_additional',
			'category_save'
		);

		foreach($hooks as $hook)
		{
			$data = array(
				'class'    => __CLASS__,
				'method'   => 'flush_site_stashes',
				'hook'     => $hook,
				'settings' => serialize($this->settings),
				'version'  => $this->version,
				'enabled'  => 'y'
			);

			$this->EE->db->insert('extensions', $data);
		}
	}

	// ----------------------------------------------------------------------
	/**
	 * Flush Site Stashes
	 *
	 * Called by $hooks, removes all globally-scoped stashes
	 */
	public function flush_site_stashes()
	{
		if ( ! class_exists('Stash'))
		{
			include_once PATH_THIRD . 'stash/mod.stash.php';
		}

		$params = array(
			'scope' => 'site'
		);

		Stash::destroy($params);

		/**
		 * Bonus feature! Also flush CE Cache caches for specific hooks.
		 */

		$ce_cache_hooks = $this->EE->config->item('stash_breaker_ce_cache_hooks');
		$backtrace = debug_backtrace();
		$hook_called = $backtrace[2]['args'][0];

		if (is_array($ce_cache_hooks) && in_array($hook_called, $ce_cache_hooks))
		{

			if ( ! $this->EE->addons_model->module_installed('ce_cache'))
			{
				return;
			}

			if ( ! class_exists('Ce_cache_break'))
			{
				include_once PATH_THIRD . 'ce_cache/libraries/Ce_cache_break.php';
			}

			$cache_break = new Ce_cache_break();

			$ce_cache_config = array(
				'stash_breaker_ce_cache_items'        => array(),
				'stash_breaker_ce_cache_tags'         => array(),
				'stash_breaker_ce_cache_refresh'      => false,
				'stash_breaker_ce_cache_refresh_time' => 1
			);

			foreach ($ce_cache_config as $name => $value)
			{
				if ($this->EE->config->item($name)) {
					$ce_cache_config[$name] = $this->EE->config->item($name);
				}
			}

			$cache_break->break_cache(
				$ce_cache_config['stash_breaker_ce_cache_items'],
				$ce_cache_config['stash_breaker_ce_cache_tags'],
				$ce_cache_config['stash_breaker_ce_cache_refresh'],
				$ce_cache_config['stash_breaker_ce_cache_refresh_time']
			);
		}
	}

	// ----------------------------------------------------------------------

	/**
	 * Disable Extension
	 *
	 * This method removes information from the exp_extensions table
	 *
	 * @return void
	 */
	function disable_extension()
	{
		$this->EE->db->where('class', __CLASS__);
		$this->EE->db->delete('extensions');
	}

	// ----------------------------------------------------------------------

	/**
	 * Update Extension
	 *
	 * This function performs any necessary db updates when the extension
	 * page is visited
	 *
	 * @return 	mixed	void on update / false if none
	 */
	function update_extension($current = '')
	{
		if ($current == '' OR $current == $this->version)
		{
			return FALSE;
		}
	}

	// ----------------------------------------------------------------------
}

/* End of file ext.stash_breaker.php */
/* Location: /system/expressionengine/third_party/stash_breaker/ext.stash_breaker.php */