<?php

class PersonnagesManager
{
  private $db; 

  public function __construct()
  {
    $this->db = new PDO('mysql:host=localhost;dbname=test', 'root', '');
  }
  
  public function add(Personnage $perso)
  {
    $q = $this->db->prepare('INSERT INTO personnages_v2(nom_team, nom, type,vie, vie_max) VALUES(:nom_team, :nom, :type, :vie, :vie_max)');
    $q->bindValue(':nom_team',$perso->nom_team());
    $q->bindValue(':nom', $perso->nom());
    $q->bindValue(':type', $perso->type());
    switch ($perso->type())
    {
      case 'guerrier': $vie = 90;break;
      case 'magicien': $vie = 100;break;
      case 'brute': $vie = 150;break;
      case 'enchanteur': $vie = 120;break;
    }
    $q->bindValue(':vie', $vie);
    $q->bindValue(':vie_max', $vie);
    $q->execute();
  }

  public function add_bot(Personnage $perso)
  {
    $q = $this->db->prepare('INSERT INTO personnages_bot(id_partie, nom, type, level, vie, vie_max) VALUES(:id_partie, :nom, :type, :level, :vie, :vie_max)');
    
    $q->bindValue(':nom', $perso->nom());
    $q->bindValue('id_partie',$perso->id_partie());
    $q->bindValue(':type', $perso->type());
    $q->bindValue(':level', $perso->level());
    $q->bindValue(':vie', $perso->vie());
    $q->bindValue(':vie_max', $perso->vie_max());
    
    $q->execute();
  }
  
  public function count()
  {
    return $this->db->query('SELECT COUNT(*) FROM personnages_v2')->fetchColumn();
  }
  
  public function delete(Personnage $perso)
  {
    $this->db->exec('DELETE FROM personnages_v2 WHERE id = '.$perso->id());
  }

  public function delete_bot(Personnage $perso)
  {
    $this->db->exec('DELETE FROM personnages_bot WHERE id = '.$perso->id());
  }
  
  public function exists($info)
  {
    if (is_int($info)) // On veut voir si tel personnage ayant pour id $info existe.
    {
      return (bool) $this->db->query('SELECT COUNT(*) FROM personnages_v2 WHERE id = '.$info)->fetchColumn();
    }
    
    $q = $this->db->prepare('SELECT COUNT(*) FROM personnages_v2 WHERE nom_team = :nom_team');
    $q->execute([':nom_team' => $info]);
    
    return (bool) $q->fetchColumn();
  }

  public function exists_bot($info)
  {
    if (is_int($info)) // On veut voir si tel personnage ayant pour id $info existe.
    {
      return (bool) $this->db->query('SELECT COUNT(*) FROM personnages_bot WHERE id = '.$info)->fetchColumn();
    }
    
    $q = $this->db->prepare('SELECT COUNT(*) FROM personnages_bot WHERE nom = :nom');
    $q->execute([':nom' => $info]);
    
    return (bool) $q->fetchColumn();
  }
  
  public function get($info)
  {
    if (is_int($info))
    {
      $q = $this->db->prepare('SELECT id, nom, vie, vie_max, level, experience, type, atout, timeEndormi, timePower, tour_frapper FROM personnages_v2 WHERE id = :id');
      $q->execute([':id' => $info]);
      $perso = $q->fetch(PDO::FETCH_ASSOC);
    }
    
    else
    {
      $q = $this->db->prepare('SELECT id, nom, vie, vie_max, level, experience, type, atout, timeEndormi, timePower, tour_frapper FROM personnages_v2 WHERE nom = :nom');
      $q->execute([':nom' => $info]);
      $perso = $q->fetch(PDO::FETCH_ASSOC);
    }
    
    switch ($perso['type'])
    {
      case 'guerrier': return new Guerrier($perso);
      case 'magicien': return new Magicien($perso);
      case 'brute': return new Brute($perso);
      case 'enchanteur': return new Enchanteur($perso);
      default: return null;
    }
  }

  public function get_bot($info)
  {
    if (is_int($info))
    {
      $q = $this->db->prepare('SELECT id, nom, vie, vie_max, level, experience, type, atout, timeEndormi, timePower, tour_frapper FROM personnages_bot WHERE id = :id');
      $q->execute([':id' => $info]);
      $perso = $q->fetch(PDO::FETCH_ASSOC);
    }
    
    else
    {
      $q = $this->db->prepare('SELECT id, nom, vie, vie_max, level, experience, type, atout, timeEndormi, timePower, tour_frapper FROM personnages_bot WHERE nom = :nom');
      $q->execute([':nom' => $info]);
      $perso = $q->fetch(PDO::FETCH_ASSOC);
    }
    
    switch ($perso['type'])
    {
      case 'guerrier': return new Guerrier($perso);
      case 'magicien': return new Magicien($perso);
      case 'brute': return new Brute($perso);
      case 'enchanteur': return new Enchanteur($perso);
      default: return null;
    }
  }
  
