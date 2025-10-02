<?php
class User {
    private $id;
    public $login;
    public $email;
    public $firstname;
    public $lastname;
    

    private $pdo; // pour stocker la connexion à la base de données

    // Constructeur = appelé quand on crée un nouvel objet User
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // CREATE - ajouter un utilisateur
    public function create() {
        $sql = "INSERT INTO utilisateurs (login, email, firstname, lastname) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$this->login, $this->email, $this->firstname, $this->lastname]);
        $this->id = $this->pdo->lastInsertId(); // récupère l'ID auto-généré
    }

    // READ - récupérer un utilisateur par son ID
    public function read($id) {
        $sql = "SELECT * FROM utilisateurs WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch();

        if ($data) {
            $this->id = $data['id'];
            $this->login = $data['login'];
            $this->email = $data['email'];
            $this->firstname = $data['firstname'];
            $this->lastname = $data['lastname'];
        }
    }

    // UPDATE - modifier un utilisateur
    public function update() {
        $sql = "UPDATE utilisateurs SET login = ?, email = ?, firstname = ?, lastname = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$this->login, $this->email, $this->firstname, $this->lastname, $this->id]);
    }

    // DELETE - supprimer un utilisateur
    public function delete() {
        $sql = "DELETE FROM utilisateurs WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$this->id]);
    }
}
?>


