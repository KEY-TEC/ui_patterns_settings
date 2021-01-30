<?php

namespace Drupal\ui_patterns_settings\Plugin\UIPatterns\SettingType;

use Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting;
use Drupal\ui_patterns_settings\Plugin\EnumerationSettingTypeBase;

/**
 * Select setting type.
 *
 * @UiPatternsSettingType(
 *   id = "select",
 *   label = @Translation("Select")
 * )
 */
class SelectSettingType extends EnumerationSettingTypeBase {

  /**
   * {@inheritdoc}
   */
  protected function getEnumerationType(PatternDefinitionSetting $def) {
    return 'select';
  }

}
