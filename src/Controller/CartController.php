<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CartController
 * @package App\Controller
 *
 * @Route("/cart")
 */
class CartController extends AbstractController
{
    /**
     * @Route("/", name="cart")
     * @Method("GET")
     */
    public function cart(Request $request): Response
    {
        return $this->render('Cart/cart.html.twig', [
            'products' => $request->getSession()->get('products', [])
        ]);
    }

    /**
     * @Route("/add/{sku}", name="add_to_cart")
     * @param Request $request
     * @param Product $product
     *
     * @return Response
     */
    public function addToCart(Request $request, Product $product): Response
    {
        $quantity = $request->query->get('quantity', 1);

        if ($quantity < 1) {
            return new Response("Error: Quantity is less than 1");
        }

        $session = $request->getSession();
        $products = $session->get('products', []);
        $found = false;
        $promotion = $product->getPromotion() == null ? null : $product->getPromotion();

        foreach ($products as $key => $value) {
            if ($product->getId() === $value['product']->getId()) {
                $products[$key]['quantity'] += $quantity;
                $found = true;
            }
        }
        if ($found == false) {
            array_push($products, ['product' => $product, 'quantity' => $quantity, 'promotion' => $promotion]);
        }
        $session->set('products', $products);

        dump($session->get('products'));

        return new Response();
    }

    /**
     * @Route("/remove/{sku}", name="remove_to_cart")
     * @param Request $request
     * @param Product $product
     *
     * @return Response
     * @throws EntityNotFoundException
     */
    public function removeToCart(Request $request, Product $product): Response
    {
        $session  = $request->getSession();
        $products = $session->get('products', []);
        $found    = false;
        foreach ($products as $key => $value) {
            if ($product->getId() === $value['product']->getId()) {
                unset($products[$key]);
                $found = true;
            }
        }
        if ($found == false) {
            throw new EntityNotFoundException('Product not found in cart.');
        }
        $session->set('products', $products);

        return $this->redirectToRoute("cart");
    }
}
