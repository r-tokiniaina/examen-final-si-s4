CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT,
    prenom TEXT,
    email TEXT UNIQUE,
    mot_de_passe TEXT,
    role TEXT
);

-- FOREIGN KEY(id_user) REFERENCES users(id)

INSERT INTO users (nom, prenom, email, mot_de_passe, role)
VALUES ('Admin', 'Admin', 'admin@gmail.com', '$2y$10$s0gTQ0.ihOLNJN9PZ.jVruktyEGeZsWZ6HsuoJr833A9yzG5Stw.u', 'administrateur'),
       ('User', 'User', 'user@gmail.com', '$2y$10$s0gTQ0.ihOLNJN9PZ.jVruktyEGeZsWZ6HsuoJr833A9yzG5Stw.u', '');
