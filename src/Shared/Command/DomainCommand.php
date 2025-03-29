<?php

declare(strict_types=1);

namespace App\Shared\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

#[AsCommand(name: 'app:create-domain', description: 'Hello PhpStorm')]
class DomainCommand extends Command
{
    public function __construct(
        #[Autowire('%kernel.project_dir%/config/extra/twig-paths.yaml')]
        private string $twigConfig,
        #[Autowire('%kernel.project_dir%/config/extra/doctrine-mappings.yaml')]
        private string $doctrineConfig,
        #[Autowire('%kernel.project_dir%/src')]
        private string $src,
    ) {
        parent::__construct();
    }

    protected function getTemplatesRootDir(string $domain): string
    {
        return sprintf('src/%s/templates', $domain);
    }

    protected function getDoctrineMapping(string $domain): array
    {
        return [
            'type' => 'attribute',
            'is_bundle' => false,
            'dir' => '%kernel.project_dir%/src/'.$domain,
            'prefix' => sprintf('App\\%s\\Entity', $domain),
            'alias' => $domain,
        ];
    }

    protected function domainDirs(): array
    {
        return [
            'ApiResource',
            'Command',
            'Controller',
            'DataFixtures',
            'DependencyInjection',
            'Entity',
            'Event',
            'Exception',
            'Form',
            'Model',
            'Repository',
            'Security',
            'Service',
            'Twig',
            'Utils',
            'templates',
        ];
    }

    protected function getAlias(string $domain): string
    {
        return sprintf('%s', $domain);
    }

    public function configure(): void
    {
        $this->addArgument('domain', InputArgument::REQUIRED, 'Domain name to add: will create a new domain in src/ & configure twig & doctrine.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filesystem = new Filesystem();

        $filesystem->mkdir($this->src.'/'.$input->getArgument('domain'));
        foreach ($this->domainDirs() as $dir) {
            $filesystem->mkdir($this->src.'/'.$input->getArgument('domain').'/'.$dir);
        }

        $twigConfig = Yaml::parseFile($this->twigConfig);
        $twigConfig['twig']['paths'][$this->getTemplatesRootDir($input->getArgument('domain'))] = $this->getAlias($input->getArgument('domain'));
        file_put_contents($this->twigConfig, Yaml::dump($twigConfig, 100));

        $doctrineConfig = Yaml::parseFile($this->doctrineConfig);
        $doctrineConfig['doctrine']['orm']['mappings'][$input->getArgument('domain')] = $this->getDoctrineMapping($input->getArgument('domain'));
        file_put_contents($this->doctrineConfig, Yaml::dump($doctrineConfig, 100));

        return Command::SUCCESS;
    }
}
