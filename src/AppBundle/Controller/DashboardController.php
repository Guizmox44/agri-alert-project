<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Task;
use AppBundle\Entity\Weather;
use AppBundle\Entity\Species;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Date;

class DashboardController extends Controller
{

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboardAction()
    {
        $user = $this->getUser();

        $weatherId = $user->getWeather()->getId();
        $weather = $this->getDoctrine()->getRepository(Weather::class)->findOneBy(['id' => $weatherId]);

        $todayDate = new \DateTime();

        $d=$todayDate->format('Y-m-d');
        $results = $this->getDoctrine()->getRepository(Task::class)->findTaskByDay($user,$d);

        $products = $this->getDoctrine()->getRepository(Product::class)->findAlert($user);

        $messages = $this->getDoctrine()->getRepository(Message::class)->getOpenMessageByUser($user);

        return $this->render('application/dashboard.html.twig',[
            'user' => $user,
            'weather' => $weather,
            'todayDate' => $todayDate,
            'tasks'=>$results,
            'products'=>$products,
            'messages'=>$messages
        ]);
    }


    /**
     * @Route("/cgu", name="cgu")
     */
    public function cguAction()
    {
        return $this->render('cgu.html.twig');
    }

    /**
     * @Route("/laTeam", name="team")
     */
    public function teamAction()
    {
        return $this->render('team.html.twig');
    }


}
