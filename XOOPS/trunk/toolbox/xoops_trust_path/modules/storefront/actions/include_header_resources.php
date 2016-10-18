<?php
$xoops_module_header = <<< EOF
<script><!--
jQuery.noConflict();
jQuery(document).ready(function(){
});
//--></script>
EOF;
$xoops_module_header .= $xoopsTpl->get_template_vars("xoops_module_header");

$xoopsTpl->assign("xoops_module_header", $xoops_module_header);
