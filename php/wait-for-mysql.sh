#!/bin/bash
set -e

# Loop until MySQL is available
until mysql -h "mysql" -u"root" -p"root" -e 'SELECT 1'; do
  >&2 echo "MySQL is unavailable - sleeping"
  sleep 5
done

>&2 echo "MySQL is up - executing command"
exec "$@"
