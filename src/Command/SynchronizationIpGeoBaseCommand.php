<?php

namespace App\Command;

use App\Exceptions\FactoryNotFoundException;
use App\Services\IpGeoBase\EntityStrategy;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use wapmorgan\UnifiedArchive\UnifiedArchive;

/**
 * Class SynchronizationIpGeoBaseCommand
 * @package App\Command
 */
class SynchronizationIpGeoBaseCommand extends Command
{
    const DATE_BASE_URL = 'http://ipgeobase.ru/files/db/Main/geo_files.zip';

    const PATH_DOWNLOAD_FILE = '/tmp/geo_files.zip';

    const PARTIAL_ROW_COUNT = 10000;

    /** @var SymfonyStyle */
    protected $io;

    /** @var ContainerInterface */
    protected $container;

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

    protected function configure(): void
    {
        $this
            ->setName('synchronization:ip-geo-base')
            ->setDescription('Synchronization date base ip.');
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        ini_set("memory_limit", "512M");

        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws GuzzleException
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->io->writeln('Start process synchronization.');

        $this->downloadDataBase();

        $archive = UnifiedArchive::open(self::PATH_DOWNLOAD_FILE);

        foreach ($archive->getFileNames() as $fileName) {
            $fileContext = mb_convert_encoding($archive->getFileContent($fileName), 'UTF8', 'cp-1251');

            $this->fileContextToDb($fileContext, $fileName);
        }

        $this->io->success('Success.');
    }

    /**
     * @throws GuzzleException
     */
    protected function downloadDataBase(): void
    {
        $this->io->writeln('Download data base.');

        $progressBar = new ProgressBar($this->io);
        $progressBar->start();

        (new Client())->request(
            'GET',
            self::DATE_BASE_URL,
            [
                'sink' => self::PATH_DOWNLOAD_FILE,
                'progress' => function (int $downloadTotal, int $downloadedBytes, int $uploadTotal, int $uploadedBytes) use ($progressBar) {
                    $progressBar->setMaxSteps($downloadTotal);
                    $progressBar->setProgress($downloadedBytes);
                }
            ]
        );

        $progressBar->finish();
        $this->io->writeln('');
    }

    /**
     * @param string $context
     * @param string $fileName
     *
     * @throws DBALException
     * @throws FactoryNotFoundException
     */
    protected function fileContextToDb(string $context, string $fileName): void
    {
        $this->io->writeln("Import to db file: {$fileName}.");

        $rows = preg_split('/$\R?^/m', $context);
        $progressBar = new ProgressBar($this->io);
        $rowCount = 0;
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->container->get('doctrine')->getManager();
        $tableName = $entityManager->getClassMetadata(EntityStrategy::getClassNameByFileName($fileName))->getTableName();
        $connection = $entityManager->getConnection();
        $platform   = $connection->getDatabasePlatform();

        $connection->executeUpdate($platform->getTruncateTableSQL($tableName));

        $progressBar->setMaxSteps(count($rows));
        $progressBar->start();

        foreach ($rows as $row) {
            $entity = EntityStrategy::createFromRowByFileName($row, $fileName);

            $entityManager->persist($entity);

            ++$rowCount;

            if (($rowCount % self::PARTIAL_ROW_COUNT) === 0) {
                $entityManager->flush();
                $entityManager->clear();
            }

            $progressBar->setProgress($rowCount);
        }

        $entityManager->flush();

        $progressBar->finish();

        $this->io->writeln('');
    }
}
