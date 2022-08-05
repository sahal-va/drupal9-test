<?php

namespace Drupal\current_timestamp\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\current_timestamp\CurrentTimeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a current time block.
 *
 * @Block(
 *   id = "current_timestamp_current_time",
 *   admin_label = @Translation("Current Time"),
 *   category = @Translation("Custom")
 * )
 */
class CurrentTimeBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The current_timestamp.current_time service.
   *
   * @var \Drupal\current_timestamp\CurrentTimeInterface
   */
  protected $currentTime;

  /**
   * The immutable config of current timestamp.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $config;

  /**
   * Constructs a new CurrentTimeBlock instance.
   *
   * @param array $configuration
   *   The plugin configuration, i.e. an array with configuration values keyed
   *   by configuration option name. The special key 'context' may be used to
   *   initialize the defined contexts by setting it to an array of context
   *   values keyed by context names.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\current_timestamp\CurrentTimeInterface $current_time
   *   The current_timestamp.current_time service.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CurrentTimeInterface $current_time, ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentTime = $current_time;
    $this->config = $config_factory->get('current_timestamp.settings');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_timestamp.current_time'),
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build['some_text'] = [
      '#type' => 'markup',
      '#markup' => $this->t('This block displays the current time in @tzone timezone.', [
        '@tzone' => $this->config->get('timezone'),
      ]),
    ];
    $build['time'] = [
      '#theme' => 'current_timestamp_block',
      '#header' => $this->t('----------------------------'),
      '#content' => [
        '#lazy_builder' => [
          'current_timestamp.current_time:generateTimestamp',
          [],
        ],
        '#create_placeholder' => TRUE,
      ],
      '#footer' => $this->t('----------------------------'),
    ];
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return $this->config->getCacheTags();
  }

}
