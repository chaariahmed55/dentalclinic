<?php 

namespace App\Utils;

use phpDocumentor\Reflection\Types\Integer;

class BCLineChart{
    private $mn;

    private $sum;

    public function getSum()
    {
        return (float) $this->sum;
    }
 
    public function setSum($sum)
    {
        $this->sum = (float)$sum;

        return $this;
    }

    public function getMn()
    {
        return (integer)$this->mn;
    }

    public function setMn($mn)
    {
        $this->mn = (integer)$mn;

        return $this;
    }
}

?>