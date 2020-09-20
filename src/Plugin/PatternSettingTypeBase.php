<?php

namespace Drupal\ui_patterns_settings\Plugin;

use Drupal\Component\Plugin\ConfigurableInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\PluginBase;
use Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for UI Patterns Setting plugins.
 */
abstract class PatternSettingTypeBase extends PluginBase implements ConfigurableInterface, PatternSettingTypeInterface {

  /**
   * Return pattern definitions for setting .
   *
   * @var \Drupal\ui_patterns\Definition\PatternDefinition
   */
  private $patternDefinition;

  /**
   * Return pattern definitions for setting .
   *
   * @var \Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting
   */
  private $patternSettingDefinition;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, array $plugin_definition) {
    $configuration += $this->defaultConfiguration();
    $this->patternSettingDefinition = $configuration['pattern_setting_definition'];
    $this->patternDefinition = $configuration['pattern_definition'];
    unset($configuration['pattern_setting_definition']);
    unset($configuration['pattern_definition']);
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * Return value if set otherwise take the default value.
   *
   * @param mixed $value
   *   The provided value.
   *
   * @return string
   *   The value for this setting
   */
  protected function getValue($value) {
    if ($value === NULL) {
      return $this->getPatternSettingDefinition()->getDefaultValue();
    }
    else {
      return $value === NULL ? "" : $value;
    }
  }

  /**
   * Return pattern setting definition.
   *
   * @return \Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting
   *   Pattern setting definition.
   */
  protected function getPatternSettingDefinition() {
    return $this->patternSettingDefinition;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $plugin = new static($configuration, $plugin_id, $plugin_definition);

    /** @var \Drupal\Core\StringTranslation\TranslationInterface $translation */
    $translation = $container->get('string_translation');

    $plugin->setStringTranslation($translation);

    return $plugin;
  }

  /**
   * {@inheritdoc}
   */
  public function label() {
    $plugin_definition = $this->getPluginDefinition();
    return $plugin_definition['label'];
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    $plugin_definition = $this->getPluginDefinition();
    return isset($plugin_definition['description']) ? $plugin_definition['description'] : '';
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getConfiguration() {
    return $this->configuration;
  }

  /**
   * {@inheritdoc}
   */
  public function setConfiguration(array $configuration) {
    $this->configuration = $configuration + $this->defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function calculateDependencies() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function preprocess($value, array $context) {
    $def = $this->getPatternSettingDefinition();
    $value = $this->settingsPreprocess($value, $context, $def);
    return $value;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsPreprocess($value, array $context, PatternDefinitionSetting $def) {
    return $value;
  }

  /**
   * Returns the bind form field.
   *
   * @param array $form
   *   The form definition array for the settings configuration form.
   * @param string $value
   *   The stored default value.
   * @param \Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting $def
   *   The pattern definition.
   *
   * @return array
   *   The form.
   */
  protected function tokenForm(array $form, $value, PatternDefinitionSetting $def) {
    $form[$def->getName() . "_token"] = [
      '#type' => 'textfield',
      '#title' => "Token",
      '#description' => $this->t("Token for %label", ['%label' => $def->getLabel()]),
      '#default_value' => $this->getValue($value),
      '#attributes' => ['class' => ['js-ui-patterns-settings__token']],
      '#wrapper_attributes' => ['class' => ['js-ui-patterns-settings__token-wrapper']],
    ];
    return $form;
  }

  /**
   * Check required input fields in layout forms.
   *
   * @param $element
   *   The element to validate.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   * @param $form
   *   The form.
   */
  public function validateLayout($element, FormStateInterface &$form_state, &$form) {
    $parents = $element['#parents'];
    $value = $form_state->getValue($parents);
    $parents[ count($parents) -1 ] = $parents[ count($parents) -1 ] . '_token';
    $token_value = $form_state->getValue($parents);
    if (empty($value) && empty($token_value)) {
      // Check if a variant is selected and the value
      // is provided by the variant.
      $variant = $form_state->getValue(['layout_configuration', 'pattern', 'variant']);
      if (!empty($variant)) {
        $variant_def = $this->patternDefinition->getVariant($variant);
        $variant_ary = $variant_def->toArray();
        if (!empty($variant_ary['settings'][$this->patternSettingDefinition->getName()])) {
          return;
        }
      }

      $form_state->setError($element, t('@name field is required.', ['@name' => $element['#title']]));
    }
  }

  protected function handleInput(&$input, PatternDefinitionSetting $def, $form_type) {
    $input['#attributes']['class'][] = 'js-ui-patterns-settings__input';
    $input['#wrapper_attributes']['class'][] = 'js-ui-patterns-settings__input-wrapper';
    if ($def->getRequired()) {
      $input['#title'] .=' *';
      if ($form_type === 'layouts_display') {
        $input['#def'] = $def;
        $input['#element_validate'][] = [$this, 'validateLayout'];
      }
    }
  }

  /**
   * {@inheritdoc}
   *
   * Creates a generic configuration form for all settings types.
   * Individual settings plugins can add elements to this form by
   * overriding PatternSettingTypeBaseInterface::settingsForm().
   * Most plugins should not override this method unless they
   * need to alter the generic form elements.
   *
   * @see \Drupal\Core\Block\BlockBase::blockForm()
   */
  public function buildConfigurationForm(array $form, $value, $token_value, $form_type) {
    $def = $this->getPatternSettingDefinition();
    $form = $this->settingsForm($form, $value, $def, $form_type);
    $classes = 'js-ui-patterns-settings__wrapper';
    if ($def->getAllowToken()) {
      if (!empty($token_value)) {
        $classes .= ' js-ui-patterns-settings--token-has-value';
      }
      $form[$def->getName()]['#prefix'] = '<div class="' . $classes . '">';
    }
    if ($def->getAllowToken()) {
      $form = $this->tokenForm($form, $token_value, $def);
      $form[$def->getName() . '_token']['#suffix'] = '</div>';
    }

    return $form;
  }

}
