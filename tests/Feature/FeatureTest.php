<?php

namespace Tests\Feature;

use Nonetallt\Jinitialize\Testing\TestCase;

class FeatureTest extends TestCase
{

    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function setUp()
    {
        parent::setUp();
        $this->registerLocalPlugin(__DIR__.'/../../composer.json');
    }
}
