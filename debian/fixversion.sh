#!/bin/bash
VERSTR=`dpkg-parsechangelog --show-field Version`
COMPOSER_VERSTR=`echo ${VERSTR}|sed 's/-/./g'`
sed -i -e "/public static \$libVersion/c\    public static \$libVersion = '${VERSTR}-deb';" debian/php-vitexsoftware-datamolino/usr/share/php/Datamolino/ApiClient.php
sed -i -e "/public static \$libVersion/c\    public static \$libVersion = '${VERSTR}';" src/Datamolino/ApiClient.php
