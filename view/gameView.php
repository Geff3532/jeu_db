<?php $title = 'TP : Mini jeu de combat - Version 2'; ?>

<?php ob_start(); ?>
<?php
if (isset($_SESSION['team']) && isset($_SESSION['id'])) 
{
  $team = $_SESSION['team'];
  $id = $_SESSION['id'];
  $tour = $_SESSION['tour'];
?>
  <h2>Team <?= $team ?></h2>
  <h3>Tour <?= $_SESSION['tour']?></h3>
  <p><a href="?quit=1">Déconnexion</a> <a href="?tour=1">Tour suivant</a></p>
  <?php
  if (isset($message)) 
  {
    echo '<p>', $message, '</p>'; 
  }
  ?>
  <fieldset class="team">
      <legend>Mes informations</legend>
<?php
  $retourPersos = $manager->getList($team,true);
  if (empty($retourPersos))
  {
    echo '<h1>Game Over</h1>';
  }
  foreach ($retourPersos as $perso)
  {
?>  
      <p class="player_from_team">
        Type : <?= ucfirst($perso->type())?><br>
        Nom : <?= htmlspecialchars($perso->nom())?><br>
        Vie : <?= $perso->vie()?><br>
        Vie max : <?= $perso->vie_max()?><br>
        Level : <?= $perso->level()?><br>
        Experience : <?= $perso->experience()?><br>
        Atout : <?php $perso->found_atout();$manager->update($perso);echo $perso->atout();?>
        </p><div>
        <br><form method="post" action="?id=<?= $perso->id()?>">
          <input type="submit" value="Selectionner">
        </form>
        <?php
        if (isset($_GET['id']))
        {
          $current_player = $manager->get((int) $_GET['id']);
          if ($current_player->type() == "enchanteur" && $current_player->powerAvailable($tour) && !$current_player->estEndormi($tour))
          {
            ?><br><form method="post" action="?id=<?=$current_player->id()?>&guerir=<?=$perso->id()?>">
              <input type="submit" value="Guerir">
            </form><?php
          }
        }
        ?></div>

<?php } ?>
  </fieldset>
  <fieldset class="team">
    <legend>Adversaires</legend>
<?php
  $retourBots = $manager->getList_bot($id);
  if (empty($retourBots))
  {
    ?>
    <h1>Félicitations vous avez réussi ce niveau !</h1>
    <?php
  }
  foreach ($retourBots as $perso)
  {
?>  
      <p class="player_from_team">
        Type : <?= ucfirst($perso->type()) ?><br>
        Nom : <?= htmlspecialchars($perso->nom()) ?><br>
        Vie : <?= $perso->vie() ?><br>
        Vie max : <?= $perso->vie_max()?><br>
        Level : <?= $perso->level() ?><br>
        Experience : <?= $perso->experience() ?><br>
        Atout : <?php $perso->found_atout();$manager->update_bot($perso);echo $perso->atout();?>
        </p>
        <div>
        <?php
        if (isset($_GET['id']))
        {
          $current_player = $manager->get((int) $_GET['id']);
          if ($current_player->frapperAvailable($tour) && !$current_player->estEndormi($tour))
          {
          ?><br><form method="post" action="?id=<?=$current_player->id()?>&frapper=<?=$perso->id()?>">
            <input type="submit" value="Frapper">
          </form><?php
          }
          if ($current_player->type() == 'magicien' && $current_player->powerAvailable($tour) && !$current_player->estEndormi($tour) && $current_player->atout() > 0)      
          {
            ?><br><form method="post" action="?id=<?=$current_player->id()?>&ensorceler=<?=$perso->id()?>">
              <input type="submit" value="Ensorceler">
            </form><?php
          }
        }
        ?></div>
    
<?php } ?>
  </fieldset>
<?php } ?>
<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>