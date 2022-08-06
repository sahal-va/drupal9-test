<?php

namespace Drupal\current_timestamp;

/**
 * Provides an interface for current time service.
 */
interface CurrentTimeInterface {

  /**
   * Returns the current timestamp in required timezone.
   *
   * @return string
   *   The current time string for the configured timezone.
   */
  public function getTime();

}
