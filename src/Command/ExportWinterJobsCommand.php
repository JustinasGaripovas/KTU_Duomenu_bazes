<?php

namespace App\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\Console\Output\OutputInterface;

class ExportWinterJobsCommand extends ContainerAwareCommand
{

    protected static $defaultName = 'app:export-winter-jobs';

    protected function configure()
    {
        $this
            ->setDescription('Make excel file from WinterJobs entity')
            ->addArgument('dateFrom', InputArgument::OPTIONAL, 'For testing purpose')
            ->addArgument('dateTo', InputArgument::OPTIONAL, 'For testing purpose')
            ->addOption('generateXLS');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //$io = new SymfonyStyle($input, $output);
        if ($input->getOption('generateXLS')) {
            if ($input->getArgument('dateFrom') == null) {
                $dateFrom = new \DateTime('now');
                $dateFrom = $dateFrom->modify('- 24 hours');
                $dateFrom = $dateFrom->format('Y-m-d H:m:s');
            } else {
                $dateFrom = $input->getArgument('dateFrom');
            }
            if ($input->getArgument('dateTo') == null) {
                $dateTo = new \DateTime('now');
                $dateTo = $dateTo->format('Y-m-d H:m:s');
            } else {
                $dateTo = $input->getArgument('dateTo');
            }
            $dql = "SELECT r FROM App:WinterJobs r WHERE (r.Date >= '$dateFrom' AND r.Date <= '$dateTo')  ORDER BY r.Date ASC";
            $em = $this->getContainer()->get('doctrine.orm.entity_manager');
            $query = $em->createQuery($dql);
            $report = $query->execute();
            $reader = IOFactory::createReader('Xlsx');
            $spreadsheet = $reader->load('public/DAIS_GIS.xlsx');
            $index = 2;
            foreach ($report as $item) {
                foreach ($item->getRoadSections() as $value) {
                    $spreadsheet->getActiveSheet()
                        ->setCellValue('A' . $index, $item->getSubunitName())
                        ->setCellValue('B' . $index, $item->getDate()->format('Y-m-d'))
                        ->setCellValue('C' . $index, $item->getTimeFrom()->format('H:m'))
                        ->setCellValue('D' . $index, $item->getTimeTo()->format('H:m'))
                        ->setCellValue('E' . $index, $item->getMechanism())
                        ->setCellValue('F' . $index, $item->getJob())
                        ->setCellValue('G' . $index, $value->getSectionId())
                        ->setCellValue('H' . $index, $value->getSectionType())
                        ->setCellValue('I' . $index, $value->getSectionBegin())
                        ->setCellValue('J' . $index, $value->getSectionEnd())
                        ->setCellValue('K' . $index, $value->getSaltValue())
                        ->setCellValue('L' . $index, $value->getSandValue())
                        ->setCellValue('M' . $index, $value->getSolutionValue());
                    $index++;
                }
            }
            // Rename worksheet
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
            $writer->save('/home/samba/DAIS/DAIS_GIS.csv');
            $output->writeln('Valio');
        }
    }
}
