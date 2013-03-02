<?php
/**
 * A set of object and array of objects utility functions.
 * 
 * @package SplotFoundation
 * @subpackage Utils
 * @author Michał Dudek <michal@michaldudek.pl>
 * 
 * @copyright Copyright (c) 2013, Michał Dudek
 * @license MIT
 */
namespace Splot\Foundation\Utils;

/**
 * @static
 */
class ObjectUtils
{
	
	/**
	 * Returns an array with a list of all values (not unique) assigned to a key in a collection of objects.
	 * 
	 * @param array $array Array with collection of objects.
	 * @param string $key Key to filter by.
	 * @return array Array with the list.
	 */
	public static function keyFilter($objects, $key) {
		$objects = (is_array($objects)) ? $objects : array($objects);
		$return = array();
		
		foreach($objects as &$object) {
			if (isset($object->$key)) {
				$return[] = $object->$key;
			}
		}
		
		return $return;		
	}
	
	/**
	 * Assigns a value under a key of a collection of objects to its main (top level) key.
	 * 
	 * @param array $array Array of objects to be exploded.
	 * @param string $key Key on which to explode.
	 * @return array Exploded array.
	 */
	public static function keyExplode(&$array, $key) {
		if (!is_array($array)) return array();
		$return = array();
		
		foreach($array as $k => &$row) {
			$return[$row->$key] = $row;
		}
		
		return $return;
	}
	
	/**
	 * Filters out all values from a multidimensional array that contains objects that match the given value of they given associative level 2 key.
	 * 
	 * @param array $array Array to filter from.
	 * @param string $key Key to filter by.
	 * @param mixed $value Value to filter by.
	 * @param bool $preserveKey[optional] Should the level 1 key be preserved or not? Default false.
	 * @return array Filtered array.
	 */
	public static function filterByKeyValue(&$array, $key, $value, $preserveKey = false) {
		if (!is_array($array)) return array();
		$return = array();
		
		foreach($array as $k => &$row) {
			if ((isset($row->$key)) AND ($row->$key == $value)) {
				if ($preserveKey) {
					$return[$k] = $row;
				} else {
					$return[] = $row;
				}
			}
		}
		
		return $return;
	}
	
	/**
	 * Categorize all items from the collection of objects by the value of a specific key.
	 * 
	 * @param array $array Array to parse.
	 * @param string $key Key to categorize by.
	 * @param bool $preserveKey[optional] Preserve keys? Default false.
	 * @return array Categorized array with collections of objects.
	 */
	public static function categorizeByKey(&$array, $key, $preserveKey = false) {
		if (!is_array($array)) return array();
		$return = array();
		
		foreach($array as $k => &$object) {
			$keyValue = $object->$key;
			if (!isset($keyValue)) continue;
			if ((!is_string($keyValue)) AND (!is_numeric($keyValue))) continue;

			if (!isset($return[$keyValue])) $return[$keyValue] = array();
			
			if ($preserveKey) {
				$return[$keyValue][$k] = $object;
			} else {
				$return[$keyValue][] = $object;
			}
		}
		
		return $return;
	}
	
	/**
	 * Sorts a collection of object based on the specified key.
	 * 
	 * @param array $objects Collection of objects to sort.
	 * @param string $key Key to sort by.
	 * @param bool $reverse[optional] True for descending order, false (default) for ascending.
	 * @return array Sorted collection of objects.
	 */
	public static function multiSort($objects, $key, $reverse = false) {
		if ((!is_array($objects)) OR (empty($objects))) return array();
		
		$direction = $reverse ? 'SORT_DESC' : 'SORT_ASC';
		
		$sorted = array();
		foreach($objects as $i => &$object) {
			$sorted[] = (isset($object->$key)) ? $object->$key : null;
		}
		
		array_multisort($sorted, constant($direction), $objects);
		return $objects;
	}
	
	/**
	 * Resets keys of an array containing a collection of objects to numerical values starting with 0.
	 * 
	 * @param array $array Array with collection of objects to reset.
	 * @return array Resetted array.
	 */
	public static function resetKeys(&$array) {
		if (!is_array($array)) return array();
		$return = array();
		
		foreach($array as &$row) {
			$return[] = $row;
		}
		
		return $return;
	}
	
}
