<?php

namespace App\Domains\View\Services;

use App\Domains\View\DTOs\ViewDTO;
use App\Domains\View\Enums\ViewableEntitiesEnum;
use App\Domains\View\Interfaces\ViewCounterInterface;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;

/**
 * Service for recording views by publishing to Kafka.
 *
 * Implements ViewCounterInterface to publish view events to the 'viewed' Kafka topic.
 * Uses asynchronous messaging for view recording, allowing for better scalability
 * and decoupling of view processing from the main application flow.
 *
 * @package App\Domains\View\Services
 */
class KafkaViewCounter implements ViewCounterInterface
{
  /**
   * Record a view by publishing it to the Kafka 'viewed' topic.
   *
   * Converts the ViewDTO to an array payload and publishes it as a Kafka message
   * with appropriate headers and key for message routing and identification.
   *
   * @param ViewDTO $viewDTO The view data to publish
   * @return void
   * @throws \Exception When Kafka publishing fails
   */
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
    }
  }
}