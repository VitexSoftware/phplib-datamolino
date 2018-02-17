FROM vitexus/ease-framework
COPY src/ /usr/share/php/Datamolino
COPY debian/composer.json /usr/share/php/Datamolino/composer.json
COPY docs/  /usr/share/doc/libphp-datamolino/html
