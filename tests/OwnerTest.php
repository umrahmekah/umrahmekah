<?php

namespace Tests;

class OwnerTest extends \Tests\TestCase
{
    /** @test */
    public function it_has_owner_helper()
    {
        $this->assertTrue(function_exists('owner'));
    }
}
