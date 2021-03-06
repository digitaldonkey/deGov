<?php

namespace Drupal\degov_tweets\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\degov_tweets\TwitterAPIExchange;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Provides a 'TwitterBlock' block.
 *
 * @Block(
 *  id = "degov_twitter_block",
 *  admin_label = @Translation("Twitter block"),
 * )
 */
class TwitterBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Definition of TwitterAPIExchange.
   *
   * @var \Drupal\degov_tweets\TwitterAPIExchange
   */
  protected $twitter;

  /**
   * TwitterFeedBlock constructor.
   *
   * @param array $configuration
   *   Block plugin config.
   * @param $plugin_id
   *   Block plugin plugin_id.
   * @param $plugin_definition
   *   Block plugin definition.
   * @param \Drupal\degov_tweets\TwitterAPIExchange $twitter
   *   The Twitter service.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    TwitterAPIExchange $twitter) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->twitter = $twitter;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('degov_tweets.twitter')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['markup'] = [
      '#type' => 'markup',
      '#markup' => t('<a href="@url">Create a twitter app on the twitter developer site</a>', ['@url' => 'https://dev.twitter.com/apps/']),
    ];
    $form['tweets_username'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Twitter username'),
      '#default_value' => isset($this->configuration['tweets_username']) ? $this->configuration['tweets_username'] : 'deGov',
    ];
    $form['tweets_limit'] = [
      '#type' => 'number',
      '#title' => $this->t('Limit'),
      '#default_value' => isset($this->configuration['tweets_limit']) ? $this->configuration['tweets_limit'] : 12,
    ];
    $form['tweets_update_every'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Update every'),
      '#description' => $this->t('Set the number in seconds. E.g. 3600 = 1 hour'),
      '#default_value' => isset($this->configuration['tweets_update_every']) ? $this->configuration['tweets_update_every'] : 7200,
    ];
    $form['access_token'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Access token'),
      '#default_value' => isset($this->configuration['access_token']) ? $this->configuration['access_token'] : '',
    ];
    $form['token_secret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Token secret'),
      '#default_value' => isset($this->configuration['token_secret']) ? $this->configuration['token_secret'] : '',
    ];
    $form['consumer_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Consumer key'),
      '#default_value' => isset($this->configuration['consumer_key']) ? $this->configuration['consumer_key'] : '',
    ];
    $form['consumer_secret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Consumer secret'),
      '#default_value' => isset($this->configuration['consumer_secret']) ? $this->configuration['consumer_secret'] : '',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $form_state->cleanValues();
    foreach ($form_state->getValues() as $key => $value) {
      $this->configuration[$key] = $value;
    }
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Exception
   */
  public function build() {
    $build = [];

    // Make a request to Twitter API.
    $settings = [
      'oauth_access_token' => $this->configuration['access_token'],
      'oauth_access_token_secret' => $this->configuration['token_secret'],
      'consumer_key' => $this->configuration['consumer_key'],
      'consumer_secret' => $this->configuration['consumer_secret'],
    ];
    $this->twitter->setSettings($settings);

    $params = [
      'screen_name' => $this->configuration['tweets_username'],
      'count' => $this->configuration['tweets_limit'],
    ];

    $dev_mode = \Drupal::config('degov_devel.settings')->get('dev_mode');
    if ($dev_mode) {
      $response = $this->twitter->performRequest();
    }
    else {
      $response = $this->twitter
        ->setGetfield($params)
        ->buildOauth('https://api.twitter.com/1.1/statuses/user_timeline.json')
        ->performRequest();
    }

    if ($response) {
      $tweets = json_decode($response);
      $build = [
        '#theme' => 'degov_tweets',
        '#tweets' => $tweets,
        '#cache' => [
          'max-age' => is_numeric($this->configuration['tweets_update_every']) ? $this->configuration['tweets_update_every'] : self::getMaxAge(),
        ],
      ];
    }

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    $cache_tags = parent::getCacheTags();
    $cache_tags[] = 'config:degov_devel.settings';
    return $cache_tags;
  }

  /**
   * Returns default max-age value for caching.
   */
  public static function getMaxAge() {
    $path = [
      drupal_get_path('module', 'degov_tweets'),
      'config',
      'block',
      'block.block.twitterblock.yml',
    ];
    $content = Yaml::parseFile(implode('/', $path));

    return $content['settings']['tweets_update_every'];
  }

}
