<?php
/**
 * Useful Logger class to log various messages and categorize them with categories and tags.
 * 
 * @package SplotFoundation
 * @subpackage Debug
 * @author Michał Dudek <michal@michaldudek.pl>
 * 
 * @copyright Copyright (c) 2013, Michał Dudek
 * @license MIT
 */
namespace Splot\Foundation\Debug;

use Splot\Foundation\Utils\ArrayUtils;
use Splot\Foundation\Debug\Debugger;

/**
 * @static
 */
class Logger
{
	
	/**
	 * List of all log messages.
	 * 
	 * @var array
	 */
	private static $_log = array();

	/**
	 * Is the logger enabled or not?
	 * 
	 * @var bool
	 */
	private static $_enabled = true;
	
	/**
	 * Log the specified item. It will be added to the log stack.
	 * 
	 * @param string $message Message to log.
	 * @param string $category [optional] Category to put the item in.
	 * @param array $additional [optional] Additional data in a form of array.
	 * @param string $tags [optional] Tags for this log item as string, separated by comma.
	 * @return null|bool Returns (bool) false if log is disabled.
	 */
	public static function log($message, $category = 'General', array $additional = array(), $tags = null) {
		if (!self::isEnabled()) {
			return false;
		}

		$category = (empty($category)) ? 'General' : $category;
		if (!empty($tags) AND (!is_array($tags))) {
			$tags = explode(',', $tags);
			$tags = array_map('trim', $tags);
		} else {
			$tags = array();
		}
		
		self::$_log[] = array(
			'time' => Debugger::getTime('global', 8),
			'message' => strip_tags($message),
			'category' => $category,
			'additional' => $additional,
			'tags' => $tags
		);
	}
	
	/**
	 * Return all category names from the log.
	 * 
	 * @return array List of all category names.
	 */
	public static function getCategories() {
		return ArrayUtils::resetKeys(array_unique(ArrayUtils::keyFilter(self::$_log, 'category')));
	}
	
	/**
	 * Return all log items from the given category.
	 * 
	 * @param string $category Category name.
	 * @return array
	 */
	public static function getCategoryLog($category) {
		if (empty($category)) {
			return array();
		}
		
		$items = ArrayUtils::filterByKeyValue(self::$_log, 'category', $category);
		$items = ArrayUtils::keyFilter($items, 'item');
		return array_reverse($items, true);
	}
	
	/**
	 * Return the full log.
	 * 
	 * @param bool $sortByCategory [optional] Should the log items be sorted by categories? Default: false.
	 * @return array
	 */
	public static function getLog($sortByCategory = false) {
		$log = array_reverse(self::$_log, true);
		if ($sortByCategory) {
			$log = array_reverse(ArrayUtils::categorizeByKey($log, 'category', true), true);
			foreach($log as $category => &$items) {
				$log[$category] = $items;
			}
		}
		return $log;
	}

	/**
	 * Sets the logger to be enabled or disabled.
	 * 
	 * @param bool $enabled
	 */
	public static function setEnabled($enabled) {
		self::$_enabled = $enabled;
	}

	/**
	 * Checks if the logger is enabled.
	 * 
	 * @return bool
	 */
	public static function getEnabled() {
		return self::$_enabled;
	}

	/**
	 * Checks if the logger is enabled.
	 * 
	 * @return bool
	 */
	public static function isEnabled() {
		return self::getEnabled();
	}
	
}