create table operateurs (
    id integer primary key autoincrement,
    libelle text not null,
    pct_commission real
);


create table prefixes (
	id integer primary key autoincrement,
	id_operateur integer,
	valeur text not null
);

create table types_operations (
	id integer primary key autoincrement,
	libelle text not null
);

create table baremes_frais (
	id integer primary key autoincrement,
	type_operation integer not null,
	montant_min integer,
	montant_max integer,
	frais integer
);

create table clients (
	id integer primary key autoincrement,
	numero varchar(20) not null
);

create table operations (
	id integer primary key autoincrement,
	type integer not null,
	montant integer not null,
	frais integer,
	num_source text,
	num_destination text,
	date_operation date
);

create table admins (
	id integer primary key autoincrement,
	email text not null,
	mot_de_passe text not null
);


insert into prefixes (valeur) values
	('033'),
	('037');

insert into types_operations (libelle) values
	('Dépôt'),
	('Retrait'),
	('Transfert');


insert into baremes_frais (type_operation, montant_min, montant_max, frais) values
	(2, 100, 1000, 50),
	(2, 1001, 5000, 50),
	(2, 5001, 10000, 100),
	(2, 10001, 25000, 200),
	(2, 25001, 50000, 400),

	(2, 50001, 100000, 800),
	(2, 100001, 250000, 1500),
	(2, 250001, 500000, 1500),
	(2, 500001, 1000000, 2500),
	(2, 1000001, 2000000, 3000),

	(3, 100, 1000, 50),
	(3, 1001, 5000, 50),
	(3, 5001, 10000, 100),
	(3, 10001, 25000, 200),
	(3, 25001, 50000, 400),

	(3, 50001, 100000, 800),
	(3, 100001, 250000, 1500),
	(3, 250001, 500000, 1500),
	(3, 500001, 1000000, 2500),
	(3, 1000001, 2000000, 3000);

insert into clients (numero) values
	('033 12 345 67'), -- 70 000 azo avy @depot (- tranfert -400) ( reste 40 000 - 400 = 39 600 )
	('033 11 223 34'), -- 30 000 azo avy @transfert

	('037 12 345 67'), -- 10 000 azo avy @depot (- tranfert -50) (reste 9 000 - 50 = 8 950)
	('037 11 223 34'); -- 1 000 azo avy @transfert

insert into operations (type, montant, frais, num_source, num_destination, date_operation) values
	(1, 70000, 800, '', '033 12 345 67', '2026-05-01'), -- depot
	(3, 30000, 400, '033 12 345 67', '033 11 223 34', '2026-05-18'), -- transfert

	(1, 10000, 100, '', '037 12 345 67', '2026-01-01'), -- depot
	(3, 1000, 50, '037 12 345 67', '037 11 223 34', '2026-03-09'); -- transfert


insert into admins (email, mot_de_passe) values
	('admin@root.dev', '$2y$10$s0gTQ0.ihOLNJN9PZ.jVruktyEGeZsWZ6HsuoJr833A9yzG5Stw.u'); -- 1234



create view v_mouvements as
select o.num_destination as numero, (o.montant + o.frais) as montant, o.date_operation
from operations o
where o.type = 1
union
select o.num_source, -(o.montant + o.frais), o.date_operation
from operations o
where o.type = 2
union
select o.num_source, -(o.montant + o.frais), o.date_operation
from operations o
where o.type = 3
union
select o.num_destination, (o.montant + o.frais), o.date_operation
from operations o
where o.type = 3;


create view v_soldes as
select m.numero, SUM(m.montant) as montant
from v_mouvements m
group by m.numero;
