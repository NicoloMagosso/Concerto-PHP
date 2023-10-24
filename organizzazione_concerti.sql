CREATE DATABASE IF NOT EXISTS organizzazione_concerti;
create table IF NOT EXISTS organizzazione_concerti.concerti(
id int not null auto_increment primary key,
codice varchar(50),
titolo varchar(50),
descrizione varchar(100),
data datetime
);