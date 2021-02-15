<?php


namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Weather;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;

class QueryApi
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function citiesData($geonameId)
    {
        //dans la recherche pour geonameid https://api.teleport.org/api/cities/geonameid%3A3435910/
        //https://api.teleport.org/api/urban_areas/slug:new-york/scores/
        //https://api.teleport.org/api/cities/geonameid:2988507  paris
        //http://localhost:8000/city/3489854 jamaica
        //4717560 texas

        $jsonString = file_get_contents('https://api.teleport.org/api/cities/geonameid:' . $geonameId);
        $objectResponse = json_decode($jsonString);

        try {
            // We find the city's name with city:urban_area
            $urbanArea = 'city:urban_area';
            $cityName = str_replace(' ', '-', $objectResponse->_links->$urbanArea->name);
        } catch (\Throwable $th) {
            // If we don't find the city's name with city_urban_area, then we use city:admin1_division
            $adminDivision = 'city:admin1_division';
            $cityName = str_replace(' ', '-', $objectResponse->_links->$adminDivision->name);
        }
        $cityNameUnsplash = $cityName;
        //dd($objectResponse->_links->$adminDivision->name);
        //dd($objectResponse->_links);
        // this header is added to all requests made by this client
        $client = HttpClient::create(['headers' => [
            'User-Agent' => 'My Fancy App',
        ]]);

        $urlUrbanAreas = 'https://api.teleport.org/api/urban_areas/slug:' . strtolower($cityName) . '/scores/';
        $responseUrlUrbanAreas = $client->request('GET', $urlUrbanAreas);
        // Responses are lazy: this code is executed as soon as headers are received
        if (200 == $responseUrlUrbanAreas->getStatusCode()) {
            $jsonStringScores = file_get_contents('https://api.teleport.org/api/urban_areas/slug:' . strtolower($cityName) . '/scores/');
            $objectResponseScores = json_decode($jsonStringScores);
        } else {
            $objectResponseScores = null;
            $cityName = null;
        }

        return [$objectResponseScores, $objectResponse, $cityName, $cityNameUnsplash];
    }


    public function cityDataDescription($geonameId)
    {
        //https://api.teleport.org/api/cities/geonameid:2988507  paris
        //http://localhost:8000/city/3489854 jamaica

        $jsonString = file_get_contents('https://api.teleport.org/api/cities/geonameid:' . $geonameId);
        $objectResponse = json_decode($jsonString);

        try {
            // We find the city's name with city:urban_area
            $urbanArea = 'city:urban_area';
            $cityName = str_replace(' ', '-', $objectResponse->_links->$urbanArea->name);
        } catch (\Throwable $th) {
            // If we don't find the city's name with city_urban_area, then we use city:admin1_division
            $adminDivision = 'city:admin1_division';
            $cityName = str_replace(' ', '-', $objectResponse->_links->$adminDivision->name);
        }
        // this header is added to all requests made by this client
        $client = HttpClient::create(['headers' => [
            'User-Agent' => 'My Fancy App',
        ]]);

        $urlUrbanAreas = 'https://api.teleport.org/api/urban_areas/slug:' . strtolower($cityName) . '/scores/';
        $responseUrlUrbanAreas = $client->request('GET', $urlUrbanAreas);
        // Responses are lazy: this code is executed as soon as headers are received
        if (200 == $responseUrlUrbanAreas->getStatusCode()) {
            $jsonStringScores = file_get_contents('https://api.teleport.org/api/urban_areas/slug:' . strtolower($cityName) . '/scores/');
            $objectResponseScores = json_decode($jsonStringScores)->summary;
            if (!$objectResponseScores) {
                return null;
            }
        } else {
            return null;
        }

        return $objectResponseScores;
    }






    public function cityDataImage($cityNameUnsplash)
    {
        if (isset($cityNameUnsplash)) {
            dump($cityNameUnsplash);
            //$cityNameUnsplash = str_replace('\%C3%A3', 'ã', $cityNameUnsplash);
            //'https://api.unsplash.com/search/photos/?query=' . $cityNameUnsplash . '&client_id=' . $_ENV['API_KEY_UNSPLASH'];
            //landscape
            //$urlImageQuery = 'https://api.unsplash.com/search/photos/?orientation=landscape&query=' . $cityNameUnsplash . '&client_id=' . $_ENV['API_KEY_UNSPLASH'];
            $urlImageQuery = 'https://api.unsplash.com/search/photos/?query=' . $cityNameUnsplash . '&client_id=' . $_ENV['API_KEY_UNSPLASH'];
            $jsonStringUrlImage = file_get_contents($urlImageQuery);
            $objectResponseUrlImage = json_decode($jsonStringUrlImage);
            $urlImage = $objectResponseUrlImage->results[0]->urls;
        } else {
            $urlImage = null;
        }
        return $urlImage;
    }



    public function cityDataImages($cityNameUnsplash)
    {
        if (isset($cityNameUnsplash)) {
            /*  $urlImageQuery = 'https://api.unsplash.com/search/photos/?query=' . $cityNameUnsplash . '&client_id=' . $_ENV['API_KEY_UNSPLASH'];
            $jsonStringUrlImage = file_get_contents($urlImageQuery);
            $objectResponseUrlImage = json_decode($jsonStringUrlImage);
            $urlImage = $objectResponseUrlImage->results[0]->urls;
 */
            $urlRandomImageQuery = 'https://api.unsplash.com/photos/random?query=' . $cityNameUnsplash . '&count=18&client_id=' . $_ENV['API_KEY_UNSPLASH'];
            $jsonStringUrlRandomImage = file_get_contents($urlRandomImageQuery);
            $objectResponseUrlRandomImage = json_decode($jsonStringUrlRandomImage);
            $randomImagesArray = [];

            foreach ($objectResponseUrlRandomImage as $urlRandomImage) {
                $randomImagesArray[] = $urlRandomImage->urls;
            }
        } else {
            $randomImagesArray = [];
        }
        return $randomImagesArray;
    }



    public function citiesDataImages($cityNameUnsplash)
    {
        if (isset($cityNameUnsplash)) {
            $urlImageQuery = 'https://api.unsplash.com/search/photos/?query=' . $cityNameUnsplash . '&client_id=' . $_ENV['API_KEY_UNSPLASH'];
            $jsonStringUrlImage = file_get_contents($urlImageQuery);
            $objectResponseUrlImage = json_decode($jsonStringUrlImage);
            $urlImage = $objectResponseUrlImage->results[0]->urls;

            $urlRandomImageQuery = 'https://api.unsplash.com/photos/random?query=' . $cityNameUnsplash . '&count=18&client_id=' . $_ENV['API_KEY_UNSPLASH'];
            $jsonStringUrlRandomImage = file_get_contents($urlRandomImageQuery);
            $objectResponseUrlRandomImage = json_decode($jsonStringUrlRandomImage);
            $randomImagesArray = [];

            foreach ($objectResponseUrlRandomImage as $urlRandomImage) {
                $randomImagesArray[] = $urlRandomImage->urls;
            }
        } else {
            $urlImage = null;
            $randomImagesArray = [];
        }
        return [$urlImage, $randomImagesArray];
    }
}
