drop database if exists n0kama00;
CREATE database n0kama00;
use n0kama00;

CREATE TABLE IF NOT EXISTS user (
        first_name varchar(50) NOT NULL,
        last_name varchar(50) NOT NULL,
        username varchar(50) NOT NULL,
        password varchar(150) NOT NULL,
        PRIMARY KEY (username)
        );

CREATE TABLE IF NOT EXISTS user_info(
    username varchar(50) NOT NULL,
    email varchar(50) PRIMARY KEY,
    phone int NOT NULL,
    address varchar (50) NOT NULL,
    zipcode varchar (5) NOT NULL,
    city varchar(20) NOT NULL,
    FOREIGN KEY (username) 
    REFERENCES user(username)
    );