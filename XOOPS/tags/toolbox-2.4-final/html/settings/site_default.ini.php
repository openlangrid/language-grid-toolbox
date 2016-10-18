<?php
/**

[Cube]
Root=XOOPS_ROOT_PATH
Controller=Legacy_Controller
#SystemModules=system,legacy,user,legacyRender
SystemModules=legacy,user,legacyRender,stdCache
RecommendedModules=pm,altsys,protector
RoleManager=Legacy_RoleManager
Salt=XOOPS_SALT

#
# You can register plural render systems.
#
[RenderSystems]
Legacy_RenderSystem=Legacy_RenderSystem
Legacy_AdminRenderSystem=Legacy_AdminRenderSystem
Legacy_DbthemeRenderSystem=Legacy_DbthemeRenderSystem
Legacy_WizMobileRenderSystem=Legacy_WizMobileRenderSystem

[Legacy]
AutoPreload=1
Theme=admin
AllowDBProxy=false
IsReverseProxy=false

#                  #
# Primary Preloads #
#                  #

[Legacy.PrimaryPreloads]
protectorLE_Filter=/modules/legacy/preload/protectorLE/protectorLE.class.php
Legacy_SystemModuleInstall=/modules/legacy/preload/Primary/SystemModuleInstall.class.php
#Legacy_SiteClose=/modules/legacy/preload/Primary/SiteClose.class.php
HdLegacy_SiteClose=/modules/hdLegacy/preload/Primary/SiteClose.class.php
User_PrimaryFilter=/modules/user/preload/Primary/Primary.class.php
Legacy_NuSoapLoader=/modules/legacy/preload/Primary/NuSoapLoader.class.php
Legacy_SessionCallback=/modules/legacy/preload/Primary/SessionCallback.class.php

#            #
# components #
#            #

[Legacy_Controller]
path=/modules/hdLegacy/kernel
class=HdLegacy_Controller

[Legacy_RenderSystem]
path=/modules/legacyRender/kernel
class=Legacy_RenderSystem
SystemTemplate=system_comment.html, system_comments_flat.html, system_comments_thread.html, system_comments_nest.html, system_notification_select.html, system_dummy.html, system_redirect.html
SystemTemplatePrefix=legacy

[Legacy_AdminRenderSystem]
path=/modules/legacyRender/kernel
class=Legacy_AdminRenderSystem
ThemeDevelopmentMode=false

[Legacy_DbthemeRenderSystem]
root=XOOPS_TRUST_PATH
path=/modules/dbtheme/class
class=Legacy_DbthemeRenderSystem

[Legacy_WizMobileRenderSystem]
root=XOOPS_TRUST_PATH
path=/modules/wizmobile/class
class=Legacy_WizMobileRenderSystem

[Legacy_RoleManager]
path=/modules/legacy/kernel
class=Legacy_RoleManager

*/
?>