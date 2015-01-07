<?php
/**
 * @package     LOGman
 * @copyright   Copyright (C) 2011 - 2015 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

/**
 * Kunena LOGman Plugin.
 *
 * Provides event handlers for dealing with Kunena events.
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Joomlatools\Plugin\LOGman
 */
class PlgLogmanKunena extends ComLogmanPluginJoomla
{
    protected $_ignore_save = array();

    protected function _initialize(KObjectConfig $config)
    {
        $config->append(array(
            'resources' => array(
                'topic',
                'message',
                'category'
            )
        ));

        parent::_initialize($config);
    }

    public function update(&$args)
    {
        // Ignore messages move tasks.
        if ($this->getObject('request')->getData()->task == 'move') {
            return null;
        }

        return parent::update($args);
    }

    protected function _getCategoryObjectData($data, $event)
    {
        return array(
            'id'       => $data->id,
            'name'     => $data->name,
            'metadata' => array('description' => $data->description, 'parent' => $data->parent_id)
        );
    }

    protected function _getAnnouncementObjectData($data, $event)
    {
        return array(
            'id'       => $data->id,
            'name'     => $data->title,
            'metadata' => array('description' => $data->description)
        );
    }

    protected function _getMessageObjectData($data, $event)
    {
        $query = $this->getObject('lib:database.query.select')
                      ->table('kunena_topics')
                      ->columns('subject')
                      ->where('id = :id')
                      ->bind(array('id' => $data->thread));

        $topic = $this->getObject('lib:database.adapter.mysqli')->select($query, KDatabase::FETCH_FIELD);

        return array(
            'id'       => $data->id,
            'name'     => $data->subject,
            'metadata' => array(
                'parent'   => $data->parent,
                'category' => $data->catid,
                'topic'    => array('id' => $data->thread, 'title' => $topic)
            )
        );
    }

    protected function _getTopicObjectData($data, $event)
    {
        return array(
            'id'       => $data->id,
            'name'     => $data->subject,
            'metadata' => array('category' => $data->category_id)
        );
    }

    public function onKunenaAfterSave($context, $data, $isNew)
    {
        $context = $this->_getCleanContext($context);

        $parts = explode('.', $context);

        if (isset($parts[1]))
        {
            $resource = $parts[1];

            if (!in_array($resource, $this->_ignore_save))
            {
                // Message and topic add/edit actions may trigger edit actions on their corresponding topic and category.
                if (in_array($resource, array('message', 'topic'))) {
                    $this->_ignore_save = array_merge($this->_ignore_save, array('topic', 'category'));
                }

                $task = $this->getObject('request')->getQuery()->task;

                switch ($task)
                {
                    case 'delete':
                        $verb   = 'trash';
                        $result = 'trashed';
                        break;
                    case 'undelete':
                        $verb   = 'restore';
                        $result = 'restored';
                        break;
                    case 'lock':
                        $verb   = 'lock';
                        $result = 'locked';
                        break;
                    case 'unlock':
                        $verb   = 'unlock';
                        $result = 'unlocked';
                        break;
                    case 'sticky':
                        $verb   = 'stick';
                        $result = 'sticked';
                        break;
                    case 'unsticky':
                        $verb   = 'unstick';
                        $result = 'unsticked';
                        break;
                    default:
                        $verb   = $isNew ? 'add' : 'edit';
                        $result = null;
                        break;
                }

                $this->log(array(
                    'data'    => $data,
                    'verb'    => $verb,
                    'result'  => $result,
                    'context' => $context,
                    'event'   => 'onKunenaAfterSave'
                ));
            }
            else unset($this->_ignore_save[array_search($resource, $this->_ignore_save)]); // Ignore logging and remove the resource from the log list.
        }
    }

    public function onKunenaAfterDelete($context, $data)
    {
        $context = $this->_getCleanContext($context);

        $this->log(array(
            'data'    => $data,
            'verb'    => 'delete',
            'context' => $context,
            'event'   => 'onKunenaAfterDelete'
        ));
    }

    protected function _getCleanContext($context)
    {
        $parts = explode('.', $context);

        if (isset($parts[1]))
        {
            $resource = $parts[1];

            if (strpos($resource, 'KunenaForum') === 0)
            {
                $resource = strtolower(substr($resource, 11));
                $context = sprintf('%s.%s', $parts[0], $resource);
            }
        }

        return $context;
    }
}