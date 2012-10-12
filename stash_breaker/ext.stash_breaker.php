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
	public $description    = 'Automatically flush site-scoped Stashes when entries are updated. Requires Stash 2.3.0 or greater.';
	public $docs_url       = '';
	public $name           = 'Stash Breaker';
	public $settings_exist = 'n';
	public $version        = '0.1';

	/**
	 * Constructor
	 *
	 * @param 	mixed	Settings array or empty string if none exist.
	 */
	public function __construct($settings = '')
	{
		$this->EE =& get_instance();
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
			'low_variables_post_save',
			'low_variables_delete',
			'delete_entries_end'
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