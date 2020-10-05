CREATE USER 'zemian'@'localhost' IDENTIFIED BY 'test123';
CREATE DATABASE testdb CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
GRANT ALL PRIVILEGES ON testdb.* TO 'zemian'@'localhost';
FLUSH PRIVILEGES;
