#!/bin/sh
echo "Creating symbolic links and setting permissions as needed."
chmod 600 site.ini
if [ -f "override/site.ini" ]; then
    chmod 600 override/site.ini
fi
if [ -f "override/site.ini.append" ]; then
    chmod 600 override/site.ini.append
fi

touch error.log
chmod 600 error.log

# [cache section]
# This part will create the cache dirs which are needed and make sure
# that they are writeable by php.

dirs="
admin/tmp
ezad/admin/cache
ezaddress/admin/cache
ezarticle/admin/cache
ezarticle/cache
ezbug/user/cache
ezbug/admin/cache
ezcalendar/admin/cache
ezcalendar/user/cache
ezcontact/admin/cache
ezexample/admin/cache
ezfilemanager/files
ezforum/admin/cache
ezforum/cache
ezimagecatalogue/catalogue
ezimagecatalogue/catalogue/variations
ezlink/admin/cache
ezlink/cache
eznewsfeed/admin/cache
eznewsfeed/cache
ezpoll/admin/cache
ezpoll/cache
ezstats/admin/cache
eztodo/admin/cache
eztrade/admin/cache
eztrade/cache
ezuser/admin/cache
ezfilemanager/admin/cache
ezimagecatalogue/admin/cache
"

for dir in $dirs
do
    if [ -d $dir ]; then
	    echo "$dir already exist"
    else
        echo "Creating $dir"
	    mkdir -p $dir
    fi
    chmod 750 $dir   
done

for dir in $dirs
do
    override_dir="override/"$dir
    if [ -d $override_dir ]; then
	chmod 750 $override_dir
    fi
done

# [admin section]
# This part will link the modules into the admin directory

