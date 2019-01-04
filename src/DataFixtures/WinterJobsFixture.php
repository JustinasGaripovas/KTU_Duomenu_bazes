<?php

namespace App\DataFixtures;

use App\Entity\Mechanism;
use App\Entity\WinterJobRoadSection;
use App\Entity\WinterJobs;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class WinterJobsFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {


        $manager->clear();

        for ($i = 0; $i<500;$i++)
        {
            for ($j = 0; $j<80;$j++)
            {
                 $product = new WinterJobs();

                $product->setSubunitName("Kaunas");
                $product->setSubunit($j);
                $product->setCreatedBy("dais.TEST");
                $product->setCreatedAt(new \DateTime('now'));
                $product->setDate(new \DateTime('now'));
                $product->setJob("Valymas");
                $product->setRoadSectionSearch("string");
                $product->setTimeFrom(new \DateTime());
                $product->setTimeTo(new \DateTime());

                $mech = new Mechanism();
                $roadSec = new WinterJobRoadSection();


                $mech->setSubunit($j);
                $mech->setNumber("CAR NUMBER");
                $random =0;
                try {
                    $random = random_int(0, 3);
                } catch (\Exception $e) {
                }

                switch ($random)
                {
                    case 0:
                        $roadSec->setSectionId("A1");

                        $mech->setType("Kiti");
                        break;

                    case 1:
                        $mech->setType("Sunkvežimis");
                        $roadSec->setSectionId("3535");

                        break;

                    case 2:
                        $mech->setType("Autogreideris");
                        $roadSec->setSectionId("8000");

                        break;

                    case 3:
                        $mech->setType("Traktorius");
                        $roadSec->setSectionId("B8");

                        break;
                }

                $mech->setTypeId(1);

                $product->setMechanism($mech->getType());

                $roadSec->setRoadSectionSearch("empty");
                $roadSec->setLevel(1);
                $roadSec->setSaltChecked(true);
                $roadSec->setSaltValue($i);
                $roadSec->setSandChecked(true);
                $roadSec->setSandValue($j);
                $roadSec->setSectionBegin(1);
                $roadSec->setSectionEnd(5);
                $roadSec->setSectionName("secName");
                $roadSec->setSolutionChecked(true);
                $roadSec->setSolutionValue(1);

                $product->setRoadSections(array($roadSec));



                $manager->persist($product);

            }

            $manager->flush();
            $manager->clear();

        }


        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
