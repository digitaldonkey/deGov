<?php

namespace Drupal\Tests\degov_html_mail\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\Core\Extension\ModuleHandler;

class ModuleInstallationTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'degov_html_mail',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->installConfig(['degov_html_mail']);
  }

  public function testSetup(): void {
    /**
     * @var ModuleHandler $moduleHandler
     */
    $moduleHandler = \Drupal::service('module_handler');
    self::assertTrue($moduleHandler->moduleExists('degov_html_mail'));
    self::assertTrue($moduleHandler->getModule('degov_html_mail'));
  }

}