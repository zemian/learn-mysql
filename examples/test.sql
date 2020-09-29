-- Notes for MySQL (8)
-- https://dev.mysql.com/doc/refman/8.0/en/

-- Data Types
-- https://dev.mysql.com/doc/refman/8.0/en/data-types.html

/*

CHAR          Fixed length and space padded character string (sql: CHAR)
VARCHAR       Variable length character string (sql: VARCHAR)
TEXT          Variable length character large string (sql: TEXT)

INT           Declare integer number (sql: INT 32 bits)
SMALLINT      16 bits
BIGINT        64 bits
SERIAL        Auto increment integer (INT with sequence)
              An alias for BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE.

REAL             Declare floating-point number 32 bits
DOUBLE PRECISION Declare floating-point number 64 bits

NUMERIC(p,s)     Declare fixed-point number (sql: NUMERIC)

DATE          Declare date
TIME          Declare time
TIMESTAMP     Declare datetime (stored as UTC but convert to client session timezone)

BLOB          Binary data

 */

-- test table
create table test(
  id       serial primary key,
  ts       timestamp default current_timestamp,
  cat      varchar(10),

  price    numeric(19,4),
  qty      int,

  txtdata  text,
  bindata  blob,

  distx    real,
  disty    double precision
);
insert into test(cat, price, qty) values ('test', 100000.10, 50000),
                                         ('test', 100000.20, 0),
                                         ('test', 100000.00, 1),
                                         ('test', 9977000.3333, 179),
                                         ('test', 104729.1129, 104729);
insert into test(cat, bindata, txtdata) values ('test2', X'CAFEBABE', 'CAFEBABE');
insert into test(cat, bindata) values ('test3', unhex(replace(uuid(), '-','')));
insert into test(cat, bindata) values ('test3', unhex(replace(uuid(), '-','')));
insert into test(cat, bindata) values ('test3', unhex(replace(uuid(), '-','')));
update test set txtdata = hex(bindata) where cat = 'test3';
insert into test(cat, distx, disty) values ('test4', rand(), rand());
insert into test(cat, distx, disty) values ('test4', rand(), rand());
insert into test(cat, distx, disty) values ('test4', rand(), rand());
select sum(price) from test where cat = 'test';
select * from test order by cat, ts desc;
