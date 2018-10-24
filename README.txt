=== Plugin Name ===
Contributors: sdaland2, gschoppe
Donate link: https://github.com/stephanieland352
Tags: comments, spam
Requires at least: 4.5.0
Tested up to: 4.7
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Changes the WP Job manager plugin to search locations with a radius.

== Description ==

This plugin geocodes the location search field in the [jobs] shortcode. It then compares the longitude and latitude to a find results within a radius (miles or kilometers) defined in the settings.



== Installation ==
1. Install the WP Jobs Manager Plugin
1. Upload `wp-job-manager-geolocation-search.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Define your distance unit and radius under the wp-jobs-manager settings in the "Geolocation Settings" tab.

== Frequently Asked Questions ==

= Why am I getting no results =

Check to see if your postings are Published and that they are not expired.

= They are still not showing up =

Check the Job Listings Screen in the Geolocated column to make sure the job has been geolocated. If not, try resaving the job post.

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.0 =
* A change since the previous version.
* Another change.

= 0.5 =
* List versions from most recent at top to oldest at bottom.

== Upgrade Notice ==

= 1.0 =
Upgrade notices describe the reason a user should upgrade.  No more than 300 characters.

= 0.5 =
This version fixes a security related bug.  Upgrade immediately.

== Arbitrary section ==

You may provide arbitrary sections, in the same format as the ones above.  This may be of use for extremely complicated
plugins where more information needs to be conveyed that doesn't fit into the categories of "description" or
"installation."  Arbitrary sections will be shown below the built-in sections outlined above.

== A brief Markdown Example ==

Ordered list:

1. Some feature
1. Another feature
1. Something else about the plugin

Unordered list:

* something
* something else
* third thing

Here's a link to [WordPress](http://wordpress.org/ "Your favorite software") and one to [Markdown's Syntax Documentation][markdown syntax].
Titles are optional, naturally.

[markdown syntax]: http://daringfireball.net/projects/markdown/syntax
            "Markdown is what the parser uses to process much of the readme file"

Markdown uses email style notation for blockquotes and I've been told:
> Asterisks for *emphasis*. Double it up  for **strong**.

`<?php code(); // goes in backticks ?>`