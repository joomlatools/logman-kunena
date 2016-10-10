<?php
/**
 * @package     LOGman
 * @copyright   Copyright (C) 2011 - 2016 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

/**
 * Message/Kunena Activity Entity
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Joomlatools\Plugin\LOGman
 */
class PlgLogmanKunenaActivityMessage extends PlgLogmanKunenaActivityKunena
{

    protected function _initialize(KObjectConfig $config)
    {
        $config->append(array(
            'format'       => '{actor} {action} {object.type} {target} {target.subtype} {target.type}',
            'object_table' => 'kunena_messages'
        ));

        parent::_initialize($config);
    }

    protected function _objectConfig(KObjectConfig $config)
    {
        $url = null;

        if ($item_id = $this->_getMenuItem())
        {
            $metadata = $this->getMetadata();
            $url      = $this->_getSiteRoute(sprintf('option=com_kunena&view=topic&id=%s&catid=%s&Itemid=%s', $metadata->topic->id, $metadata->category, $item_id));

            if ($row = $this->row) {
                $url = $this->getObject('lib:http.url', array('url' => sprintf('%s#%s', $url->toString(), $row)));
            }
        }

        $config->append(array(
            'url'  => $url,
            'type' => array('url' => $url, 'find' => 'object'),
        ));

        parent::_objectConfig($config);
    }

    protected function _actionConfig(KObjectConfig $config)
    {
        switch ($this->verb)
        {
            case 'add':
                $name = 'added';
                break;
            case 'edit':
                $name = 'edited';
                break;
            default:
                $name = $this->status;
        }

        $config->append(array('objectName' => $name));

        parent::_actionConfig($config);
    }

    public function getPropertyTarget()
    {
        $url = null;

        if ($item_id = $this->_getMenuItem())
        {
            $metadata = $this->getMetadata();
            $url      = $this->_getSiteRoute(sprintf('option=com_kunena&view=topic&id=%s&catid=%s&Itemid=%s', $metadata->topic->id, $metadata->category, $item_id));
        }

        $config = array(
            'objectName' => $metadata->topic->title,
            'url'     => $url,
            'type'    => array('object' => true, 'objectName' => 'topic'),
            'subtype' => array('object' => true, 'objectName' => 'Kunena')
        );

        return $this->_getObject($config);
    }

    /**
     * Find an activity target.
     *
     * @return bool True if found, false otherwise.
     */
    protected function _findActivityTarget()
    {
        $query = $this->getObject('lib:database.query.select')
                      ->columns('COUNT(*)')
                      ->table('kunena_topics')
                      ->where('id = :id')
                      ->bind(array('id' => $this->getMetadata()->topic->id));

        // Need to catch exceptions here as table may not longer exist.
        try {
            $result = $this->getTable()->getAdapter()->select($query, KDatabase::FETCH_FIELD);
        } catch (Exception $e) {
            $result = 0;
        }

        return $result;
    }

    /**
     * Get the activity target signature.
     *
     * @return string The signature.
     */
    protected function _getTargetSignature()
    {
        return sprintf('kunena.topic.%s', $this->getMetadata()->topic->id);
    }


}