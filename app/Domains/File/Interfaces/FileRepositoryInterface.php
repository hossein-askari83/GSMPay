<?php

namespace App\Domains\File\Interfaces;

use App\Domains\File\DTOs\FileDTO;

/**
 * Interface for file repository operations.
 * 
 * This interface defines the contract for file data access operations,
 * providing methods to find, save, and delete file records.
 * 
 * @package App\Domains\File\Interfaces
 */
interface FileRepositoryInterface
{
  /**
   * Find a file by its ID.
   * 
   * @param int $id The file ID to search for
   * @return FileDTO|null Returns the file DTO if found, null otherwise
   */
  public function findOne(int $id): ?FileDTO;
  
  /**
   * Save a file record.
   * 
   * @param FileDTO $dto The file data transfer object to save
   * @return FileDTO Returns the saved file DTO (may include generated ID)
   */
  public function save(FileDTO $dto): FileDTO;
  
  /**
   * Delete a file by its ID.
   * 
   * @param int $fileId The ID of the file to delete
   * @return void
   */
  public function delete(int $fileId): void;
}