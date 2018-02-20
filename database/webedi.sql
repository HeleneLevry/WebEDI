# --- Creation de la base Commerce
create database webedi;
	use webedi;


# --- Creation des tables

#table utilisateurs
create table users(
	userId int unsigned not null auto_increment primary key,
	login varchar(20) not null unique,
	password varchar(40),
	name varchar(20), 
	firstName varchar(20),
	email varchar(40),
	attemps int unsigned not null,
	connectionDate date,
	active boolean
);

#table operations
create table operations(
	opeId int unsigned not null auto_increment primary key,
	batch int unsigned not null,
	type char(10),
	amount float(6,2),
	originAccount char(20),
	destinationAccount char(20),
	userId int unsigned not null
);

# Clés étrangères
ALTER TABLE operations ADD CONSTRAINT userId_FK FOREIGN KEY (userId) REFERENCES users(userId);