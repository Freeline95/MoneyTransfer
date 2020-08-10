-- Create user
CREATE USER postgres_user WITH PASSWORD '123456';

-- Create DB
CREATE DATABASE money_transfer_db OWNER 'postgres_user';