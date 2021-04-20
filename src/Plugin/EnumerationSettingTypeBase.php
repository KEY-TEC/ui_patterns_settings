<?php

namespace Drupal\ui_patterns_settings\Plugin;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting;

/**
 * Base class for enumerations like radios or select.
 */
abstract class EnumerationSettingTypeBase extends PatternSettingTypeBase implements PatternSettingTypeExposeable {

  /**
   * {@inheritdoc}
   */
  public function getExposeField(PatternDefinitionSetting $def) {
    $options = $def->getOptions();
    return BaseFieldDefinition::create('list_string')
      ->setLabel($def->getLabel())
      ->setCardinality(1)
      ->setRequired($def->getRequired())
      ->setDefaultValue($def->getDefaultValue())
      ->setSetting('allowed_values', $options)
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDescription($def->getDescription());
  }

  /**
   * {@inheritdoc}
   */
  public function getExposeStorage(PatternDefinitionSetting $def) {
    return \Drupal\Core\Field\BaseFieldDefinition::create('list_string')
      ->setLabel($def->getLabel())
      ;
  }

  /**
   * Returns empty option.
   *
   * @return array
   *   The empty option.
   */
  protected function emptyOption() {
    return ["" => $this->t("Please select")];
  }

  /**
   * Returns the enumeration type.
   *
   * @param \Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting $def
   *   The pattern definition setting.
   *
   * @return string
   *   The enumeration type.
   */
  protected function getEnumerationType(PatternDefinitionSetting $def) {
    return $def->getValue('enumeration_type');
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, $value, PatternDefinitionSetting $def, $form_type) {
    if ($def->getRequired() == FALSE) {
      $options = $this->emptyOption();
    }
    else {
      $options = [];
    }

    $options += $def->getOptions();
    $form[$def->getName()] = [
      '#type' => $this->getEnumerationType($def),
      '#title' => $def->getLabel(),
      '#description' => $def->getDescription(),
      '#default_value' => $this->getValue($value),
      '#options' => $options,
    ];
    $this->handleInput($form[$def->getName()], $def, $form_type);
    return $form;
  }

  public function processExpose(FieldItemListInterface $field, array $context) {
    return $field->getValue();
  }

}
