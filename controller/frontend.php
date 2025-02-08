<?php

require_once('model/Personnage.php');
require_once('model/Brute.php');
require_once('model/Enchanteur.php');
require_once('model/Guerrier.php');
require_once('model/Magicien.php');
require_once('model/PersonnagesManager.php');

function creer($team,$type,$nom)
{
    $manager = new PersonnagesManager();
    switch ($type)
    {
        case 'magicien' :
        $perso = new Magicien(['nom' => $nom, 'nom_team' => $team]);
        break;
        
        case 'guerrier' :
        $perso = new Guerrier(['nom' => $nom, 'nom_team' => $team]);
        break;
        
        case 'brute' :
        $perso = new Brute(['nom' => $nom, 'nom_team' => $team]);
        break;
        
        case 'enchanteur' :
        $perso = new Enchanteur(['nom' => $nom, 'nom_team' => $team]);
        break;
        
        default :
        $message = 'Le type du personnage est invalide.';
        break;
    }
  
    if (isset($perso)) // Si le type du personnage est valide, on a créé un personnage.
    {
        if (!$perso->nomValide())
        {
            $message = 'Le nom choisi est invalide.';
            unset($perso);
            require_once('view/connectionView.php');
        }
        elseif ($manager->exists($perso->nom()))
        {
            $message = 'Le nom du personnage est déjà pris.';
            unset($perso);
            require_once('view/connectionView.php');
        }
        else
        {
            $manager->add($perso);
        }
    }
}

function utiliser($nom,$level)
{
    $manager = new PersonnagesManager();
    if ($manager->exists($nom)) 
    {
        $_SESSION['team'] = $nom;
        $id = generate_id_partie();
        $_SESSION['id'] = $id;
        $_SESSION['tour'] = 1;
        for ($i=0;$i<3;$i++)
        {
            generate($level,$id);
        }
        header('Location: .');
    }
    else
    {
        $message = 'Cette team n\'existe pas !'; 
        require_once('view/connectionView.php');
    }
}

function ensorceler($id,$idEnsorceler)
{   
    $message = "";
    $manager = new PersonnagesManager();
    $perso = $manager->get($id);
    if (!isset($perso))
    {
        $message .= 'Merci de créer un personnage ou de vous identifier.<br>';
    }
    else
    {
        if ($perso->type() != 'magicien')
        {
            $message .= 'Seuls les magiciens peuvent ensorceler des personnages !<br>';
        }
        else
        {
            if (!$manager->exists_bot((int) $idEnsorceler))
            {
                $message .= 'Le personnage que vous voulez frapper n\'existe pas !<br>';
            }
            else
            {
                $persoAEnsorceler = $manager->get_bot((int) $idEnsorceler);
                list($retour,$degats) = $perso->lancerUnSort($persoAEnsorceler,$_SESSION['tour']);
                switch ($retour)
                {
                    case Personnage::CEST_MOI :
                        $message .= 'Mais... pourquoi voulez-vous vous ensorceler ???<br>';
                        break;
                    
                    case Personnage::PERSONNAGE_ENSORCELE :
                        $message .= 'Le personnage a bien été ensorcelé pour '.$degats.' tours !<br>';
                        $retour = $perso->upExperience('ensorceler');
                        if ($retour == Personnage::LEVEL_UP)
                        {
                            $message .= 'Vous avez gagné un level<br>';
                        }
                        break;
                    
                    case Personnage::PAS_DE_MAGIE :
                        $message .= 'Vous n\'avez pas de magie !<br>';
                        break;
                    
                    case Personnage::PERSO_ENDORMI :
                        $message .= 'Vous êtes endormi, vous ne pouvez pas lancer de sort !<br>';
                        break;
                }
            }
            $manager->update($perso);
            $manager->update_bot($persoAEnsorceler);
            require_once('view/gameView.php');
        }
    }   
}

function deconnexion()
{
    $manager = new PersonnagesManager();
    $team = $manager->getList($_SESSION['team'],false);
    foreach ($team as $perso)
    {
        $perso->setVie($perso->vie_max());
        $perso->setTimeEndormi(0);
        $perso->setTimePower(0);
        $perso->setTour_frapper(0);
        $perso->setAtout(0);
        $manager->update($perso);
    }
    $bots = $manager->getList_bot($_SESSION['id']);
    foreach ($bots as $bot)
    {
        $manager->delete_bot($bot);
    }
    session_destroy();
    header('Location: C:\xampp\htdocs\POO');
    require('view/connectionView.php');
}

