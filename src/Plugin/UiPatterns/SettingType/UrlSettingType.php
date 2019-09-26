<?php

namespace Drupal\ui_patterns_settings\Plugin\UIPatterns\SettingType;

use Drupal\Core\Url;
use Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting;

/**
 * Url setting type.
 *
 * @UiPatternsSettingType(
 *   id = "url",
 *   label = @Translation("Url")
 * )
 */
class UrlSettingType extends TokenSettingType {

  /**
   * {@inheritdoc}
   */
  public function settingsPreprocess($value, array $context, PatternDefinitionSetting $def) {
    $value = parent::settingsPreprocess($value, $context, $def);
    if (empty($value)) {
      return "";
    }
    try {
      $url = Url::fromUri($value)->toString();
    }
    catch (\Exception $e) {
      // Not a valid uri. Try user input:
      try {
        $url = Url::fromUserInput($value)->toString();
      }
      catch (\Exception $e) {
        // Not a valid url.
        return '#';
      }
    }
    return $url;
  }

}
