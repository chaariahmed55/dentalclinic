<?php
//php bin/console doctrine:fixtures:load
namespace App\DataFixtures;

use App\Entity\BonCommande;
use App\Entity\BonCommandeDetail;
use App\Entity\Equipement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BoncommandeFixures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        #region Create 30 equipement

        //used code
        $codelist = array();
        //add 30 equipement
        for ($count = 0; $count < 30; $count++) {
            $equips = new Equipement();
            $randstring = $this->gRandString();
            if(in_array($randstring, $codelist))
            {
                $count--;
                continue;
            }
            array_push($codelist, $randstring);

            $equips->setcequipement($randstring);
            $equips->setlibequipement('Libelle '.$randstring );
            $equips->setquantite( mt_rand(1, 100));
            $equips->setdescription($randstring.' On sait depuis longtemps que travailler avec du texte lisible et contenant du sens est source de distractions.');
            $equips->setimageurl('');
            $equips->setdate( (new \DateTime('now')));
            $manager->persist($equips);
        }

        $manager->flush();

        #endregion

        #region insert 50 Bon Commande

        for ($count = 0; $count < 50; $count++) {

            $bonc = new BonCommande();
            $bonc->setdateboncommandedate( $this->randomDate('2021-01-01','2021-07-29') );
            $rn = mt_rand(1, 50);
            $cf = '21/000'.( $rn < 10 ? '0'.$rn : $rn);
            $bonc->setcfournisseur($cf); 
            $bonc->setraisonsocialefournisseur('Distributeur '.$rn); 
            $bonc->setmontant(0);
            $input = array("EN ATTENTE", "VALIDER", "ANNULER");
            $rand_keys = array_rand($input);            
            $bonc->setetat($input[$rand_keys]);
            $bonc->setbvalid(( $rand_keys === 1 ? true : false ));
            
            $manager->persist($bonc);
            $manager->flush();

            $clonecodelist = $codelist;

            //create random Boncommande details
            $detcount = mt_rand(3, 10);
            $montant = 0;
            for ($countd = 0; $countd < $detcount; $countd++) {
                $detail = new BonCommandeDetail();

                $detail->setnboncommande($bonc->getnboncommande());
                $detail->setordre($countd+1);

                $randCode_keys = array_rand($clonecodelist);
                $codevalue = $clonecodelist[$randCode_keys];
                $detail->setcequipement($codevalue);
                $detail->setlibequipement('Libelle '.$codevalue);
                array_splice($clonecodelist, $randCode_keys, 1);
                $detail->setquantite(mt_rand(1, 20));
                $prix = (mt_rand(1*1000, 200*1000) / 1000);
                $detail->setprix($prix);
                $montant+=$prix;
                $manager->persist($detail);
            }

            $manager->flush();

            $bonc->setmontant($montant);
            $manager->persist($bonc);
            $manager->flush();
        }


    }

    private function gRandString($length = 10) {
        return substr(str_shuffle(str_repeat($x='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }

    private function randomDate($sStartDate, $sEndDate, $sFormat = 'Y-m-d') {
        // Convert the supplied date to timestamp
        $fMin = strtotime($sStartDate);
        $fMax = strtotime($sEndDate);
        // Generate a random number from the start and end dates
        $fVal = mt_rand($fMin, $fMax);
        // Convert back to the specified date format
        return new \DateTime(date($sFormat, $fVal));
    }
}
