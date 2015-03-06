# Stash Breaker

## Description

This is an extremely basic extension for [Stash](https://github.com/croxton/Stash) which adds cache breaking for globally scoped variables. Stash Breaker will clear out all of your saved Stash variables whenever the following occurs:

* Entries are edited or deleted.
* [Low Variables](http://gotolow.com/addons/low-variables) are edited or deleted.
* [Structure](http://buildwithstructure.com) pages are reordered.
* [Deployment hooks](https://github.com/focuslabllc/deployment_hooks.ee2_addon) are called.
* [Low Reorder](http://gotolow.com/addons/low-reorder) sets are reordered.
* Wiki articles are edited
* Posts are submitted in a Forum
* Comments are submitted, edited, or deleted
* Categories are edited or deleted

## Installation

1. Upload the stash_breaker folder to system/expressionengine/third_party
2. Install the extension via Add-Ons → Extensions

## Updating

Disable and reenable the extension to take advantage of newly added hooks.

## CE Cache Support

You can now use Stash Breaker to flush [CE Cache](http://www.causingeffect.com/software/expressionengine/ce-cache) data when the above hooks are triggered as well. You'll need to add a few config variables to set this up:

	/**
	 * Which Stash Breaker hooks should trigger CE Cache cache breaking
	 */
	$config['stash_breaker_ce_cache_hooks'] = array();

	/**
	 * CE Cache items to remove
	 */
	$config['stash_breaker_ce_cache_items'] = array();

	/**
	 * CE Cache tags to remove
	 */
	$config['stash_breaker_ce_cache_tags'] = array();

	/**
	 * Toggle CE Cache refresh support
	 */
	$config['stash_breaker_ce_cache_refresh'] = false;

	/**
	 * Seconds between refreshing CE Cache items (if refreshing is enabled)
	 */
	$config['stash_breaker_ce_cache_refresh_time'] = 1;


A basic config might look like this:

	$config['stash_breaker_ce_cache_hooks'] = array('low_variables_post_save');
	$config['stash_breaker_ce_cache_tags'] = array('home');
	$config['stash_breaker_ce_cache_refresh'] = true;

This would clear and refresh any caches tagged "home" when Low Variables are saved.