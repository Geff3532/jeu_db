<?php
class Magicien extends Personnage
{
    public function lancerUnSort(Personnage $perso,$tour)
    {
        $vie = $this->vie;
        $vie_max = $this->vie_max;
        if ($vie >= 0 && $vie <= 0.2*$vie_max)
        {
            $this->atout = 3;
        }
        elseif ($vie > 0.2*$vie_max && $vie <= 0.4*$vie_max)
        {
            $this->atout = 2;
        }
        elseif ($vie > 0.4*$vie_max && $vie <= 0.6*$vie_max)
        {
            $this->atout = 1;
        }
        else
        {
            $this->atout = 0;
        }
        
        if ($perso->id == $this->id)
        {
            return array(self::CEST_MOI,null);
        }
        
        if ($this->atout == 0)
        {
            return array(self::PAS_DE_MAGIE,null);
        }
        
        if ($this->estEndormi($tour))
        {
            return array(self::PERSO_ENDORMI,null);
        }
        $this->timePower = $tour + 3;
        $perso->timeEndormi = $tour + $this->atout;
        return array(self::PERSONNAGE_ENSORCELE,$this->atout);
    }

    public function addExperience($type)
    {
        if ($type == 'frapper')
        {
            $this->experience += 5;
        }
        if ($type == 'ensorceler')
        {
            $this->experience += 10;
        }
        if ($type == 'tuer')
        {
            $this->experience += 20;
        }
    }
}