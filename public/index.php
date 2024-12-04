<?php
/**
* Copyright 2020
* 
* @package    Universal Sandbox
* @version		4.0.1
*	@access			private
* @see				https://github.com/TaoScorpi/universal-sandbox
* @author     Henrich Barkoczy | <abrakadabrask@protonmail.com>
* @license    https://www.taoscorpi.sk/universal/licencia
*/
declare(strict_types=1);

// @Autoload
require __DIR__ . '/../vendor/autoload.php';

// @Session
session_start();

// @Run
App\Bootstrap::boot()->run();