<?php

namespace Nonetallt\Jinitialize\Plugin\Github\Commands;

use Github\Client;
use Github\Exception\ValidationFailedException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use Nonetallt\Jinitialize\JinitializeCommand;

class CreateWebhook extends JinitializeCommand
{
    private $client;
    private $username;
    private $repository;
    private $hookId;

    protected function configure()
    {
        $this->setName('create-webhook');
        $this->setDescription('Create a new webhook in repository.');
        /* $this->setHelp('Extended description here'); */
        
        $msg = 'Name of the repository where webhook will be created.';
        $this->addArgument('repository', InputArgument::REQUIRED, $msg);

        $msg = 'Use "web" for a webhook or use the name of a valid service. You can use /hooks for the list of valid service names. Note: GitHub Services will no longer be supported as of October 1, 2018. Please see the blog post for details.';
        $this->addOption('name', null, InputOption::VALUE_OPTIONAL, $msg, 'web');

        $msg = 'The URL to which the payloads will be delivered.';
        $this->addArgument('url', InputArgument::REQUIRED, $msg);

        $msg = 'Secret to be used as key to generate the HMAC hex disgest value in the X-Hub-Signature header.';
        $this->addOption('secret', 's', InputOption::VALUE_OPTIONAL, $msg);
    }

    protected function handle($input, $output, $style)
    {
        $this->client = $this->import('client');
        $this->username = $this->import('username');
        $this->repository = $input->getArgument('repository');

        /* https://developer.github.com/v3/repos/hooks/#create-a-hook */
        $params = [
            'name'   => $input->getOption('name'),
            'config' => [
                'url'          => $input->getArgument('url'),
                'content_type' => 'json',
                'secret'       => $input->getOption('secret')
            ]
        ];

        $result = $this->client->api('repo')
            ->hooks()
            ->create($this->username, $this->repository, $params);

        $this->hookId = $result['id'];
    }

    public function revert()
    {
        $this->client->api('repo')
            ->hooks()
            ->remove($this->username, $this->repository, $this->hookId);
    }

    public function requiresExecuting()
    {
        return [
            Authenticate::class
        ];
    }

    public function recommendsExecuting()
    {
        return [

        ];
    }

    public function recommendsRoot()
    {
        // bool, wether command should be executed with administrative priviliges
        return false;
    }
}
