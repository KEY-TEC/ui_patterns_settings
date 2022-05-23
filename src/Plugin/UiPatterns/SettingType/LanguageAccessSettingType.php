<?php

namespace Drupal\ui_patterns_settings\Plugin\UIPatterns\SettingType;

use Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting;

/**
 * Hides render element for unchecked languages.
 *
 * Setting type to hide the render element if:
 * Elements are checked and the current language is
 * not part of the selection.
 *
 * @UiPatternsSettingType(
 *   id = "language_access",
 *   label = @Translation("Language Access")
 * )
 */
class LanguageAccessSettingType extends LanguageCheckboxesSettingType {

  /**
   * {@inheritdoc}
   */
  public function alterElement($value, PatternDefinitionSetting $def, &$element) {
    if ($value['current_language_selected'] === FALSE) {
      hide($element);
    }
  }

}
