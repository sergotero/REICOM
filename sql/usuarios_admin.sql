-- Los siguientes usuarios son especiales para pruebas:
-- Admin: Sergio Admin; Pass: "reicom123!"
-- Profe: Sergio Profe; Pass: "reicom123!"
INSERT INTO usuarios (nombre, apellido1, apellido2, email, pass, rol) VALUES ("Sergio", "Admin", "Admin", "admin@reicom.com", "$2y$10$9e7V7vFEH1HkiW9PFckHO.4B3e8KLr2di5YlO8FoCGzEWWtldHUka", "Administrador");
INSERT INTO usuarios (nombre, apellido1, apellido2, email, pass, rol) VALUES ("Sergio", "Profe", "Profe", "profe@reicom.com", "$2y$10$9e7V7vFEH1HkiW9PFckHO.4B3e8KLr2di5YlO8FoCGzEWWtldHUka", "Profesor");