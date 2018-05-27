<?php

namespace App\Command;

use App\Entity\Product;
use App\Services\ConvertCsvToArray;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CSVImportCommand extends ContainerAwareCommand
{

    private $convertCsvToArray;

    public function __construct(ConvertCsvToArray $csvToArray)
    {
        parent::__construct();

        $this->convertCsvToArray = $csvToArray;
    }


    protected function configure()
    {
        $this->setName("import:product:csv")
            ->setDescription("Import products from CSV file")
            ->addArgument('filename', InputArgument::REQUIRED, 'CSV file of products without extension');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $now = new \DateTime();
        $output->writeln("<comment>Import started: " . $now->format('d-m-Y G:i:s') . "</comment>");

        $this->import($input, $output);

        $now = new \DateTime();
        $output->writeln("<comment>Import finished: " . $now->format('d-m-Y G:i:s') . "</comment>");
    }

    private function import(InputInterface $input, OutputInterface $output)
    {
        $data = $this->get($input, $output);

        $em = $this->getContainer()->get('doctrine')->getManager();

        $size = count($data);
        $batchSize = 20;
        $i = 1;

        $progress = new ProgressBar($output, $size);
        $progress->start();

        foreach ($data as $row) {
            $product = $em->getRepository('App:Product')->findOneBy(['sku' => $row['sku']]);

            if (!is_object($product)) {
                $product = new Product();
            }

            $vatRate = $em->getRepository('App:VatRate')->find($row['vat_id']);
            $category = $em->getRepository('App:Category')->find($row['category_id']);

            $product->setVatRate($vatRate);
            $product->setName($row['name']);
            $product->setDescription($row['description']);
            $product->setSku($row['sku']);
            $product->setPrice($row['price']);
            $product->setStatus($row['status']);
            $product->setStock($row['stock']);
            $product->setPromotion($row['promotion']);
            $product->removeCategory($category);
            $product->addCategory($category);

            $category = $em->getRepository('App:Category')->findOneBy(['slug' => 'promotions']);

            if ($row['promotion'] > 0) {
                $product->removeCategory($category);
                $product->addCategory($category);
            } else {
                $product->removeCategory($category);
            }

            $em->persist($product);

            if (($i % $batchSize) === 0) {
                $em->flush();
                $em->clear();

                $progress->advance($batchSize);

                $now = new \DateTime();
                $output->writeln(" of products imported... | " . $now->format('d-m-Y G:i:s'));
            }

            $i++;
        }

        $em->flush();
        $em->clear();

        $progress->finish();
    }

    private function get(InputInterface $input, OutputInterface $output)
    {
        $fileName = './imports/' . $input->getArgument('filename') . '.csv';

        return $this->convertCsvToArray->convert($fileName);
    }
}
