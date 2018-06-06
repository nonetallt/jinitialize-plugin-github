<?php

namespace Nonetallt\Jinitialize\Plugin\Github\Commands;

use Github\Client;
use Symfony\Component\Console\Input\InputArgument;

use Nonetallt\Jinitialize\JinitializeCommand;

class Authenticate extends JinitializeCommand
{

    protected function configure()
    {
        $this->setName('authenticate');
        $this->setDescription('Authenticate for github api.');
        /* $this->setHelp('Extended description here'); */
        
        $this->addArgument('username', InputArgument::REQUIRED, 'The username used for login.');
        $this->addArgument('password', InputArgument::REQUIRED, 'The password used for login.');
    }

    protected function handle($input, $output, $style)
    {
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');

        $client = new Client();
        $client->authenticate($username, $password, Client::AUTH_HTTP_PASSWORD);

        $this->export('client', $client);
        $this->export('username', $username);
        $this->export('password', $password);
    }

    public function revert()
    {
    }

    public function requiresExecuting()
    {
        return [

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
