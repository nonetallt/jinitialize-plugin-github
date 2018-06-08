<?php

namespace Nonetallt\Jinitialize\Plugin\Github\Commands;

use Github\Client;
use Github\Exception\ValidationFailedException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use Nonetallt\Jinitialize\JinitializeCommand;

class CreateRepository extends JinitializeCommand
{
    private $name; 
    private $client;
    private $username;

    protected function configure()
    {
        $this->setName('create-repository');
        $this->setDescription('Create a new github repository.');
        /* $this->setHelp('Extended description here'); */
        
        $this->addArgument('name', InputArgument::REQUIRED, 'The name for the created repository.');
        $this->addOption('description', 'd', InputOption::VALUE_OPTIONAL, 'The repository description');
        $this->addOption('private', 'p', InputOption::VALUE_NONE, 'Create repository as private if passed.');
    }

    protected function handle($input, $output, $style)
    {
        $this->client = $this->import('client');
        $this->username = $this->import('username');

        if(is_null($this->client)) $this->abort("The client must be initialized");

        $this->name = $input->getArgument('name');
        $description = $input->getOption('description') ?? '';
        $homepage = '';
        $public = ! $this->wasOptionPassed($input, 'private');

        try {
            /* $name, */
            /* $description  = '', */
            /* $homepage     = '', */
            /* $public       = true, */
            /* $organization = null, */
            /* $hasIssues    = false, */
            /* $hasWiki      = false, */
            /* $hasDownloads = false, */
            /* $teamId       = null, */
            /* $autoInit     = false */

            $result = $this->client->api('repo')->create($this->name, $description, $homepage, $public);
            $this->export('git_url', $result['git_url']);
            $this->export('ssh_url', $result['ssh_url']);
            $this->export('clone_url', $result['clone_url']);
            $this->export('svn_url', $result['svn_url']);
        }
        catch(ValidationFailedException $e) {
            $this->abort($e->getMessage());
        }
    }

    public function revert()
    {
        $this->client->api('repo')->remove($this->username, $this->name);
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
