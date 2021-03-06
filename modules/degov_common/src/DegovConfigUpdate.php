<?php
declare(strict_types=1);

namespace Drupal\degov_common;

use Drupal\config\StorageReplaceDataWrapper;
use Drupal\Core\Config\Config;
use Drupal\Core\Config\FileStorage;
use Drupal\Core\Config\StorageInterface;

/**
 * Class DegovConfigUpdate
 *
 * @package Drupal\degov_common
 */
class DegovConfigUpdate extends DegovConfigManagerBase {

  /**
   * Updates the configuration of a given module and type.
   *
   * @param string $module
   *   The module name.
   * @param string $config_type
   *   The configuration type, this could be install, optional or block.
   * @param string $contrib_type
   *   Type of contrib type being processed, namely a module or theme.
   */
  public function configPartialImport(string $module, string $config_type = 'install', string $contrib_type = 'module') : void {
    $source_dir = drupal_get_path($contrib_type, $module) . '/config/' . $config_type;
    $this->importConfigFiles($source_dir);
  }

  /**
   * @param string $directory
   *   A directory path to use for reading and writing of configuration files.
   * @deprecated
   */
  public function checkConfigurationChanges(string $directory) : void {
    $this->importConfigFiles($directory);
  }

  /**
   * Imports a single config file.
   *
   * @param string $module
   *   Name of the module.
   * @param string $config_name
   *   The name of a configuration object to import.
   * @param string $config_type
   *   The configuration type, this could be install, optional or block.
   */
  public function importConfigFile(string $module, string $config_name, string $config_type = 'install') {
    $file_storage = new FileStorage(drupal_get_path('module', $module) . '/config/' . $config_type);
    $data = $file_storage->read($config_name);
    $this->addUUID($config_name, $data);
    $sourceStorage = new StorageReplaceDataWrapper($this->activeStorage);
    $sourceStorage->replaceData($config_name, $data);
    $this->configImport($sourceStorage);

  }

  /**
   * Imports all configuration files from the given directory.
   *
   * @param string $directory
   *   A directory path to use for reading configuration files.
   */
  public function importConfigFiles(string $directory) : void {
    $fileStorage = new FileStorage($directory);
    $sourceStorage = new StorageReplaceDataWrapper($this->activeStorage);
    foreach ($fileStorage->listAll() as $name) {
      $data = $fileStorage->read($name);
      $this->addUUID($name, $data);
      $sourceStorage->replaceData($name, $data);
    }
    $this->configImport($sourceStorage);
  }

  /**
   * Check optional directory for configuration changes.
   *
   * @param string $optional_install_path
   */
  public function checkOptional(string $optional_install_path) : void {
    if (is_dir($optional_install_path)) {
      // Install any optional config the module provides.
      $storage = new FileStorage($optional_install_path, StorageInterface::DEFAULT_COLLECTION);
      /**
       * @var \Drupal\Core\Config\ConfigInstaller $configInstaller
       */
      $configInstaller = \Drupal::service('config.installer');
      $configInstaller->installOptionalConfig($storage, '');
    }
  }

  public function getEditableConfig(string $configStorageName): Config {
    $config = $this->configManager->getConfigFactory()->getEditable($configStorageName);
    $this->handleConfigExistence($config);

    return $config;
  }

  public function importConfigFolder(string $moduleName, string $folderName): void {
    $configFolderPath = drupal_get_path('module', $moduleName) . "/config/$folderName";
    $filesInFolder = scandir($configFolderPath, TRUE);
    foreach ($filesInFolder as $filename) {
      if (substr($filename, -4) === '.yml') {
        $parsedConfiguration = Yaml::parseFile(drupal_get_path('module', $moduleName) . "/config/$folderName/$filename");
        $configurationName = substr_replace($filename ,'', -4);
        $this->configFactory->getEditable($configurationName)
          ->setData($parsedConfiguration)
          ->save();
      }
    }
  }

  private function handleConfigExistence(Config $config): void {
    if ($config->isNew()) {
      throw new \RuntimeException('Config named ' . $config->getName() . ' is expected to be in storage. Looks like initial config is malformed.');
    }
  }

}
