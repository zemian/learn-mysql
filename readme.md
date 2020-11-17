# MySQL Database

https://dev.mysql.com/doc/refman/8.0/en/

## New Database & User Setup

```sql
CREATE USER IF NOT EXISTS 'zemian'@'localhost' IDENTIFIED BY 'test123';
CREATE DATABASE testdb CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
GRANT ALL PRIVILEGES ON testdb.* TO 'zemian'@'localhost';
```

To support older (MySQL 5) clients, it might be useful to create a user that uses older hashing algorithm
  
  CREATE USER IF NOT EXISTS 'zemiannative'@'localhost' IDENTIFIED WITH mysql_native_password BY 'test123';
  GRANT ALL PRIVILEGES ON testdb.* TO 'zemiannative'@'localhost';


If you want the user to have acess to all DB, then run:

    GRANT ALL ON *.* TO 'zemian'@'localhost';

## When to use `FLUSH PRIVILEGES` ?

The `FLUSH PRIVILEGES` is NOT needed if you use `GRANT` command. 

Privileges assigned through GRANT option do not need FLUSH PRIVILEGES to take effect - MySQL server will notice these changes and reload the grant tables immediately.

https://stackoverflow.com/questions/36463966/when-is-flush-privileges-in-mysql-really-needed

## Server Setup

For a brand new mysql installation:

```
export PATH=/usr/local/mysql/bin:$PATH
mkdir /usr/local/var/mysql-data
mysqld --datadir=/usr/local/var/mysql-data --initialize-insecure
mysqld --datadir=/usr/local/var/mysql-data --port=3306

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

## Where to add my.cnf file

Option1: Add to where installation folder is (global): /usr/local/mysql
Option2: Add to where data directory is: /usr/local/var/mysql-data
Option3: Add to anywhere and use: mysqld -defaults-file=my.cnf

Example of my.cnf

```
[mysqld]
port=3306
socket=/usr/local/var/mysql-data/mysql.sock
log-error=/usr/local/var/mysql-data/mysqld.log 
```

More my.cnf examples:

https://dev.mysql.com/doc/refman/8.0/en/option-files.html

```
[mysqld]
port=3306
socket=/tmp/mysql.sock
key_buffer_size=16M
max_allowed_packet=128M
```

## How to check where `datadir` is for a running server

    mysql -u root -e 'SHOW VARIABLES WHERE Variable_Name LIKE "datadir"'

In case if server is not running, and if you are using Homebrew service, you can check the `.plist` files like this:

    grep datadir /usr/local/Cellar/mysql/8.0.21_1/homebrew.mxcl.mysql.plist

A typical `datadir` is located at `/usr/local/var/mysql`.

IMPORANT: If you are running multiple version of MySQL, it's best to use
different `datadir` folder!

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

Using the client on bash:

    mysql -u root < input.sql

Or inside the client, you may use the `source` command.

## Test Data

Here is an example table and few rows of sample data for testing:

    mysql -u root testdb < examples/test.sql

## Authentication & MySQL 8 Password

The MySQL 8 default to use `caching_sha2_password`, while MySQL 5 is using `mysql_native_password`. The client must be supporting `caching_sha2_password` in order to connect. Otherwise, you need to change your DB user back to old password encryption like this:

    CREATE USER 'zemian'@'localhost' IDENTIFIED WITH mysql_native_password BY 'test123';


Or to change it:

    ALTER USER 'zemian'@'localhost' IDENTIFIED WITH mysql_native_password BY 'test123';

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

CREATE USER 'zemian'@'localhost' IDENTIFIED BY 'test123';
CREATE DATABASE testdb CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
GRANT ALL PRIVILEGES ON testdb.* TO 'zemian'@'localhost';
FLUSH PRIVILEGES;

mysql -u zemian -p testdb

CREATE TABLE config(id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(200), value VARCHAR(1000));
INSERT INTO config(name, value) values('foo', 'bar'), ('num', '123');
```

## How to update existing DB user password

    ALTER USER 'zemian'@'localhost' IDENTIFIED BY 'test123';

NOTE: If you want empty password (eg: for root in local dev) then simply use empty quote!

Alternatively you may use this command as well:

    mysqladmin -u root -p'oldpassword' password 'newpassword'
    
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


## TODO: MySQL 8 startup warnings

```
2020-11-16T15:14:21.848873Z 0 [Warning] [MY-013242] [Server] --character-set-server: 'utf8' is currently an alias for the character set UTF8MB3, but will be an alias for UTF8MB4 in a future release. Please consider using UTF8MB4 in order to be unambiguous.
2020-11-16T15:14:21.848891Z 0 [Warning] [MY-013244] [Server] --collation-server: 'utf8_unicode_ci' is a collation of the deprecated character set UTF8MB3. Please consider using UTF8MB4 with an appropriate collation instead.
2020-11-16T15:14:21.849725Z 0 [Warning] [MY-010159] [Server] Setting lower_case_table_names=2 because file system for /usr/local/var/mysql/ is case insensitive
```

