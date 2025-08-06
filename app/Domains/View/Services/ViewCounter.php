<?php

namespace App\Domains\View\Services;

use App\Domains\View\DTOs\ViewDTO;
use App\Domains\View\Interfaces\ViewCounterInterface;
use App\Domains\View\Models\View;
use App\Domains\View\Repositories\ViewRepository;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Service for recording views directly to the database.
 *
 * Implements ViewCounterInterface to save view records using database transactions.
 * Handles duplicate view exceptions gracefully and logs all view recording activities.
 *
 * @package App\Domains\View\Services
 */
class ViewCounter implements ViewCounterInterface
{
  public function __construct(private ViewRepository $viewRepository)
  {
  }
  
  /**
   * Record a view by saving it directly to the database.
   *
   * Uses a database transaction to ensure data consistency and handles
   * duplicate view exceptions by logging them without throwing errors.
   *
   * @param ViewDTO $viewDTO The view data to record
   * @return void
   */
  public function record(ViewDTO $viewDTO): void
  {
    try {
      DB::transaction(function () use ($viewDTO) {
        $this->viewRepository->save($viewDTO);
      });
      Log::info(sprintf(
        'Successfully recorded view for %s with ID %d%s',
        $viewDTO->viewableType,
        $viewDTO->viewableId,
        isset($viewDTO->userId) ? " by user ID {$viewDTO->userId}" : ''
      ));
    } catch (QueryException $e) {
      Log::error('Duplicate view skipped: ' . $e->getMessage());
    } catch (\Exception $e) {
      Log::error('Error processing viewed event: ' . $e->getMessage(), [
        'exception' => $e,
      ]);
    }
  }
}