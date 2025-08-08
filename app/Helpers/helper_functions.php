<?php

use Illuminate\Contracts\Foundation\Application;

if (!function_exists('bind_strategy')) {
  /**
   * Bind a strategy implementation to an interface based on config mapping.
   *
   * @param  Application  $app
   * @param  string  $interface
   * @param  string  $configKeyDriver
   * @param  string  $configKeyMap
   * @param  string  $defaultClass
   * @return void
   */
  function bind_strategy(
    Application $app,
    string $interface,
    string $configKeyDriver,
    string $configKeyMap,
    string $defaultClass
  ): void {
    $driver = config($configKeyDriver);
    $map = config($configKeyMap, []);

    $impl = $map[$driver] ?? $defaultClass;

    $app->bind($interface, $impl);
  }
}
