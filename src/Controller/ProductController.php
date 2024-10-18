<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'web_product_')]
class ProductController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/catalogue/{slug}', name: 'show')]
    public function show(string $slug): Response
    {
        $product = $this->em->getRepository(Product::class)->findOneBy(['slug' => $slug]);

        return $this->render('product/show.html.twig', [
            'product' => $product,
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
}
