<?php

namespace Drupal\ui_patterns_settings;

use Drupal\Component\Plugin\Factory\DefaultFactory;
use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\ui_patterns\Definition\PatternDefinition;

/**
 * Provides the UI Patterns Settings plugin manager.
 */
class UiPatternsSettingsManager extends DefaultPluginManager implements PluginManagerInterface {

  use StringTranslationTrait;

  private $exposedSettings = NULL;
  private $exposedVariants = NULL;

  /**
   * UiPatternsSettingsManager constructor.
   */
  public function __construct(\Traversable $namespaces, ModuleHandlerInterface $module_handler, CacheBackendInterface $cache_backend) {
    parent::__construct('Plugin/UiPatterns/SettingType', $namespaces, $module_handler, 'Drupal\ui_patterns_settings\SettingTypeInterface', 'Drupal\ui_patterns_settings\Annotation\UiPatternsSettingType');
    $this->moduleHandler = $module_handler;
    $this->alterInfo('ui_patterns_settings_info');
    $this->setCacheBackend($cache_backend, 'ui_patterns_settings', ['ui_patterns_settings']);
  }

  /**
   * Returns TRUE if a variant token can configured.
   *
   * @param \Drupal\ui_patterns\Definition\PatternDefinition $pattern_definition
   *   The pattern definition.
   *
   * @return bool
   *   Returns TRUE if a variant token can configured.
   */
  public static function allowVariantToken(PatternDefinition $pattern_definition) {
    $ary = $pattern_definition->getAdditional();
    if (isset($ary['allow_variant_token']) && $ary['allow_variant_token'] === TRUE) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  /**
   *
   */
  public function getExposeVariantField(PatternDefinition $def) {
    $options = $def->getVariantsAsOptions();
    return BundleFieldDefinition::create('list_string')
      ->setLabel($def->getLabel() . ' Variant')
      ->setCardinality(1)
      ->setRequired(TRUE)
      ->setDefaultValue(array_keys($options)[0])
      ->setSetting('allowed_values', $options)
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => -4,
      ])
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'list_default',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', FALSE)
      ->setDescription($def->getDescription());
  }

  /**
   * {@inheritdoc}
   */
  public function createInstance($plugin_id, array $configuration = []) {
    $plugin_definition = $this->getDefinition($plugin_id);
    $plugin_class = DefaultFactory::getPluginClass($plugin_id, $plugin_definition);
    // If the plugin provides a factory method, pass the container to it.
    if (is_subclass_of($plugin_class, 'Drupal\Core\Plugin\ContainerFactoryPluginInterface')) {
      $plugin = $plugin_class::create(\Drupal::getContainer(), $configuration, $plugin_id, $plugin_definition);
    }
    else {
      $plugin = new $plugin_class($configuration, $plugin_id, $plugin_definition);
    }
    return $plugin;
  }

  /**
   * @param \Drupal\ui_patterns\Definition\PatternDefinition[] $pattern_definitions
   */
  public function getExposedInfo(array $pattern_definitions) {
    if ($this->exposedSettings !== NULL) {
      return $this->exposedSettings;
    }
    $exposed_settings = [];
    foreach ($pattern_definitions as $pattern_definition) {
      $settings = UiPatternsSettings::getPatternDefinitionSettings($pattern_definition);
      foreach ($settings as $setting) {
        if ($setting->getExpose()) {
          $exposed_settings[$pattern_definition->id()]['pattern'] = $pattern_definition;
          $exposed_settings[$pattern_definition->id()]['settings'][$setting->getName()] = $setting;
        }
      }
    }
    foreach ($pattern_definitions as $pattern_definition) {
      $additional = $pattern_definition->getAdditional();
      if (isset($additional['expose_variants']) && $additional['expose_variants'] === TRUE) {
        $exposed_settings[$pattern_definition->id()]['variant'] = TRUE;
        $exposed_settings[$pattern_definition->id()]['pattern'] = $pattern_definition;
      }
    }
    $this->exposedSettings = $exposed_settings;
    return $exposed_settings;
  }

}
