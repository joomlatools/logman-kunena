<?php
/**
 * @package     LOGman
 * @copyright   Copyright (C) 2011 - 2016 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

/**
 * Topic/Kunena Activity Entity
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Joomlatools\Plugin\LOGman
 */
class PlgLogmanKunenaActivityTopic extends PlgLogmanKunenaActivityKunena
{
    protected function _initialize(KObjectConfig $config)
    {
        $config->append(array(
            'object_table' => 'kunena_topics',
            'context'      => 'site'
        ));

        parent::_initialize($config);
    }

    protected function _objectConfig(KObjectConfig $config)
    {
        $metadata = $this->getMetadata();

        $config->append(array(
            'pages' => array(
                'template'   => sprintf('option=com_kunena&view=topic&id=%s&catid=%s&Itemid=%%s', $this->row, $metadata->category),
                'conditions' => array('option' => 'com_kunena', 'view' => 'home')
            )
        ));

        parent::_objectConfig($config);
    }
}