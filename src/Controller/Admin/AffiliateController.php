<?php

namespace App\Controller\Admin;

use App\Entity\Affiliate;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AffiliateController extends AbstractController
{
    /**
     * @Route("/admin/affiliates/{page}",
     *     name="admin.affiliate.list",
     *     methods="GET",
     *     defaults={"page": 1},
     *     requirements={"page" = "\d+"}
     * )
     */
    public function list(EntityManagerInterface $em, PaginatorInterface $paginator, int $page): Response
    {
        $affiliates = $paginator->paginate(
            $em->getRepository(Affiliate::class)->createQueryBuilder('a'),
            $page,
            $this->getParameter('max_per_page'),
            [
                PaginatorInterface::DEFAULT_SORT_FIELD_NAME => 'a.active',
                PaginatorInterface::DEFAULT_SORT_DIRECTION => 'ASC',
            ]
        );

        return $this->render('admin/affiliate/list.html.twig', [
            'affiliates' => $affiliates,
        ]);
    }

    /**
     * @Route("/admin/affiliate/{id}/activate",
     *     name="admin.affiliate.activate",
     *     methods="GET",
     *     requirements={"id" = "\d+"}
     * )
     */
    public function activate(EntityManagerInterface $em, Affiliate $affiliate, MailerService $mailerService): Response
    {
        $affiliate->setActive(true);
        $em->flush();

        $mailerService->sendActivationEmail($affiliate);

        return $this->redirectToRoute('admin.affiliate.list');
    }

    /**
     * @Route("/admin/affiliate/{id}/deactivate",
     *     name="admin.affiliate.deactivate",
     *     methods="GET",
     *     requirements={"id" = "\d+"}
     * )
     */
    public function deactivate(EntityManagerInterface $em, Affiliate $affiliate): Response
    {
        $affiliate->setActive(false);
        $em->flush();

        return $this->redirectToRoute('admin.affiliate.list');
    }
}
