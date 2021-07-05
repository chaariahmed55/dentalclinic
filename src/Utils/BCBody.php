<?php 

namespace App\Utils;

use App\Entity\BonCommande;

class BCBody {

    private $entete;

    private $detail;

    public function getentete()
    {
        return $this->entete;
    }

    public function setentete( $entete): self
    {
        $this->entete = $entete;

        return $this;
    }

    public function getdetail()
    {
        return $this->detail;
    }

    public function setdetail($detail): self
    {
        $this->detail = $detail;

        return $this;
    }

}

?>