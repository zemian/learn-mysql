
## Create new datadir

export PATH=/usr/local/opt/mysql/bin:$PATH
mkdir /usr/local/var/mysql-8-data
mysqld --datadir=/usr/local/var/mysql-8-data --initialize-insecure

## Test
mysqld --datadir=/usr/local/var/mysql-8-data --port=3306
mysqladmin -u root shutdown

## Setup homebrew services (Global my.cnf)

echo '
[mysqld]
port=3306
# Do not change default socket file so "mysql" client can connect easily
#socket=/usr/local/var/mysql-8-data/mysql.sock
log-error=/usr/local/var/mysql-8-data/mysqld.log
' > /usr/local/opt/mysql/my.cnf

Change datadir in /usr/local/opt/mysql/homebrew.mxcl.mysql\@5.7.plist
