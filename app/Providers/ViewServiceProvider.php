<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use App\Domains\View\Interfaces\ViewCounterInterface;
use App\Domains\View\Services\{
  ViewCounter,
  KafkaViewCounter
};

class ViewServiceProvider extends ServiceProvider
{
  public function register(): void
  {
    $driver = config('view_counter.driver', 'kafka');

    $map = [
      'database' => ViewCounter::class,
      'kafka' => KafkaViewCounter::class,
    ];

    $impl = $map[$driver] ?? ViewCounter::class;

    $this->app->bind(ViewCounterInterface::class, $impl);
  }

  public function boot(): void
  {
    Event::listen(
      \App\Domains\View\Events\ViewableViewed::class,
      [\App\Domains\View\Listeners\ViewCountIncrement::class, 'handle']
    );
  }
}
