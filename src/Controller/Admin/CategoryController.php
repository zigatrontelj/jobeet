<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\Admin\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * Lists all categories entities.
     *
     * @Route("/admin/categories", name="admin.category.list", methods="GET")
     */
    public function list(EntityManagerInterface $em): Response
    {
        $categories = $em->getRepository(Category::class)->findAll();

        return $this->render('admin/category/list.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * Create category.
     *
     * @Route("/admin/category/create", name="admin.category.create", methods="GET|POST")
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('admin.category.list');
        }

        return $this->render('admin/category/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit category.
     *
     * @Route("/admin/category/{id}/edit", name="admin.category.edit", methods="GET|POST", requirements={"id" = "\d+"})
     */
    public function edit(Request $request, EntityManagerInterface $em, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('admin.category.list');
        }

        return $this->render('admin/category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete category.
     *
     * @Route("/admin/category/{id}/delete", name="admin.category.delete", methods="DELETE", requirements={"id" = "\d+"})
     */
    public function delete(Request $request, EntityManagerInterface $em, Category $category): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $em->remove($category);
            $em->flush();
        }

        return $this->redirectToRoute('admin.category.list');
    }
}
