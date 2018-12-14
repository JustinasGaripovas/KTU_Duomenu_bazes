<?php

namespace App\Command;

use App\Entity\WinterJobs;
use App\Entity\WinterJobUnique;
use App\Repository\WinterJobsRepository;
use App\Repository\WinterJobUniqueRepository;
use function MongoDB\BSON\fromJSON;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\Console\Output\OutputInterface;

class ExportUniqueWinterJobsCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'app:export-unique-winter-jobs';

    private $winterJobsRepository;
    private $winterJobUniqueRepository;

    public function __construct(?string $name = null,WinterJobsRepository $winterJobsRepository, WinterJobUniqueRepository $winterJobUniqueRepository)
    {
        $this->winterJobsRepository = $winterJobsRepository;
        $this->winterJobUniqueRepository = $winterJobUniqueRepository;
        parent::__construct($name);

    }


    public function transferData()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $batchIndex = 0;
        $index =0;

        $this->winterJobUniqueRepository->deleteAll();

        $from = new \DateTime('-7 day');
        $from = $from->format("Y-m-d");
        $dql = "SELECT r FROM App:WinterJobs r WHERE r.Date >= '$from'";
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $query = $em->createQuery($dql);
        $winterJobs = $query->execute();


        foreach ($winterJobs as $winterJob)
        {
            foreach ($winterJob->getRoadSections() as $roadSection)
            {
                $batchIndex++;

                if($this->validate($roadSection)) {
                    $winterJobUnique = new WinterJobUnique();

                    $winterJobUnique->setCreatedAt($winterJob->getCreatedAt());
                    $winterJobUnique->setCreatedBy($winterJob->getCreatedBy());
                    $winterJobUnique->setDate($winterJob->getDate());
                    $winterJobUnique->setJob($winterJob->getJob());
                    $winterJobUnique->setJobId($winterJob->getJobId());
                    $winterJobUnique->setJobName($winterJob->getJobName());
                    $winterJobUnique->setJobQuantity($winterJob->getJobQuantity());
                    $winterJobUnique->setMechanism($winterJob->getMechanism());
                    $winterJobUnique->setSubunit($winterJob->getSubunit());
                    $winterJobUnique->setSubunitName($winterJob->getSubunitName());

                    $winterJobUnique->setTimeFrom($winterJob->getTimeFrom());
                    $winterJobUnique->setTimeTo($winterJob->getTimeTo());
                    $winterJobUnique->setOriginalId($winterJob->getId());
                    $winterJobUnique->setUniqueId($winterJob->getId() . "-" . $index);

                    $winterJobUnique->setQuadrature($roadSection->getQuadrature());
                    $winterJobUnique->setSalt($roadSection->getSaltValue());
                    $winterJobUnique->setSand($roadSection->getSandValue());
                    $winterJobUnique->setSolution($roadSection->getSolutionValue());

                    $winterJobUnique->setSectionBegin($roadSection->getSectionBegin());
                    $winterJobUnique->setSectionEnd($roadSection->getSectionEnd());
                    $winterJobUnique->setSectionId($roadSection->getSectionId());
                    $winterJobUnique->setSectionType($roadSection->getSectionType());

                    $em->persist($winterJobUnique);

                    $index++;
                }
            }

            $index =0;

            if($batchIndex >= 100)
            {
                $batchIndex = 0;

                $em->flush();

                $em->clear();
            }
        }
        $em->flush();
        $em->clear();
    }

    function validate($roadSection)
    {
       // if(null ==$roadSection->getSectionBegin()){ return false; }
        if(null ==$roadSection->getSectionEnd()){ return false; }
        if(null ==$roadSection->getSectionId()){ return false; }

        return true;
    }


    protected function configure()
    {
        $this
            ->setDescription('Make excel file from WinterJobsUnique entity')
            ->addArgument('dateFrom', InputArgument::OPTIONAL, 'For testing purpose')
            ->addArgument('dateTo', InputArgument::OPTIONAL, 'For testing purpose')
            ->addOption('generateXLS');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $currentDate = new \DateTime('now');

        $output->writeln([
            '┌─────────────────────────┐',
            '│Welcome to export service│',
            '│                         │',
            '│Option to generate file  │',
            '│                         │',
            '│--generateXLS            │',
            '└─────────────────────────┘',
            'Current date: ' . $currentDate->format('Y-m-d H:i:s'),

        ]);

        $output->writeln([
            '┌───────────────────┐',
            '│   <fg=yellow>Preparing data</>  │',
            '└───────────────────┘',
        ]);

        $this->transferData();


        if ($input->getOption('generateXLS')) {

            $output->writeln([
                '┌───────────────────┐',
                '│  <fg=yellow>Generating file</>  │',
                '└───────────────────┘',
            ]);

            $from = new \DateTime('-7 day');
            $from = $from->format("Y-m-d");


            $dql = "SELECT r FROM App:WinterJobUnique r WHERE r.Date >= '$from' ORDER BY r.Date ASC";
            $em = $this->getContainer()->get('doctrine.orm.entity_manager');
            $query = $em->createQuery($dql);
            $report = $query->execute();
            $reader = IOFactory::createReader('Xlsx');
            $spreadsheet = $reader->load('public/DAIS_GIS_1.xlsx');
            $index = 2;
            foreach ($report as $item) {
                $spreadsheet->getActiveSheet()
                    ->setCellValue('A' . $index, $item->getUniqueId())
                    ->setCellValue('B' . $index, $item->getSubunitName())
                    ->setCellValue('C' . $index, $item->getDate()->format('Y-m-d'))
                    ->setCellValue('D' . $index, $item->getTimeFrom()->format('H:m'))
                    ->setCellValue('E' . $index, $item->getTimeTo()->format('H:m'))
                    ->setCellValue('F' . $index, $item->getMechanism())
                    ->setCellValue('G' . $index, $item->getJob())
                    ->setCellValue('H' . $index, $item->getSectionId())
                    ->setCellValue('I' . $index, $item->getSectionType())
                    ->setCellValue('J' . $index, $item->getSectionBegin())
                    ->setCellValue('K' . $index, $item->getSectionEnd())
                    ->setCellValue('L' . $index, $item->getSalt())
                    ->setCellValue('M' . $index, $item->getSand())
                    ->setCellValue('N' . $index, $item->getSolution());
                $index++;
            }
            // Rename worksheet
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
            $writer->save('/home/DAIS_GIS.csv');

            $output->writeln([
                '┌─────────────────┐',
                '│    <fg=green>Success!</>     │',
                '└─────────────────┘',
            ]);
        }
    }
}
