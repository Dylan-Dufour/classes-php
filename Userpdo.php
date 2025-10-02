<?php
class Userpdo {
    private $id;
    private $login;
    private $email;
    private $firstname;
    private $lastname;
    private $pdo;

    // --- Constructeur : connexion à la BDD via PDO ---
    public function __construct($dbhost="localhost", $dbname="ma_base", $dbuser="root", $dbpass="") {
        try {
            $this->pdo = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Pour afficher les erreurs PDO
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    // --- Inscription ---
    public function register($login, $password, $email, $firstname, $lastname) {
        // Vérifier si le login existe déjà
        $check = $this->pdo->prepare("SELECT id FROM users WHERE login = :login");
        $check->bindParam(":login", $login);
        $check->execute();

        if ($check->rowCount() > 0) {
            return "Ce login existe déjà.";
        }

        // Insérer le nouvel utilisateur
        $stmt = $this->pdo->prepare("INSERT INTO users (login, password, email, firstname, lastname) 
                                     VALUES (:login, :password, :email, :firstname, :lastname)");
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt->execute([
            ":login" => $login,
            ":password" => $hashed_password,
            ":email" => $email,
            ":firstname" => $firstname,
            ":lastname" => $lastname
        ]);

        return "Inscription réussie !";
    }

    // --- Connexion ---
    public function connect($login, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE login = :login");
        $stmt->execute([":login" => $login]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
            $this->id = $user["id"];
            $this->login = $user["login"];
            $this->email = $user["email"];
            $this->firstname = $user["firstname"];
            $this->lastname = $user["lastname"];
            return true;
        } else {
            return false;
        }
    }

    // --- Déconnexion ---
    public function disconnect() {
        $this->id = null;
        $this->login = null;
        $this->email = null;
        $this->firstname = null;
        $this->lastname = null;
        return true;
    }

    // --- Suppression du compte ---
    public function delete() {
        if ($this->id) {
            $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute([":id" => $this->id]);
            $this->disconnect();
            return "Utilisateur supprimé.";
        }
        return "Aucun utilisateur connecté.";
    }

    // --- Mise à jour du profil ---
    public function update($login, $password, $email, $firstname, $lastname) {
        if ($this->id) {
            $stmt = $this->pdo->prepare("UPDATE users SET login=:login, password=:password, email=:email, firstname=:firstname, lastname=:lastname WHERE id=:id");
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt->execute([
                ":login" => $login,
                ":password" => $hashed_password,
                ":email" => $email,
                ":firstname" => $firstname,
                ":lastname" => $lastname,
                ":id" => $this->id
            ]);

            // Mettre à jour les infos de l'objet
            $this->login = $login;
            $this->email = $email;
            $this->firstname = $firstname;
            $this->lastname = $lastname;

            return "Profil mis à jour.";
        }
        return "Aucun utilisateur connecté.";
    }

    // --- Vérifier si connecté ---
    public function isConnected() {
        return $this->id !== null;
    }

    // --- Récupérer toutes les infos ---
    public function getAllInfos() {
        if ($this->id) {
            return [
                "id" => $this->id,
                "login" => $this->login,
                "email" => $this->email,
                "firstname" => $this->firstname,
                "lastname" => $this->lastname
            ];
        }
        return "Aucun utilisateur connecté.";
    }

    // --- Récupérer login ---
    public function getLogin() {
        return $this->login;
    }

    // --- Récupérer email ---
    public function getEmail() {
        return $this->email;
    }

    // --- Récupérer prénom ---
    public function getFirstname() {
        return $this->firstname;
    }

    // --- Récupérer nom ---
    public function getLastname() {
        return $this->lastname;
    }
}
?>
