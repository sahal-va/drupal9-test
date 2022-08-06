<?php

namespace Drupal\current_timestamp\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\current_timestamp\CurrentTimeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for Current Timestamp routes.
 */
class CurrentTimestampController extends ControllerBase {

  /**
   * The current_timestamp.current_time service.
   *
   * @var \Drupal\current_timestamp\CurrentTimeInterface
   */
  protected $currentTime;

  /**
   * The controller constructor.
   *
   * @param \Drupal\current_timestamp\CurrentTimeInterface $current_time
   *   The current_timestamp.current_time service.
   */
  public function __construct(CurrentTimeInterface $current_time) {
    $this->currentTime = $current_time;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_timestamp.current_time')
    );
  }

  /**
   * Replaces the link with current time.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   The ajax response of the ajax upload.
   */
  public function getTime() {

    // Update the time.
    $response = new AjaxResponse();
    return $response->addCommand(new ReplaceCommand(
      '#anonymous_current_time',
      $this->currentTime->getTime(),
    ));
  }

}
