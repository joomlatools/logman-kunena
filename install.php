<?php
/**
 * @package     LOGman
 * @copyright   Copyright (C) 2011 - 2014 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

/**
 * LOGman - Kunena installer
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Joomlatools\Component\LOGman
 */
class plgLogmanKunenaInstallerScript
{
    /**
     * @var string The current installed LOGman version.
     */
    protected $_logman_ver = null;

    public function preflight($type, $installer)
    {
        $return = true;
        $errors = array();

        if (!class_exists('Koowa') || !class_exists('ComExtmanControllerExtension'))
        {
            if (file_exists(JPATH_ADMINISTRATOR.'/components/com_extman/extman.php') && !JPluginHelper::isEnabled('system', 'koowa')) {
                $errors[] = sprintf(JText::_('This component requires System - Nooku Framework plugin to be installed and enabled. Please go to <a href=%s>Plugin Manager</a>, enable <strong>System - Nooku Framework</strong> and try again'), JRoute::_('index.php?option=com_plugins&view=plugins&filter_folder=system'));
            }
            else $errors[] = JText::_('This component requires EXTman to be installed on your site. Please download this component from <a href=http://joomlatools.com target=_blank>joomlatools.com</a> and install it');

            $return = false;
        }

        // Check LOGman version.
        if ($return === true)
        {
            if (version_compare($this->getLogmanVersion(), '2.1.0', '<'))
            {
                $errors[] = JText::_('This component requires a newer LOGman version. Please download the latest version from <a href=http://joomlatools.com target=_blank>joomlatools.com</a> and upgrade.');
                $return   = false;
            }
        }

        if ($return == false && $errors)
        {
            $error = implode('<br />', $errors);
            $installer->getParent()->abort($error);
        }

        return $return;
    }

    /**
     * Returns the current version (if any) of LOGman.
     *
     * @return string|null The LOGman version if present, null otherwise.
     */
    public function getLogmanVersion()
    {
        if (!$this->_logman_ver) {
            $this->_logman_ver = $this->_getExtensionVersion('com_logman');
        }

        return $this->_logman_ver;
    }

    /**
     * Extension version getter.
     *
     * @param string $element The element name, e.g. com_extman, com_logman, etc.
     * @return mixed|null|string The extension version, null if couldn't be determined.
     */
    protected function _getExtensionVersion($element)
    {
        $version = null;

        $query    = "SELECT manifest_cache FROM #__extensions WHERE element = '{$element}'";
        if ($result = JFactory::getDBO()->setQuery($query)->loadResult()) {
            $manifest = new JRegistry($result);
            $version  = $manifest->get('version', null);
        }

        return $version;
    }
}
