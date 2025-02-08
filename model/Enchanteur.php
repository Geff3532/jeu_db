<?php

class Enchanteur extends Personnage
{
    public function guerison(Personnage $perso,$tour)
    {
        if ($this->estEndormi($tour))
        {
            return array(self::PERSO_ENDORMI,null);
        }
        $this->timePower = $tour + 3;
        return $perso->recevoirSoin($this->level);
    }

    public function addExperience($type)
    {
        if ($type == 'frapper')
        {
            $this->experience += 5;
        }
        if ($type == "guerir")
        {
            $this->experience += 10;
        }
        if ($type == 'tuer')
        {
            $this->experience += 20;
        }
    }
}