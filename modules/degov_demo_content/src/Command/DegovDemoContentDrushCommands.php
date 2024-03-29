<?php

namespace Drupal\degov_demo_content\Command;

use Drupal\degov_demo_content\Generator\MediaGenerator;
use Drupal\degov_demo_content\Generator\MenuItemGenerator;
use Drupal\degov_demo_content\Generator\NodeGenerator;
use Drupal\degov_demo_content\Generator\BlockContentGenerator;
use Drush\Commands\DrushCommands;

/**
 * Defines drush commands to manage the demo content.
 */
class DegovDemoContentDrushCommands extends DrushCommands {

  /**
   * The deGov Demo Content MediaGenerator.
   *
   * @var \Drupal\degov_demo_content\Generator\MediaGenerator
   */
  private $mediaGenerator;

  /**
   * The deGov Demo Content NodeGenerator.
   *
   * @var \Drupal\degov_demo_content\Generator\NodeGenerator
   */
  private $nodeGenerator;

  /**
   * The deGov Demo Content MenuItemGenerator.
   *
   * @var \Drupal\degov_demo_content\Generator\MenuItemGenerator
   */
  private $menuItemGenerator;

  /**
   * The deGov Demo Content BlockContentGenerator.
   *
   * @var \Drupal\degov_demo_content\Generator\BlockContentGenerator
   */
  private $blockContentGenerator;

  /**
   * DegovDemoContentDrushCommands constructor.
   *
   * @param \Drupal\degov_demo_content\Generator\MediaGenerator $mediaGenerator
   *   The deGov Demo Content MediaGenerator.
   * @param \Drupal\degov_demo_content\Generator\NodeGenerator $nodeGenerator
   *   The deGov Demo Content NodeGenerator.
   * @param \Drupal\degov_demo_content\Generator\MenuItemGenerator $menuItemGenerator
   *   The deGov Demo Content MenuItemGenerator.
   * @param \Drupal\degov_demo_content\Generator\BlockContentGenerator $blockContentGenerator
   *   The deGov Demo Content BlockContentGenerator.
   */
  public function __construct(MediaGenerator $mediaGenerator, NodeGenerator $nodeGenerator, MenuItemGenerator $menuItemGenerator, BlockContentGenerator $blockContentGenerator) {
    parent::__construct();
    $this->mediaGenerator = $mediaGenerator;
    $this->nodeGenerator = $nodeGenerator;
    $this->menuItemGenerator = $menuItemGenerator;
    $this->blockContentGenerator = $blockContentGenerator;
  }

  /**
   * Deletes and regenerates the demo content.
   *
   * @command degov_demo_content:reset
   * @aliases dcreg
   */
  public function resetContent() {
    $this->mediaGenerator->resetContent();
    $this->nodeGenerator->resetContent();
    $this->menuItemGenerator->resetContent();
    $this->blockContentGenerator->resetContent();

    $this->logger()->success(dt('Media items & node items & menu items reset & block items.'));
  }

  /**
   * Deletes the demo content.
   *
   * @command degov_demo_content:delete
   * @aliases dcdel
   */
  public function deleteContent() {
    $this->menuItemGenerator->deleteContent();
    $this->nodeGenerator->deleteContent();
    $this->mediaGenerator->deleteContent();
    $this->blockContentGenerator->deleteContent();
  }

  /**
   * Generates the demo content.
   *
   * @command degov_demo_content:generate
   * @aliases dcgen
   */
  public function createContent() {
    $this->mediaGenerator->generateContent();
    $this->nodeGenerator->generateContent();
    $this->menuItemGenerator->generateContent();
    $this->blockContentGenerator->generateContent();
  }

}
