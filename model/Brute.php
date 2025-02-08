<?php

class Brute extends Personnage
{
    public function addExperience($type)
    {
        if ($type == 'frapper')
        {
            $this->experience += 5;
        }
        if ($type == 'tuer')
        {
            $this->experience += 20;
        }
    }

    public function recevoirDegats(Personnage $perso)
    {
        $level = $perso->level();
        $degats = mt_rand($level,4*$level/3);
        $degats_final = (4 > $degats) ? 4 : $degats;
        $this->vie -= $degats_final;
        
        if ($this->vie <= 0)
        {
            return array(self::PERSONNAGE_TUE,$degats_final);
        }
        return array(self::PERSONNAGE_FRAPPE,$degats_final);
    }
}