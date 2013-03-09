<?php
/**
 * Timer lap to be used as a lap of a parent timer.
 * 
 * @package SplotFoundation
 * @subpackage Debug
 * @author Michał Dudek <michal@michaldudek.pl>
 * 
 * @copyright Copyright (c) 2013, Michał Dudek
 * @license MIT
 */
namespace Splot\Foundation\Debug;

use Splot\Foundation\Debug\Timer;

class TimerLap extends Timer
{

    /**
     * Constructor.
     * 
     * Starts the timer automatically by default.
     * 
     * @param bool $instantStart [optional] Should the timer be started immediately? Default: true.
     * @param float $startTime [optional] Start time (passed here so it's the same as the previous lap, to not lose any microsecond).
     * @param float $startMemory [optional] Start memory usage (passed here so it's the same as the previous lap, to not lose any byte).
     * @param float $startMemoryPeak [optional] Start memory peak (passed here so it's the same as the previous lap, to not lose any byte).
     */
    public function __construct($instantStart = true, $startTime = null, $startMemory = null, $startMemoryPeak = null) {
        if ($instantStart) {
            $this->start($startTime, $startMemory, $startMemoryPeak);
        }
    }
    
    /**
     * Start the timer lap.
     * 
     * @throws \BadMethodCallException When the timer lap was already started.
     */
    public function start($startTime = null, $startMemory = null, $startMemoryPeak = null) {
        if ($this->_startTime) {
            throw new \BadMethodCallException('Timer lap already started.');
        }

        $this->_startTime = isset($startTime) ? $startTime : static::getMicroTime();
        $this->_startMemory = isset($startMemory) ? $startMemory : static::getCurrentMemory();
        $this->_startMemoryPeak = isset($startMemoryPeak) ? $startMemoryPeak : static::getCurrentMemoryPeak();
    }
    
    /**
     * Stop the timer and return the result.
     * 
     * @param int $precision [optional] How many places to round to. Default: 8
     * @return float Duration in seconds.
     * 
     * @throws \BadMethodCallException When the timer lap was already stopped.
     */
    public function stop($precision = 8) {
        if ($this->_stopTime) {
            throw new \BadMethodCallException('Timer already stopped.');
        }

        $this->_stopTime = static::getMicroTime();
        $this->_stopMemory = static::getCurrentMemory();
        $this->_stopMemoryPeak = static::getCurrentMemoryPeak();

        return static::difference($this->_startTime, $this->_stopTime, $precision);
    }

    /**
     * Timer lap cannot track any laps.
     * 
     * @throws \BadMethodCallException Always thrown when this method is called.
     */
    public function lap($name = null) {
        throw new \BadMethodCallException('Timer lap cannot track any laps.');
    }

    /*****************************************************
     * SETTERS AND GETTERS
     *****************************************************/
    /**
     * Timer lap cannot track any laps.
     * 
     * @throws \BadMethodCallException Always thrown when this method is called.
     */
    public function getLaps() {
        throw new \BadMethodCallException('Timer lap cannot track any laps.');
    }
    
}