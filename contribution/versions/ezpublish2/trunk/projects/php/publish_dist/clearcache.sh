#!/bin/sh
echo "Clearing the cache."

dirs="
ezad
ezaddress
ezarticle
ezbug
ezcalendar
ezcontact
ezforum
ezlink
eznewsfeed
ezpoll
ezstats
eztodo
eztrade
ezuser
ezfilemanager
ezimagecatalogue
"

for dir in $dirs
do
    if [ -d $dir ]; then
	    echo "Clearing $dir"
        rm -f $dir/cache/*.cache
	if [ -d $dir/admin/cache/ ]; then
	    rm -f $dir/admin/cache/*.cache
	fi
    else
        echo "Creating $dir"
	    mkdir -p $dir
    fi
    chmod 777 $dir   
done
