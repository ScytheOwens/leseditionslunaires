<?php

namespace App\Command;

use App\Entity\Medium;
use App\Entity\Product;
use App\Entity\ProductVariant;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;

#[AsCommand(name: 'app:import:json-products', description: 'Set JSON products into Product Entity')]
class ImportJsonProductsCommand
{
    public function __construct(
        private ParameterBagInterface $parameters,
        private EntityManagerInterface $em,
        private HttpClientInterface $httpClient,
        private FilesystemOperator $coversStorage,
        private LoggerInterface $logger,
        private string $coverBucket,
        private string $minioHost
    ) {
    }

    public function __invoke(SymfonyStyle $io): int
    {
        $io->title('Importing JSON products');

        $projectRoot = $this->parameters->get('kernel.project_dir');
        $filePath = sprintf('%s/src/Resources/products.json', $projectRoot);

        if (!file_exists($filePath)) {
            $io->error('The file to import cannot be found.');

            return Command::FAILURE;
        }

        $products = json_decode(file_get_contents($filePath), true);

        if (null === $products) {
            $io->error('JSON format is invalid.');

            return Command::FAILURE;
        }

        $io->progressStart();

        foreach ($products as $product) {
            $productVariants = $this->buildProductVariants($product);

            $product = (new Product())
                ->setName($product['name'])
                ->setDescription($product['description'])
                ->setSlug($product['slug'])
                ->setReleasedOn(new \DateTimeImmutable($product['releasedOn']))
                ->setProductVariants($productVariants)
            ;

            $this->em->persist($product);
            $io->progressAdvance();
        }

        $this->em->flush();

        $io->progressFinish();

        return Command::SUCCESS;
    }

    private function buildProductVariants(array $product): array
    {
        $variants = [];

        foreach ($product['variants'] as $variant) {
            $variants[] = (new ProductVariant())
                ->setReference($variant['reference'])
                ->setRawPrice($variant['rawPrice'])
                ->setTaxRate($variant['taxRate'])
                ->setLength($variant['length'] ?? null)
                ->setWidth($variant['width'] ?? null)
                ->setHeight($variant['height'] ?? null)
                ->setWeight($variant['weight'] ?? null)
                ->setMainVariant($variant['mainVariant'] ?? false)
                ->setMedia($this->buildMedia($variant))
            ;
        }

        return $variants;
    }

    private function buildMedia(array $variant): array
    {
        $media = [];

        foreach ($variant['media'] as $medium) {
            $mediumUrl = $this->getMediumMinioUrl($medium);

            $media[] = (new Medium())
                ->setName($medium['name'])
                ->setUrl($mediumUrl)
                ->setTag($medium['tag'])
                ->setMimeType($this->coversStorage->mimeType(pathinfo($mediumUrl)['basename']))
                ->setSize($this->coversStorage->fileSize(pathinfo($mediumUrl)['basename']))
            ;
        }

        return $media;
    }

    private function getMediumMinioUrl(array $medium): string
    {
        $minioUrl = null;
        $content = null;

        try {
            $response = $this->httpClient->request('GET', $medium['link']);

            $content = $response->getContent();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        if (empty($content)) {
            $projectRoot = $this->parameters->get('kernel.project_dir');
            $filePath = sprintf('%s/assets/%s', $projectRoot, $medium['link']);

            if (!file_exists($filePath)) {
                $io->error('The file to import cannot be found.');

                return Command::FAILURE;
            }

            $content = file_get_contents($filePath);
        }

        try {
            $extension = pathinfo(parse_url($medium['link'], PHP_URL_PATH), PATHINFO_EXTENSION);
            $filename = sprintf('%s-%s.%s', $medium['name'], uniqid(), $extension);

            $this->coversStorage->write($filename, $content);

            $minioUrl = sprintf('https://s3.%s/%s/%s', $this->minioHost, $this->coverBucket, $filename);
        } catch (\Extension $e) {
            $this->logger->error($e->getMessage());
        }

        return $minioUrl;
    }
}
