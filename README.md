# Stash Breaker

## Description

This is an extremely basic extension for [Stash](https://github.com/croxton/Stash) which adds cache breaking for globally scoped variables. Stash Breaker will clear out all of your saved Stash variables whenever the following occurs:

* Entries are edited or deleted.
* [Low Variables](http://gotolow.com/addons/low-variables) are edited or deleted.
* [Structure](http://buildwithstructure.com) pages are reordered.
* [Deployment hooks](https://github.com/focuslabllc/deployment_hooks.ee2_addon) are called.
* [Low Reorder](http://gotolow.com/addons/low-reorder) sets are reordered.

## Installation

1. Upload the stash_breaker folder to system/expressionengine/third_party
2. Install the extension via Add-Ons → Extensions

## Updating

Disable and reenable the extension to take advantage of newly added hooks.