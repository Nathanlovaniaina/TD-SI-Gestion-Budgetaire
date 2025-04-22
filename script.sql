-- Création de la base de données
CREATE DATABASE IF NOT EXISTS gestion_budget;
USE gestion_budget;

-- Table Catégorie
CREATE TABLE Categorie (
    id_cate INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL
);

-- Table Département
CREATE TABLE Departement (
    id_departement INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

-- Table Nature
CREATE TABLE Nature (
    id_nature INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    id_departement INT,
    FOREIGN KEY (id_departement) REFERENCES Departement(id_departement) ON DELETE CASCADE
);

-- Table Type
CREATE TABLE Type (
    id_type INT AUTO_INCREMENT PRIMARY KEY,
    id_cate INT NOT NULL,
    id_nature INT NOT NULL,
    libelle VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_cate) REFERENCES Categorie(id_cate) ON DELETE CASCADE,
    FOREIGN KEY (id_nature) REFERENCES Nature(id_nature) ON DELETE CASCADE
);

-- Table Budget
CREATE TABLE Budget (
    id_budget INT AUTO_INCREMENT PRIMARY KEY,
    montant DECIMAL(15,2) NOT NULL CHECK (montant >= 0),
    date_budget DATE,
    id_departement INT NOT NULL,
    id_type INT NOT NULL,
    description VARCHAR(255),
    FOREIGN KEY (id_departement) REFERENCES Departement(id_departement) ON DELETE CASCADE,
    FOREIGN KEY (id_type) REFERENCES Type(id_type) ON DELETE CASCADE
);

-- Table User
CREATE TABLE User (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    identifiant VARCHAR(50) UNIQUE NOT NULL,
    mdp VARCHAR(255) NOT NULL,  -- Stocker en hashé
    id_departement INT NOT NULL,
    FOREIGN KEY (id_departement) REFERENCES Departement(id_departement) ON DELETE CASCADE
);

-- Table Autorisation (Lecture)
CREATE TABLE Autorisation (
    id_autorisation INT AUTO_INCREMENT PRIMARY KEY,
    id_departement_proprietaire INT NOT NULL,
    id_departement_possession INT NOT NULL,
    FOREIGN KEY (id_departement_proprietaire) REFERENCES Departement(id_departement) ON DELETE CASCADE,
    FOREIGN KEY (id_departement_possession) REFERENCES Departement(id_departement) ON DELETE CASCADE
);

CREATE TABLE Prevision (
    id_prevision INT AUTO_INCREMENT PRIMARY KEY,
    id_departement INT NOT NULL,
    id_type INT NOT NULL,
    montant DECIMAL(15,2) NOT NULL CHECK (montant >= 0),
    date_prevision DATE NOT NULL,
    FOREIGN KEY (id_departement) REFERENCES Departement(id_departement) ON DELETE CASCADE,
    FOREIGN KEY (id_type) REFERENCES Type(id_type) ON DELETE CASCADE
);


-- Insertion des départements
INSERT INTO Departement (nom) VALUES 
('Finance'),
('Ressources Humaines'),
('Informatique');

-- Insertion des catégories
INSERT INTO Categorie (nom) VALUES 
('Frais de fonctionnement'),
('Investissements'),
('Salaires');

-- Insertion des natures de dépenses
INSERT INTO Nature (nom, id_departement) VALUES 
('Matériel informatique', 3),
('Formation du personnel', 2),
('Entretien des locaux', 1);

-- Insertion des types de dépenses
INSERT INTO Type (id_cate, id_nature, libelle) VALUES 
(1, 1, 'Achat de PC'),
(2, 2, 'Formation en gestion'),
(3, 3, 'Réparation bureaux');

-- Insertion des budgets
INSERT INTO Budget (montant, date_budget, id_departement, id_type, description) VALUES 
(5000.00, '2025-03-01', 3, 1, 'Achat de nouveaux ordinateurs'),
(2000.00, '2025-02-15', 2, 2, 'Formation des employés en gestion'),
(1500.00, '2025-01-20', 1, 3, 'Entretien des bureaux');

-- Insertion des utilisateurs
INSERT INTO User (identifiant, mdp, id_departement) VALUES 
('admin_finance', 'hashedpassword1', 1),
('admin_rh', 'hashedpassword2', 2),
('admin_it', 'hashedpassword3', 3);

-- Insertion des autorisations
INSERT INTO Autorisation (id_departement_proprietaire, id_departement_possession) VALUES 
(1, 2), -- Le département Finance donne accès aux RH
(1, 3), -- Le département Finance donne accès à l'IT
(2, 3); -- Le département RH donne accès à l'IT

INSERT INTO Prevision (id_departement, id_type, montant, date_prevision) 
VALUES 
(3, 1, 7000, '2025-04-01'), -- Achat PC IT
(2, 2, 3000, '2025-04-01'), -- Formation RH
(1, 3, 2000, '2025-04-01'); -- Entretien Finance

