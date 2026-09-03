<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'static_')]
class StaticController extends AbstractController
{
    public const STATIC_PAGE_SLUG_TEMPLATE_MAPPING = [
        'cgv' => 'general_terms',
        'mentions-legales' => 'legal_notices',
    ];

    #[Route('/{slug}', name: 'show')]
    public function show(string $slug): Response
    {
        $templateFilename = self::STATIC_PAGE_SLUG_TEMPLATE_MAPPING[$slug];

        if (empty($templateFilename)) {
            throw $this->createNotFoundException('Cette page n\'existe pas.');
        }

        return $this->render(sprintf('static/%s.html.twig', $templateFilename));
    }
}
