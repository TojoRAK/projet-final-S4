

CREATE TABLE conf_prefix (
    id     INTEGER PRIMARY KEY AUTOINCREMENT,
    prefix TEXT NOT NULL UNIQUE
);

CREATE TABLE type_operation (
    id      INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle TEXT NOT NULL UNIQUE
);

CREATE TABLE tranches (
    id                INTEGER PRIMARY KEY AUTOINCREMENT,
    min               INTEGER NOT NULL,
    max               INTEGER NOT NULL,
    frais             INTEGER NOT NULL,
    id_type_operation INTEGER NOT NULL,
    CHECK (min <= max),
    FOREIGN KEY (id_type_operation) REFERENCES type_operation(id)
);

CREATE TABLE clients (
    id        INTEGER PRIMARY KEY AUTOINCREMENT,
    nom       TEXT NOT NULL,
    telephone TEXT NOT NULL UNIQUE
);

CREATE TABLE compte (
    id        INTEGER PRIMARY KEY AUTOINCREMENT,
    id_client INTEGER NOT NULL,
    solde     INTEGER NOT NULL DEFAULT 0,
    FOREIGN KEY (id_client) REFERENCES clients(id)
);

CREATE TABLE transactions (
    id                INTEGER PRIMARY KEY AUTOINCREMENT,
    id_type_operation INTEGER NOT NULL,
    montant           INTEGER NOT NULL,
    frais_applique    INTEGER NOT NULL DEFAULT 0,
    date              TEXT NOT NULL DEFAULT (datetime('now')),
    FOREIGN KEY (id_type_operation) REFERENCES type_operation(id)
);

CREATE TABLE mouvements (
    id             INTEGER PRIMARY KEY AUTOINCREMENT,
    id_transaction INTEGER NOT NULL,
    id_compte      INTEGER NOT NULL,
    sens           TEXT NOT NULL CHECK (sens IN ('debit', 'credit')),
    FOREIGN KEY (id_transaction) REFERENCES transactions(id),
    FOREIGN KEY (id_compte) REFERENCES compte(id)
);

INSERT INTO conf_prefix (prefix) VALUES ('033'), ('037');
REFERENCES
INSERT INTO type_operation (libelle) VALUES ('depot'), ('retrait'), ('transfert');

INSERT INTO tranches (min, max, frais, id_type_operation) VALUES
    (100,      1000,    50,   2),
    (1001,     5000,    50,   2),
    (5001,     10000,   100,  2),
    (10001,    25000,   200,  2),
    (25001,    50000,   400,  2),
    (50001,    100000,  800,  2),
    (100001,   250000,  1500, 2),
    (250001,   500000,  1500, 2),
    (500001,   1000000, 2500, 2),
    (1000001,  2000000, 3000, 2);


INSERT INTO compte(id_client, solde) VALUES (1,0);
INSERT INTO clients (nom, telephone) VALUES ('tojo','0335258294');
INSERT INTO clients (nom, telephone) VALUES ('fifaliana','0372152576');

INSERT INTO clients (nom, telephone) VALUES ('roberto' , '0331234567');

INSERT INTO compte(id_client,solde) VALUES (2,0);
INSERT INTO compte(id_client,solde) VALUES (3,0);
INSERT INTO compte(id_client,solde) VALUES (4,0);


CREATE TABLE operateur (
    id            INTEGER PRIMARY KEY AUTOINCREMENT,
    username      TEXT NOT NULL UNIQUE,
    password      TEXT NOT NULL,           -- hash via password_hash()
    date_creation TEXT NOT NULL DEFAULT (datetime('now'))
);

INSERT INTO operateur (username, password) VALUES ('admin', '$2y$12$RIL6dmqdaj4eqJN2dpx67OsRRA0/R33OSJmzUwbN9/iDhV8Vu6TFy');

CREATE TABLE autres_operateurs(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom_operateur TEXT NOT NULL UNIQUE
);

ALTER TABLE conf_prefix ADD COLUMN id_operateur REFERENCES autres_operateurs(id) DEFAULT NULL;

CREATE TABLE conf_commission(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_operateur INTEGER REFERENCES autres_operateurs(id),
    pourcentage DOUBLE
);

insert into autres_operateurs(nom_operateur) values ('Orange'),('Airtel'),('Telma');
insert into conf_commission(id_operateur,pourcentage) values (1,0.05),(2,0.05),(3,0.05);


