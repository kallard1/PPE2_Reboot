<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
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
 * @Route("/admin/categories")
 * @Security("has_role('ROLE_ADMIN')")
 *
 */
class CategoryController extends AbstractController
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route("/", methods={"GET"}, name="admin_categories")
     */
    public function index(): Response
    {
        return $this->render('Admin/Categories/index.html.twig', [
            'categories' => $this->getDoctrine()->getManager()->getRepository('App:Category')->findAll()
        ]);
    }

    /**
     * @Route("/add", methods={"GET", "POST"}, name="admin_categories_add")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function add(Request $request): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('admin_categories');
        }

        return $this->render('Admin/Categories/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{slug}", methods={"GET", "POST"}, name="admin_categories_edit")
     *
     * @param Request  $request
     * @param Category $category
     *
     * @return Response
     */
    public function edit(Request $request, Category $category): Response
    {
        if ($category === null) {
            throw new NotFoundHttpException("Category not found");
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('admin_categories');
        }

        return $this->render('Admin/Categories/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{slug}", methods={"POST"}, name="admin_categories_delete")
     *
     * @param Request  $request
     * @param Category $category
     *
     * @return Response
     */
    public function delete(Request $request, Category $category): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_categories');
        }

        $category->getProducts()->clear();

        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        $this->addFlash('success', $this->translator->trans('category.deleted.successfully'));

        return $this->redirectToRoute('admin_categories');
    }
}
