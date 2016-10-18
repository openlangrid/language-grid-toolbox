<?php

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( 'FSHARE_CNST_LOADED' ) ) {

define( 'FSHARE_CNST_LOADED' , 1 ) ;

// System Constants (Don't Edit)
define( "GPERM_INSERTABLE" , 1 ) ;
define( "GPERM_SUPERINSERT" , 2 ) ;
define( "GPERM_EDITABLE" , 4 ) ;
define( "GPERM_SUPEREDIT" , 8 ) ;
define( "GPERM_DELETABLE" , 16 ) ;
define( "GPERM_SUPERDELETE" , 32 ) ;
define( "GPERM_TOUCHOTHERS" , 64 ) ;
define( "GPERM_SUPERTOUCHOTHERS" , 128 ) ;
define( "GPERM_RATEVIEW" , 256 ) ;
define( "GPERM_RATEVOTE" , 512 ) ;
define( "GPERM_TELLAFRIEND" , 1024 ) ;

// Global Group Permission
define( "_MD_ALBM_GPERM_G_INSERTABLE" , "Post (need approval)" ) ;
define( "_MD_ALBM_GPERM_G_SUPERINSERT" , "Super Post" ) ;
define( "_MD_ALBM_GPERM_G_EDITABLE" , "Edit (need approval)" ) ;
define( "_MD_ALBM_GPERM_G_SUPEREDIT" , "Super Edit" ) ;
define( "_MD_ALBM_GPERM_G_DELETABLE" , "Delete (need approval)" ) ;
define( "_MD_ALBM_GPERM_G_SUPERDELETE" , "Super Delete" ) ;
define( "_MD_ALBM_GPERM_G_TOUCHOTHERS" , "Touch files posted by others" ) ;
define( "_MD_ALBM_GPERM_G_SUPERTOUCHOTHERS" , "Super Touch others" ) ;
define( "_MD_ALBM_GPERM_G_RATEVIEW" , "View Rate" ) ;
define( "_MD_ALBM_GPERM_G_RATEVOTE" , "Vote" ) ;
define( "_MD_ALBM_GPERM_G_TELLAFRIEND" , "Tell a friend" ) ;

// Caption
define( "_MD_ALBM_CAPTION_TOTAL" , "Total:" ) ;
define( "_MD_ALBM_CAPTION_GUESTNAME" , "Guest" ) ;
define( "_MD_ALBM_CAPTION_REFRESH" , "Refresh" ) ;
define( "_MD_ALBM_CAPTION_IMAGEXYT" , "Size(Type)" ) ;
define( "_MD_ALBM_CAPTION_CATEGORY" , "Category" ) ;

}

?>