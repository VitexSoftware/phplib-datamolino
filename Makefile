package=$(head -n1 debian/changelog | awk '{print $1}')
repoversion=$(shell LANG=C aptitude show php-flexibee | grep Version: | awk '{print $$2}')
nextversion=$(shell echo $(repoversion) | perl -ne 'chomp; print join(".", splice(@{[split/\./,$$_]}, 0, -1), map {++$$_} pop @{[split/\./,$$_]}), "\n";')


all:

fresh:
	git pull
	cd debian
	composer update
	cd ..

clean:
	rm -rf debian/php-vitexsoftware-datamolino
	rm -rf debian/php-vitexsoftware-datamolino-doc
	rm -rf debian/*.log
	rm -rf docs/*
	rm -rf vendor/* debian/vendor

phpunit:
	phpunit tests/ --configuration phpunit.xml

docker:
	docker build . -t phplib-datamolino
	docker push vitexus/phplib-datamolino:latest

deb:
	debuild -i -us -uc -b

release:
	echo Release v$(nextversion)
	dch -v $(nextversion) `git log -1 --pretty=%B | head -n 1`
	debuild -i -us -uc -b
	git commit -a -m "Release v$(nextversion)"
	git tag -a $(nextversion) -m "version $(nextversion)"



.PHONY : install
	
