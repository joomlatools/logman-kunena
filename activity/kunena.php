<?php
/**
 * @package     LOGman
 * @copyright   Copyright (C) 2011 - 2016 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

/**
 * Kunena Activity Entity
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Joomlatools\Plugin\LOGman
 */
class PlgLogmanKunenaActivityKunena extends ComLogmanModelEntityActivity
{
    /**
     * @var int A Kunena home menu item ID.
     */
    static protected $_item_id;

    protected function _initialize(KObjectConfig $config)
    {
        $config->append(array(
            'format'  => '{actor} {action} {object.subtype} {object.type} title {object}'
        ));

        parent::_initialize($config);
    }

    public function getPropertyImage()
    {
        switch ($this->verb)
        {
            case 'restore':
                $icon = 'k-icon-reload';
                break;
            case 'lock':
                $icon = 'k-icon-lock-locked';
                break;
            case 'unlock':
                $icon = 'k-icon-lock-unlocked';
                break;
            case 'stick':
                $icon = 'k-icon-star';
                break;
            case 'unstick':
                $icon = 'k-icon-underline';
                break;
            default:
                $icon = parent::getPropertyImage();
                break;
        }

        return $icon;
    }

    protected function _objectConfig(KObjectConfig $config)
    {
        $config->append(array('subtype' => array('object' => true, 'objectName' => 'Kunena')));

        parent::_objectConfig($config);
    }
}