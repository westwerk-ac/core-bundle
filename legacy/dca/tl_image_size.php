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
 * Table tl_image_size
 */
$GLOBALS['TL_DCA']['tl_image_size'] =
[

	// Config
	'config' =>
	[
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_theme',
		'ctable'                      => ['tl_image_size_item'],
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'onload_callback' =>
		[
			['tl_image_size', 'checkPermission'],
			['tl_image_size', 'showJsLibraryHint']
		],
		'sql' =>
		[
			'keys' =>
			[
				'id' => 'primary',
				'pid' => 'index'
			]
		]
	],

	// List
	'list' =>
	[
		'sorting' =>
		[
			'mode'                    => 4,
			'fields'                  => ['name'],
			'panelLayout'             => 'filter;search,limit',
			'headerFields'            => ['name', 'author', 'tstamp'],
			'child_record_callback'   => ['tl_image_size', 'listImageSize'],
			'child_record_class'      => 'no_padding'
		],
		'global_operations' =>
		[
			'all' =>
			[
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			]
		],
		'operations' =>
		[
			'edit' =>
			[
				'label'               => &$GLOBALS['TL_LANG']['tl_image_size']['edit'],
				'href'                => 'table=tl_image_size_item',
				'icon'                => 'edit.gif'
			],
			'editheader' =>
			[
				'label'               => &$GLOBALS['TL_LANG']['tl_image_size']['editheader'],
				'href'                => 'table=tl_image_size&amp;act=edit',
				'icon'                => 'header.gif',
				'button_callback'     => ['tl_image_size', 'editHeader']
			],
			'copy' =>
			[
				'label'               => &$GLOBALS['TL_LANG']['tl_image_size']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif'
			],
			'cut' =>
			[
				'label'               => &$GLOBALS['TL_LANG']['tl_image_size']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset()"'
			],
			'delete' =>
			[
				'label'               => &$GLOBALS['TL_LANG']['tl_image_size']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			],
			'show' =>
			[
				'label'               => &$GLOBALS['TL_LANG']['tl_image_size']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			]
		]
	],

	// Palettes
	'palettes' =>
	[
		'default'                     => '{title_legend},name,width,height,resizeMode,zoom;{expert_legend},sizes,densities'
	],

	// Fields
	'fields' =>
	[
		'id' =>
		[
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		],
		'pid' =>
		[
			'foreignKey'              => 'tl_theme.name',
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => ['type'=>'belongsTo', 'load'=>'lazy']
		],
		'tstamp' =>
		[
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		],
		'name' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_image_size']['name'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'search'                  => true,
			'flag'                    => 1,
			'eval'                    => ['mandatory'=>true, 'maxlength'=>64],
			'sql'                     => "varchar(64) NULL"
		],
		'sizes' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_image_size']['sizes'],
			'inputType'               => 'text',
			'explanation'             => 'imageSizeDensities',
			'exclude'                 => true,
			'eval'                    => ['helpwizard'=>true, 'maxlength'=>255, 'tl_class'=>'w50', 'decodeEntities'=>true],
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'densities' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_image_size']['densities'],
			'inputType'               => 'text',
			'explanation'             => 'imageSizeDensities',
			'exclude'                 => true,
			'eval'                    => ['helpwizard'=>true, 'maxlength'=>255, 'tl_class'=>'w50'],
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'width' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_image_size']['width'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'eval'                    => ['rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'clr w50'],
			'sql'                     => "int(10) NULL"
		],
		'height' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_image_size']['height'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'eval'                    => ['rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50'],
			'sql'                     => "int(10) NULL"
		],
		'resizeMode' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_image_size']['resizeMode'],
			'inputType'               => 'select',
			'options'                 => ['proportional', 'box', 'crop'],
			'reference'               => &$GLOBALS['TL_LANG']['MSC'],
			'exclude'                 => true,
			'eval'                    => ['helpwizard'=>true, 'tl_class'=>'clr w50'],
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'zoom' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_image_size']['zoom'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'eval'                    => ['rgxp'=>'prcnt', 'nospace'=>true, 'tl_class'=>'w50'],
			'sql'                     => "int(10) NULL"
		]
	]
];


/**
 * Class tl_image_size
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class tl_image_size extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}


	/**
	 * Check permissions to edit the table
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		if (!$this->User->hasAccess('image_sizes', 'themes'))
		{
			$this->log('Not enough permissions to access the image sizes module', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}
	}


	/**
	 * List an image size
	 * @param array
	 * @return string
	 */
	public function listImageSize($row)
	{
		$html = '<div class="tl_content_left">';
		$html .= $row['name'];

		if ($row['width'] || $row['height'])
		{
			$html .= ' <span style="color:#b3b3b3;padding-left:3px">' . $row['width'] . 'x' . $row['height'] . '</span>';
		}

		if ($row['zoom'])
		{
			$html .= ' <span style="color:#b3b3b3;padding-left:3px">(' . (int)$row['zoom'] . '%)</span>';
		}

		$html .= "</div>\n";

		return $html;
	}


	/**
	 * Show a hint if a JavaScript library needs to be included in the page layout
	 */
	public function showJsLibraryHint()
	{
		if ($_POST || Input::get('act') != 'edit')
		{
			return;
		}

		// Return if the user cannot access the layout module (see #6190)
		if (!$this->User->hasAccess('themes', 'modules') || !$this->User->hasAccess('layout', 'themes'))
		{
			return;
		}

		System::loadLanguageFile('tl_layout');
		Message::addInfo(sprintf($GLOBALS['TL_LANG']['tl_image_size']['picturefill'], $GLOBALS['TL_LANG']['tl_layout']['addPicturefill'][0]));
	}


	/**
	 * Return the edit header button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function editHeader($row, $href, $label, $title, $icon, $attributes)
	{
		return $this->User->canEditFieldsOf('tl_image_size') ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}
}