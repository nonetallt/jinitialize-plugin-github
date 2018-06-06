<?php

namespace Tests\Traits;

use Github\Client;
use Dotenv\Dotenv;

trait TestsGithub
{
    private $username;
    private $password;
    private $repository;

    private function loadEnv()
    {
        $this->username = getenv('GITHUB_USERNAME');
        $this->password = getenv('GITHUB_PASSWORD');
        $this->repository = getenv('GITHUB_TEST_REPOSITORY');
    }

    private function removeTestRepository()
    {
        $client = new Client();
        $client->authenticate($this->username, $this->password, Client::AUTH_HTTP_PASSWORD);
        $client->api('repo')->remove($this->username, $this->repository);
    }

    public static function setUpBeforeClass()
    {
        $dotenv = new Dotenv(dirname(dirname(__DIR__)));
        $dotenv->load();

        $required = ['GITHUB_USERNAME', 'GITHUB_PASSWORD', 'GITHUB_TEST_REPOSITORY'];

        if(count(array_intersect(array_keys($_ENV), $required)) !== count($required)) {

            $missing = array_diff($required, array_keys($_ENV));
            $missing = implode(', ', $missing);
            $msg = "You need to set up the following values in your .env to run this test: [$missing]";
            echo PHP_EOL . $msg . PHP_EOL;
            exit(1);
            throw new \Exception($msg);
        }
    }

}
