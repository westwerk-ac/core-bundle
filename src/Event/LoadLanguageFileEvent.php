<?php

/*
 * This file is part of Contao.
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao\CoreBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Allows to execute logic when a language file is loaded.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class LoadLanguageFileEvent extends Event
{
    use LanguageAwareTrait;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $cacheKey;

    /**
     * Constructor.
     *
     * @param string $name     The file name
     * @param string $language The language
     * @param string $cacheKey The cache key
     */
    public function __construct(&$name, &$language, &$cacheKey)
    {
        $this->name     = &$name;
        $this->language = &$language;
        $this->cacheKey = &$cacheKey;
    }

    /**
     * Returns the name.
     *
     * @return string The name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name.
     *
     * @param string $name The name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the cache key.
     *
     * @return string The cache key
     */
    public function getCacheKey()
    {
        return $this->cacheKey;
    }

    /**
     * Sets the cache key.
     *
     * @param string $cacheKey The cache key
     */
    public function setCacheKey($cacheKey)
    {
        $this->cacheKey = $cacheKey;
    }
}
