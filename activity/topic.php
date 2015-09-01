<?php
/**
 * @package     LOGman
 * @copyright   Copyright (C) 2011 - 2015 Timble CVBA. (http://www.timble.net)
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
            'object_table' => 'kunena_topics'
        ));

        parent::_initialize($config);
    }

    protected function _objectConfig(KObjectConfig $config)
    {
        $url = null;

        if ($item_id = $this->_getMenuItem())
        {
            $metadata = $this->getMetadata();
            $url      = sprintf('%sindex.php?option=com_kunena&view=topic&id=%s&catid=%s&Itemid=%s', JURI::root(), $this->row, $metadata->category, $item_id);
            $url      = $this->getObject('lib:http.url', array('url' => $url));
        }

        $config->append(array('url' => $url));

        parent::_objectConfig($config);
    }
}