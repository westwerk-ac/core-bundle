<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao;


/**
 * Class ModuleHtml
 *
 * Front end module "HTML".
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class ModuleHtml extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_html';


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		$this->Template->html = (TL_MODE == 'FE') ? $this->html : htmlspecialchars($this->html);
	}
}
