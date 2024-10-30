=== Category Column ===
Contributors: tepelstreel
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=TQ9M9VJMAWA3Q
Tags: column, sidebar, widget, category, newspaper, image, multi widget
Requires at least: 2.9
Tested up to: 4.5
Stable tag: 4.5

The Category Column does simply, what the name says; it will show excerpts of the latest posts in your sidebar.

== Description ==

The Category Column is mainly designed to give your blog a bit more of a newspaper behaviour. E.g. The plugin shows the latest posts from all categories with an offset of three posts (which are in the main column) on our homepage.

If there is a post thumbnail, it will be displayed above the headline of the post. No further text will appear. If there is no thumbnail, only the headline and the excerpt of the post will be shown. When the plugin can detect neither the thumbnail nor the excerpt of a post, it will display just the first couple of sentenses (or words) of a post.

The Category Column was tested up to WP 4.5. It should work with versions down to 2.9 but was never tested on those.

== Installation ==

1. Upload the `category-coloumn` folder to the `/wp-content/plugins/` directory (I know, I made a spellingmistake on submission) ;)
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place and customize your widgets
4. Ready

== Frequently Asked Questions ==

= I styled the widget container myself and it looks bad. What do I do? =

The styling of the widget requires some knowledge of css. If you are not familiar with that, try adding

`padding: 10px;
margin-bottom: 10px;`
 
to the style section.

= My widget should have rounded corners, how do I do that? =

Add something like

`-webkit-border-top-left-radius: 5px;
-webkit-border-top-right-radius: 5px;
-moz-border-radius-topleft: 5px;
-moz-border-radius-topright: 5px;
border-top-left-radius: 5px;
border-top-right-radius: 5px;`
 
to the widget style. This is not supported by all browsers yet, but should work in almost all of them.

= My widget should have a shadow, how do I do that? =

Add something like

`-moz-box-shadow: 10px 10px 5px #888888;
-webkit-box-shadow: 10px 10px 5px #888888;
box-shadow: 10px 10px 5px #888888;`
 
to the widget style to get a nice shadow down right of the container. This is not supported by all browsers yet, but should work in almost all of them.

== Screenshots ==

1. The plugin's work on our homepage
2. The widget's settings section

== Changelog ==

= 4.5 =

* WP 4.5 ready
* New framework

= 4.4 =

* WP 4.3 ready
* New framework

= 4.3 =

* WP 4.1 ready
* New framework

= 4.2.2 =

* Mistake in image class fixed

= 4.2.1 =

* Interference with Wordpress Page Widgets eliminated

= 4.2 =

* DSS now compressible

= 4.1 =

* All 'Divided by Zero' errors should be eliminated

= 4.0 =

* Much better ability to recognize images, more foolproof and more flexible

= 3.9.1 =

* adjustments in framework, possibility to add image border

= 3.9 = 

* more responsive, a new feature and changes in framework

= 3.8.1 =

* bugfix with missing file

= 3.8 =

* Adjustment of the framework and more functionality

= 3.7 =

* Complete overhaul of the code
* Hooks into the [Ads Easy Plugin](http://wordpress.org/extend/plugins/adeasy) if Google AdSense Tags are in use

= 3.6 =

* More accurate excerpt
* Thumbnails work now also, when using galleries
* Correct 'alt' and 'title' tags

= 3.5 =
* The style textarea is now resizable and the input fields got smaller

= 3.2 =
* Bugfix concerning title slugs; settings accessible from plugin page now

= 3.1 =
* In case the sidebar containing the widget is displayed on single post pages, the post in the main column is not shown in the widget

= 3.0 =
* The offset is now increasing with paginaition on the frontpage of the blog

= 2.9.8 =
* Frontpage is taken instead of homepage now for more accurate working

= 2.9.7 =
* Small bugfix with Thumbnail

= 2.9.6 =
* German and Dutch translation added. Bugfix with Thumbnail for versions elder than 2.9

= 2.9.5 =
* Better working of the excerpt now. If there is no post thumbnail defined, the first picture of the post is taken as such.

= 2.9.1 =
* Small bugfix with the widget-style textarea and minor changes (widget is filled with default values at first use)

= 2.9 =
* The Category Column is a fully customizable multi widget now

= 2.1 =
* Plugin cleans the options database after itself on deinstallation (obsolete since version 2.9)

= 2.0 =
* Customizable for a bit and initial release

= 1.0 =
* Basic plugin for personal use

== Upgrade Notice ==

= 2.9.5 =
The excerpt function of the plugin works more accurate now and if there is no thumbnail, the first picture of the post is taken as such

= 2.9.6 =
German and Dutch translation added. Bugfix with Thumbnail for versions elder than 2.9

= 2.9.7 =
Small bugfix with Thumbnail

= 2.9.8 =
Frontpage is taken instead of homepage now for more accurate working

= 3.0 =
The offset is now increasing with paginaition on the frontpage of the blog

= 3.1 =
In case the sidebar containing the widget is displayed on single post pages, the post in the main column is not shown in the widget

= 3.2 =
Bugfix concerning title slugs; settings accessible from plugin page now

= 3.5 =
The style textarea is now resizable and the input fields got smaller

= 3.6 =
Better excerpts and corract alt and title tags, fetching the thumbnails works now also with galleries

= 3.7 =

Complete overhaul of the code
Hooks into the Ads Easy Plugin if Google AdSense Tags are in us

= 3.8 =

Adjustment of the framework and more functionality

= 3.8.1 =

bugfix with missing file

= 3.9 = 

more responsive, a new feature and changes in framework

= 3.9.1 =

adjustments in framework, possibility to add image border

= 4.0 =

Much better ability to recognize images, more foolproof and more flexible

= 4.1 =

All 'Divided by Zero' errors should be eliminated

= 4.2 =

DSS now compressible

= 4.2.1 =

Interference with Wordpress Page Widgets eliminated

= 4.2.2 =

Mistake in image class fixed

= 4.3 =

WP 4.1 ready; new framework

= 4.4 =

WP 4.3 ready; new framework

= 4.5 =

WP 4.5 ready; new framework