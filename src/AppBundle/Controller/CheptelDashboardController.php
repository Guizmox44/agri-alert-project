<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Species;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CheptelDashboardController extends Controller
{
    /**
     * @Route("/cheptel", name="cheptel")
     */
    public function cheptelAction()
    {

        $ovins = $this->countAnimals('ovins');
        $bovins = $this->countAnimals('bovins');
        $caprins = $this->countAnimals('caprins');
        $porcins = $this->countAnimals('porcins');


        return $this->render('application/cheptel/dashboard.html.twig', [
            'bovins' => $bovins,
            'ovins' => $ovins,
            'caprins' => $caprins,
            'porcins' => $porcins
        ]);
    }

    private function countAnimals($species)
    {
        $getDatas = $this->getDoctrine()->getRepository(Species::class)->coutAnimalBySpecies($species);

        foreach ($getDatas as $value)
        {
            if ($value['id'] === null)
            {
                return  0;
            }else{
                return count($getDatas);
            }
        }
    }
}
