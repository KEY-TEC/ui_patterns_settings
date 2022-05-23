<?php

namespace Drupal\ui_patterns_settings\Plugin\UIPatterns\SettingType;

use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting;
use Drupal\ui_patterns_settings\Plugin\EnumerationSettingTypeBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Language selection Setting type.
 *
 * @UiPatternsSettingType(
 *   id = "langauge_selection",
 *   label = @Translation("Checkboxes")
 * )
 */
class LanguageSelection extends EnumerationSettingTypeBase {

  /**
   * The language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected LanguageManagerInterface $languageManager;

  protected function getOptions(PatternDefinitionSetting $def) {
    $languages = $this->languageManager->getLanguages();
    $options = [];
    foreach ($languages as $language) {
      $options[$language->getId()] = $language->getName();
    }
    return $def->getOptions();
  }

  /**
   * {@inheritdoc}
   */
  protected function getEnumerationType(PatternDefinitionSetting $def) {
    return $def->getValue('enumeration_type') ?? 'checkboxes';
  }

  /**
   * {@inheritdoc}
   */
  public function settingsPreprocess($value, array $context, PatternDefinitionSetting $def) {
    $selected_options = [];
    $defined_options = $def->getOptions();
    if (is_array($value)) {
      foreach ($value as $checkbox_key => $checkbox_value) {
        if ($checkbox_value != "0") {
          $selected_options[$checkbox_key] = isset($defined_options[$checkbox_value]) ? $defined_options[$checkbox_value] : $checkbox_value;
        }
      }
    }
    return $selected_options;
  }



  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ) {
    $plugin = parent::create($container, $configuration, $plugin_id, $plugin_definition);
    $plugin->languageManager = $container->get('language_manager');
    return $plugin;
  }
}
