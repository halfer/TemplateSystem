<?php

// Don't define this if it's already been defined
if (!class_exists('TemplateComponentBase'))
{
	abstract class TemplateComponentBase
	{
		protected $controller;
		protected $root;

		public function __construct(TemplateSystem $controller, $root)
		{
			$this->controller = $controller;
			$this->root = $root;
		}

		abstract public function execute();

		/**
		 * Gets the currently running controller
		 * 
		 * @return TemplateSystem
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