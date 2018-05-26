<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NavController extends AbstractController
{
    public function categoriesAction()
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('App:Category');

        $categories = $repository->getCategories(true);

        return $this->render('default/_categories.html.twig', [
            'categories' => $categories
        ]);
    }
}
