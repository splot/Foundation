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

use Splot\Foundation\Debug\TimerLap;

class Timer
{
	
	/**
	 * When the timer has started.
	 * 
	 * @var float
	 */
	protected $_startTime;

    /**
     * How much memory was used when the timer started.
     * 
     * @var int
     */
    protected $_startMemory;

    /**
     * What was the biggest memory peak when the timer was started.
     * 
     * @var int
     */
    protected $_startMemoryPeak;

	/**
	 * When the timer has stopped.
	 * 
	 * @var float
	 */
	protected $_stopTime;

    /**
     * How much memory was used when the timer stopped.
     * 
     * @var int
     */
    protected $_stopMemory;

    /**
     * What was the biggest memory peak when the timer has stopped.
     * 
     * @var int
     */
    protected $_stopMemoryPeak;

    /**
     * Holds a list of laps that have been run for this timer.
     * 
     * @var array
     */
    protected $_laps = array();

    /**
     * Currently running lap.
     * 
     * @var TimerLap
     */
    private $_currentLap;
	
	/**
	 * Constructor.
     * 
     * Starts the timer automatically by default.
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
     * 
     * @throws \BadMethodCallException When the timer was already started.
	 */
	public function start() {
        if ($this->_startTime) {
            throw new \BadMethodCallException('Timer already started.');
        }

		$this->_startTime = static::getMicroTime();
        $this->_startMemory = static::getCurrentMemory();
        $this->_startMemoryPeak = static::getCurrentMemoryPeak();

        $this->_currentLap = new TimerLap(true, $this->_startTime, $this->_startMemory, $this->_startMemoryPeak);
	}
	
	/**
	 * Stop the timer and return the result.
	 * 
	 * @param int $precision [optional] How many places to round to. Default: 8
	 * @return float Duration in seconds.
     * 
     * @throws \BadMethodCallException When the timer was already stopped.
	 */
	public function stop($precision = 8) {
        if ($this->_stopTime) {
            throw new \BadMethodCallException('Timer already stopped.');
        }

		$this->_stopTime = static::getMicroTime();
        $this->_stopMemory = static::getCurrentMemory();
        $this->_stopMemoryPeak = static::getCurrentMemoryPeak();

        $this->lap(); // trigger lap to stop this final lap

		return static::difference($this->_startTime, $this->_stopTime, $precision);
	}

    public function lap($name = null) {
        $this->_currentLap->stop();

        $this->_laps[] = array(
            'name' => $name ? $name : count($this->_laps) + 1,
            'lap' => $this->_currentLap
        );

        // start a new lap
        $this->_currentLap = new TimerLap(true, $this->_currentLap->getStopTime(), $this->_currentLap->getStopMemory(), $this->_currentLap->getStopMemoryPeak());
    }

    /*****************************************************
     * SETTERS AND GETTERS
     *****************************************************/
    /**
     * Returns the duration of the timer.
     * 
     * If not stopped yet then it will simply return time difference between its' start and current time.
     * 
     * @param int $precision [optional] How many places to round to. Default: 8.
     * @return float Duration in seconds.
     */
    public function getDuration($precision = 8) {
        $stopTime = $this->_stopTime ? $this->_stopTime : static::getMicroTime();
        return static::difference($this->_startTime, $stopTime, $precision);
    }

    /**
     * Returns the memory usage during the timer - the difference between the start memory and peak memory during the timer.
     * 
     * If not stopped yet then it will simply return memory difference between its' start and current memory usage.
     * 
     * @return int Memory usage in bytes.
     */
    public function getMemoryUsage() {
        $peakMemory = $this->_stopMemoryPeak ? $this->_stopMemoryPeak : static::getCurrentMemory();
        return static::memoryDifference($this->_startMemory, $stopMemory);
    }

    /**
     * Returns seconds with microtime when the timer started.
     * 
     * @return float
     */
    public function getStartTime() {
        return $this->_startTime;
    }

    /**
     * Returns how many bytes of memory PHP was using when the timer started.
     * 
     * @return int
     */
    public function getStartMemory() {
        return $this->_startMemory;
    }

    /**
     * Returns how many bytes of memory PHP was using at most (peak) when the timer started.
     * 
     * @return int
     */
    public function getStartMemoryPeak() {
        return $this->_startMemoryPeak;
    }

    /**
     * Returns seconds with microtime when the timer stopped.
     * 
     * @return float
     */
    public function getStopTime() {
        return $this->_startTime;
    }

    /**
     * Returns how many bytes of memory PHP was using when the timer stopped.
     * 
     * @return int
     */
    public function getStopMemory() {
        return $this->_startMemory;
    }

    /**
     * Returns the laps.
     * 
     * @return array
     */
    public function getLaps() {
        return $this->_laps;
    }

    /*****************************************************
     * STATIC FUNCTIONS
     *****************************************************/
    /**
     * Calculates the difference between two times.
     * 
     * @param float $startTime Start time.
     * @param float $endTime End time.
     * @param int $precision [optional] How many places to round to. Default: 8
     * @return float
     */
    public static function difference($startTime, $endTime, $precision = 8) {
        $precision = (is_int($precision)) ? $precision : 8;
        return round($endTime - $startTime, $precision);
    }

    /**
     * Calculates the difference between two memory usages.
     * 
     * @param int $startMemory Start memory usage.
     * @param int $endMemory End memory usage.
     * @return int
     */
    public static function memoryDifference($startMemory, $endMemory) {
        return $endMemory - $startMemory;
    }

    /**
     * Returns how many bytes of memory PHP was using at most (peak) when the timer stopped.
     * 
     * @return int
     */
    public function getStopMemoryPeak() {
        return $this->_startMemoryPeak;
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

    /**
     * Returns the current memory usage.
     * 
     * @return int
     */
    public static function getCurrentMemory() {
        return memory_get_usage(true);
    }

    /**
     * Returns the current peak memory usage.
     * 
     * @return int
     */
    public static function getCurrentMemoryPeak() {
        return memory_get_peak_usage(true);
    }
	
}