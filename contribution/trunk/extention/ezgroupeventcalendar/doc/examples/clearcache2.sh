#!/bin/sh
echo "Clearing the cache."

#ezrfp
rfp_clear_dirs="
ezcontact
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


clear_base_dirs="
ezgroupeventcalendar
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
ezsitemanager
ezquiz
classes
ezurltranslator
ezbulkmail
ezform
ezmediacatalogue
ezsysinfo
ezvote
"
root="$PWD";

for dir in $clear_base_dirs
do
    if [ -d $dir ]; then
            echo "Clearing $dir"
        if [ -d $dir/cache ]; then 
            #rm -f $dir/cache/*.cache
            cd "$dir/cache/";
	    find . -name '*.cache' -print0 | xargs -0 rm -vf
	    #rm -f $dir/cache/*.php
            #find . -name '*.php' -print0 ; 
            find . -name '*.php' -print0 | xargs -0 rm -vf
	    cd "$root";
	fi
	if [ -d $dir/admin/cache/ ]; then
	    cd "$dir/admin/cache/";
	    #rm -f $dir/admin/cache/*.cache
            find . -name '*.cache' -print0 | xargs -0 rm -vf
	    cd "$root";
	fi 
	if [ -d $dir/user/cache/ ]; then
	    cd "$dir/user/cache/";
	    #rm -f $dir/user/cache/*.cache
            find . -name '*.cache' -print0 | xargs -0 rm -vf
	    cd "$root";
	fi
	    echo "";
    else
        echo "Creating $dir"
	    #echo "problem with $root | $PWD | $dir \n"
	    mkdir -p $dir
    fi
    #chmod 777 $dir   
done
