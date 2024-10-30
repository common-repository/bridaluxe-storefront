=== Bridaluxe ===
Contributors: nickohrn
Tags: admin, affiliate, storefront, bridaluxe
Requires at least: 2.6
Tested up to: 2.6.2
Stable tag: 1.0.5

Instantly add a complete Bridaluxe affiliate store to your WordPress blog.

== Description ==

The Bridaluxe plugin for WordPress allows you to easily and quickly add a complete Bridaluxe affiliate
store, consisting of thousands of unique and specialized products.

To add the Bridaluxe capabilities to your WordPress blog, insert a [bridaluxe] shortcode into any post or page.  You
set your affiliate ID under 'Settings > Bridaluxe'.

Also included with this plugin is a new template tag that allows you to display a navigation menu for the Bridaluxe functionality.
 

== Installation ==

This plugin follows the standard WordPress plugin installation method.

1. Upload the `bridaluxe` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How do I include the Bridaluxe Storefront? =
The only thing you have to do is add the [bridaluxe] shorttag to a page or post content.

= How do I include the optional navigation item? =
You can include the navigation item anywhere that you want using the template tag `bridaluxe_navigation`.

Include the following in your template:

`if function_exists( 'bridaluxe_navigation' ) {
	bridaluxe_navigation();
}`

= What is the recommended way to use this plugin? =
The best way to use the Bridaluxe Storefront plugin is as follows:

1. Upload and activate the plugin
1. Create a new page template for your theme that removes the page title and adds the `bridaluxe_navigation` template tag in your sidebar.
1. Create a new page with content `[bridaluxe]` and choose the page template you created in step 2
1. Ensure that your WordPress permalinks settings are saved.  The plugin works better if you have selected something other than default

After following these steps, you can style the content however you wish by adding styles to your template's `style.css` file.
That's it, you have a complete storefront!