<?php
/**
 * LoggerCategory class for private instantiation and use inside of classes for easier logging of their messages in their own category.
 * 
 * Use:
 *  $this->_logger = new \Splot\Foundation\Debug\LoggerCategory('My Class');
 *  $this->_logger->log('My message');
 * 
 * @package SplotFoundation
 * @subpackage Debug
 * @author Michał Dudek <michal@michaldudek.pl>
 * 
 * @copyright Copyright (c) 2013, Michał Dudek
 * @license MIT
 */
namespace Splot\Foundation\Debug;

use Splot\Foundation\Debug\Logger;

class LoggerCategory
{

    /**
     * Is the logger enabled or not?
     * 
     * @var bool
     */
    private $_enabled = true;

    /**
     * Name of this logger category.
     * 
     * @var string
     */
    private $_name;

    /**
     * Constructor.
     * 
     * @param string $name Name for this logger category.
     */
    public function __construct($name) {
        $this->_name = $name;
    }

    /**
     * Magic function introduced in PHP 5.3.x called when the class is used as a function, e.g. $this->_logger('Log item');
     * 
     * @param string $message Message to log.
     * @param array $additional [optional] Additional data in a form of array.
     * @param string $tags [optional] Tags for this log item as string, separated by comma.
     * @return null|bool Returns (bool) false if log is disabled.
     */
    public function __invoke($message, array $additional = array(), $tags = null) {
        return $this->log($message, $additional, $tags);
    }

    /**
     * Logs the given message.
     * 
     * @param string $message Message to log.
     * @param array $additional [optional] Additional data in a form of array.
     * @param string $tags [optional] Tags for this log item as string, separated by comma.
     * @return null|bool Returns (bool) false if log is disabled.
     */
    public function log($message, array $additional = array(), $tags = null) {
        return Logger::log($message, $this->getName(), $additional, $tags);
    }

    /**
     * Returns the log.
     * 
     * @return array
     */
    public function getLog() {
        return Logger::getCategoryLog($this->getName());
    }

    /**
     * Returns name of this logger.
     * 
     * @return string
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * Sets the logger to be enabled or disabled.
     * 
     * @param bool $enabled
     */
    public function setEnabled($enabled) {
        $this->_enabled = $enabled;
    }

    /**
     * Checks if the logger is enabled.
     * 
     * @return bool
     */
    public function getEnabled() {
        $this->_enabled;
    }

    /**
     * Checks if the logger is enabled.
     * 
     * @return bool
     */
    public function isEnabled() {
        return $this->getEnabled();
    }

}