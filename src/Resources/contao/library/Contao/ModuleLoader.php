<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;

@trigger_error('Using the Contao\ModuleLoader class has been deprecated and will no longer work in Contao 5.0. Use the container parameter "kernel.bundles" instead.', E_USER_DEPRECATED);


/**
 * Loads modules based on their autoload.ini configuration
 *
 * The class reads the autoload.ini files of the available modules and returns
 * an array of active modules with their dependencies solved.
 *
 * Usage:
 *
 *     $arrModules = ModuleLoader::getActive();
 *     $arrModules = ModuleLoader::getDisabled();
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 *
 * @deprecated Deprecated since Contao 4.0, to be removed in Contao 5.0.
 *             Use the container parameter "kernel.bundles" instead.
 */
class ModuleLoader
{

	/**
	 * Old module names
	 * @var array
	 */
	private static $legacy = array
	(
		'ContaoCoreBundle'       => 'core',
		'ContaoCalendarBundle'   => 'calendar',
		'ContaoCommentsBundle'   => 'comments',
		'ContaoFaqBundle'        => 'faq',
		'ContaoListingBundle'    => 'listing',
		'ContaoNewsBundle'       => 'news',
		'ContaoNewsletterBundle' => 'newsletter'
	);


	/**
	 * Return the active modules as array
	 *
	 * @return array An array of active modules
	 */
	public static function getActive()
	{
		$bundles = array_keys(\System::getContainer()->getParameter('kernel.bundles'));

		foreach (static::$legacy as $bundleName => $module)
		{
			if (in_array($bundleName, $bundles))
			{
				$bundles[] = $module;
			}
		}

		return $bundles;
	}


	/**
	 * Return the disabled modules as array
	 *
	 * @return array An array of disabled modules
	 */
	public static function getDisabled()
	{
		return array();
	}
}
