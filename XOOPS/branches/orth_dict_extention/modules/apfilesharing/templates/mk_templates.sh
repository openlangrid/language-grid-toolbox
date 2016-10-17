#!/bin/sh
if [ -z "$1" ]; then
	echo 'usage: mk_templates.sh modulesnumber'
else

cp -a blocks/filesharing_block_rphoto.html blocks/filesharing$1_block_rphoto.html
cp -a blocks/filesharing_block_tophits.html blocks/filesharing$1_block_tophits.html
cp -a blocks/filesharing_block_tophits_p.html blocks/filesharing$1_block_tophits_p.html
cp -a blocks/filesharing_block_topnews.html blocks/filesharing$1_block_topnews.html
cp -a blocks/filesharing_block_topnews_p.html blocks/filesharing$1_block_topnews_p.html
cp -a filesharing_categories.html filesharing$1_categories.html
cp -a filesharing_footer.html filesharing$1_footer.html
cp -a filesharing_header.html filesharing$1_header.html
cp -a filesharing_imagemanager.html filesharing$1_imagemanager.html
cp -a filesharing_photo_in_list.html filesharing$1_photo_in_list.html
perl -pe "s/db\\:filesharing_/db\\:filesharing$1_/g" <filesharing_index.html >filesharing$1_index.html
perl -pe "s/db\\:filesharing_/db\\:filesharing$1_/g" <filesharing_photo.html >filesharing$1_photo.html
perl -pe "s/db\\:filesharing_/db\\:filesharing$1_/g" <filesharing_ratephoto.html >filesharing$1_ratephoto.html
perl -pe "s/db\\:filesharing_/db\\:filesharing$1_/g" <filesharing_topten.html >filesharing$1_topten.html
perl -pe "s/db\\:filesharing_/db\\:filesharing$1_/g" <filesharing_viewcat_list.html >filesharing$1_viewcat_list.html
perl -pe "s/db\\:filesharing_/db\\:filesharing$1_/g" <filesharing_viewcat_table.html >filesharing$1_viewcat_table.html

fi
