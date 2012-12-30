Introduction
============

TemplateSystem is a tiny template system, which although it is ideal for Wordpress plugins, can be used anywhere a lightweight PHP view layer is required. It is, at the time of writing, just two classes.

It provides these features:

+ Separation of business logic and view HTML
+ Encouragement towards object-based MVC for traditionally procedural WP systems
+ Separation of templates into partials (fragments without their own controller)
+ Separation of templates into components (fragments with their own controller)

Basics
======

License
-------

The license terms for TemplateSystem is the same as Wordpress, to make it easy for WP users. That is, this code is licensed under GPLv2 (or later). 

Installing
----------

If you're using Git, installing is easy. From the root of your plugin folder:

    mkdir vendor
    git submodule add https://github.com/halfer/TemplateSystem.git vendor/TemplateSystem
    git submodule init
    git commit

Including
---------

From your template root, you can do just this:

    require_once $root . '/vendor/TemplateSystem/TemplateSystem.php';

The component base class will be loaded automatically if it is required.

Usage
-----

Your entry point to the plugin should create a controller class, which is a descendent of the `TemplateSystem` class. Personally, I like to extend `TemplateSystem` to a base class for the whole plugin (e.g. MyPluginBase) and then any plugin entry points can extend that. That helps provide a common parent in which shared controller code may reside.

So you could have:

	class MyPluginBase extends TemplateSystem {} /* in lib/MyPluginBase.php */
	class MyPluginMain extends MyPluginBase {} /* in lib/MyPluginMain.php */

When instantiating a controller, the full path of the plugin should be provided to the constructor:

    $root = dirname(__FILE__);

    require_once $root . '/vendor/TemplateSystem/TemplateSystem.php';
    require_once $root . '/lib/MyPluginBase.php';
    require_once $root . '/lib/MyPluginMain.php';
    
    new MyPluginMain( $root );

When rendering a template in a controller - say for an options page - we use something like this:

    $this->renderTemplate(
		'info',
		array(
			'usefulData' => $usefulData,
		)
	);

This expects a file `templates/info.php` to exist in the plugin, to render this view. The developer can then expect `$usefulData` to be available in that template as supplied, as well as `$this`, pointing at the controller instance.

If the developer wishes to call a snippet (otherwise known as a partial) from a template, he/she can do so thusly:

    <?php $this->renderPartial( 'snippet', array( 'usefulData' => $usefulData, ) ) ?>

This will look up the file `templates/_snippet.php`, and render it in situ, again with the specified variables passed on. The underscore helps differentiate between a full template and a partial template.

Components
----------

The developer may also include a partial with its own logic, otherwise known as a component. To do this, a component class must be created in `/components` inside the plugin, and it must extend `TemplateComponentBase`. This may then be called thus:

    <?php $this->renderComponent( 'ClassName', 'componentName' ) ?>

Code inside a component instance can easily access the current controller, via `$this->getController()`.

Code inside a component partial is rendered in the context of the controller, so `$this` will work fine. Access to the component instance itslef can be obtained using `$this->getComponentInstance()`.

Advanced usage
--------------

Some occasions call for the rendering of a partial or a component into a variable, rather than to the browser. This is most useful in AJAX operations where the output needs to be converted before being sent to the client. The calls for this are very similar to the rendering calls above:

    $html = $this->getRenderedPartial( 'snippet', array( 'usefulData' => $usefulData, ) );
    $html = $this->getRenderedComponent( 'ClassName', 'componentName' );

Utility methods
---------------

The base controller contains a method to access `$_REQUEST`, which is `$this->getInput('key')`. This is preferable to accessing $_REQUEST directly, as it will return null without PHP warnings if the key does not exist.

Future development
------------------

* Fragment caching, via file or memcache, would be very useful, and should be easy to add.

General
-------

Feedback and improvements are very welcome.
