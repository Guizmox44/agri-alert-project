<?php

namespace AppBundle\Service;


use AppBundle\Entity\User;
use AppBundle\Entity\Weather;
use Doctrine\ORM\EntityManager;

Class Meteo
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function weatherManager()
    {
        $users = $this->em->getRepository(User::class)->findAll();

        // On boucle dessus
        foreach ($users as  $user) {
            $codePostal = $this->em->getRepository(User::class)->getZipCode($user->getId());
            $apiMeteo = $this->getCurl($codePostal['zipCode']);


            $weatherData = $this->weatherLogic($apiMeteo);


            if ($user->getWeather() != null)
            {
                $weather = $this->em->getRepository(Weather::class)->findOneBy(['id' => $user->getWeather()]);
                $this->addWeather($weather, $weatherData);
                $weather->setUpdatedAt( new \Datetime());
            }else{
                $weather = new Weather();
                $user->setWeather($weather);
                $this->addWeather($weather, $weatherData);
                $this->em->persist($weather);
            }
        }
        $this->em->flush();

    }


    private function getCurl($code)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "api.openweathermap.org/data/2.5/weather?zip=".urlencode($code).",fr&appid=b6f63d26d26dde61f2299010b96cc476",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $jsonResponse = json_decode($response, true);
        if ($jsonResponse) {
            return $jsonResponse;
        } else {
            return null;
        }
    }

    private function getCelsius($temperature)
    {
        $calcul = $temperature - 273.15;

        return $calcul;

    }

    private function getWind($windSpeed)
    {
        //Conversion m/s en Km/h
        $calcul = $windSpeed * (3600 / 1000);

        return $calcul;

    }

   private function getWeatherIcon($icon)
   {
       $icon = strtolower(substr($icon, 0, 2));

       return $icon;
   }

   private function weatherLogic($api)
   {
       $tab = [
           'icon' => $this->getWeatherIcon($api['weather'][0]['icon']),
           'celsius' => $this->getCelsius($api['main']['temp']),
           'wind' => $this->getWind($api['wind']['speed']),
           'moisture' => $api['main']['humidity'],
       ];

       return $tab;
   }

    private function addWeather($weather, $weatherData)
    {
        $weather->setImage($weatherData['icon']);
        $weather->setTemperature($weatherData['celsius']);
        $weather->setMoisture($weatherData['moisture']);
        $weather->setWindSpeed($weatherData['wind']);
    }

}