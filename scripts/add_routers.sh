#!/bin/bash
cd /var/htdocs/wotom.net/_cli/routers
while (true)
do
cd /var/htdocs/wotom.net/_modules/routers/scripts/
php -f /var/htdocs/wotom.net/_modules/routers/scripts/ADD_ROUTERS_FROM_CSV.php 
done
