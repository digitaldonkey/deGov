<?php

namespace Drupal\degov_theme\Preprocess;

use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Path\PathMatcherInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Node.
 *
 * @package Drupal\degov_theme\Preprocess
 */
class Node implements ContainerInjectionInterface {

  /**
   * Definition of DateFormatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  private $dateFormatter;

  /**
   * Definition of PathMatcher.
   *
   * @var \Drupal\Core\Path\PathMatcherInterface
   */
  private $pathMatcher;

  /**
   * Node constructor.
   *
   * @param \Drupal\Core\Datetime\DateFormatterInterface $dateFormatter
   *   Date formatter object.
   * @param \Drupal\Core\Path\PathMatcherInterface $pathMatcher
   *   Path Matcher object.
   */
  public function __construct(DateFormatterInterface $dateFormatter, PathMatcherInterface $pathMatcher) {
    $this->dateFormatter = $dateFormatter;
    $this->pathMatcher = $pathMatcher;
  }

  /**
   * {@inheritdoc}
   */
  static public function create(ContainerInterface $container) {
    return new static(
      $container->get('date.formatter'),
      $container->get('path.matcher')
    );
  }

  /**
   * Preprocess node theme.
   *
   * @param array $variables
   */
  public function preprocess(array &$variables): void {
    /** @var \Drupal\node\Entity\Node $node */
    $node = $variables['node'];
    // Add created time to the search teaser template.
    if ($variables['view_mode'] == 'teaser') {
      $variables['bundle'] = $variables['node']->type->entity->label();
      $variables['date'] = $this->dateFormatter
        ->format($node->getCreatedTime(), 'custom', 'd.m.Y');
    }

    // The configuration for "event" content type doesn't use the responsive
    // image we need in our teaser. Set it here.
    if ($node->bundle() === 'event') {
      $responsive_image_style_id = $this->determineResponsiveImageStyle($variables['view_mode']);
      if ($medias = $node->get('field_teaser_image')->referencedEntities()) {
        /** @var \Drupal\media\Entity\Media $media */
        $media = reset($medias);
        $variables['content']['field_teaser_image'] = [
          '#type' => 'responsive_image',
          '#responsive_image_style_id' => $responsive_image_style_id,
          '#uri' => $media->image->entity->getFileUri(),
        ];
      }
    }

    $variables['is_front'] = $this->pathMatcher->isFrontPage();
  }

  /**
   * Helper function to determine which image style to use.
   *
   * Determine responsive image style for particular view mode.
   *
   * @param string $view_mode_name
   *   The view mode to test.
   *
   * @return string
   */
  protected function determineResponsiveImageStyle(string $view_mode_name): string {
    $responsive_image_style_id = '';
    if (in_array($view_mode_name, [
      'long_text',
      'preview',
      'small_image',
    ])) {
      $responsive_image_style_id = 'teaser_squared';
    }
    elseif ($view_mode_name === 'slim') {
      $responsive_image_style_id = 'teaser_landscape';
    }
    return $responsive_image_style_id;
  }

}
