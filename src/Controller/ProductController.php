<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'product_')]
class ProductController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('/catalogue', name: 'list')]
    public function list(): Response
    {
        $categories = $this->em->getRepository(Category::class)->findBy(['parent' => null]);

        return $this->render('product/list.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/catalogue/{slug}', name: 'show')]
    public function show(string $slug): Response
    {
        $product = $this->em->getRepository(Product::class)->findOneBy(['slug' => $slug]);

        if (null === $product) {
            throw $this->createNotFoundException('Ce livre n\'existe pas.');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'tabs' => $this->getTabs($product),
        ]);
    }

    #[Route('admin/livre/nouveau', name: 'create', methods: ['POST'])]
    public function create(): Response
    {
        $product = new Product();
        $product->setName('Symbiose');
        $product->setDescription('Blabla');
        $product->setSlug('symbiose');
        $product->setCreatedAt(new \DateTimeImmutable());

        $this->em->persist($product);
        $this->em->flush();

        return new Response('Saved new product with id '.$product->getId());
    }

    #[Route('admin/livre/{id}', name: 'update', methods: ['PUT', 'PATCH'])]
    public function update(string $id): Response
    {
        return new Response('Update product with id '.$product->getId());
    }

    #[Route('admin/livre/suppression/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(string $id): Response
    {
        return new Response('Delete product with id '.$product->getId());
    }

    private function getTabs(Product $product): array
    {
        $tabs = [
            [
                'label' => 'Résumé',
                'template' => 'component/tab/_tab_text.html.twig',
                'vars' => [
                    'text' => $product->getDescription(),
                ],
            ],
            [
                'label' => 'Détails techniques',
                'template' => 'component/tab/_tab_details.html.twig',
                'vars' => [
                    'details' => [
                        [
                            'label' => 'Titre',
                            'content' => $product->getName(),
                        ],
                        [
                            'label' => 'ISBN',
                            'content' => $product->getMainVariant()->getReference(),
                        ],
                        [
                            'label' => 'Date de sortie',
                            'content' => date_format($product->getReleasedOn(), 'd/m/Y'),
                        ],
                    ],
                ],
            ],
        ];

        $builtTabs = [];
        foreach ($tabs as $tab) {
            if (empty(array_filter($tab['vars']))) {
                continue;
            }

            $builtTabs[] = [
                'label' => $tab['label'],
                'content' => $this->renderView($tab['template'], $tab['vars']),
            ];
        }

        return $builtTabs;
    }
}
