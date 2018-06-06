<?php

namespace Tests\Unit;

use Github\Client;
use Github\Exception\RuntimeException;
use Tests\Traits\TestsGithub;
use Nonetallt\Jinitialize\Testing\TestCase;

class CreateRepositoryCommandTest extends TestCase
{
    use TestsGithub;
    
    /**
     * @group api
     */
    public function testRepositoryDoesNotExist()
    {
        $this->expectException(RuntimeException::class);
        $client = new Client();
        $result = $client->api('repo')->show($this->username, $this->repository);
    }

    /**
     * @group api
     */
    public function testTestRepositoryIsCreated()
    {
        $this->runCommand("github:authenticate $this->username $this->password");
        $this->runCommand("github:create-repository $this->repository");

        $client = new Client();
        $result = $client->api('repo')->show($this->username, $this->repository);
        $this->assertEquals("$this->username/$this->repository", $result['full_name']);
    }

    /**
     * @group api
     */
    public function testRevertRemovesTheRepository()
    {
        $this->runCommand("github:authenticate $this->username $this->password");
        $tester = $this->runCommand("github:create-repository $this->repository");
        $tester->getCommand()->revert();

        /* Assert that runtime exception is encountered because repo is not found */
        $this->expectException(RuntimeException::class);
        $client = new Client();
        $result = $client->api('repo')->show($this->username, $this->repository);
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
    }
}
