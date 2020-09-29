# MySQL Database

https://dev.mysql.com/doc/refman/8.0/en/

More references:
* https://dev.mysql.com/doc/refman/8.0/en/mysql-command-options.html How to use `mysql` client
* https://dev.mysql.com/doc/refman/8.0/en/data-types.html - Data types
* https://dev.mysql.com/doc/refman/8.0/en/sql-function-reference.html Functions & Operators
* https://dev.mysql.com/doc/refman/8.0/en/sql-statements.html SQL statements

## MySQL 8 on Mac

```bash
brew install services mysql
brew services start mysql

# Or you may start it manually:
mysql.server start

mysql -u root
```

## Setup MySQL DB 8

```
mysql -u root

CREATE USER 'zemian'@'localhost' IDENTIFIED WITH mysql_native_password BY 'test123';
CREATE DATABASE zemiandb CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
GRANT ALL PRIVILEGES ON zemiandb.* TO 'zemian'@'localhost';
FLUSH PRIVILEGES;

mysql -u zemian -p zemiandb

CREATE TABLE config(id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(200), value VARCHAR(1000));
INSERT INTO config(name, value) values('foo', 'bar'), ('num', '123');
```

## How to update existing DB user password

    ALTER USER 'zemian'@'localhost' IDENTIFIED WITH mysql_native_password BY 'test123';


## Where is `my.cnf` file?

Run `mysql --help` to see where `my.cnf` is loaded.

Ref: https://thisinterestsme.com/charset-255-unknown-mysql/

## Setup MySQL 5.7 on Mac

ref: https://gist.github.com/operatino/392614486ce4421063b9dece4dfe6c21

```
brew install mysql@5.7
brew link --force mysql@5.7

# Add the following to path
echo 'export PATH="/usr/local/opt/mysql@5.7/bin:$PATH"' >> ~/.zshrc

# Verify
mysql -V # => mysql  Ver 14.14 Distrib 5.7.31, for osx10.15 (x86_64) using  EditLine wrapper


# NOTE: If you have previous database already created `/usr/local/var/mysql` data directory, 
# you need to remove it and then reinitialize database first.
# Note and record the root password
mysqld --initialize

brew services start mysql@5.7

# Or you may start it manually:
mysql.server start

# To change the root password, or NOT set password
mysqladmin -u root -p password ''
```

## How to backup and restore DB for local dev

```
# Backup
mysqldump --single-transaction --quick --no-autocommit --extended-insert=false -u root zemiandb > zemiandb-`date +%s`-dump.sql

# Restore
mysql -f -u root zemiandb < zemiandb-<date>-dump.sql
```

