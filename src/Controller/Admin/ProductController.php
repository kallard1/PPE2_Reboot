<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\VatRate;
use App\Form\CategoryType;
use App\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class CategoryController
 * @package App\Controller\Admin
 * @Route("/admin/products")
 * @Security("has_role('ROLE_ADMIN')")
 *
 */
class ProductController extends AbstractController
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route("/", methods={"GET"}, name="admin_products")
     */
    public function index(): Response
    {
        return $this->render('Admin/Products/index.html.twig', [
            'products' => $this->getDoctrine()->getManager()->getRepository('App:Product')->findAll()
        ]);
    }

    /**
     * @Route("/add", methods={"GET", "POST"}, name="admin_product_add")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function add(Request $request): Response
    {
        $product = new Product();
        $vat = new VatRate();

        $product->setVatRate($vat);

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('admin_products');
        }

        return $this->render('Admin/Products/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", methods={"GET", "POST"}, name="admin_product_edit")
     *
     * @param Request $request
     * @param Product $product
     *
     * @return Response
     */
    public function edit(Request $request, Product $product): Response
    {
        if ($product === null) {
            throw new NotFoundHttpException("Product not found");
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('admin_products');
        }

        return $this->render('Admin/Products/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", methods={"POST"}, name="admin_product_delete")
     *
     * @param Request $request
     * @param Product $product
     *
     * @return Response
     */
    public function delete(Request $request, Product $product): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_products');
        }

        $product->getCategories()->clear();

        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        $this->addFlash('success', $this->translator->trans('product.deleted.successfully'));

        return $this->redirectToRoute('admin_products');
    }
}
