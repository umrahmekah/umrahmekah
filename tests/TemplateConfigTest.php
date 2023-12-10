<?php

namespace Tests;

class TemplateConfigTest extends \Tests\TestCase
{
    /** @test */
    public function it_has_blue_ocean_config_file()
    {
        $this->assertFileExists(config_path('templates/blue-ocean.php'));
    }

    /** @test */
    public function it_has_template_configuration_helper()
    {
        $this->assertTrue(function_exists('template_configurations'));
    }
}
