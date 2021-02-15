<?php

namespace App\Service;

use App\Entity\City;
use App\Repository\CityRepository;
use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;

class SearchMatchTag
{

    public function match(array $labels)
    {
        $tagsId = [];
        foreach ($labels as $label) {
            if (is_array($label)) {
                foreach ($label as $value) {
                    $tagsId[] = $value;
                }
            } else {
                $tagsId[] = $label;
            }
        }

        return $tagsId;

    }
}
