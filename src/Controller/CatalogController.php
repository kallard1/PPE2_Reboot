<?php

namespace App\Controller;

use App\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;


class CatalogController extends Controller
{
    /**
     * @Route("/catalog/{slug}", name="catalog")
     * @Method("GET")
     *
     * @param Category $category
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Category $category)
    {
        $manager = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('App:Product');

        return $this->render('Catalog/index.html.twig', [
            'products' => $manager->getCatalogProduct($category),
            'category' => $category,
        ]);
    }
}
