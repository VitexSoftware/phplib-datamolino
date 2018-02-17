all: fresh build install

fresh:
	git pull
	cd debian
	composer update
	cd ..

install: build
	echo install
	
build: doc
	docker build -t php-datamolino .
	echo build

clean:
	rm -rf debian/php-datamolino
	rm -rf debian/php-datamolino-doc
	rm -rf debian/*.log
	rm -rf docs/*
	rm -rf vendor/* debian/vendor

doc:
	debian/apigendoc.sh

test:
	phpunit tests/ --configuration phpunit.xml

docker:
	docker build . -t phplib-datamolino
	docker push vitexus/phplib-datamolino:latest

deb:
	debuild -i -us -uc -b

.PHONY : install
	
