#!/bin/bash
echo "Creating symbolic links and setting permissions as needed."
chmod 666 site.ini
chmod 666 ezforum/intl/en_GB/*.ini
chmod 666 ezforum/intl/no_NO/*.ini

touch error.log
chmod 666 error.log

if [ -d admin/tmp ]; then
	echo "admin/tmp already exist"
else
	mkdir -p admin/tmp
fi

if [ -d ezimagecatalogue/catalogue ]; then
	echo "ezimagecatalogue/catalogue already exist"
else
	mkdir -p ezimagecatalogue/catalogue
fi

if [ -d ezimagecatalogue/catalogue/variations ]; then
	echo "ezimagecatalogue/catalogue/variations already exist"
else
	mkdir -p ezimagecatalogue/catalogue/variations
fi


if [ -d ezpoll/cache ]; then
	echo "ezpoll/cache already exist"
else
	mkdir -p ezpoll/cache
fi

if [ -d ezarticle/cache ]; then
	echo "ezarticle/cache already exist"
else
	mkdir -p ezarticle/cache
fi

if [ -d ezforum/cache ]; then
	echo "ezforum/cache already exist"
else
	mkdir -p ezforum/cache
fi

if [ -d ezlink/cache ]; then
	echo "ezlink/cache already exist"
else
	mkdir -p ezlink/cache
fi

chmod 777 admin/tmp
chmod 777 ezimagecatalogue/catalogue
chmod 777 ezimagecatalogue/catalogue/variations
chmod 777 ezarticle/cache
chmod 777 ezforum/cache
chmod 777 ezlink/cache
chmod 777 ezpoll/cache


if [ -d admin/error.log ]; then
	echo "admin/error.log already exist"
else
	ln -s ../error.log admin/error.log
fi

if [ -d admin/ezlink ]; then
	echo "admin/ezlink already exist"
else
	ln -s ../ezlink admin/ezlink
fi

if [ -f admin/site.ini ]; then
	echo "admin/site.ini already exist"
else
	ln -s ../site.ini admin/site.ini
fi

if [ -d admin/ezforum ]; then
	echo "admin/ezforum already exist"
else
	ln -s ../ezforum admin/ezforum
fi

if [ -d admin/ezarticle ]; then
	echo "admin/ezarticle already exist"
else
	ln -s ../ezarticle admin/ezarticle
fi

if [ -d admin/classes ]; then
	echo "admin/classes already exist"
else
	ln -s ../classes admin/classes
fi


if [ -d admin/eznews ]; then
	echo "admin/eznews already exist"
else
	ln -s ../eznews admin/eznews
fi

if [ -d admin/ezimagecatalogue ]; then
	echo "admin/ezimagecatalogue already exist"
else
	ln -s ../ezimagecatalogue admin/ezimagecatalogue
fi

if [ -d admin/ezpoll ]; then
	echo "admin/ezpoll already exist"
else
	ln -s ../ezpoll admin/ezpoll
fi

if [ -d admin/ezuser ]; then
	echo "admin/ezuser already exist"
else
	ln -s ../ezuser admin/ezuser
fi

if [ -d admin/ezsession ]; then
	echo "admin/ezsession already exist"
else
	ln -s ../ezsession admin/ezsession
fi

if [ -d admin/ezcontact ]; then
	echo "admin/ezcontact already exist"
else
	ln -s ../ezcontact admin/ezcontact
fi
