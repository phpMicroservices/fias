<?php

namespace App\Command;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Predis\Client as RedisClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SynchronizationFiasCommand
 * @package App\Command
 */
class SynchronizationFiasCommand extends Command
{
    const URL_FIAS_FULL_DB = 'http://fias.nalog.ru/Public/Downloads/Actual/fias_dbf.rar';

    const URL_FIAS_DELTA_DB = 'http://fias.nalog.ru/Public/Downloads/Actual/fias_delta_dbf.rar';

    const URL_FIAS_LAST_DATE = 'http://fias.nalog.ru/Public/Downloads/Actual/VerDate.txt';

    const PARTIAL_ROW_COUNT = 10000;

    const REDIS_KEY_NAME_LAST_DATE_SYNCHRONIZATION = 'fias:last-date-synchronization';

    /** @var SymfonyStyle */
    protected $io;

    /** @var ContainerInterface */
    protected $container;

    /** @var RedisClient */
    protected $redis;

    /**
     * SynchronizationIpGeoBaseCommand constructor.
     * @param string|null $name
     * @param ContainerInterface $container
     */
    public function __construct(string $name = null, ContainerInterface $container)
    {
        parent::__construct($name);

        $this->container = $container;
    }

    protected function configure()
    {
        $this
            ->setName('synchronization:fias')
            ->setDescription('Synchronization FAIS.');
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);

        $this->redis = new RedisClient();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $urlDbFIAS = self::URL_FIAS_FULL_DB;

        if ($this->isLastSynchronization()) {
            $urlDbFIAS = self::URL_FIAS_DELTA_DB;
        }
    }

    /**
     * @return \DateTime
     * @throws GuzzleException
     */
    protected function getLastDateUpdateFIAS(): \DateTime
    {
        $this->io->write('Get last date update FIAS:');

        $date = (new Client())
            ->request(
                'GET',
                self::URL_FIAS_LAST_DATE
            )
            ->getBody();


        $this->io->writeln($date);

        return new \DateTime($date);
    }

    /**
     * @return bool
     */
    protected function isLastSynchronization(): bool
    {
        return $this->redis->get(self::REDIS_KEY_NAME_LAST_DATE_SYNCHRONIZATION) !== null;
    }

    /**
     * @return \DateTime
     */
    protected function getLastDateSynchronization(): \DateTime
    {
        return new \DateTime(intval($this->redis->get(self::REDIS_KEY_NAME_LAST_DATE_SYNCHRONIZATION)));
    }

    /**
     * @param \DateTime $dateTime
     */
    protected function setLastDateSynchronization(\DateTime $dateTime): void
    {
        $this->redis->set(self::REDIS_KEY_NAME_LAST_DATE_SYNCHRONIZATION, $dateTime->getTimestamp());
    }
}
