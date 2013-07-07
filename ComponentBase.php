<?php
/**
 * This file is part of the TemplateSystem library, a small controller-template system
 * 
 * Read more: https://github.com/halfer/TemplateSystem
 * Licensing terms at the same as Wordpress: http://wordpress.org/about/license/
 */

// See namespace notes in ControllerBase.php
namespace TemplateSystem\Change2;

// Don't define this if it's already been defined
if (!class_exists('TemplateSystem\Change2\ComponentBase'))
{
	abstract class ComponentBase
	{
		protected $controller;
		protected $root;

		public function __construct($controller, $root)
		{
			// Check controller is of the right type here
			if (!is_subclass_of($controller, 'TemplateSystem\Change2\ControllerBase'))
			{
				throw new \Exception('The controller passed to the component must inherit ControllerBase');
			}

			$this->controller = $controller;
			$this->root = $root;
		}

		abstract public function execute();

		/**
		 * Gets the currently running controller
		 * 
		 * @return ControllerBase
		 */
		public function getController()
		{
			return $this->controller;
		}

		public function getRoot()
		{
			return $this->root;
		}
	}
}