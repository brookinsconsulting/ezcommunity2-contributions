#!/bin/sh
## Creates symlinks for files that are NOT supposed to be owned by apache.

files="
error.log
ezlink
site.ini
ezforum
ezarticle
ezad
classes
ezclassified
ezimagecatalogue
ezfilemanager
ezpoll
ezuser
ezsession
ezcontact
ezstats
eztodo
eznewsfeed
eztrade
ezaddress
ezbug
ezexample
ezcalendar
ezerror
"

for file in $files
do
    if [ -e $file ]; then
	if [ -e admin/$file ]; then
	    echo "admin/$file already exist"
	else
	    echo "Linking ./$file to admin/$file"
	    ln -s ../$file admin/$file
	fi
    fi
done

if [ -d "override" ]; then
    if [ ! -d "admin/override" ]; then
	echo "Linking override to admin/override"
	ln -sf ../override admin/override
    fi
fi
