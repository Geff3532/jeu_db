<?php
class Guerrier extends Personnage
{
  public function recevoirDegats(Personnage $perso)
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

    $level = $perso->level();
    $dim_atout = 5 - $this->atout;
    $degats = mt_rand(($dim_atout-1)*$level/3,$dim_atout*$level/3);
    $degats_final = ($dim_atout > $degats) ? $dim_atout : $degats;

    $parade = mt_rand(0,99);
    if ($parade <= 10+$level/4)
    {
        return array(self::PARADE,$degats_final);
    }

    $this->vie -= $degats_final;

    if ($this->vie <= 0)
    {
      return array(self::PERSONNAGE_TUE,$degats_final);
    }
    
    // Sinon, on se contente de mettre à jour les dégâts du personnage.
    return array(self::PERSONNAGE_FRAPPE,$degats_final);
  }

    public function addExperience($type)
    {
        if ($type == 'frapper')
        {
            $this->experience += 5;
        }
        if ($type == 'parade')
        {
            $this->experience += 10;
        }
        if ($type == 'tuer')
        {
            $this->experience += 20;
        }
    }
}