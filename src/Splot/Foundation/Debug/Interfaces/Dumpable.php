<?php
/**
 * Interface that allows the Debugger to easily dump clean objects.
 * 
 * @package SplotFoundation
 * @subpackage Debug
 * @author Michał Dudek <michal@michaldudek.pl>
 * 
 * @copyright Copyright (c) 2013, Michał Dudek
 * @license MIT
 */
namespace Splot\Foundation\Debug\Interfaces;

interface Dumpable
{

	/**
	 * Returns the object in a form of printable array.
	 * 
	 * @return array
	 */
	public function toDumpableArray();

}