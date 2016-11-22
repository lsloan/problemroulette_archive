SQL_SERVER='localhost'
SQL_USER='problemroulette'
SQL_DATABASE='pr'

SCHEMA_SQL_FILE='sql/schema.sql'
if [ -f 'sql/pr.sql' ]; then
  SCHEMA_SQL_FILE='sql/pr.sql'
fi

mysql -v -u root << EOT
create database if not exists ${SQL_DATABASE};
grant all privileges on ${SQL_DATABASE}.* to '${SQL_USER}'@'${SQL_SERVER}';
connect ${SQL_DATABASE};
$(cat ${SCHEMA_SQL_FILE})
EOT

REMOTE_USER_STATEMENT='SetEnv REMOTE_USER jtritz'
SCOTCHBOX_CONF_FILEPATH='/etc/apache2/sites-enabled/scotchbox.local.conf'

grep -q "${REMOTE_USER_STATEMENT}" ${SCOTCHBOX_CONF_FILEPATH} || \
  sudo sed -i \
  's/^\([[:space:]]*\)\(DocumentRoot.*\)$/\1\2\n\1'"${REMOTE_USER_STATEMENT}"'/' \
  ${SCOTCHBOX_CONF_FILEPATH} 
