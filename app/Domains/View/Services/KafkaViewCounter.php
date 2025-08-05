<?php

namespace App\Domains\View\Services;

use App\Domains\View\DTOs\ViewDTO;
use App\Domains\View\Enums\ViewableEntitiesEnum;
use App\Domains\View\Interfaces\ViewCounterInterface;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;

class KafkaViewCounter implements ViewCounterInterface
{
  public function record(ViewDTO $viewDTO): void
  {
    $payload = $viewDTO->toArray();

    $message = new Message(
      body: $payload,
      headers: ['origin' => 'laravel-app'],
      key: 'view-' . $viewDTO->viewableId
    );

    try {
      Kafka::publish()
        ->onTopic('viewed')
        ->withMessage($message)
        ->send();
    } catch (\Exception $e) {
      \Log::error('Failed to publish Kafka message: ' . $e->getMessage());
      throw $e;
    }
  }
}