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

use \Environment, \FeedItem, \System;


/**
 * Class Feed
 *
 * Provide methods to generate RSS/Atom feeds.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class Feed extends System
{

	/**
	 * Feed name
	 * @var string
	 */
	protected $strName;

	/**
	 * Data array
	 * @var array
	 */
	protected $arrData = array();

	/**
	 * Items
	 * @var array
	 */
	protected $arrItems = array();


	/**
	 * Take an array of arguments and initialize the object
	 * @param string
	 */
	public function __construct($strName)
	{
		parent::__construct();
		$this->strName = $strName;
	}


	/**
	 * Set an object property
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		$this->arrData[$strKey] = $varValue;
	}


	/**
	 * Return an object property
	 * @param string
	 * @return mixed
	 */
	public function __get($strKey)
	{
		if (isset($this->arrData[$strKey]))
		{
			return $this->arrData[$strKey];
		}

		return parent::__get($strKey);
	}


	/**
	 * Check whether a property is set
	 * @param string
	 * @return boolean
	 */
	public function __isset($strKey)
	{
		return isset($this->arrData[$strKey]);
	}


	/**
	 * Add an item
	 * @param \FeedItem
	 */
	public function addItem(FeedItem $objItem)
	{
		$this->arrItems[] = $objItem;
	}


	/**
	 * Generate an RSS 2.0 feed and return it as XML string
	 * @return string
	 */
	public function generateRss()
	{
		$this->adjustPublicationDate();

		$xml  = '<?xml version="1.0" encoding="' . $GLOBALS['TL_CONFIG']['characterSet'] . '"?>';
		$xml .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">';
		$xml .= '<channel>';
		$xml .= '<title>' . specialchars($this->title) . '</title>';
		$xml .= '<description>' . specialchars($this->description) . '</description>';
		$xml .= '<link>' . specialchars($this->link) . '</link>';
		$xml .= '<language>' . $this->language . '</language>';
		$xml .= '<pubDate>' . date('r', $this->published) . '</pubDate>';
		$xml .= '<generator>Contao Open Source CMS</generator>';
		$xml .= '<atom:link href="' . specialchars(Environment::get('base') . $this->strName) . '.xml" rel="self" type="application/rss+xml" />';

		foreach ($this->arrItems as $objItem)
		{
			$xml .= '<item>';
			$xml .= '<title>' . specialchars($objItem->title) . '</title>';
			$xml .= '<description><![CDATA[' . preg_replace('/[\n\r]+/', ' ', $objItem->description) . ']]></description>';
			$xml .= '<link>' . specialchars($objItem->link) . '</link>';
			$xml .= '<pubDate>' . date('r', $objItem->published) . '</pubDate>';
			$xml .= '<guid>' . ($objItem->guid ? $objItem->guid : specialchars($objItem->link)) . '</guid>';

			// Enclosures
			if (is_array($objItem->enclosure))
			{
				foreach ($objItem->enclosure as $arrEnclosure)
				{
					$xml .= '<enclosure url="' . $arrEnclosure['url'] . '" length="' . $arrEnclosure['length'] . '" type="' . $arrEnclosure['type'] . '" />';
				}
			}

			$xml .= '</item>';
		}

		$xml .= '</channel>';
		$xml .= '</rss>';

		return $xml;
	}


	/**
	 * Generate an Atom feed and return it as XML string
	 * @return string
	 */
	public function generateAtom()
	{
		$this->adjustPublicationDate();

		$xml  = '<?xml version="1.0" encoding="' . $GLOBALS['TL_CONFIG']['characterSet'] . '"?>';
		$xml .= '<feed xmlns="http://www.w3.org/2005/Atom" xml:lang="' . $this->language . '">';
		$xml .= '<title>' . specialchars($this->title) . '</title>';
		$xml .= '<subtitle>' . specialchars($this->description) . '</subtitle>';
		$xml .= '<link rel="alternate" href="' . specialchars($this->link) . '" />';
		$xml .= '<id>' . specialchars($this->link) . '</id>';
		$xml .= '<updated>' . preg_replace('/00$/', ':00', date('Y-m-d\TH:i:sO', $this->published)) . '</updated>';
		$xml .= '<generator>Contao Open Source CMS</generator>';
		$xml .= '<link href="' . specialchars(Environment::get('base') . $this->strName) . '.xml" rel="self" />';

		foreach ($this->arrItems as $objItem)
		{
			$xml .= '<entry>';
			$xml .= '<title>' . specialchars($objItem->title) . '</title>';
			$xml .= '<content type="xhtml"><div xmlns="http://www.w3.org/1999/xhtml">' . preg_replace('/[\n\r]+/', ' ', $objItem->description) . '</div></content>';
			$xml .= '<link rel="alternate" href="' . specialchars($objItem->link) . '" />';
			$xml .= '<updated>' . preg_replace('/00$/', ':00', date('Y-m-d\TH:i:sO', $objItem->published)) . '</updated>';
			$xml .= '<id>' . ($objItem->guid ? $objItem->guid : specialchars($objItem->link)) . '</id>';
			$xml .= '<author><name>' . $objItem->author . '</name></author>';

			// Enclosures
			if (is_array($objItem->enclosure))
			{
				foreach ($objItem->enclosure as $arrEnclosure)
				{
					$xml .= '<link rel="enclosure" type="' . $arrEnclosure['type'] . '" href="' . $arrEnclosure['url'] . '" length="' . $arrEnclosure['length'] . '" />';
				}
			}

			$xml .= '</entry>';
		}

		return $xml . '</feed>';
	}


	/**
	 * Adjust the publication date
	 */
	protected function adjustPublicationDate()
	{
		if (!empty($this->arrItems) && $this->arrItems[0]->published > $this->published)
		{
			$this->published = $this->arrItems[0]->published;
		}
	}
}
