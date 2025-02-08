<?php

require_once('controller/frontend.php');
session_start();

try 
{
    if (!isset($_SESSION['id']) && !isset($_SESSION['team']))
    {
        if (!empty($_POST['creer']) && !empty($_POST['nom_team2'])) 
        {
            if (!empty($_POST['nom1']) && !empty($_POST['type1']) && !empty($_POST['nom2']) && !empty($_POST['type2']) && !empty($_POST['nom3']) && !empty($_POST['type3']) && !empty($_POST['level2']))
            {
                $id_partie = generate_id_partie();
                for ($i=1;$i<4;$i++)
                {
                    creer($_POST['nom_team2'],$_POST['type'.$i],$_POST['nom'.$i]);
                    generate($_POST['level2'],$id_partie);
                }
                $_SESSION['id'] = $id_partie;
                $_SESSION['team'] = $_POST['nom_team2'];
                $_SESSION['tour'] = 1;
                header('Location: .');
            }
            else
            {
                $message = 'Completer tous les champs pour creer votre equipe';
            }
        }

        elseif (!empty($_POST['utiliser'])) 
        {
            if (!empty($_POST['nom_team1']) && !empty($_POST['level1']))
            {
                utiliser($_POST['nom_team1'],$_POST['level1']);
            }
            else
            {
                $message = 'Completer tous les champs pour creer votre equipe';
            }
        }
        require_once('view/connectionView.php');
    }
    else
    {
        $manager = new PersonnagesManager();

        if (isset($_GET['quit']))
        {
            deconnexion();
            header('Location: .');
        }

        if (isset($_GET['tour']))
        {
            jouer_tour_bot($_SESSION['id'],$_SESSION['tour']);
        }

        if (isset($_GET['frapper']) && isset($_GET['id'])) 
        {
            index_frapper((int) $_GET['id'],(int) $_GET['frapper']);
        }

        if (isset($_GET['guerir']) && isset($_GET['id']))
        {
            index_guerir((int) $_GET['id'],(int) $_GET['guerir']);
        }

        if (isset($_GET['ensorceler']) && isset($_GET['id']))
        {
            ensorceler((int) $_GET['id'],(int) $_GET['ensorceler']);
        }

        require_once('view/gameView.php');
    }
}
catch(Exception $e) {
  echo 'Erreur : ' . $e->getMessage();
}