
## Create new datadir

export PATH=/usr/local/opt/mysql@5.7/bin:$PATH
mkdir /usr/local/var/mysql-5.7-data
mysqld --datadir=/usr/local/var/mysql-5.7-data --initialize-insecure

## Test
mysqld --datadir=/usr/local/var/mysql-5.7-data --port=3307
mysqladmin -u root shutdown

## Setup homebrew services (Global my.cnf)

echo '
[mysqld]
port=3307
socket=/usr/local/var/mysql-5.7-data/mysql.sock
log-error=/usr/local/var/mysql-5.7-data/mysqld.log
' > /usr/local/opt/mysql@5.7/my.cnf

Change datadir in /usr/local/opt/mysql@5.7/homebrew.mxcl.mysql\@5.7.plist
