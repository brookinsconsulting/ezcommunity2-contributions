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

for dir in $clear_dirs
do
    if [ -d $dir ]; then
            echo "Clearing $dir"
        rm -f $dir/admin/*~
        rm -f $dir/admin/templates/nsb_rfp/*~
        rm -f $dir/user/*~
        rm -f $dir/user/templates/nsb_rfp/*~
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
