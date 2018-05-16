<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function adminAction()
    {
        $totalUser = count($this->getDoctrine()->getRepository(User::class)->findAll());

        $totalMessage = count($this->getDoctrine()->getRepository(User::class)->getUserMessages());


        return $this->render('application/admin/adminDashBoard.html.twig', [
            'totalUser' => $totalUser,
            'totalMessage' => $totalMessage
        ]);
    }


}
