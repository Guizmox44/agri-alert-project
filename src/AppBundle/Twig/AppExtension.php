<?php

namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('get_compare_date', [$this, 'getCompareDate']),
            new \Twig_SimpleFilter('get_today_date', [$this, 'getTodayDate'])
        );
    }

    public function getCompareDate($date)
    {
        $currentDate= new \DateTime();

        $diff = date_diff($date, $currentDate);

        $differences["Mois"] = $diff->m;
        $differences["Jour"] = $diff->d % 7;
        $differences["Heures"] = $diff->h;
        $differences["Minutes"] = $diff->i;
        $differences["Secondes"] = $diff->s;


        if ($differences["Mois"] && $differences["Jour"] && $differences["Heures"] && $differences["Minutes"] > 0 )
        {
            $dateFormated = $differences["Mois"] .' Mois '. $differences["Jour"] .' Jours '. $differences["Heures"] . ' Heures ' . $differences["Minutes"] . ' Minutes';

        } elseif ($differences["Jour"] && $differences["Heures"] && $differences["Minutes"] > 0 ) {

            $dateFormated = $differences["Jour"] .' Jours '. $differences["Heures"] . ' Heures et ' . $differences["Minutes"] . ' Minutes';

        } elseif ($differences["Heures"] && $differences["Minutes"] > 0 ) {

            $dateFormated = $differences["Heures"] . ' Heures et ' . $differences["Minutes"] . ' Minutes';

        } elseif ($differences["Minutes"] > 0) {

            $dateFormated = $differences["Minutes"] . ' Minutes';

        } else{
            $dateFormated = $differences["Secondes"] . ' Secondes';
        }

        return $dateFormated;
    }


    public function getTodayDate($date)
    {
        $month = $date->format('m');
        $day = $date->format('d');
        $year = $date->format('Y');

        $getMonth = $this->transformMonths($month);

        return $day . ' ' . $getMonth . ' ' . $year;
    }

    private function transformMonths($monthNumber)
    {
        $month = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

        return $month[$monthNumber - 1 ]  ;
    }
}