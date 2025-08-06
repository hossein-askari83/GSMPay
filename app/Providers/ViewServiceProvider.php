<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use App\Domains\View\Interfaces\ViewCounterInterface;
use App\Domains\View\Services\{
  ViewCounter,
  KafkaViewCounter
};

/**
 * Service provider for view-related services and event handling.
 *
 * Registers the appropriate ViewCounterInterface implementation based on configuration
 * and sets up event listeners for view tracking functionality.
 *
 * @package App\Providers
 */
class ViewServiceProvider extends ServiceProvider
{
  /**
   * Register view counter service bindings.
   *
   * Binds ViewCounterInterface to the appropriate implementation based on
   * the configuration (database or kafka).(could use a config file)
   */
  public function register(): void
  {
    $driver = 'kafka';

    $map = [
      'database' => ViewCounter::class,
      'kafka' => KafkaViewCounter::class,
    ];

    $impl = $map[$driver] ?? ViewCounter::class;

    $this->app->bind(ViewCounterInterface::class, $impl);
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    Event::listen(
      \App\Domains\View\Events\ViewableViewed::class,
      [\App\Domains\View\Listeners\ViewCountIncrement::class, 'handle']
    );
  }
}
