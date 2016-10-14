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
            'object_table' => 'kunena_messages',
            'context'      => 'site'
        ));

        parent::_initialize($config);
    }

    protected function _objectConfig(KObjectConfig $config)
    {
        $metadata = $this->getMetadata();

        $config->append(array(
            'pages' => array(
                'template'   => sprintf('option=com_kunena&view=topic&id=%s&catid=%s&Itemid=%%s', $metadata->topic->id, $metadata->category),
                'conditions' => array('option' => 'com_kunena', 'view' => 'home')
            ),
            'type'  => array('object' => true, 'find' => 'object')
        ));

        parent::_objectConfig($config);

        if (($row = $this->row) && $config->url->site)
        {
            $url = $this->getObject('lib:http.url', array('url' => sprintf('%s#%s', $this->_getRoute($config->url->site), $row)));

            $config->url->site = $url;
            $config->type->append(array('url' => array('site' => $url)));
        }
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
        $metadata = $this->getMetadata();

        $config = array(
            'find'       => 'target',
            'objectName' => $metadata->topic->title,
            'type'       => array('object' => true, 'objectName' => 'topic'),
            'subtype'    => array('object' => true, 'objectName' => 'Kunena')
        );

        $pages = $this->_findPages(array(
            'conditions' => array('option' => 'com_kunena', 'view' => 'home'),
            'levels'     => $this->getViewLevels(),
            'components' => $this->package
        ));

        if ($pages)
        {
            $page = $pages[0];
            $config['url'] = array('site' => sprintf('option=com_kunena&view=topic&id=%s&catid=%s&Itemid=%s', $metadata->topic->id, $metadata->category, $page->id));
        }

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