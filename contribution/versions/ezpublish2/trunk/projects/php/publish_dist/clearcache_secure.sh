#!/bin/sh

if [ `whoami` != "root" ] 
then
echo "Must be run as root or the apache user"
exit 0
fi

echo "Clearing the cache."

dirs="
ezlink
ezforum
ezpoll
eztrade
ezarticle
ezcontact
eztodo
ezstats
eznewsfeed
ezad
ezuser
ezaddress
ezcalendar/user
"

for dir in $dirs
do
    if [ -d $dir ]; then
	    echo "Clearing $dir"
        rm -f $dir/cache/*.cache
	if [ -d $dir/admin/cache/ ]; then
	    rm -f $dir/admin/cache/*.cache
	fi
#    else
#        echo "Creating $dir"
#	    mkdir -p $dir
    fi
    chmod 750 $dir   
done
