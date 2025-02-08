<?php
abstract class Personnage
{
  protected $atout,
            $level,
            $nom_team,
            $experience,
            $vie,
            $vie_max,
            $id,
            $id_partie,
            $nom,
            $timeEndormi,
            $timePower,
            $tour_frapper,
            $type;
  
  const CEST_MOI = 1; // Constante renvoyée par la méthode `frapper` si on se frappe soit-même.
  const PERSONNAGE_TUE = 2; // Constante renvoyée par la méthode `frapper` si on a tué le personnage en le frappant.
  const PERSONNAGE_FRAPPE = 3; // Constante renvoyée par la méthode `frapper` si on a bien frappé le personnage.
  const PERSONNAGE_ENSORCELE = 4; // Constante renvoyée par la méthode `lancerUnSort` (voir classe Magicien) si on a bien ensorcelé un personnage.
  const PAS_DE_MAGIE = 5; // Constante renvoyée par la méthode `lancerUnSort` (voir classe Magicien) si on veut jeter un sort alors que la magie du magicien est à 0.
  const PERSO_ENDORMI = 6; // Constante renvoyée par la méthode `frapper` si le personnage qui veut frapper est endormi.
  const PARADE = 7; //Constante renvoyé par la methode frapper (voir classe Guerrier)
  const SOIGNER = 8; // Constante renvoyer par l'enchanteur
  const VIE_MAX = 9; // Constante renvoyer quand la vie du perso est déja au max
  const LEVEL_UP = 10; // Constante envoyer quand le personnage monte de level

  public function __construct(array $donnees)
  {
    $this->hydrate($donnees);
    $this->type = strtolower(static::class);
  }
  
  public function estEndormi($tour)
  {
    return $this->timeEndormi > $tour;
  }

  public function recevoirSoin($level)
  {
    $heal = (int) round(mt_rand(9.5*$level/3,10.5*$level/3));
    $heal = (10 > $heal) ? 10 : $heal;
    $this->vie += $heal;
    if ($this->vie >= $this->vie_max)
    {
      $this->vie = $this->vie_max;
      return array(self::VIE_MAX,$heal);
    }
    return array(self::SOIGNER,$heal);
  }

  public function upExperience($categorie)
  {
      $this->addExperience($categorie);
      if (5000 - (100-$this->level)*$this->experience <= 0)
      {
          $this->experience = 0;
          $this->level += 1;
          $add = (int) round(0.1 * $this->vie_max);
          $add = (25 >= $add) ? $add : 25;
          $this->vie += ($add >= 10) ? 10 : $add;
          $this->vie_max += $add;
          return self::LEVEL_UP;
      }
  }
  
  public function frapper(Personnage $perso,$tour)
  {
    if ($perso->id == $this->id)
    {
      return array(self::CEST_MOI,null);
    }
    
    if ($this->estEndormi($tour))
    {
      return array(self::PERSO_ENDORMI,null);
    }
    $this->tour_frapper = $tour + 1;
    return $perso->recevoirDegats($this);
  }
  
  public function hydrate(array $donnees)
  {
    foreach ($donnees as $key => $value)
    {
      $method = 'set'.ucfirst($key);
      
      if (method_exists($this, $method))
      {
        $this->$method($value);
      }
    }
  }
  
  public function nomValide()
  {
    return !empty($this->nom);
  }
  
  public function recevoirDegats(Personnage $perso)
  {
    $level = $perso->level();
    $degats = mt_rand(4*$level/3,5*$level/3);
    $degats = (5 > $degats) ? 5 : $degats;
    $this->vie -= $degats;
    
    if ($this->vie <= 0)
    {
      return array(self::PERSONNAGE_TUE,$degats);
    }
    
    return array(self::PERSONNAGE_FRAPPE,$degats);
  }

  public function found_atout()
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
  }

  public function powerAvailable($tour)
  {
    return $tour >= $this->timePower;
  }

  public function frapperAvailable($tour)
  {
    return $tour >= $this->tour_frapper;
  }

  public function isAlive()
  {
    return $this->vie > 0;
  }

  public function reveil()
  { 
    return 'Reveil au tour '.$this->timeEndormi;
  }

  public function tour_frapper()
  {
    return $this->tour_frapper;
  }

  public function timePower()
  {
    return $this->timePower;
  }

  public function nom_team()
  {
    return $this->nom_team;
  }

  public function id_partie()
  {
    return $this->id_partie;
  }

  public function experience()
  {
    return $this->experience;
  }

  public function level()
  {
    return $this->level;
  }

  public function vie_max()
  {
    return $this->vie_max;
  }
  
  public function atout()
  {
    return $this->atout;
  }
  
  public function vie()
  {
    return $this->vie;
  }
  
  public function id()
  {
    return $this->id;
  }
  
  public function nom()
  {
    return $this->nom;
  }
  
  public function timeEndormi()
  {
    return $this->timeEndormi;
  }
  
  public function type()
  {
    return $this->type;
  }

  public function setTour_frapper($frapper)
  {
    $this->tour_frapper = (int) $frapper;
  }

  public function setTimePower($tour)
  {
    $this->timePower = (int) $tour;
  }

  public function setNom_team($nom)
  {
    if (is_string($nom))
    {
      $this->nom_team = $nom;
    }
  }

  public function setId_partie($id)
  {
    if (is_string($id))
    {
      $this->id_partie = $id;
    }
  }

  public function setExperience($experience)
  {
    $experience = (int) $experience;
    if ($experience >= 0)
    {
      $this->experience = $experience;
    }
  }

  public function setLevel($level)
  {
    $level = (int) $level;
    if ($level > 0 && $level <= 100)
    {
      $this->level = $level;
    }
  }

  public function setVie_max($vie_max)
  {
    $vie_max = (int) $vie_max;
    if ($vie_max > 0)
    {
      $this->vie_max = $vie_max;
    }
  }
  
  public function setAtout($atout)
  {
    $atout = (int) $atout;
    
    if ($atout >= 0)
    {
      $this->atout = $atout;
    }
  }
  
  public function setVie($vie)
  {
    $vie = (int) $vie;
    
    if ($vie >= 0)
    {
      $this->vie = $vie;
    }
  }
  
  public function setId($id)
  {
    $id = (int) $id;
    
    if ($id > 0)
    {
      $this->id = $id;
    }
  }
  
  public function setNom($nom)
  {
    if (is_string($nom))
    {
      $this->nom = $nom;
    }
  }
  
  public function setTimeEndormi($time)
  {
    $this->timeEndormi = (int) $time;
  }
}