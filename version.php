<?php
/**
 * @package     LOGman
 * @copyright   Copyright (C) 2011 - 2014 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

/**
 * Version
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Joomlatools\Plugin\LOGman
 */
class PlgLogmanKunenaVersion extends KObject
{
    const VERSION = '2.1.0';

    /**
     * The LOGman API version used by the plugin. i.e. the LOGman version that got used for developing this plugin.
     */
    const API_VERSION = '2.1.0';

    /**
     * Get the version
     *
     * @return string
     */
    public function getVersion()
    {
        return self::VERSION;
    }

    /**
     * Get the LOGman API version.
     *
     * @return string
     */
    public function getApiVersion()
    {
        return self::API_VERSION;
    }
}