  public function getList($nom,$alive) //Si $alive == true on selectionne que les perso vivants de la team sinon tous
  {
    $persos = [];
    $q = $this->db->prepare('SELECT id, nom_team, nom, vie, vie_max, timeEndormi, level, experience, type, atout, timeEndormi, timePower, tour_frapper FROM personnages_v2 WHERE nom_team = :nom_team ORDER BY nom_team');
    $q->execute([':nom_team' => $nom]);
    
    while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
    {
      if ($alive)
      {
        if ($donnees['vie'] > 0)
        {
          switch ($donnees['type'])
          {
            case 'guerrier': $persos[] = new Guerrier($donnees); break;
            case 'magicien': $persos[] = new Magicien($donnees); break;
            case 'brute': $persos[] = new Brute($donnees); break;
            case 'enchanteur': $persos[] = new Enchanteur($donnees); break;
          }
        }
      }
      else
      {
        switch ($donnees['type'])
        {
          case 'guerrier': $persos[] = new Guerrier($donnees); break;
          case 'magicien': $persos[] = new Magicien($donnees); break;
          case 'brute': $persos[] = new Brute($donnees); break;
          case 'enchanteur': $persos[] = new Enchanteur($donnees); break;
        }
      }
    }
    
    return $persos;
  }

  public function getList_bot($id)
  {
    $persos = [];
    $q = $this->db->prepare('SELECT id, nom, vie, vie_max, level, experience, type, atout, timeEndormi, timePower, tour_frapper FROM personnages_bot WHERE id_partie = :id_partie ORDER BY nom');
    $q->execute([':id_partie' => $id]);
    
    while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
    {
      switch ($donnees['type'])
      {
        case 'guerrier': $persos[] = new Guerrier($donnees); break;
        case 'magicien': $persos[] = new Magicien($donnees); break;
        case 'brute': $persos[] = new Brute($donnees); break;
        case 'enchanteur': $persos[] = new Enchanteur($donnees); break;
      }
    }
    
    return $persos;
  }
  
  public function update(Personnage $perso)
  {
    $q = $this->db->prepare('UPDATE personnages_v2 SET vie = :vie, vie_max = :vie_max, experience = :experience, level = :level, timeEndormi = :timeEndormi, atout = :atout, timePower = :timePower, tour_frapper = :tour_frapper WHERE id = :id');
    
    $q->bindValue(':vie', $perso->vie(), PDO::PARAM_INT);
    $q->bindValue(':timeEndormi', $perso->timeEndormi(), PDO::PARAM_INT);
    $q->bindValue(':atout', $perso->atout(), PDO::PARAM_INT);
    $q->bindValue(':id', $perso->id(), PDO::PARAM_INT);
    $q->bindValue(':vie_max', $perso->vie_max(), PDO::PARAM_INT);
    $q->bindValue(':experience', $perso->experience(), PDO::PARAM_INT);
    $q->bindValue(':level', $perso->level(), PDO::PARAM_INT);
    $q->bindValue(':timePower', $perso->timePower(), PDO::PARAM_INT);
    $q->bindValue(':tour_frapper', $perso->tour_frapper(), PDO::PARAM_INT);
    
    $q->execute();
  }

  public function update_bot(Personnage $perso)
    {
      $q = $this->db->prepare('UPDATE personnages_bot SET vie = :vie, vie_max = :vie_max, experience = :experience, level = :level, atout = :atout, timeEndormi = :timeEndormi, timePower = :timePower, tour_frapper = :tour_frapper WHERE id = :id');
      
      $q->bindValue(':vie', $perso->vie(), PDO::PARAM_INT);
      $q->bindValue(':timeEndormi', $perso->timeEndormi(), PDO::PARAM_INT);
      $q->bindValue(':atout', $perso->atout(), PDO::PARAM_INT);
      $q->bindValue(':id', $perso->id(), PDO::PARAM_INT);
      $q->bindValue(':vie_max', $perso->vie_max(), PDO::PARAM_INT);
      $q->bindValue(':experience', $perso->experience(), PDO::PARAM_INT);
      $q->bindValue(':level', $perso->level(), PDO::PARAM_INT);
      $q->bindValue(':timePower', $perso->timePower(), PDO::PARAM_INT);
      $q->bindValue(':tour_frapper', $perso->tour_frapper(), PDO::PARAM_INT);
      
      $q->execute();
    }
}