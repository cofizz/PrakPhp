CREATE DATABASE IF NOT EXISTS readmore CHARACTER SET utf8 COLLATE utf8_general_ci;
USE readmore;

DROP TABLE IF EXISTS stavke_porudzbine;
DROP TABLE IF EXISTS porudzbine;
DROP TABLE IF EXISTS proizvodi;
DROP TABLE IF EXISTS kategorije;
DROP TABLE IF EXISTS korisnici;

CREATE TABLE korisnici (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ime VARCHAR(50) NOT NULL,
    prezime VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    lozinka VARCHAR(255) NOT NULL,
    uloga VARCHAR(20) NOT NULL DEFAULT 'user',
    aktiviran TINYINT NOT NULL DEFAULT 0,
    aktivacioni_kod VARCHAR(64),
    neuspeli_pokusaji INT NOT NULL DEFAULT 0,
    prvi_neuspeh DATETIME NULL,
    zakljucan_do DATETIME NULL,
    poslednja_prijava DATETIME NULL,
    kreiran DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE kategorije (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naziv VARCHAR(50) NOT NULL,
    opis VARCHAR(255)
);

CREATE TABLE proizvodi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kategorija_id INT NOT NULL,
    naziv VARCHAR(150) NOT NULL,
    autor VARCHAR(100) NOT NULL,
    opis TEXT,
    cena DECIMAL(10,2) NOT NULL,
    stara_cena DECIMAL(10,2) NULL,
    godina INT,
    strane INT,
    ocena DECIMAL(2,1) DEFAULT 0,
    broj_recenzija INT DEFAULT 0,
    slika VARCHAR(255),
    thumbnail VARCHAR(255),
    kreiran DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kategorija_id) REFERENCES kategorije(id)
);

CREATE TABLE porudzbine (
    id INT AUTO_INCREMENT PRIMARY KEY,
    korisnik_id INT NOT NULL,
    ukupno DECIMAL(10,2) NOT NULL,
    adresa VARCHAR(255),
    datum DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (korisnik_id) REFERENCES korisnici(id)
);

CREATE TABLE stavke_porudzbine (
    id INT AUTO_INCREMENT PRIMARY KEY,
    porudzbina_id INT NOT NULL,
    proizvod_id INT NOT NULL,
    kolicina INT NOT NULL,
    cena DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (porudzbina_id) REFERENCES porudzbine(id),
    FOREIGN KEY (proizvod_id) REFERENCES proizvodi(id)
);

INSERT INTO kategorije (id, naziv, opis) VALUES
(1, 'Klasici', 'Vanvremenska klasicna dela svetske knjizevnosti'),
(2, 'Trileri', 'Napete price pune preokreta'),
(3, 'Istorijski', 'Istorijski romani i epovi'),
(4, 'Fantastika', 'Magicni svetovi i epska fantastika'),
(5, 'Naucna fantastika', 'Naucna fantastika i buducnost'),
(6, 'Misterija', 'Misterije i detektivske price');

