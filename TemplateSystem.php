<?php

// Don't define this if it's already been defined
if (!class_exists('TemplateSystem'))
{
	abstract class TemplateSystem
	{
		protected $root;
		protected $component;

		public function __construct($root)
		{
			$this->root = $root;
		}

		/**
		 * Call this from the front controller to run all executable methods in the child
		 */
		public function runAll()
		{
			$run = 0;
			foreach (array('preExecute', 'execute', 'postExecute') as $method)
			{
				if (method_exists($this, $method))
				{
					$this->$method();
					$run++;
				}
			}

			// If nothing has been run, throw an error
			if (!$run)
			{
				throw new Exception('The child controller class should implement at least one of the preExecute, execute, postExecute methods');
			}
		}

		public function renderTemplate($template, array $params = array())
		{
			extract($params);
			require_once "{$this->root}/templates/{$template}.php";
		}

		public function renderPartial($template, array $params = array())
		{
			return $this->renderTemplate('_' . $template, $params);
		}

		public function renderComponent($class, $template)
		{
			// Load component base and specific component classes
			require_once dirname(__FILE__) . '/TemplateComponentBase.php';
			require_once $this->root . '/components/' . $class . '.php';

			// Ensure the new thing extends the base correctly
			$this->component = new $class($this, $this->root);
			if (!($this->component instanceof TemplateComponentBase))
			{
				throw new Exception('Components must extend TemplateComponentBase');
			}

			$templateVars = $this->component->execute();
			$this->renderTemplate('_' . $template, $templateVars);
		}

		/**
		 * Gets the current component instance (useful in the component template)
		 * 
		 * @return TemplateComponentBase
		 */
		protected function getComponentInstance()
		{
			return $this->component;
		}

		public function getRenderedPartial($template, array $params = array())
		{
			$ok = ob_start();
			$this->renderPartial($template, $params);
			$contents = ob_get_contents();
			ob_clean();

			return $contents;			
		}

		public function getRenderedComponent($class, $template)
		{
			$ok = ob_start();
			$this->renderComponent($class, $template);
			$contents = ob_get_contents();
			ob_clean();

			return $contents;
		}

		protected function getInput($key)
		{
			return isset($_REQUEST[$key]) ? $_REQUEST[$key] : null;
		}

		public function getRoot()
		{
			return $this->root;
		}
	}
}