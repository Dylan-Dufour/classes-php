<?php
require_once "user-pdo.php";

// On instancie la classe
$user = new Userpdo("localhost", "ma_base", "root", "");

// Test inscription
echo $user->register("Tom13", "azerty", "thomas@gmail.com", "Thomas", "DUPONT");

// Test connexion
if ($user->connect("Tom13", "azerty")) {
    echo "<br>Connexion réussie !";
    print_r($user->getAllInfos());
} else {
    echo "<br>Échec de la connexion.";
}

// Test mise à jour
echo "<br>" . $user->update("Tom14", "azerty123", "thomas_new@gmail.com", "Tom", "DUPONT");

// Vérifier infos mises à jour
print_r($user->getAllInfos());

// Test suppression
echo "<br>" . $user->delete();

// Vérifier déconnexion
echo "<br>Connecté ? " . ($user->isConnected() ? "Oui" : "Non");
