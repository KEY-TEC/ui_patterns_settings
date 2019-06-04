<?php

namespace Drupal\ui_patterns_settings;

use Drupal\Core\Entity\Entity;
use Drupal\Core\Entity\EntityBase;
use Drupal\Core\Entity\EntityInterface;
use Drupal\ui_patterns\Definition\PatternDefinition;
use Drupal\ui_patterns\UiPatterns;
use Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting;

/**
 * UI Patterns setting factory class.
 *
 * @package Drupal\ui_patterns_settings
 */
class UiPatternsSettings {

  /**
   * Get pattern manager setting instance.
   *
   * @return \Drupal\ui_patterns_settings\UiPatternsSettingsManager
   *   UI Patterns setting manager instance.
   */
  public static function getManager() {
    return \Drupal::service('plugin.manager.ui_patterns_settings');
  }


  /**
   * Preprocess setiting variables. Called before rendered.
   *
   * @param string $pattern_id
   *   Pattern ID for which to preprocess.
   * @param array $settings
   *   The stored settings.
   * @param string $variant
   *   The variant.
   * @param bool $preview
   *   Is preview.

   * @param \Drupal\Core\Entity\Entity $entity
   *   The entity of the pattern. Useful for dynamic settings.
   *
   * @return array
   *   The processed settings.
   */
  public static function preprocess($pattern_id, array $settings, $variant, $preview, Entity $entity = NULL) {
    $processed_settings = [];
    $definition = UiPatterns::getPatternDefinition($pattern_id);
    $context = [];
    $context['entity'] = $entity;
    $settings_definition = UiPatternsSettings::getPatternDefinitionSettings($definition);
    foreach ($settings_definition as $key => $setting_definition) {
      if ($setting_definition->getForcedValue()) {
        $value = $setting_definition->getForcedValue();
      }
      elseif (isset($settings[$key])) {
        $value = $settings[$key];
      }
      elseif ($preview && !empty($setting_definition->getPreview())) {
        $value = $setting_definition->getPreview();
      }
      else {
        $value = $setting_definition->getDefaultValue();
      }
      if ($variant != 'default' && $variant != NULL) {
        $variant_ob = $definition->getVariant($variant);
        if ($variant_ob != NULL) {
          $variant_ary = $variant_ob->toArray();
          if (isset($variant_ary['settings']) && isset($variant_ary['settings'][$key])) {
            $value = $variant_ary['settings'][$key];
          }
        }
      }
      $settingType = UiPatternsSettings::createSettingType($setting_definition);
      $processed_settings[$key] = $settingType->preprocess($value, $context);
    }
    return $processed_settings;

  }

  /**
   * Get setting definitions for a pattern definition.
   *
   * @param \Drupal\ui_patterns\Definition\PatternDefinition $defintion
   *   The definition.
   *
   * @return \Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting[]
   *   Setting pattern definitons.
   */
  public static function getPatternDefinitionSettings(PatternDefinition $definition) {
    $additional = $definition->getAdditional();
    $settings_ary = isset($additional['settings']) ? $additional['settings'] : [];
    $settings = [];
    if (!empty($settings_ary)) {
      foreach ($settings_ary as $key => $setting_ary) {
        $settings[$key] = new PatternDefinitionSetting($key, $setting_ary);

      }
    }
    return $settings;
  }

  /**
   * Create setting type plugin.
   *
   * @param \Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting $settingDefintion
   *   The setting defintion.
   *
   * @return \Drupal\ui_patterns_settings\Plugin\PatternSettingTypeInterface
   *   UI Patterns setting manager instance.
   */
  public static function createSettingType(PatternDefinitionSetting $settingDefintion) {
    $configuration = [];
    $configuration['pattern_setting_definition'] = $settingDefintion;
    return \Drupal::service('plugin.manager.ui_patterns_settings')
      ->createInstance($settingDefintion->getType(), $configuration);
  }

}
