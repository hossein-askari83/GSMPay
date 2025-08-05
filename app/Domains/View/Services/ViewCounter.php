<?php

namespace App\Domains\View\Services;

use App\Domains\View\DTOs\ViewDTO;
use App\Domains\View\Interfaces\ViewCounterInterface;
use App\Domains\View\Models\View;
use App\Domains\View\Repositories\ViewRepository;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ViewCounter implements ViewCounterInterface
{
  public function __construct(private ViewRepository $viewRepository)
  {
  }
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