<?php

namespace App\Command;

use App\Entity\City;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;

class CityDumpDataCommand extends Command
{
    protected static $defaultName = 'app:city:dump-data';

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure()
    {
        $this->setDescription(
            'Will look up a city name in the csv file, then find this city\'s data in the API and insert it in the database'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $finder = new Finder();

        // find all files in the current directory
        $finder->files()->in('csv');

        // check if there are any search results
        if (!$finder->hasResults()) {
            echo 'File not found';
        }

        foreach ($finder as $file) {
            $absoluteFilePath = $file->getRealPath();

            $csv = array_map('str_getcsv', file($absoluteFilePath));

            foreach ($csv as $itemCity) {
                try {
                    //city name: $itemCity[1]
                    $cityName = str_replace(' ', '%20', $itemCity[1]);

                    $jsonString = file_get_contents('https://api.teleport.org/api/cities/?search=' . $cityName . '&limit=1');
                    $objectResponse = json_decode($jsonString);
                    $filterSearchResults = 'city:search-results';

                    //get the geonameId
                    $filterItem = 'city:item';
                    $geonameIdUrlSearched = ($objectResponse->_embedded->$filterSearchResults[0]->_links->$filterItem->href);
                    $geonameIdUrlSearchedArray = explode('/', $geonameIdUrlSearched);
                    $geonameIdUrlSearchedArray = (array_reverse($geonameIdUrlSearchedArray));
                    $geonameIdUrlSearchedArray = explode(':', $geonameIdUrlSearchedArray[1]);
                    $geonameId = $geonameIdUrlSearchedArray[1];

                    //get the countryName
                    $matchFullNameArray = explode(',', $objectResponse->_embedded->$filterSearchResults[0]->matching_full_name);
                    $countryName = trim((array_reverse($matchFullNameArray))[0]);

                    $city = new City();
                    $cityName = str_replace('%20', ' ', $itemCity[1]);
                    $city->setCityName($cityName);
                    $city->setCountryName($countryName);
                    $city->setGeonameId($geonameId);
                    $city->setCreatedAt(new \DateTime);
                    $this->em->persist($city);
                    $this->em->flush();
                } catch (Exception $e) {
                    echo 'Caught exception: ',  $e->getMessage(), "\n";
                }
            }
        }

        $io = new SymfonyStyle($input, $output);
        $io->success('Task finished --help to see your options.');

        return 0;
    }
}