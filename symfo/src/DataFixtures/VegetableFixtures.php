<?php
namespace App\DataFixtures;


use DateInterval;
use App\Entity\Vegetable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;




class VegetableFixtures extends Fixture{

    public function load(ObjectManager $manager)
    {
         
         $citron = new Vegetable();
         $citron->setName('citron');
         $water_irrigation_interval_Citron = new DateInterval('P1D');
         //$citron->setWaterIrrigationInterval($water_irrigation_interval_Citron);
         //$citron->setGrowingInterval('P1D');
         $citron->setImage('images/citron.svg');
         $manager->persist($citron);

         $kiwi = new Vegetable();
         $kiwi->setName('kiwi');
         //$kiwi->setWaterIrrigationInterval('P1D');
         //$kiwi->setGrowingInterval('P1D');
         $manager->persist($kiwi);

         $mandarine = new Vegetable();
         $mandarine->setName('mandarine');
         //$mandarine->setWaterIrrigationInterval('P1D');
         //$mandarine->setGrowingInterval('P1D');
         $manager->persist($mandarine);

         $clémentine = new Vegetable();
         $clémentine->setName('clémentine');
         //$clémentine->setWaterIrrigationInterval('P1D');
         //$clémentine->setGrowingInterval('P1D');
         $manager->persist($clémentine);

         $orange = new Vegetable();
         $orange->setName('orange');
         //$orange->setWaterIrrigationInterval('P1D');
         //$orange->setGrowingInterval('P1D');
         $manager->persist($orange);

         $pamplemousse = new Vegetable();
         $pamplemousse->setName('pamplemousse');
         //$pamplemousse->setWaterIrrigationInterval('P1D');
         //$pamplemousse->setGrowingInterval('P1D');
         $manager->persist($pamplemousse);

         $poire = new Vegetable();
         $poire->setName('poire');
         //$poire->setWaterIrrigationInterval('P1D');
         //$poire->setGrowingInterval('P1D');
         $manager->persist($poire);

         $pomme = new Vegetable();
         $pomme->setName('pomme');
         //$pomme->setWaterIrrigationInterval('P1D');
         //$pomme->setGrowingInterval('P1D');
         $manager->persist($pomme);
         
         $rhubarbe = new Vegetable();
         $rhubarbe->setName('rhubarbe');
         //$rhubarbe->setWaterIrrigationInterval('P1D');
         //$rhubarbe->setGrowingInterval('P1D');
         $manager->persist($rhubarbe);

         $fraise = new Vegetable();
         $fraise->setName('fraise');
         //$fraise->setWaterIrrigationInterval('P1D');
         //$fraise->setGrowingInterval('P1D');
         $manager->persist($fraise);

         $framboise = new Vegetable();
         $framboise->setName('framboise');
         //$framboise->setWaterIrrigationInterval('P1D');
         //$framboise->setGrowingInterval('P1D');
         $manager->persist($framboise);

         $cerise = new Vegetable();
         $cerise->setName('cerise');
         //$cerise->setWaterIrrigationInterval('P1D');
         //$cerise->setGrowingInterval('P1D');
         $manager->persist($cerise);

         $abricot = new Vegetable();
         $abricot->setName('abricot');
         //$abricot->setWaterIrrigationInterval('P1D');
         //$abricot->setGrowingInterval('P1D');
         $manager->persist($abricot);

         $cassis = new Vegetable();
         $cassis->setName('cassis');
         //$cassis->setWaterIrrigationInterval('P1D');
         //$cassis->setGrowingInterval('P1D');
         $manager->persist($cassis);

         $groseille = new Vegetable();
         $groseille->setName('groseille');
         //$groseille->setWaterIrrigationInterval('P1D');
         //$groseille->setGrowingInterval('P1D');
         $manager->persist($groseille);

         $melon = new Vegetable();
         $melon->setName('melon');
         //$melon->setWaterIrrigationInterval('P1D');
         //$melon->setGrowingInterval('P1D');
         $manager->persist($melon);

         $figue = new Vegetable();
         $figue->setName('figue');
         //$figue->setWaterIrrigationInterval('P1D');
         //$figue->setGrowingInterval('P1D');
         $manager->persist($figue);

         $prune = new Vegetable();
         $prune->setName('prune');
         //$prune->setWaterIrrigationInterval('P1D');
         //$prune->setGrowingInterval('P1D');
         $manager->persist($prune);

         $mûre = new Vegetable();
         $mûre->setName('mûre');
         //$mûre->setWaterIrrigationInterval('P1D');
         //$mûre->setGrowingInterval('P1D');
         $manager->persist($mûre);

         $myrtille = new Vegetable();
         $myrtille->setName('myrtille');
         //$myrtille->setWaterIrrigationInterval('P1D');
         //$myrtille->setGrowingInterval('P1D');
         $manager->persist($myrtille);

         $pastèque = new Vegetable();
         $pastèque->setName('pastèque');
         //$pastèque->setWaterIrrigationInterval('P1D');
         //$pastèque->setGrowingInterval('P1D');
         $manager->persist($pastèque);

         $pêche = new Vegetable();
         $pêche->setName('pêche');
         //$pêche->setWaterIrrigationInterval('P1D');
         //$pêche->setGrowingInterval('P1D');
         $manager->persist($pêche);

         $raisin = new Vegetable();
         $raisin->setName('raisin');
         //$raisin->setWaterIrrigationInterval('P1D');
         //$raisin->setGrowingInterval('P1D');
         $manager->persist($raisin);

         $coing = new Vegetable();
         $coing->setName('coing');
         //$coing->setWaterIrrigationInterval('P1D');
         //$coing->setGrowingInterval('P1D');
         $manager->persist($coing);

         $avocat = new Vegetable();
         $avocat->setName('avocat');
         //$avocat->setWaterIrrigationInterval('P1D');
         //$avocat->setGrowingInterval('P1D');
         $manager->persist($avocat);

         $ail = new Vegetable();
         $ail->setName('ail');
         //$ail->setWaterIrrigationInterval('P1D');
         //$ail->setGrowingInterval('P1D');
         $manager->persist($ail);
    

         $manager->flush();
        }
}