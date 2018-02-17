#!/bin/bash
/usr/bin/apigen generate --source src --destination docs --title "PHP Datamolino `cat debian/version` " --charset UTF-8 --access-levels public --access-levels protected --php --tree
