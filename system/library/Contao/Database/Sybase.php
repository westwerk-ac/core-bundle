<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Library
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao;

use \Database, \Database_Sybase_Statement;


/**
 * Class Database_Sybase
 *
 * Driver class for Sybase databases.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class Database_Sybase extends Database
{

	/**
	 * List tables query
	 * @var string
	 */
	protected $strListTables = "SELECT name FROM sysobjects WHERE type='U' ORDER BY name";

	/**
	 * List fields query
	 * @var string
	 */
	protected $strListFields = "SELECT c.column_name, c.column_type, c.width FROM syscolumn c, systable t WHERE t.table_name='%s' AND c.table_id=t.table_id AND t.table_type='BASE'";


	/**
	 * Connect to the database server and select the database
	 */
	protected function connect()
	{
		$strHost = $GLOBALS['TL_CONFIG']['dbHost'];

		if ($GLOBALS['TL_CONFIG']['dbPort'])
		{
			$strHost .= ':' . $GLOBALS['TL_CONFIG']['dbPort'];
		}

		if ($GLOBALS['TL_CONFIG']['dbPconnect'])
		{
			$this->resConnection = @sybase_pconnect($strHost, $GLOBALS['TL_CONFIG']['dbUser'], $GLOBALS['TL_CONFIG']['dbPass'], $GLOBALS['TL_CONFIG']['dbCharset']);
		}
		else
		{
			$this->resConnection = @sybase_connect($strHost, $GLOBALS['TL_CONFIG']['dbUser'], $GLOBALS['TL_CONFIG']['dbPass'], $GLOBALS['TL_CONFIG']['dbCharset']);
		}

		if (is_resource($this->resConnection))
		{
			@sybase_select_db($GLOBALS['TL_CONFIG']['dbDatabase']);
		}
	}


	/**
	 * Disconnect from the database
	 */
	protected function disconnect()
	{
		@sybase_close();
	}


	/**
	 * Return the last error message
	 * @return string
	 */
	protected function get_error()
	{
		return sybase_get_last_message();
	}


	/**
	 * Auto-generate a FIND_IN_SET() statement
	 * @param string
	 * @param string
	 * @param boolean
	 * @return string
	 */
	protected function find_in_set($strKey, $strSet, $blnIsField=false)
	{
		$arrSet = trimsplit(',', $strSet);

		foreach ($arrSet as $k=>$v)
		{
			$arrSet[$k] = str_replace("'", "''", $v);
		}

		return $strKey . "='" . implode("' DESC, $strKey='", $arrSet) . "' DESC";
	}


	/**
	 * Return a standardized array with field information
	 *
	 * Standardized format:
	 * - name:       field name (e.g. my_field)
	 * - type:       field type (e.g. "int" or "number")
	 * - length:     field length (e.g. 20)
	 * - precision:  precision of a float number (e.g. 5)
	 * - null:       NULL or NOT NULL
	 * - default:    default value (e.g. "default_value")
	 * - attributes: attributes (e.g. "unsigned")
	 * - extra:      extra information (e.g. auto_increment)
	 * @param string
	 * @return array
	 * @todo This function is not tested yet, nor is the list tables and list fields statement!
	 */
	protected function list_fields($strTable)
	{
		$arrReturn = array();
		$arrFields = $this->query(sprintf($this->strListFields, $strTable))->fetchAllAssoc();

		foreach ($arrFields as $k=>$v)
		{
			$arrReturn[$k]['name'] = $v['column_name'];
			$arrReturn[$k]['type'] = $v['column_type'];
			$arrReturn[$k]['length'] = $v['width'];
		}

		return $arrReturn;
	}


	/**
	 * Change the current database
	 * @param string
	 * @return boolean
	 */
	protected function set_database($strDatabase)
	{
		return @sybase_select_db($strDatabase);
	}


	/**
	 * Begin a transaction
	 */
	protected function begin_transaction()
	{
		@sybase_query("BEGIN TRAN");
	}


	/**
	 * Commit a transaction
	 */
	protected function commit_transaction()
	{
		@sybase_query("COMMIT TRAN");
	}


	/**
	 * Rollback a transaction
	 */
	protected function rollback_transaction()
	{
		@sybase_query("ROLLBACK TRAN");
	}


	/**
	 * Lock one or more tables
	 * @param array
	 * @todo implement
	 */
	protected function lock_tables($arrTables) {}


	/**
	 * Unlock all tables
	 * @todo implement
	 */
	protected function unlock_tables() {}


	/**
	 * Return the table size in bytes
	 * @param string
	 * @return integer
	 * @todo implement
	 */
	protected function get_size_of($strTable) {}


	/**
	 * Return the next autoincrement ID of a table
	 * @param string
	 * @return integer
	 * @todo implement
	 */
	protected function get_next_id($strTable) {}


	/**
	 * Create a Database_Statement object
	 * @param resource
	 * @param boolean
	 * @return \Database_Sybase_Statement
	 */
	protected function createStatement($resConnection, $blnDisableAutocommit)
	{
		return new Database_Sybase_Statement($resConnection, $blnDisableAutocommit);
	}
}
