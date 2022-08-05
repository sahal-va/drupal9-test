<?php

namespace Drupal\current_timestamp;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Service for current timestamp module.
 */
class CurrentTime implements CurrentTimeInterface {

  /**
   * The immutable config of current timestamp.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $config;

  /**
   * Constructs a CurrentTime object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->config = $config_factory->get('current_timestamp.settings');
  }

  /**
   * {@inheritdoc}
   */
  public function getTime() {
    $timezone = $this->config->get('timezone');
    $current_time = new DrupalDateTime('now', $timezone);
    return $current_time->format('jS M Y - h:i A');
  }

}
