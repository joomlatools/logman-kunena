<?php
/**
 * @package     LOGman
 * @copyright   Copyright (C) 2011 - 2015 Timble CVBA. (http://www.timble.net)
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
        $config->append(array('format' => '{actor} {action} {object.subtype} {object.type} title {object}'));
        parent::_initialize($config);
    }

    public function getPropertyImage()
    {
        switch ($this->verb)
        {
            case 'restore':
                $icon = 'icon-repeat';
                break;
            case 'lock':
                $icon = 'icon-lock';
                break;
            case 'unlock':
                $icon = 'icon-ok-sign';
                break;
            case 'stick':
                $icon = 'icon-star';
                break;
            case 'unstick':
                $icon = 'icon-star-empty';
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

    /**
     * Menu Item ID getter.
     *
     * @return int The menu item ID, 0 if not found.
     */
    protected function _getMenuItem()
    {
        if (!isset(self::$_item_id))
        {
            $query = $this->getObject('lib:database.query.select')
                          ->table('menu')
                          ->columns(array('id', 'link'))
                          ->where('link LIKE :option')
                          ->where('link LIKE :view')
                          ->where('client_id = :client')
                          ->where('published = :published')
                          ->bind(array(
                              'option'    => '%option=com_kunena%',
                              'view'      => '%view=home%',
                              'client'    => 0,
                              'published' => 1
                          ));

            $id = $this->getObject('lib:database.adapter.mysqli')->select($query, KDatabase::FETCH_FIELD);

            if (!$id) {
                $id = 0;
            }

            self::$_item_id = $id;
        }

        return self::$_item_id;
    }
}