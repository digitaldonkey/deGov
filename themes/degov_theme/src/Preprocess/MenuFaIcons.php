<?php
/**
 * @file MenuFaIcons.php.
 */

namespace Drupal\degov_theme\Preprocess;


use Drupal\Core\Render\Markup;
use Drupal\Core\Template\Attribute;

/**
 * Class MenuFaIcons
 *
 * @package Drupal\degov_theme\Preprocess
 */
class MenuFaIcons {

  /**
   * Preprocess the menu theme.
   *
   * Move FA classes to separate tag.
   *
   * @param array $items
   *  Links items.
   * @param $menu_name
   *  Menu name.
   */
  static public function preprocess(array &$items, $menu_name) {
    $supported_menus = [
      'main',
      'footer',
      'account',
    ];
    if (!in_array($menu_name, $supported_menus)) return;

    // Add support for fontawesome 5.
    foreach ($items as $key => &$item) {
      /** @var \Drupal\Core\Url $url */
      $url = $item['url'];
      $classes = &$url->getOption('attributes')['class'];

      if (!is_array($classes)) {
        continue;
      }

      $fa_classes = ['ml-2'];
      $index = NULL;
      foreach (['fa', 'fas', 'far', 'fab', 'fal'] as $fa_base_class) {
        $index = array_search($fa_base_class, $classes);
        if ($index !== FALSE) {
          $fa_classes[] = $classes[$index];
          unset($classes[$index]);
        }
      }
      $index = NULL;
      foreach ($classes as $index => $class) {
        if (stripos($class,'fa-') !== FALSE) {
          $fa_classes[] = $classes[$index];
          unset($classes[$index]);
          $attributes = $url->getOption('attributes');
          $url->setOption('attributes', ['class' => $classes] + $attributes);
          // TODO: add link attribute option to position the icon (left|right).
          $item['title'] = [
            '#type' => 'markup',
            '#markup' => $item['title'],
            'icon' => [
              '#type' => 'html_tag',
              '#tag' => 'i',
              '#value' => '&nbsp;',
              '#attributes' => ['class' => $fa_classes],
            ],
          ];
        }
      }

      if ($item['below']) {
        self::preprocess($item['below'], $menu_name);
      }
    }
  }
}
