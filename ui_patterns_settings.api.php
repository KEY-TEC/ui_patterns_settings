<?php

/**
 * @file
 * API file.
 */

/**
 * Alter UI Patterns Settings settings before they are printed.
 *
 * Implement this hook to override the configured pattern settings for
 * specific patterns or to configure custom setting logic.
 *
 * @param array $settings
 *   Pattern settings.
 * @param array $context
 *   Context Properties: The context and the entity of the pattern.
 *
 * @see \Drupal\ui_patterns_settings\Element\PatternSettings
 */
function hook_ui_pattern_settings_settings_alter(array &$settings, array $context) {
  if ($context['#layout']->getPluginId() === 'pattern_section') {
    $settings['padding_bottom'] = 'large';
  }
}
