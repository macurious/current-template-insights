=== Current Template Insights ===
Contributors: macurious
Donate link: https://paypal.me/wpmacurious
Tags: admin bar, template, debug, theme, development
Requires at least: 5.5
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

**Quickly view the active template file and important page information directly in your WordPress admin bar.**

== Description ==

**Displays the current template file and key page details in the WordPress admin bar for logged-in administrators.**  
No setup required: just install, activate, and see the info bar on any front-end page.

**Features:**
- Shows “📄 Template: <filename>” in the front-end admin bar
- Details on hover/click: full template path, post ID, post type, slug, theme name & version, locale, body classes, query vars, conditionals, DB query count, memory usage, and more
- No settings page, no configuration
- Works with classic and block/FSE themes (special info for block themes)
- Developer-friendly, lightweight, no bloat

== Installation ==

1. Upload the `current-template-insights` folder to `/wp-content/plugins/`
2. Activate the plugin via the Plugins menu
3. Visit any front-end page as an admin to view the template info in the admin bar

== Frequently Asked Questions ==

= Does this work with Full Site Editing (block) themes? =  
Yes! With block (Full Site Editing) themes, WordPress uses a core PHP file (`template-canvas.php`) as a wrapper for block templates. The plugin displays the actual PHP template in use. (Future versions may add a more descriptive message for block themes.)

= Is there a settings page? =  
No settings page — just install and go. All information is displayed in the admin bar for logged-in admins.

= Why can't i see the template information on the front-end? =  
The plugin displays its information in the WordPress admin bar. If the admin bar is disabled in the frontend (via user settings, code, or another plugin), then no output will be displayed.

= Can I extend or customize the details shown? =  
Yes, developers can use the `current_template_insights_details` filter hook to add or modify the debug info.

== Screenshots ==

1. Admin bar showing current template and dropdown with details
2. More complex example showing the single post template of a CPT

== Changelog ==

= 1.0.0 =
* First public release

== Upgrade Notice ==

= 1.0.0 =
First public release.

