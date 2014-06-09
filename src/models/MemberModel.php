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


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Reads and writes members
 *
 * @package   Models
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2014
 */
class MemberModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_member';


	/**
	 * Find an active member by their username or e-mail address
	 *
	 * @param string $strValue   The username or password
	 * @param array  $arrOptions An optional options array
	 *
	 * @return \Model|null The model or null if there is no member
	 *
	 * @throws \Exception If there is more than one matching account
	 */
	public static function findActiveByUsernameOrEmail($strValue, array $arrOptions=array())
	{
		$time = time();
		$t = static::$strTable;

		// Try the e-mail address first
		$arrColumns = array("$t.email=? AND $t.login=1 AND ($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.disable=''");
		$objCollection = static::findBy($arrColumns, array($strValue, $strValue), $arrOptions);

		// Then try the username
		if ($objCollection === null)
		{
			$arrColumns = array("$t.username=? AND $t.login=1 AND ($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.disable=''");
			$objCollection = static::findBy($arrColumns, array($strValue, $strValue), $arrOptions);
		}

		// Validate the result set
		if ($objCollection === null)
		{
			return null;
		}
		elseif ($objCollection->count() > 1)
		{
			throw new \Exception("Found more than one account with the given username or e-mail address");
		}

		return $objCollection->current();
	}


	/**
	 * Find an active member by their e-mail-address and username
	 *
	 * @param string $strEmail    The e-mail address
	 * @param string $strUsername The username
	 * @param array  $arrOptions  An optional options array
	 *
	 * @return \Model|null The model or null if there is no member
	 */
	public static function findActiveByEmailAndUsername($strEmail, $strUsername=null, array $arrOptions=array())
	{
		$time = time();
		$t = static::$strTable;

		$arrColumns = array("$t.email=? AND $t.login=1 AND ($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.disable=''");

		if ($strUsername !== null)
		{
			$arrColumns[] = "$t.username=?";
		}

		return static::findOneBy($arrColumns, array($strEmail, $strUsername), $arrOptions);
	}
}
