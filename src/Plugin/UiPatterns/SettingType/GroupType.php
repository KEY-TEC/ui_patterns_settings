<?php

namespace Drupal\ui_patterns_settings\Plugin\UIPatterns\SettingType;

use Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting;
use Drupal\ui_patterns_settings\Plugin\PatternSettingTypeBase;

/**
 * Group (fieldgroup/details) setting type.
 *
 * @UiPatternsSettingType(
 *   id = "group",
 *   label = @Translation("Group")
 * )
 */
class GroupType extends PatternSettingTypeBase {

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, $value, PatternDefinitionSetting $def, $form_type) {
    $def = $this->getPatternSettingDefinition();
    $form[$def->getName()] = [
      '#type' => 'fieldset',
      '#title' => $def->getLabel(),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsPreprocess($value, array $context, PatternDefinitionSetting $def) {
    return NULL;
  }

}
