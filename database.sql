CREATE TABLE groups(
    id int not null auto_increment primary key ,
    name varchar(255) not null
);

CREATE TABLE frogs(
    id int not null auto_increment primary key,
    weight int not null,
    color varchar(150) not null,
    batch varchar(150) null
);
