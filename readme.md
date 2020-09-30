# MySQL Database

https://dev.mysql.com/doc/refman/8.0/en/

## Server Setup

For a brand new mysql installation:

```
export PATH=/usr/local/mysql/bin:$PATH
cd /usr/local/var
mysqld --datadir=./mysql-data --initialize-insecure
mysqld --datadir=./mysql-data --port=3306

# To shutdown, you must use another terminal:
mysqladmin -u root shutdown

# To connect with client
mysql --port=3306 -u root
```

* The `--initialize-insecure` means to init with root without password. If you do not use use this, the password will print on console, and you must copy it.
* On MacOS, the Homebrew might install mysql into `/usr/local/opt/mysql` instead.
* If `--datadir` path is just a folder name, then it will be relative to installation path.
* You may use explicit server encoding: `--character-set-server=utf8mb4 --collation-server=utf8mb4_unicode_ci`
* You can also shutdown withg  `kill <PID>` command

For more server options see https://dev.mysql.com/doc/refman/8.0/en/server-options.html

## Docs

More references:
* https://dev.mysql.com/doc/refman/8.0/en/mysql-command-options.html How to use `mysql` client
* https://dev.mysql.com/doc/refman/8.0/en/data-types.html - Data types
* https://dev.mysql.com/doc/refman/8.0/en/sql-function-reference.html Functions & Operators
* https://dev.mysql.com/doc/refman/8.0/en/sql-statements.html SQL statements

## Quick Client Commands

```
show databases;
use testdb;
show tables;
desc hello;
select version();
select * \c;
exit
```

## Quick SQL Test

```sql
create table hello(name text, num int);
insert into hello values('foo', 123);
insert into hello values('foo2', 123);
select * from hello;
update hello set num = 99 where name = 'foo';
delete from hello where name = 'foo';
```

## Loading SQL from file

    mysql -u root < input.sql

## New Database Setup

```sql
CREATE USER 'zemian'@'localhost' IDENTIFIED BY 'test123';
CREATE DATABASE testdb CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
GRANT ALL PRIVILEGES ON testdb.* TO 'zemian'@'localhost';
FLUSH PRIVILEGES;
```

## Test Data

Here is an example table and few rows of sample data for testing:

    mysql -u root testdb < examples/test.sql

## Authentication

If you want older MySQL 5 user password encryption, create user like this:

    CREATE USER 'zemian'@'localhost' IDENTIFIED WITH mysql_native_password BY 'test123';

See https://dev.mysql.com/doc/refman/8.0/en/native-pluggable-authentication.html

## Install and Setup MySQL 8 on Mac

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
CREATE DATABASE testdb CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
GRANT ALL PRIVILEGES ON testdb.* TO 'zemian'@'localhost';
FLUSH PRIVILEGES;

mysql -u zemian -p testdb

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
mysqldump --single-transaction --quick --no-autocommit --extended-insert=false -u root testdb > testdb-`date +%s`-dump.sql

# Restore
mysql -f -u root testdb < testdb-<date>-dump.sql
```

