<?php

namespace App\Domains\File\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphMany;


/**
 * Interface for entities that can own files.
 * 
 * This interface defines the contract for models that can have associated files
 * through a polymorphic relationship. It provides methods to manage file ownership,
 * retrieve files, and configure storage settings.
 * 
 * @package App\Domains\File\Interfaces
 */
interface FileOwnerInterface
{
  /**
   * Get the unique identifier for the file owner.
   * 
   * This method returns the primary key or unique identifier that will be used
   * to establish the relationship between files and this owner entity.
   * 
   * @return int|string The unique identifier for the file owner
   */
  public function getFileKey(): int|string;

  /**
   * Get the polymorphic relationship to files.
   * 
   * This method defines the morphMany relationship that allows this entity
   * to have multiple associated files.
   * 
   * @return MorphMany The morphMany relationship to files
   */
  public function files(): MorphMany;

  /**
   * Get files grouped by their file types.
   * 
   * This method returns an array of files organized by their file type categories,
   * making it easier to access files of specific types.
   * 
   * @return array An array of files grouped by file types
   */
  public function getGroupedFiles(): array;

  /**
   * Get the storage disk name for file storage.
   * 
   * This method returns the name of the storage disk that should be used
   * for storing files associated with this owner.
   * 
   * @return string The storage disk name
   */
  public function getStorageDisk(): string;
}