<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController {

    /**
     * @Route("/", name="homepage")
     * @Method("GET")
     *
     * @return Response
     */
    public function index(): Response {
        return $this->render("Homepage/index.html.twig", []);
    }
}