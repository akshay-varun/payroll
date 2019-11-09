-- Creating the database
CREATE DATABASE payroll;

-- Create an new user and give SELECT, INSERT & UPDATE PERMISSION on the DB
CREATE USER 'payroll_user'@'localhost' IDENTIFIED BY 'Aksh!rthmcjjdskm8';
GRANT SELECT, INSERT, UPDATE ON payroll.* TO 'payroll_user'@'localhost';