INSERT INTO proizvodi (kategorija_id, naziv, autor, opis, cena, stara_cena, godina, strane, ocena, broj_recenzija, slika, thumbnail) VALUES
(1, 'Crime and Punishment', 'Fyodor Dostoevsky', 'Psiholoski roman o krivici i iskupljenju.', 12.99, NULL, 1866, 671, 5.0, 20000, 'https://m.media-amazon.com/images/I/51Vg24nKbPL._AC_UF1000,1000_QL80_.jpg', 'https://m.media-amazon.com/images/I/51Vg24nKbPL._AC_UF1000,1000_QL80_.jpg'),
(2, 'Gone Girl', 'Gillian Flynn', 'Triler o nestanku supruge i tamnim tajnama braka.', 14.99, 18.99, 2012, 422, 4.5, 10000, 'https://www.knjizare-vulkan.rs/files/watermark/files/images/slike_proizvoda/thumbs_w/394844_w_0_0px.jpg', 'https://www.knjizare-vulkan.rs/files/watermark/files/images/slike_proizvoda/thumbs_w/394844_w_0_0px.jpg'),
(3, 'The Pillars of the Earth', 'Ken Follett', 'Epska prica o gradnji katedrale u srednjem veku.', 16.50, NULL, 1989, 973, 4.9, 1200, 'https://images3.penguinrandomhouse.com/cover/9781429586764', 'https://images3.penguinrandomhouse.com/cover/9781429586764'),
(2, 'The Silent Patient', 'Alex Michaelides', 'Psiholoski triler o zeni koja je prestala da govori.', 13.99, 17.50, 2019, 336, 4.6, 4000, 'https://m.media-amazon.com/images/I/91lslnZ-btL._AC_UF1000,1000_QL80_.jpg', 'https://m.media-amazon.com/images/I/91lslnZ-btL._AC_UF1000,1000_QL80_.jpg'),
(1, 'Pride and Prejudice', 'Jane Austen', 'Klasicna prica o ljubavi i predrasudama.', 9.99, NULL, 1813, 432, 4.7, 8000, 'https://m.media-amazon.com/images/I/712P0p5cXIL._AC_UF1000,1000_QL80_.jpg', 'https://m.media-amazon.com/images/I/712P0p5cXIL._AC_UF1000,1000_QL80_.jpg'),
(3, 'All the Light We Cannot See', 'Anthony Doerr', 'Prica o slepoj devojci i nemackom decaku u Drugom svetskom ratu.', 15.99, NULL, 2014, 531, 4.8, 2500, 'https://m.media-amazon.com/images/I/81WY6M9XikL._AC_UF1000,1000_QL80_.jpg', 'https://m.media-amazon.com/images/I/81WY6M9XikL._AC_UF1000,1000_QL80_.jpg'),
(5, 'Dune', 'Frank Herbert', 'Epska naucna fantastika o pustinjskoj planeti Arakis.', 17.99, 21.99, 1965, 688, 4.9, 14000, 'https://m.media-amazon.com/images/I/81Ua99CURsL._AC_UF1000,1000_QL80_.jpg', 'https://m.media-amazon.com/images/I/81Ua99CURsL._AC_UF1000,1000_QL80_.jpg'),
(6, 'And Then There Were None', 'Agatha Christie', 'Deset stranaca na ostrvu, jedan po jedan nestaju.', 11.50, NULL, 1939, 264, 4.7, 8000, 'https://m.media-amazon.com/images/I/81nChcVy7CL._AC_UF1000,1000_QL80_.jpg', 'https://m.media-amazon.com/images/I/81nChcVy7CL._AC_UF1000,1000_QL80_.jpg'),
(4, 'The Name of the Wind', 'Patrick Rothfuss', 'Prica o legendarnom carobnjaku Kvouteu.', 16.99, NULL, 2007, 662, 4.8, 4000, 'https://m.media-amazon.com/images/S/compressed.photo.goodreads.com/books/1704917687i/186074.jpg', 'https://m.media-amazon.com/images/S/compressed.photo.goodreads.com/books/1704917687i/186074.jpg'),
(5, '1984', 'George Orwell', 'Distopijski roman o totalitarnom drustvu.', 10.99, NULL, 1949, 328, 4.9, 20000, 'https://upload.wikimedia.org/wikipedia/sr/0/08/1984_vv.jpg', 'https://upload.wikimedia.org/wikipedia/sr/0/08/1984_vv.jpg'),
(3, 'Wolf Hall', 'Hilary Mantel', 'Uspon Tomasa Kromvela na dvoru Henrija VIII.', 14.50, 18.00, 2009, 650, 4.5, 3000, 'https://m.media-amazon.com/images/I/715MYJy6kjL._AC_UF1000,1000_QL80_.jpg', 'https://m.media-amazon.com/images/I/715MYJy6kjL._AC_UF1000,1000_QL80_.jpg'),
(2, 'The Girl with the Dragon Tattoo', 'Stieg Larsson', 'Novinar i hakerka istrazuju staru misteriju nestanka.', 13.50, 16.99, 2005, 644, 4.6, 7000, 'https://m.media-amazon.com/images/I/81YW99XIpJL._UF1000,1000_QL80_.jpg', 'https://m.media-amazon.com/images/I/81YW99XIpJL._UF1000,1000_QL80_.jpg'),
(4, 'Mistborn: The Final Empire', 'Brandon Sanderson', 'Pobuna protiv besmrtnog Lorda Vladara u svetu magije metala.', 15.99, NULL, 2006, 541, 4.8, 5000, 'https://m.media-amazon.com/images/I/91U6rc7u0yL._AC_UF1000,1000_QL80_.jpg', 'https://m.media-amazon.com/images/I/91U6rc7u0yL._AC_UF1000,1000_QL80_.jpg'),
(6, 'In the Woods', 'Tana French', 'Detektiv istrazuje ubistvo povezano sa svojom proslosti.', 12.99, NULL, 2007, 429, 4.4, 3000, 'https://m.media-amazon.com/images/I/91jMHH2+qHL._UF1000,1000_QL80_.jpg', 'https://m.media-amazon.com/images/I/91jMHH2+qHL._UF1000,1000_QL80_.jpg');

INSERT INTO korisnici (ime, prezime, email, lozinka, uloga, aktiviran) VALUES
('Admin', 'Readmore', 'admin@gmail.com', '$2y$10$2dMUTDxPsmIad.jjZACHF.q7A.tICihkKUbMD8pW2lzzWSANujDW6', 'admin', 1),
('Filip', 'Terzic', 'user@gmail.com', '$2y$10$DRrsdM6DcL7RkcfgWkqdH.LcGOCBhnOlLuC21zvDZROhzXIVnlz9C', 'user', 1);
