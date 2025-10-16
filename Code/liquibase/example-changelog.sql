--liquibase formatted sql

--changeset nous:1
CREATE TYPE role_type AS ENUM ('secretaire','prof','eleve','respon');
CREATE TYPE statut_absence AS ENUM ('valide','report','refus');
CREATE TYPE type_seance AS ENUM ('CM','TP','TD','DS','TP APP','BEN');
CREATE TYPE reponse_type AS ENUM ('accepte','refuse','enAttente');
--rollback drop type role_type, statut_absence,type_seance,reponse_type CASCADE ;

--changeset nous:2
CREATE TABLE if not exists Utilisateur
(
    idUtilisateur   SERIAL PRIMARY KEY,
    nom             VARCHAR(100),
    prenom          VARCHAR(100),
    prenom2         VARCHAR(100),
    email           VARCHAR(150) UNIQUE NOT NULL,
    motDePasse      VARCHAR(200)        NOT NULL,
    role            role_type           NOT NULL,
    groupe          VARCHAR(50),
    dateDeNaissance DATE,
    diplome         VARCHAR(100)
);
--rollback drop table Utilisateur Cascade;

--changeset nous:3
CREATE TABLE if not exists Cours
(
    idCours SERIAL PRIMARY KEY,
    matiere VARCHAR(100),
    idProf  INT REFERENCES Utilisateur (idUtilisateur)
);
--rollback drop table Cours Cascade;

--changeset nous:4
CREATE TABLE if not exists Seance
(
    idSeance     SERIAL PRIMARY KEY,
    idCours      INT REFERENCES Cours (idCours),
    heureDebut   time NOT NULL,
    typeSeance   type_seance,
    enseignement VARCHAR(100),
    salle        VARCHAR(50),
    prof         VARCHAR(100),
    controle     BOOLEAN,
    duree        time,
    date         date
);
--rollback drop table Seance Cascade;

--changeset nous:5
CREATE TABLE if not exists Absence
(
    idAbsence  SERIAL PRIMARY KEY,
    idSeance   INT REFERENCES Seance (idSeance),
    idEtudiant INT REFERENCES Utilisateur (idUtilisateur),
    statut     statut_absence,
    estRetard  boolean not null default false
);
--rollback drop table Absence Cascade;

--changeset nous:6
CREATE TABLE if not exists NotificationEmail
(
    idNotification SERIAL PRIMARY KEY,
    contenu        TEXT,
    sujet          VARCHAR(200),
    idUtilisateur  INT REFERENCES Utilisateur (idUtilisateur),
    idAbsence      INT REFERENCES Absence (idAbsence)
);
--rollback drop table NotificationEmail Cascade;

--changeset nous:7
CREATE TABLE if not exists Justificatif
(
    idJustificatif      SERIAL PRIMARY KEY,
    dateSoumission      DATE,
    commentaire_absence TEXT,
    verrouille          BOOLEAN
);
--rollback drop table Justificatif Cascade;

--changeset nous:8
CREATE TABLE if not exists AbsenceEtJustificatif
(
    idJustificatif SERIAL references Justificatif,
    idAbsence      INT REFERENCES Absence (idAbsence),
    primary key (idAbsence, idJustificatif)
);
--rollback drop table AbsenceEtJustificatif Cascade;

--changeset nous:9
CREATE TABLE if not exists FichierJustificatif
(
    pathJustificatif text primary key,
    idJustificatif   SERIAL references Justificatif
);
--rollback drop table FichierJustificatif Cascade;

--changeset nous:10
CREATE TABLE if not exists TraitementJustificatif
(
    idTraitement           SERIAL PRIMARY KEY,
    attente                BOOLEAN,
    reponse                reponse_type,
    idUtilisateur          INT REFERENCES Utilisateur (idUtilisateur),
    date                   TIMESTAMP,
    commentaire_validation TEXT,
    cause                  VARCHAR(100)
);
--rollback drop table TraitementJustificatif Cascade;

--changeset nous:11
CREATE TABLE if not exists JustificatifEtTraitementJustificatif
(
    idTraitement   SERIAL references TraitementJustificatif (idTraitement),
    idJustificatif INT REFERENCES Justificatif (idJustificatif),
    primary key (idJustificatif, idTraitement)
);
--rollback drop table JustificatifEtTraitementJustificatif Cascade

--changeset nous:12
CREATE TABLE if not exists CoursEtUtilisateur
(
    idUtilisateur   SERIAL references Utilisateur,
    idCours INT REFERENCES Cours ,
    primary key (idUtilisateur, idCours)
    );
--rollback drop table CoursEtUtilisateur Cascade

--changeset Kilian:13
Alter table Seance add constraint difference UNIQUE(enseignement,heureDebut,salle,date);
--rollback Alter table Seance drop constraint difference;

--changeset Kilian:14
Alter table Absence add constraint diff UNIQUE(idSeance,idEtudiant);
--rollback Alter table Absence drop constraint diff;

--changeset Kilian:15
DROP table JustificatifEtTraitementJustificatif
--rollback CREATE TABLE if not exists CoursEtUtilisateur(idUtilisateur   SERIAL references Utilisateur,idCours INT REFERENCES Cours ,primary key (idUtilisateur, idCours));

--changet Kilian:16
Alter table TraitementJustificatif add column idJustificatif INT REFERENCES Justificatif (idJustificatif);
--rollback Alter table TraitementJustificatif drop column idJustificatif ;

--changeset Kilian:17
Alter table TraitementJustificatif add constraint differ UNIQUE(idUtilisateur,idJustificatif);
--rollback Alter table TraitementJustificatif drop constraint differ;