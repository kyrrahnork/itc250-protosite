#nm-wdk SQL file
#nm-wdk-01182018.sql
# search and replace on PREFIX_, replace with your prefix inside config.php 

drop table if exists PREFIX_Admin;
create table PREFIX_Admin(
AdminID int unsigned not null auto_increment primary key,
LastName varchar(50) DEFAULT '',
FirstName varchar(50) DEFAULT '',
Email varchar(120) DEFAULT '',
Privilege ENUM('admin','superadmin','developer') DEFAULT 'admin',
AdminPW varChar(255),
NumLogins int DEFAULT 0,
DateAdded DATETIME,
LastLogin DATETIME
);

insert into PREFIX_Admin values (NULL,'Sprockets','Spacely','developer@example.com','developer','92429d82a41e930486c6de5ebda9602d55c39986',0,now(),now());

drop table if exists PREFIX_Categories;
create table PREFIX_Categories(
CategoryID int unsigned not null auto_increment primary key,
Category varchar(120),
Description text
);
insert into PREFIX_Categories values(NULL, 'DotNet', 'Microsoft \'s flagship server technology.' );
insert into PREFIX_Categories values(NULL, 'PHP', 'The world\'s most popular open source technology.');
insert into PREFIX_Categories values(NULL, 'Programming', 'Books of general programming interest.');
insert into PREFIX_Categories values(NULL, 'HTML', 'Web page architecture.');
insert into PREFIX_Categories values(NULL, 'Networking', 'How networks connect us.');
insert into PREFIX_Categories values(NULL, 'ASP', 'Microsoft \'s classic server technology.');

drop table if exists PREFIX_Books;
create table PREFIX_Books(
BookID int unsigned not null auto_increment primary key,
BookTitle varchar(120),
Authors varchar(120),
CategoryID int DEFAULT 0,
ISBN varchar(30),
Edition varchar(20),
Description text,
Rating int,
Price float(6,2)
); 

insert into PREFIX_Books values(NULL, 'Professional ADO.NET','Smith',1,'568524456','2nd Edition','A great .NET book',8, 23.50);
insert into PREFIX_Books values(NULL, 'Apache Server Unleashed','Jones',2,'12345678','1st Edition','A great PHP book',7, 29.50);
insert into PREFIX_Books values(NULL, 'ASP.NET Unleashed','Doe',1,'345678976','1st Edition','A great .NET book',9, 39.95);
insert into PREFIX_Books values(NULL, 'Introducing .NET','Wilson',1,'67890567','3rd Edition','A great .NET book',8, 24.45);
insert into PREFIX_Books values(NULL, 'Professional C#','Jones',1,'568524456','1st Edition','A great .NET book',6, 38.45);
insert into PREFIX_Books values(NULL, 'Beginning C++','Jackson',3,'12345678','1st Edition','A great programming book',10, 41.40);
insert into PREFIX_Books values(NULL, 'Beginning J++','Johnson',3,'345678976','1st Edition','A great programming book',8,44.30);
insert into PREFIX_Books values(NULL, 'Beginning PHP','Smith',2,'345678976','2nd Edition','A great PHP book',7, 55.50);
insert into PREFIX_Books values(NULL, 'Beginning MySQL','McDonald',2,'67890567','1st Edition','A great PHP book',6, 98.20);
insert into PREFIX_Books values(NULL, 'Beginning Visual Basic','Cox',3,'12345678','1st Edition','A great .NET book',8, 58.95);
insert into PREFIX_Books values(NULL, 'Beginning XHTML','Jones',4,'12345678','1st Edition','A great HTML book',5, 39.95);
insert into PREFIX_Books values(NULL, 'Hacking Exposed','Evans',5,'12345678','2nd Edition','A great .NET book',9, 22.20);
insert into PREFIX_Books values(NULL, 'Effective Java','Franklin',3,'568524456','1st Edition','A great programming book',8, 91.20);
insert into PREFIX_Books values(NULL, 'JavaScript Bible','Jones',4,'12345678','1st Edition','A great HTML book',6, 33.55);
insert into PREFIX_Books values(NULL, 'Beginning PHP4 and XML','Doe',2,'12345678','2nd Edition','A great PHP book',7, 48.50);
insert into PREFIX_Books values(NULL, 'VBScript Regular Expressions','Smith',3,'12345678','1st Edition','A great programming book',7, 49.50);
insert into PREFIX_Books values(NULL, 'Programming ASP','Johnson',6,'67890567','4th Edition','A great ASP book',8, 49.50);
insert into PREFIX_Books values(NULL, 'Programming PHP','Doe',2,'345678976','1st Edition','A great PHP book',9, 49.50);
insert into PREFIX_Books values(NULL, 'Programming C#','Jones',1,'568524456','1st Edition','A great .NET book',7, 49.50);
insert into PREFIX_Books values(NULL, 'Programming Java','Smith',3,'56780765','5th Edition','A great programming book',6, 49.50);
insert into PREFIX_Books values(NULL, 'Introducing XML','Evans',4,'12345678','1st Edition','A great HTML book',8, 33.95);

drop table if exists test_Customers;
create table test_Customers
( CustomerID int unsigned not null auto_increment primary key,
LastName varchar(50),
FirstName varchar(50),
Email varchar(80)
);

insert into test_Customers values
(NULL,"Smith","Bob","bob@fake.com"),
(NULL,"Jones","Bill","bill@fake.com"),
(NULL,"Doe","John","john@fake.com"),
(NULL,"Rules","Ann","ann@fake.com");

drop table if exists test_Muffins;
create table test_Muffins
( MuffinID int unsigned not null auto_increment primary key,
MuffinName varchar(60),
Description text,
MetaDescription varchar(255),
MetaKeywords varchar(255),
Price decimal(5,2)
);
insert into test_Muffins values (NULL,"Apple","An apple muffin a day keeps the doctor away!","Apple muffins are our specialty.","apple",.99);
insert into test_Muffins values (NULL,"Banana Nut","Bananas and walnuts combine in a rich and rewarding muffin!","Go ape over our banana nut muffins!","banana,walnut,banana nut",1.50);
insert into test_Muffins values (NULL,"Blueberry","Our wildly popular traditional blueberry muffin.","Our Blueberry muffins are made from fresh blueberries.","blueberry,berry,anti-oxidant",1.25);
insert into test_Muffins values (NULL,"Chocolate","The chocoholics love us for this one!","Keep our chocolate muffins on hand for emergencies!","chocolate,chocolate chip",1.50);
insert into test_Muffins values (NULL,"Bran","Our bran muffins are a favorite among our regular customers!","Our bran muffins keep you going!","bran",.99);
insert into test_Muffins values (NULL,"Raspberry","A truly decadent raspberry streusel muffin!","Rasberry streusel for special occasions!","raspberry streusel,raspberry,berry",1.99);
