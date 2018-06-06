<?php

namespace Tests\Unit;

use Github\Client;
use Github\Exception\RuntimeException;
use Tests\Traits\TestsGithub;
use Nonetallt\Jinitialize\Testing\TestCase;

class CreateWebhookCommandTest extends TestCase
{
    use TestsGithub;

    private $webhookName;

    /**
     * @group api
     */
    public function testWebhookIsCreated()
    {

        $this->runCommand("github:authenticate $this->username $this->password");
        $this->runCommand("github:create-repository $this->repository");
        $this->runCommand("github:create-webhook $this->repository $this->webhookName $this->webhookUrl");

        $this->assertTrue($this->webhookExists($this->webhookName));
    }

    private function webhookExists(string $name)
    {
        $client = new Client();
        $client->authenticate($this->username, $this->password, Client::AUTH_HTTP_PASSWORD);
        $hooks = $client->api('repo')->hooks()->all($this->username, $this->repository);

        foreach($hooks as $hook) {
            if($hook['name'] === $name) return true;
        }
        return false;
    }

    public function setUp()
    {
        parent::setUp();
        $this->registerLocalPlugin(__DIR__.'/../../composer.json');
        $this->loadEnv();

        try{
            $this->removeTestRepository();
        } 
        catch(RuntimeException $e) {
        }

        $this->webhookName = 'web';
    }
}
