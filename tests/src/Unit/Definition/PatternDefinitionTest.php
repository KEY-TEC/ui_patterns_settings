<?php

namespace Drupal\Tests\ui_patterns_settings\Unit\Definition;

use Drupal\Tests\UnitTestCase;
use Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting;

/**
 * @coversDefaultClass \Drupal\ui_patterns\Definition\PatternDefinition
 *
 * @group ui_patterns
 */
class PatternDefinitionSettingsTest extends UnitTestCase {

  /**
   * Test getters.
   *
   * @dataProvider definitionGettersProvider
   */
  public function testGettersSetters($getter, $name, $value) {
    $pattern_definition = new PatternDefinitionSetting('test', [$name => $value]);
    $this->assertEquals(call_user_func([$pattern_definition, $getter]), $value);
  }

  /**
   * Test field singleton.
   */
  public function testExposedConfig() {
    $config = [
      'expose' => [
        'node:basic',
        'node:article',
      ],
    ];
    $pattern_definition_setting = new PatternDefinitionSetting('test', $config, 'test');
    $configs = $pattern_definition_setting->getExposeConfigs();

    $this->assertEquals(
      [
        'node',
        'basic',
        'node',
        'article',
      ],
      [
        $configs[0]->getEntityType(),
        $configs[0]->getBundle(),
        $configs[1]->getEntityType(),
        $configs[1]->getBundle(),
      ]);

  }

  /**
   * Provider.
   *
   * @return array
   *   Data.
   */
  public function definitionGettersProvider() {
    return [
      ['getRequired', 'required', TRUE],
      ['getAllowToken', 'allow_token', TRUE],
      ['getName', 'name', 'Name'],
      ['getDescription', 'description', 'Pattern description.'],
      ['getType', 'type', 'textfield'],
    ];
  }

}
