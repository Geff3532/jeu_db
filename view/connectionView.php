<?php $title = 'JOUER'; ?>

<?php ob_start(); ?>
<form action="" method="post" name="form">

    <fieldset>
        <legend>Jouer</legend>
        <br>

        Nom de la team : <input type="text" name="nom_team1" maxlength="50" /> 

        Niveau :
            <select name="level1">
                <option value="jeune recrue">Jeune recrue</option>
                <option value="facile">Facile</option>
                <option value="moyen">Moyen</option>
                <option value="intermediaire">Intermédiaire</option>
                <option value="eleve">Elevé</option>
                <option value="expert">Expert</option>
                <option value="champion">Champion</option>
                <option value="GameMaster">GameMaster</option>
            </select>

        <input type="submit" value="Se connecter" name="utiliser" /><br /><br><br>

    </fieldset><br>

    <fieldset>

        <legend>Inscription</legend><br>

        Nom de la team : <input type="text" name="nom_team2" maxlength="50" /><br><br>

        Nom perso 1 : <input type="text" name="nom1" maxlength="50" />

        Type perso 1 :
        <select name="type1">
            <option value="magicien">Magicien</option>
            <option value="guerrier">Guerrier</option>
            <option value="brute">Brute</option>
            <option value="enchanteur">Enchanteur</option>
        </select><br><br>

        Nom perso 2 : <input type="text" name="nom2" maxlength="50" />

        Type perso 2 :
        <select name="type2">
            <option value="magicien">Magicien</option>
            <option value="guerrier">Guerrier</option>
            <option value="brute">Brute</option>
            <option value="enchanteur">Enchanteur</option>
        </select><br><br>

        Nom perso 3 : <input type="text" name="nom3" maxlength="50" />

        Type perso 3 :
        <select name="type3">
            <option value="magicien">Magicien</option>
            <option value="guerrier">Guerrier</option>
            <option value="brute">Brute</option>
            <option value="enchanteur">Enchanteur</option>
        </select><br><br>

        Niveau :
            <select name="level2">
                <option value="jeune recrue">Jeune recrue</option>
                <option value="facile">Facile</option>
                <option value="moyen">Moyen</option>
                <option value="intermediaire">Intermédiaire</option>
                <option value="eleve">Elevé</option>
                <option value="expert">Expert</option>
                <option value="champion">Champion</option>
                <option value="GameMaster">GameMaster</option>
            </select>

        <input type="submit" value="Creer une team" name="creer" />

    </fieldset><br>

    <fieldset>

        <legend>Votre equipe</legend><br>

        Nom de la team : <input type="text" name="my_team" maxlength="50"/> 
        <input type="submit" value="Voir ma team" name="view_my_team"/>
    
    </fieldset>

    <br><br>

    <fieldset>

        <legend>Règles du jeu</legend>

        <div>
            <h3>Déroulement</h3>
            <p>
                Chaque équipe est composé de 3 héros. Chacun de ses héros peut être de type Magicien, Enchanteur, Brute ou Guerrier.
                Chacun des ses types a ses particularités. Lors du combat vous affrontez une équipe adverse, le but est de tous les tuer.
                Si l'un de vos perso meurt vous le recupererez a la fin du combat. Le combat se deroule par tour, vous commencez puis la 
                team adverse joue.
                A chaque tour, vous pouvez frapper un adversaire et/ou utiliser un pouvoir propre à votre personnage. Vous pouvez ne pas 
                agir avec tous les personnages si vous le souhaitez. Chaque action rapporte de l'expérience, 5 points par attaque, 10 points
                par pouvoir et 20 points si vous tuez un adversaire. Si vous avez suffisament de points vous gagnez un level et des points 
                de vie en plus !
            </p>
        </div>

        <div>
            <h3>Caracteristiques</h3>
                <h4>Magicien :</h4>
                <ul>
                    <li>Pv de base : 100</li>
                    <li>Pouvoir : Peut étourdir un adversaire pour plusieurs tours une fois tous les 3 tours ! 
                        Mais seulement si il lui reste moins de 60% de sa vie. Moins il a de vie plus 
                        l'etourdissement dure longtemps.</li>
                </ul>
                <h4>Guerrier :</h4>
                <ul>
                    <li>Pv de base : 90</li>
                    <li>Pouvoir : Peut parer une attaque quel que soit sa force.
                        Les chances de parer sont de 10% + 0.25 x level du guerrier.</li>
                </ul>
                <h4>Brute :</h4>
                <ul>
                    <li>Pv de base : 150</li>
                    <li>Pouvoir : A beaucoup de pv et encaisse 20% moins de dégats que tous les autres joueurs.</li>
                </ul>
                <h4>Enchanteur :</h4>
                <ul>
                    <li>Pv de base : 120</li>
                    <li>Pouvoir : Peut guerir des alliés ou lui-même tous les 3 tours.</li>
                </ul>
        </div>

    </fieldset>

</form>

<?php if (isset($message))
{
    echo '<p>'.$message.'</p>';
}
?>
<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>