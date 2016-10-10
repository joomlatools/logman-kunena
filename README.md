LOGman Kunena plugin
========================

Plugin for integrating [Kunena](http://www.kunena.org/) with LOGman. [LOGman](https://www.joomlatools.com/extensions/logman/) is a user analytics and audit trail solution for Joomla.

## Installation

### Composer

You can install this package using [Composer](https://getcomposer.org/). Create a `composer.json` file inside the root directory of your Joomla! site containing the following code:

```
{
    "require": {        
        "joomlatools/plg_logman_kunena": "dev-master"
    },
    "minimum-stability": "dev"
}
```

Run composer install.

### Package

For downloading an installable package just make use of the **Download ZIP** button located in the right sidebar of this page.

After downloading the package, you may install this plugin using the Joomla! extension manager.

## Usage

After the package is installed, make sure to enable the plugin and that both LOGman and Kunena are installed.

## Supported activities

The following Kunena actions are currently logged:

### Topics

* Add
* Edit
* Delete
* Lock/Unlock
* Stick/Unstick
* Trash/Restore

### Categories

* Add
* Edit
* Delete

### Messages

* Add
* Edit
* Delete
* Trash/Restore

## Limitations

Due to a problem in the event dispatching system of the latest Kunena version (v5.0.2), activities for announcements are not currently supported.
