#!/bin/sh
echo "Clearing the cache."

clear_dirs="
ezrfp
ezaddress
ezarticle
ezstats
ezuser
ezfilemanager
ezsitemanager
classes
ezurltranslator
ezform
"


old_dirs="
ezad
ezrfp
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
ezsitemanager
ezquiz
classes
ezurltranslator
ezbulkmail
ezform
ezmediacatalogue
ezsysinfo
"

for dir in $clear_dirs
do
    if [ -d $dir ]; then
	    echo "Clearing $dir"
        rm -f $dir/cache/*.cache
	rm -f $dir/cache/*.php
	if [ -d $dir/admin/cache/ ]; then
	    rm -f $dir/admin/cache/*.cache
	fi
	if [ -d $dir/user/cache/ ]; then
	    rm -f $dir/user/cache/*.cache
	fi
    else
        echo "Creating $dir"
	    # mkdir -p $dir
    fi
    chmod 777 $dir   
done
