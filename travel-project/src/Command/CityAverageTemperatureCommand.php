<?php

namespace App\Command;

use App\Entity\Weather;
use App\Repository\CityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CityAverageTemperatureCommand extends Command
{
    protected static $defaultName = 'app:city:average-temperature';
    private $em;
    private $cityRepository;

    public function __construct(EntityManagerInterface $em, CityRepository $cityRepository)
    {
        parent::__construct();
        $this->em = $em;
        $this->cityRepository = $cityRepository;
    }

    protected function configure()
    {
        $this
            ->setDescription('Set the average temperature per month')
            ->addArgument('month', InputArgument::REQUIRED, 'The month')
            ->addArgument('year', InputArgument::REQUIRED, 'The year');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $month = $input->getArgument('month');
        $year = $input->getArgument('year');


        if ($month !== null && $year !== null) {
            $io->note(sprintf('You passed an argument: %s', $month));
            $io->note(sprintf('You passed an argument: %s', $year));
            $cities = $this->cityRepository->findAll();
            foreach ($cities as $objectCity) {
                //dd($cities);
                try {
                    $city = $objectCity->getCityName();
                    $io->note(sprintf('city =>: %s', $city));

                    $cityName = str_replace(' ', '%2', $city);
                    $url = 'http://api.worldweatheronline.com/premium/v1/past-weather.ashx';
                    $urlWeatherQuery = $url . '?key=' . $_ENV['API_KEY_WEATHER'] . '&q=' . $cityName . '&format=json&date=' . $year . '-' . $month . '-01&enddate=' . $year . '-' . $month . '-28&lang=fr';
                    $jsonStringRangeTemp = file_get_contents($urlWeatherQuery);
                    $objectResponseRangeTemp = json_decode($jsonStringRangeTemp);
                    $arrayTempByMonth = $objectResponseRangeTemp->data->weather;

                    $arrayMinTemp = [];
                    $arrayMaxTemp = [];
                    foreach ($arrayTempByMonth as $value) {
                        //$value->date,
                        $arrayMinTemp[] = $value->mintempC;
                        $arrayMaxTemp[] = $value->maxtempC;
                    }
                    $averageMinByMonth = (int) ceil(array_sum($arrayMinTemp) / count($arrayMinTemp));
                    $averageMaxByMonth = (int) ceil(array_sum($arrayMaxTemp) / count($arrayMaxTemp));

                    dump($averageMinByMonth);
                    dump($averageMaxByMonth);

                    $weather = new Weather();

                    switch (true) {
                        case ($averageMinByMonth < 0):
                            $weather->setTempLevelMin(0);
                            break;
                        case ($averageMinByMonth >= 0 && $averageMinByMonth < 5):
                            $weather->setTempLevelMin(1);
                            break;
                        case ($averageMinByMonth >= 5 && $averageMinByMonth < 10):
                            $weather->setTempLevelMin(2);
                            break;
                        case ($averageMinByMonth >= 10 && $averageMinByMonth < 15):
                            $weather->setTempLevelMin(3);
                            break;
                        case ($averageMinByMonth >= 15 && $averageMinByMonth < 20):
                            $weather->setTempLevelMin(4);
                            break;
                        case ($averageMinByMonth >= 20 && $averageMinByMonth < 25):
                            $weather->setTempLevelMin(5);
                            break;
                        case ($averageMinByMonth >= 25 && $averageMinByMonth < 30):
                            $weather->setTempLevelMin(6);
                            break;
                        case ($averageMinByMonth >= 30):
                            $weather->setTempLevelMin(7);
                            break;
                    }

                    switch (true) {
                        case ($averageMaxByMonth < 0):
                            $weather->setTempLevelMax(0);
                            break;
                        case ($averageMaxByMonth >= 0 && $averageMaxByMonth < 5):
                            $weather->setTempLevelMax(1);
                            break;
                        case ($averageMaxByMonth >= 5 && $averageMaxByMonth < 10):
                            $weather->setTempLevelMax(2);
                            break;
                        case ($averageMaxByMonth >= 10 && $averageMaxByMonth < 15):
                            $weather->setTempLevelMax(3);
                            break;
                        case ($averageMaxByMonth >= 15 && $averageMaxByMonth < 20):
                            $weather->setTempLevelMax(4);
                            break;
                        case ($averageMaxByMonth >= 20 && $averageMaxByMonth < 25):
                            $weather->setTempLevelMax(5);
                            break;
                        case ($averageMaxByMonth >= 25 && $averageMaxByMonth < 30):
                            $weather->setTempLevelMax(6);
                            break;
                        case ($averageMaxByMonth >= 30):
                            $weather->setTempLevelMax(7);
                            break;
                    }

                    $weather->setAverageMin($averageMinByMonth);
                    $weather->setAverageMax($averageMaxByMonth);
                    $weather->setMonth($month);
                    $weather->setYear($year);
                    $weather->setCreatedAt(new \DateTime);
                    $weather->setUpdatedAt(new \DateTime);
                    $weather->setCity($objectCity);
                    $this->em->persist($weather);
                    $this->em->flush();
                } catch (Exception $e) {
                    echo 'Caught exception: ',  $e->getMessage(), "\n";
                }
            }
        } else {
            $io->error('You must give all the command arguments.');
            return 1;
        }

        $io->success('Weather upgrade. Pass --help to see your options.');

        return 0;
    }
}
