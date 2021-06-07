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
   * Provider.
   *
   * @return array
   *   Data.
   */
  public function definitionGettersProvider() {
    return [
      ['getRequired', 'required', TRUE],
      ['getAllowToken', 'allow_token', TRUE],
      ['getExpose', 'expose', TRUE],
      ['getName', 'name', 'Name'],
      ['getDescription', 'description', 'Pattern description.'],
      ['getType', 'type', 'textfield'],
    ];
  }

}
