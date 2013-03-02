<?php
/**
 * Timer to time various events.
 * 
 * @package SplotFoundation
 * @subpackage Debug
 * @author Michał Dudek <michal@michaldudek.pl>
 * 
 * @copyright Copyright (c) 2013, Michał Dudek
 * @license MIT
 */
namespace Splot\Foundation\Debug;

class Timer
{
	
	/**
	 * When the timer has started.
	 * 
	 * @var float
	 */
	private $_startTime = 0;

	/**
	 * When the timer has stopped.
	 * 
	 * @var float
	 */
	private $_stopTime = 0;
	
	/**
	 * Create an instance of Timer with the timer started by default.
	 * 
	 * @param bool $instantStart [optional] Should the timer be started immediately? Default: true.
	 */
	public function __construct($instantStart = true) {
		if ($instantStart) {
			$this->start();
		}
	}
	
	/**
	 * Start the timer.
	 */
	public function start() {
		$this->_startTime = self::getMicroTime();
	}
	
	/**
	 * Stop the timer and return the result.
	 * 
	 * @param int $roundPlaces [optional] How many places to round to. Default: 3
	 * @return float Timer result.
	 */
	public function stop($roundPlaces = 3) {
		$this->_stopTime = self::getMicroTime();
		return $this->_calculate($this->_stopTime, $roundPlaces);
	}
	
	/**
	 * Return the current time on the timer without stopping it.
	 * 
	 * @param bool $stop [optional] Should the timer be stopped as well? Default: false;
	 * @param int $roundPlaces [optional] How many places to round to. Default: 3
	 * @return float Timer result.
	 */
	public function getTime($stop = false, $roundPlaces = 3) {
		if ($stop) {
			$this->stop();
			$microTime = $this->_stopTime;
		} else {
			$microTime = self::getMicroTime();
		}
		
		return $this->_calculate($microTime, $roundPlaces);
	}
	
	/**
	 * Calculates the difference between the given time and the start time.
	 * 
	 * @param object $givenTime
	 * @param int $roundPlaces [optional] How many places to round to. Default: 3
	 * @return float
	 */
	private function _calculate($givenTime, $roundPlaces = 3) {
		$roundPlaces = (is_int($roundPlaces)) ? $roundPlaces : 8;
		return round($givenTime - $this->_startTime, $roundPlaces);
	}
	
	/**
	 * Creates an easier to use value of PHP's microtime()
	 * 
	 * @return float Time with seconds and microseconds.
	 */
	public static function getMicroTime() {
		list($microSec, $sec) = explode(' ', microtime());
		return $sec + $microSec;
	}
	
}