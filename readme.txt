=== WPLO Survey ===
Contributors: edgewebapps
Tags: Luminate, Luminate Online, Blackbaud, Surveys, Newsletters
Requires at least: 5
Tested up to: 5.7.1
Requires PHP: 7.3
Stable tag: 2.5.1
License: GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Connect Luminate Online surveys to WordPress without having to code- just enter the survey id and you'll get a shortcode to embed on your pages.

== Description ==

This plugin allows you to enter a LO survey id into the WPLO Survey plugin in WordPress to create a fully responsive, customizable LO survey that you can embed anywhere on your WordPress site.

NEW: Major rewrite of plugin for version 2.0- all Luminate Online Survey elements are now supported.  Survey elements can be added in any order now- just setup your form in LO, paste the survey id in WPLO Survey and publish your form!

* Automatically creates LO surveys in WordPress
* Shortcode system for embedding these forms anywhere on your site
* Fully responsive donation form
* CSS Styling tab in plugin to change how the form looks
* Post survey signup thank you on the same page (you can tell the plugin what sort of message to display after a successful signup)
* Error reporting that includes your organization's email address for support
* Google Analytics support built in via a GTM datalayer push event, customizable through the WordPress interface
* FB Pixel support built in, fires event on successful survey submit
* add a progress bar to your Luminate surveys in WordPress, useful for signup/pledge campaigns that have a goal- goal requires "Constituent Registration Info" in order to work, and a goal set under form styling.
* jQuery datepicker enabled for constituent date fields
* NEW: Added support for all LO survey question types except captcha (eg caption, rating scale, short text, etc).  Just make your survey in LO, add it in WPLO Survey and start collecting data!
* NEW: WPLO Survey will now autopopulate your LO surveys into WordPress- just selected them from a list instead of copying and pasting the survey id!

== Screenshots ==

1. Example of goal bar on a LO survey in WP 
2. Example of jQuery datepicker for an LO date of birth field in WP
3. Example of multiple choice questions from LO survey in WP
4. Example of responsive LO survey in WP
5. Form styling options in WP admin area
6. Form analytics options in WP admin area

== Changelog ==

= 2.5.1 =
* Added some bug fixes/speed improvements.

= 2.5 =
* Added bug fixes, improved functionality of plugin with complex LO forms.

= 2.4 =
* Added support for multi-locale surveys, users can now select which language version survey will use

= 2.32 =
* Minor fixes for css styling

= 2.31 =
* Fix for filemtime error, occured when enqueuing stylesheet for plugin.

= 2.3 =
* Added cleave.js for input formatting on the fly- constituent registration phone number now only allows valid phone numbers in it for example.  This feature will be expanded to other fields as well to help have more intuitive field inputs that eliminate user error/misunderstanding and ensures data is formatted correctly.

= 2.2 =
* Add jQuery validation plugin to handle survey submission error handling more intuitively.
* Rewrote some of the error reporting system.
* Cleaned up some of the css for displaying fields (especially two input mode/numbered list mode)

= 2.0 =
* Major rewrite of plugin, adds support for all Luminate Online Survey items except captcha (already uses built in systems for captcha replacement). 

= 1.0 =
* Initial version.

== Installation ==
Install plugin.  Activate, and then enter your Luminate Online open api settings into the plugin setup page.  You're ready to load Luminate Online surveys/newsletters in WordPress!

== Frequently Asked Questions ==
What survey functions are supported?

Every Luminate Online survey function is supported!  If there's any features that don't work as expected or could be improved, let us know and we'll improve it.