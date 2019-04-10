<?php

namespace Drupal\ui_patterns_settings\Element;

use Drupal\Core\Template\Attribute;
use Drupal\ui_patterns\UiPatterns;
use Drupal\ui_patterns_settings\UiPatternsSettings;

/**
 * Renders a pattern element.
 *
 */
class PatternSettings  {

  /**
   * Process settings for preview.
   *
   * @param array $element
   *   Render array.
   *
   * @return array
   *   Render array.
   */
  public static function processPreviewSettings(array $element) {
    return PatternSettings::processSettings($element, TRUE);
  }

  /**
   * Process settings.
   *
   * @param array $element
   *   Render array.
   *
   * @return array
   *   Render array.
   */
  public static function processSettings(array $element, $preview = FALSE) {
    // Make sure we don't render anything in case fields are empty.
    if (self::hasSettings($element)) {
      $settings = isset($element['#settings']) ? $element['#settings'] : [];
      if (empty($settings)) {
        $settings = isset($element['#ds_configuration']['layout']['settings']['pattern']['settings']) ? $element['#ds_configuration']['layout']['settings']['pattern']['settings'] : [];
      }
      $context = $element['#context'];
      $pattern_id = $element['#id'];
      $entity = $context->getProperty('entity');
      $variant = isset($element['#variant']) ? $element['#variant'] : NULL;
      $settings = UiPatternsSettings::preprocess($pattern_id, $settings, $variant, $preview, $entity);
      unset($element['#settings']);
      foreach ($settings as $name => $setting) {
        $key = '#' . $name;
        if (!isset($element[$key])) {
          $element[$key] = $setting;
        }
        else {
          if ($setting instanceof Attribute && $element[$key] instanceof Attribute) {
            $element[$key] = new Attribute(array_merge($setting->toArray(), $element[$key]->toArray()));
          }
          elseif (is_array($element[$key]) && is_array($setting)) {
            $element[$key] = array_merge($element[$key], $setting);
          }
        }
      }
    }
    return $element;
  }


  /**
   * Whereas pattern has settings or not.
   *
   * @return bool
   *   TRUE or FALSE.
   */
  public static function hasSettings($element) {
    $definition = UiPatterns::getPatternDefinition($element['#id']);
    $settings = UiPatternsSettings::getPatternDefinitionSettings($definition);
    if ($definition != NULL && count($settings) != 0) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }
}
