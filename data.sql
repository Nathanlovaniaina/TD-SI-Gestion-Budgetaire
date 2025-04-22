-- Insertion des données dans Categorie
INSERT INTO Categorie (nom) VALUES 
('Recette'), 
('Dépense');

-- Insertion des données dans Departement
INSERT INTO Departement (nom) VALUES 
('Ressources Humaines'), 
('Informatique'), 
('Finance');

-- Insertion des données dans Nature
INSERT INTO Nature (nom, id_departement) VALUES 
('Salaire', 1), 
('Matériel informatique', 2), 
('Logiciels', 2), 
('Investissements', 3);

-- Insertion des données dans Type
INSERT INTO Type (id_cate, id_nature, libelle) VALUES 
(1, 1, 'Paiement des salaires'), 
(2, 2, 'Achat de matériel'), 
(2, 3, 'Achat de logiciels'), 
(1, 4, 'Investissements divers');

-- Insertion des données dans Budget
INSERT INTO Budget (montant, id_departement, id_type) VALUES 
(50000.00, 1, 1), 
(20000.00, 2, 2), 
(15000.00, 2, 3), 
(75000.00, 3, 4);


SELECT 
    Budget.id_budget,
    SUM(Budget.montant) AS montant,
    Departement.id_departement,
    Departement.nom AS departement,
    Categorie.id_cate,
    Categorie.nom AS categorie,
    Nature.id_nature,
    Nature.nom AS nature,
    Type.id_type,
    Type.libelle AS type_depense
FROM Budget
JOIN Type ON Budget.id_type = Type.id_type
JOIN Nature ON Type.id_nature = Nature.id_nature
JOIN Departement ON Budget.id_departement = Departement.id_departement
JOIN Categorie ON Type.id_cate = Categorie.id_cate
WHERE Budget.date_budget BETWEEN :date_debut AND :date_fin
GROUP BY Type.id_type;