function index_frapper($id,$frapper)
{
    $message = "";
    $manager = new PersonnagesManager();
    $perso = $manager->get($id);
    if (!isset($perso))
    {
        $message .= 'Merci de créer un personnage ou de vous identifier.<br>';
        require_once('view/connectionView.php');
    }
    
    else
    {
        if (!$manager->exists_bot((int) $frapper))
        {
            $message .= 'Le personnage que vous voulez frapper n\'existe pas !<br>';
        }
    
        else
        {
            $persoAFrapper = $manager->get_bot((int) $frapper);
            list($retour,$degats) = $perso->frapper($persoAFrapper,$_SESSION['tour']); 
            switch ($retour)
            {
                case Personnage::CEST_MOI :
                    $message .= 'Mais... pourquoi voulez-vous vous frapper ???<br>';
                    break;
                
                case Personnage::PERSONNAGE_FRAPPE :
                    $message .= $perso->nom().' a infligé '.$degats.' degats à '.$persoAFrapper->nom().'<br>';
                    $rep = $perso->upExperience('frapper');
                    if ($rep == Personnage::LEVEL_UP)
                    {
                        $message .= $perso->nom().' as gagné un level<br>';
                    }
                    break;
            
                case Personnage::PERSONNAGE_TUE :
                    $message .= $perso->nom().' a infligé '.$degats.' degats à '.$persoAFrapper->nom().' et l\'as tué !<br>';
                    $rep = $perso->upExperience('tuer');
                    if ($rep == Personnage::LEVEL_UP)
                    {
                        $message .= $perso->nom().' as gagné un level<br>';
                    }
                    $manager->delete_bot($persoAFrapper);
                    break;
                
                case Personnage::PERSO_ENDORMI :
                    $message .= $perso->nom().' es endormi, vous ne pouvez pas frapper de personnage !<br>';
                    break;
                
                case Personnage::PARADE :
                    $message = $persoAFrapper->nom().' a parer votre attaque de '.$degats.' degats !<br>';
                    $persoAFrapper->upExperience('frapper');
                    break;
            }
            $manager->update($perso);
            $manager->update_bot($persoAFrapper);
            require_once('view/gameView.php');
        }
    }
}

function index_guerir($id,$guerir)
{
    $message = "";
    $manager = new PersonnagesManager();
    $perso = $manager->get($id);
    if (!isset($perso))
    {
        $message .= 'Merci de créer un personnage ou de vous identifier.<br>';
    }
    else
    {
        if ($perso->type() != 'enchanteur')
        {
            $message .= 'Seuls les enchanteurs peuvent guerir des personnages !<br>';
        }
        else
        {
            if (!$manager->exists((int) $guerir))
            {
                $message .= 'Le personnage que vous voulez guerir n\'existe pas !<br>';
            }
            else
            {
                $persoAGuerir = $manager->get((int) $guerir);
                if ($perso->id() == $guerir)
                {
                    $persoAGuerir = $perso;
                }
                list($retour,$degats) = $perso->guerison($persoAGuerir,$_SESSION['tour']);
                switch ($retour)
                {
                    case Personnage::SOIGNER :
                        $message .= $persoAGuerir->nom().' a gagné '.$degats.' points de vie !<br>';
                        $rep = $perso->upExperience('guerir');
                        if ($rep == Personnage::LEVEL_UP)
                        {
                            $message .= $perso->nom().' as gagné un level<br>';
                        }
                        break;

                    case Personnage::PERSO_ENDORMI :
                        $message .= $perso->nom().' es endormi, vous ne pouvez pas guerir de personnage !<br>';
                        break;

                    case Personnage::VIE_MAX :
                        $message .= $persoAGuerir->nom().' a gagné '.$degats.' points de vie et atteint sa vie max !<br>';
                        $rep = $perso->upExperience('guerir');
                        if ($rep == Personnage::LEVEL_UP)
                        {
                            $message .= $perso->nom().' as gagné un level<br>';
                        }
                        break;
                }
                $manager->update($persoAGuerir);
                $manager->update($perso);
                require_once('view/gameView.php');
            }
        }
    }
}

function generate($level,$id_partie)
{
    $manager = new PersonnagesManager();
    switch ($level)
    {
        case 'jeune recrue':
            $minlevel = 1;
            $maxlevel = 5;
            break;

        case 'facile':
            $minlevel = 5;
            $maxlevel = 10;
            break;
        
        case 'moyen':
            $minlevel = 10;
            $maxlevel = 20;
            break;
        
        case 'intermediaire':
            $minlevel = 20;
            $maxlevel = 40;
            break;
        
        case 'eleve':
            $minlevel = 40;
            $maxlevel = 60;
            break;

        case 'expert':
            $minlevel = 60;
            $maxlevel = 80;
            break;
        
        case 'champion':
            $minlevel = 80;
            $maxlevel = 90;
            break;
        
        case 'GameMaster':
            $minlevel = 95;
            $maxlevel = 100;
            break;
        
        default:
            throw new Error('Niveau de jeu invalide');
    }
    $choice = ['Brute','Enchanteur','Guerrier','Magicien'];
    $type = $choice[mt_rand(0,3)];
    $level = mt_rand($minlevel,$maxlevel);
    $nom = $type.' #'.(string) mt_rand(0,999);
    while ($manager->exists($nom))
    {
        $nom = $type.' #'.(string) mt_rand(0,999);
    }
    switch ($type)
    {
        case 'Magicien' :
        $perso = new Magicien(['nom' => $nom, 'vie_max' => 100]);
        break;
        
        case 'Guerrier' :
        $perso = new Guerrier(['nom' => $nom, 'vie_max' => 90]);
        break;
        
        case 'Brute' :
        $perso = new Brute(['nom' => $nom, 'vie_max' => 150]);
        break;
        
        case 'Enchanteur' :
        $perso = new Enchanteur(['nom' => $nom, 'vie_max' => 120]);
        break;
    }
    $perso->setLevel($level);
    $perso->setId_partie($id_partie);
    $vie = calcul_vie_level($perso);
    $perso->setVie($vie);
    $perso->setVie_max($vie);
    $manager->add_bot($perso);
}

