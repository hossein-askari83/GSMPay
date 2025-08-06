<?php

namespace App\Console\Commands;

use App\Domains\View\DTOs\ViewDTO;
use App\Domains\View\Models\View;
use App\Domains\View\Repositories\ViewRepository;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Junges\Kafka\Facades\Kafka;
use Illuminate\Console\Command;
use Junges\Kafka\Message\ConsumedMessage;

/**
 * Kafka consumer command for processing "viewed" events.
 * 
 * This command subscribes to the "viewed" Kafka topic and processes incoming
 * view events. It converts the event data to a ViewDTO and stores it in the
 * database through the ViewRepository. The command handles duplicate views
 * gracefully by catching QueryExceptions and logs all processing activities.
 * 
 * Usage:
 * php artisan kafka:consume-viewed
 * 
 * @package App\Console\Commands
 */
class ConsumeViewed extends Command
{

  public function __construct(private ViewRepository $viewRepository)
  {
    parent::__construct();
  }
  protected $signature = 'kafka:consume-viewed';
  protected $description = 'Consume "viewed" events and store in DB';

  public function handle(): void
  {
    Kafka::consumer()
      ->withHandler($this)
      ->subscribe(['viewed'])
      ->build()
      ->consume();
  }

  public function __invoke(ConsumedMessage $message): void
  {
    try {
      $data = $message->getBody();

      $this->viewRepository->save(ViewDTO::fromArray($data));

      Log::info(sprintf(
        'Successfully recorded view for %s with ID %d%s',
        $data['viewable_type'],
        $data['viewable_id'],
        isset($data['user_id']) ? " by user ID {$data['user_id']}" : ''
      ));
    } catch (QueryException $e) {
      Log::error('Duplicate view skipped: ' . $e->getMessage());
    } catch (\Exception $e) {
      Log::error('Error processing viewed event: ' . $e->getMessage(), [
        'message' => $message->getBody(),
        'exception' => $e,
      ]);
      $this->error('Error processing event: ' . $e->getMessage());
    }
  }
}