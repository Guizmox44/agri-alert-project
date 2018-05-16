<?php
namespace AppBundle\Command;


use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bridge\Doctrine\RegistryInterface as Doctrine;

class MeteoCommand extends Command
{
    private $doctrine;
    private $container;

    public function __construct(Doctrine $doctrine, ContainerInterface $container)
    {
        $this->doctrine = $doctrine;
        $this->container = $container;
        parent::__construct();
    }
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('get:api:meteo')
            // the short description shown while running "php bin/console list"
            ->setDescription('Get meteo to open weather map')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('using the API of weather map')
            ->addOption(
                'dump',
                'd',
                InputOption::VALUE_NONE,
                'Use it to dump information.'
            );
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Meteo Api',
            '==============',
            '',
        ]);
        if($input->getOption('dump')) {
            // outputs a message followed by a "\n"
            $output->writeln('Whoa :scream:');
            // outputs a message without adding a "\n" at the end of the line
            $output->write('You are about to ');
            $output->writeln('scrap meteo !');
        }

        $this->container->get('meteo.data')->weatherManager();

        $output->writeln([
            'Meteo importée'
        ]);

    }

}