function generate_id_partie()
{
    $a = '';
    for ($i=0; $i < 8; $i++) 
    { 
        $a .= (string) mt_rand(0,9);
    }
    return $a;
}

function calcul_vie_level(Personnage $perso)
{
    $vie = $perso->vie_max();
    for ($i=1;$i<$perso->level();$i++)
    {
        $add = (int) round(0.1 * $vie);
        $add = (25 >= $add) ? $add : 25;
        $vie += $add;
    }
    return $vie;
}

function jouer_tour_bot($id,$tour)
{
    $manager = new PersonnagesManager();
    $bots = $manager->getList_bot($id);
    $team = $manager->getList($_SESSION['team'],true); 
    if (!empty($team))
    {
        $guerir = [];
        $message = "";
        foreach ($bots as $bot)
        {
            if ($bot->vie() < $bot->vie_max())
            {
                $guerir[] = $bot;
            }
            if (!$bot->estEndormi($tour))
            {
                if ($bot->type() == 'magicien' && $bot->powerAvailable($tour))
                {
                    $magicien = $bot;
                }
                if ($bot->type() == 'enchanteur' && $bot->powerAvailable($tour))
                {
                    $enchanteur = $bot;
                }
                $choice = $team[mt_rand(0,count($team)-1)];
                list($retour,$degats) = $bot->frapper($choice,$tour);
                switch ($retour)
                {
                    case Personnage::CEST_MOI:
                        $message .= $bot->nom()." ne peux se frapper soi-meme.<br>";
                        break;

                    case Personnage::PERSO_ENDORMI:
                        $message .= $bot->nom()." est endormi.<br>";
                        break;

                    case Personnage::PERSONNAGE_TUE:
                        $message .= $bot->nom()." a infligé ".$degats." degats et tué ".$choice->nom().".<br>";
                        break;
                    
                    case Personnage::PERSONNAGE_FRAPPE:
                        $message .= $bot->nom()." a infligé ".$degats." degats à ".$choice->nom().".<br>";
                        break;

                    case Personnage::PARADE:
                        $message .= $choice->nom()." a paré l'attaque de ".$bot->nom()." de ".$degats." degats !!<br>";
                        break;

                }
                $manager->update($choice);
            }
        } 
        if (isset($magicien) && $magicien->found_atout() > 0)
        {
            $choice = $team[mt_rand(0,count($team)-1)];
            list($retour,$degats) = $magicien->lancerUnSort($choice,$tour);
            switch ($retour)
            {
                case Personnage::CEST_MOI:
                    $message .= $magicien->nom()." ne peux s'endormir soi-meme.<br>";
                    break;

                case Personnage::PAS_DE_MAGIE:
                    $message .= $magicien->nom()." n'a pas de magie.<br>";
                    break;
                
                case Personnage::PERSO_ENDORMI:
                    $message .= $magicien->nom()." est endormi.<br>";
                    break;
                
                case Personnage::PERSONNAGE_ENSORCELE:
                    $message .= $magicien->nom()." a étourdi ".$choice->nom()." pour ".$degats."tours.<br>";
                    break;
            }
            $manager->update($choice);
            $manager->update_bot($magicien);
            
        } 
        if (isset($enchanteur) && count($guerir) > 0)
        {
            $choice_bot_to_heal = $guerir[mt_rand(0,count($guerir)-1)];
            list($retour,$degats) = $enchanteur->guerison($choice_bot_to_heal,$tour);
            switch ($retour)
            {
                case Personnage::PERSO_ENDORMI:
                    $message .= $enchanteur->nom()." est endormi.<br>";
                    break;

                case Personnage::VIE_MAX:
                    $message .= $enchanteur->nom()." a gueri ".$choice_bot_to_heal->nom()." de ".$degats." pv et a atteint sa vie max.<br>";
                    break;
                
                case Personnage::SOIGNER:
                    $message .= $enchanteur->nom()." a gueri ".$choice_bot_to_heal->nom()." de ".$degats." pv.<br>";
                    break;
            }
            $manager->update_bot($enchanteur);
            $manager->update_bot($choice_bot_to_heal);
        }
        $_SESSION['tour'] += 1;
    }
    require_once('view/gameView.php');
}