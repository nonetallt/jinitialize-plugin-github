<?php

namespace Tests\Unit;

use Github\Client;
use Tests\Traits\TestsGithub;
use Nonetallt\Jinitialize\Testing\TestCase;

class AuthenticateCommandTest extends TestCase
{
    use TestsGithub;

    /**
     * @group api
     */
    public function testExportsClientVariable()
    {
        $this->runCommand("github:authenticate $this->username $this->password");
        $plugin = $this->getApplication()->getContainer()->getPlugin('github');
        $client = $plugin->getContainer()->get('client');

        $this->assertInstanceOf(Client::class, $client);
    }

    public function setUp()
    {
        parent::setUp();
        $this->registerLocalPlugin(__DIR__.'/../../composer.json');
        $this->loadEnv();
    }
}
