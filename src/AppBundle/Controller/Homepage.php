<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Homepage extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(SessionInterface $session)
    {

        return $this->render('home/homepage.html.twig');
    }
}
