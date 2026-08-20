<?php

namespace App\Command;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Psr\Log\LoggerInterface;

#[AsCommand(name: 'app:import:json-categories', description: 'Set JSON categories into Category Entity')]
class ImportJsonCategoriesCommand
{
    public function __construct(
        private ParameterBagInterface $parameters,
        private EntityManagerInterface $em,
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(SymfonyStyle $io): int
    {
        $io->title('Importing JSON categories');

        $projectRoot = $this->parameters->get('kernel.project_dir');
        $filePath = sprintf('%s/src/Resources/categories.json', $projectRoot);

        if (!file_exists($filePath)) {
            $io->error('The file to import cannot be found.');

            return Command::FAILURE;
        }

        $categories = json_decode(file_get_contents($filePath), true);

        if (null === $categories) {
            $io->error('JSON format is invalid.');

            return Command::FAILURE;
        }

        $io->progressStart();

        foreach ($categories as $category) {
            $entityCategory = $this->importJsonCategoryRecursive($io, $category);

            if (!($entityCategory instanceof Category)) {
                continue;
            }

            $this->em->persist($entityCategory);
        }

        $this->em->flush();

        $io->progressFinish();
        $io->success('JSON categories imported.');

        return Command::SUCCESS;
    }

    private function importJsonCategoryRecursive(SymfonyStyle $io, array $category): ?Category
    {

        $childCategories = [];

        if (isset($category['children']) && !empty($category['children'])) {
            foreach ($category['children'] as $child) {
                $childCategories[] = $this->importJsonCategoryRecursive($io, $child);
            }
        }

        $category['children'] = $childCategories;
        $entityCategory = $this->hydrateCategory($category);

        if (null === $entityCategory) {
            $io->error(sprintf('Category %s cannot be imported.', $category['reference']));
        }

        $io->progressAdvance();

        return $entityCategory;
    }

    private function hydrateCategory(array $rawCategory): ?Category
    {
        $category = $this->em->getRepository(Category::class)->findOneBy(['reference' => $rawCategory['reference']]);

        if (null === $category) {
            $category = new Category();
        }

        try {
            $category
                ->setName($rawCategory['name'])
                ->setReference($rawCategory['reference'])
                ->setSlug($rawCategory['slug'])
                ->setDescription($rawCategory['description'] ?? null)
                ->setChildren($rawCategory['children'])
            ;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());

            return null;
        }

        return $category;
    }
}
