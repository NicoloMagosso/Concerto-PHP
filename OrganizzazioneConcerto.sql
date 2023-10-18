create database OrganizzazioneConcerto;
create table organizzazioneconcerto.concerti(
_id int not null auto_increment primary key,
_codice varchar(50),
_titolo varchar(50),
_descrizione varchar(100),
_data datetime
);