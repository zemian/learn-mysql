The Employees sample database was developed by Patrick Crews and Giuseppe Maxia and provides a combination of a large base of data (approximately 160MB) spread over six separate tables and consisting of 4 million records in total. The structure is compatible with a wide range of storage engine types. Through an included data file, support for partitioned tables is also provided. 

https://dev.mysql.com/doc/employee/en/employees-installation.html

1. Download zip file from https://github.com/datacharmer/test_db (Go to Code dropdown the Download ZIP)
2. Run:
    ```sh
    shell> unzip test_db-master.zip
    shell> cd test_db-master/
    shell> mysql -t < employees.sql
    ```
