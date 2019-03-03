<?php

namespace Drupal\ui_patterns_settings\Plugin\UIPatterns\SettingType;

use Drupal\Component\Utility\Html;
use Drupal\Core\Template\Attribute;
use Drupal\ui_patterns_settings\Plugin\PatternSettingTypeBase;

/**
 * Attributes setting type.
 *
 * @UiPatternsSettingType(
 *   id = "attributes",
 *   label = @Translation("Attributes")
 * )
 */
class AttributesSettingType extends PatternSettingTypeBase {

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, $value) {
    $def = $this->getPatternSettingDefinition();
    $value = $this->getValue($value);
    $description = $this->getDescription() != NULL ? $this->getDescription() : $this->t('E.g. role="navigation" class="class-1"');
    $form[$def->getName()] = [
      '#type' => 'textfield',
      '#title' => $def->getLabel(),
      '#description' => $description,
      '#default_value' => $value,
      '#required' => $def->getRequired(),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function preprocess($value, array $context) {
    $parse_html = '<div ' . $value . '></div>';
    $attributes = [];
    foreach (HTML::load($parse_html)->getElementsByTagName('div') as $div) {
      foreach ($div->attributes as $attr) {
        $attributes[$attr->nodeName] = $attr->nodeValue;
      }
    }
    return new Attribute($attributes);
  }

}
