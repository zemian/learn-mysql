## Setup Python with MySQL Driver

```sh
python3 -m venv mypy
source mypy/bin/activate
python -V

pip list
pip install mysql-connector-python

cd learn-mysql/python-examples
python hello.py

# To exit
deactivate
```

See https://dev.mysql.com/doc/connector-python/en/connector-python-example-connecting.html
