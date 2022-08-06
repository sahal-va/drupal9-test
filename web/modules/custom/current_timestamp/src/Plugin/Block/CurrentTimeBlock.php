<?php

namespace Drupal\current_timestamp\Plugin\Block;

use Drupal\Core\Url;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
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
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

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
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   Current user.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CurrentTimeInterface $current_time, ConfigFactoryInterface $config_factory, AccountInterface $current_user) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentTime = $current_time;
    $this->config = $config_factory->get('current_timestamp.settings');
    $this->currentUser = $current_user;
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
      $container->get('config.factory'),
      $container->get('current_user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build['last_built'] = [
      '#type' => 'markup',
      '#markup' => $this->t('This block is being cached from @time.', [
        '@time' => $this->currentTime->getTime(),
      ]),
    ];
    $build['some_text'] = [
      '#type' => 'markup',
      '#markup' => $this->t('<br/>This block displays the current time in @tzone timezone.', [
        '@tzone' => $this->config->get('timezone'),
      ]),
    ];
    $build['data'] = [
      '#theme' => 'current_timestamp_block',
      '#header' => '------------------------------------------------------------------------',
      '#content' => [
        'country' => $this->config->get('country'),
        'city' => $this->config->get('city'),
        'time' => [
          '#lazy_builder' => [
            'current_timestamp.current_time:generateTimestamp',
            [],
          ],
          '#create_placeholder' => TRUE,
        ],
      ],
      '#footer' => '------------------------------------------------------------------------',
    ];

    // Since anonymous users don't have a session, bigpipe won't work.
    // Attaching a link to trigger JS, so we can replace with time.
    // Sample issue: https://www.drupal.org/project/big_pipe/issues/2603046
    // @todo Remove this code once bigpipe starts supporting for Anonymous.
    if ($this->currentUser->isAnonymous()) {
      $build['data']['#content']['time'] = [
        '#type' => 'link',
        '#title' => '',
        '#url' => Url::fromRoute('current_timestamp.get_time'),
        '#attributes' => [
          'id' => 'anonymous_current_time',
          'class' => 'use-ajax',
        ],
        '#attached' => [
          'library' => 'current_timestamp/update_time',
        ],
      ];
    }

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return $this->config->getCacheTags();
  }

}
