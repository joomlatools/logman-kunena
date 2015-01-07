<?php
/**
 * @package     LOGman
 * @copyright   Copyright (C) 2011 - 2015 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

/**
 * Category/Kunena Activity Entity
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Joomlatools\Plugin\LOGman
 */
class PlgLogmanKunenaActivityCategory extends PlgLogmanKunenaActivityKunena
{
    protected function _initialize(KObjectConfig $config)
    {
        $config->append(array('object_table' => 'kunena_categories'));
        parent::_initialize($config);
    }

    protected function _objectConfig(KObjectConfig $config)
    {
        $config->append(array('url' => 'option=com_kunena&view=categories&layout=edit&catid=' . $this->row));
        parent::_objectConfig($config);
    }
}