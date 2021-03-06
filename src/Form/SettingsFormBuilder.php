<?php

namespace Drupal\ui_patterns_settings\Form;

use Drupal\ui_patterns\Definition\PatternDefinition;
use Drupal\ui_patterns\UiPatterns;
use Drupal\ui_patterns_settings\UiPatternsSettings;

/**
 * Build settings in manage display form.
 */
class SettingsFormBuilder {

  /**
   * Build pattern settings fieldset.
   *
   * @param array $form
   *   Form array.
   * @param \Drupal\ui_patterns\Definition\PatternDefinition $definition
   *   The pattern definition.
   * @param array $configuration
   *   The pattern configuration.
   */
  public static function layoutForm(array &$form, PatternDefinition $definition, array $configuration) {
    $settings = UiPatternsSettings::getPatternDefinitionSettings($definition);
    $form['variant']['#attributes']['class'][] = 'ui-patterns-variant-selector-' . $definition->id();
    if (!empty($settings)) {
      foreach ($settings as $key => $setting) {
        if (empty($setting->getType()) || !$setting->isFormVisible()) {
          continue;
        }

        if (!isset($form['settings'])) {
          $form['settings'] = [
            '#type' => 'fieldset',
            '#title' => t('Settings'),
          ];
        }
        $setting_value = isset($configuration['pattern']['settings'][$key]) ? $configuration['pattern']['settings'][$key] : NULL;
        $settingType = UiPatternsSettings::createSettingType($setting);
        $form['settings'] += $settingType->buildConfigurationForm([], $setting_value);
      }
      SettingsFormBuilder::buildVariantsForm(".ui-patterns-variant-selector-" . $definition->id(), $form['settings'], $definition);
    }
  }

  /**
   * Build pattern settings for display form.
   *
   * @param array $form
   *   Form array.
   * @param array $configuration
   *   Configurations array.
   */
  public static function displayForm(array &$form, array $configuration) {
    foreach (UiPatterns::getPatternDefinitions() as $pattern_id => $definition) {
      $settings = UiPatternsSettings::getPatternDefinitionSettings($definition);
      $form['variants'][$pattern_id]['#attributes']['class'][] = 'ui-patterns-variant-selector-' . $pattern_id;
      if (!empty($settings)) {
        foreach ($settings as $key => $setting) {
          if (empty($setting->getType()) || !$setting->isFormVisible()) {
            continue;
          }
          if (!isset($form['pattern_settings'][$pattern_id])) {
            $form['pattern_settings'][$pattern_id] = [
              '#type' => 'fieldset',
              '#title' => t('Settings'),
              '#states' => [
                'visible' => [
                  'select[id="patterns-select"]' => ['value' => $pattern_id],
                ],
              ],
            ];
          }
          $fieldset = &$form['pattern_settings'][$pattern_id];
          $settingType = UiPatternsSettings::createSettingType($setting);
          $setting_value = isset($configuration['pattern_settings'][$pattern_id][$key]) ? $configuration['pattern_settings'][$pattern_id][$key] : "";
          $fieldset += $settingType->buildConfigurationForm([], $setting_value);
        }
        SettingsFormBuilder::buildVariantsForm('.ui-patterns-variant-selector-' . $pattern_id, $fieldset, $definition);
      }

    }
  }

  /**
   * Hide all settings which are configured by the variant.
   *
   * @param string $select_selector
   *   The id of the variant select field.
   * @param array $fieldset
   *   The fieldset.
   * @param \Drupal\ui_patterns\Definition\PatternDefinition $definition
   *   The pattern definition.
   */
  private static function buildVariantsForm($select_selector, array &$fieldset, PatternDefinition $definition) {
    $variants = $definition->getVariants();
    foreach ($variants as $variant) {
      $variant_ary = $variant->toArray();
      $settings = isset($variant_ary['settings']) ? $variant_ary['settings'] : [];
      foreach ($settings as $name => $setting) {
        if (isset($fieldset[$name])) {
          // Add an or before a new state begins.
          if (isset($fieldset[$name]['#states']['invisible']) && count($fieldset[$name]['#states']['invisible']) != 0) {
            $fieldset[$name]['#states']['invisible'][] = 'or';
          }
          // Hide configured setting.
          $fieldset[$name]['#states']['invisible'][][$select_selector]['value'] = $variant->getName();
        }
      }
    }
  }

}
