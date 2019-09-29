<?php

namespace Drupal\ui_patterns_settings\Plugin;

use Drupal\Component\Plugin\ConfigurableInterface;
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
   * @var \Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting
   */
  private $patternSettingDefinition;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, array $plugin_definition) {
    $configuration += $this->defaultConfiguration();
    $this->patternSettingDefinition = $configuration['pattern_setting_definition'];
    unset($configuration['pattern_setting_definition']);
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
    if (empty($value)) {
      return $this->getPatternSettingDefinition()->getDefaultValue();
    }
    else {
      return $value;
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
  public function buildConfigurationForm(array $form, $value) {
    $def = $this->getPatternSettingDefinition();
    $form = $this->settingsForm($form, $value, $def);
    return $form;
  }

}
