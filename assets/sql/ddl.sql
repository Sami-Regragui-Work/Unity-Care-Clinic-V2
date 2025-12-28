-- Active: 1766926269402@@127.0.0.1@3306@UCCV2
DROP DATABASE UCCV2;
CREATE DATABASE UCCV2;

USE UCCV2;

CREATE TABLE patients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    gender ENUM('Male', 'Female', 'Other'),
    date_of_birth DATE,
    phone VARCHAR(15) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE,
    address VARCHAR(255)
);

CREATE TABLE departments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) UNIQUE NOT NULL,
    location VARCHAR(100) NOT NULL
);

CREATE TABLE doctors (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    specialization VARCHAR(50),
    phone VARCHAR(15) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    department_id INT NULL
);

ALTER TABLE doctors
ADD CONSTRAINT doctors_departements 
FOREIGN KEY (department_id) 
REFERENCES departments (id) 
ON DELETE SET NULL;