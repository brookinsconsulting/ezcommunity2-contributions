#!/bin/bash
echo "Creating symbolic links and setting permissions as needed."
chmod 666 site.ini

touch error.log
chmod 666 error.log



dirs="
admin/tmp
ezimagecatalogue/catalogue
ezimagecatalogue/catalogue/variations
ezfilemanager/files
ezpoll/cache
ezarticle/cache
eznewsfeed/cache
ezforum/cache
ezlink/cache
"

for dir in $dirs
do
    if [ -d $dir ]; then
	    echo "$dir already exist"
    else
        echo "Creating $dir"
	    mkdir -p $dir
    fi
    chmod 777 $dir   
done

files="
error.log
ezlink
site.ini
ezforum
ezarticle
ezad
classes
ezclassified
eznews
ezimagecatalogue
ezfilemanager
ezpoll
ezuser
ezsession
ezcontact
ezstats
"

for file in $files
do
    if [ -d admin/$file ]; then
	    echo "admin/$file already exist"
    else
	    echo "Linking ./$file to admin/$file"
	    ln -s ../$file admin/$file
    fi
done
