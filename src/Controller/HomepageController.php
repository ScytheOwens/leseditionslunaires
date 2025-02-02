<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomepageController extends AbstractController
{
    #[Route('/', name: 'web_home_index')]
    public function index(): Response
    {
        return $this->render('homepage/index.html.twig');
    }
}
