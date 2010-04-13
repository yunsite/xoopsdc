<?php

if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
    exit("Access Denied");
}
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'index';

function updatecache($cacheDir,$type){
	$d = dir($cacheDir);
	while (false !== ($entry = $d->read())) {
		if(preg_match("/.*\.{$type}$/", $entry)) {
			unlink($cacheDir .'/'.$entry);
		}
	}
	$d->close();
}

xoops_cp_header();

switch($op) {
default:
case "index":
    echo "<h4>"._AM_TOOLS_INDEX."</h4>";
  	include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
  	$form = new XoopsThemeForm("","updatecache","./admin.php?fct=tools","post",true);
  	$form->addElement(new XoopsFormLabel(_AM_TOOLS_TIP,_AM_TOOLS_TIPS));
  	$checkbox_options = array(
  	"updatexoopscache"=>_AM_TOOLS_UPDATEXOOPSCACHE,
  	"updatesmartycache"=>_AM_TOOLS_UPDATESMARTYCACHE,
  	"updatesmartycompile"=>_AM_TOOLS_UPDATESMARTYCOMPILE
  	);
  	$checkbox = new XoopsFormCheckBox(_AM_TOOLS_OPTIONS,"options",array_keys($checkbox_options));
  	$checkbox->addOptionArray($checkbox_options);
  	$form->addElement($checkbox);
  	$form->addElement(new XoopsFormHidden("op","updatecache"));
  	$form->addElement(new XoopsFormHidden("step","1"));
  	$form->addElement(new XoopsFormButton("","submit",_SUBMIT,"submit"));
  	$form->display();
  	break;
case "updatecache":
    if($_REQUEST['step'] == 1){
        $options = implode('_', $_REQUEST['options']);
        $url = "./admin.php?fct=tools&op=updatecache&step=2&options={$options}";
        $updating = _AM_TOOLS_UPDATING;
        
$msg = <<<EOF
<div class="loading" style="text-align:center;padding:100px 0;">
    <img src="./images/loader.gif" />
    <p>{$updating}</P>
</div>
<script type="text/javascript" language="javascript">
    function redirect(url){
        location.replace(url);
    }
    
setTimeout("redirect('{$url}');", 2000);

</script>
EOF;
    
echo $msg;
    }elseif ($_REQUEST['step'] == 2) {
      	$options = explode("_",$_REQUEST['options']);
        
      	foreach ($options as $k){
      			if ($k === 'updatexoopscache'){
      			$d = XOOPS_VAR_PATH . '/caches/xoops_cache';
      			updatecache($d,"php");
      			updatecache($d,"html");
      			updatecache($d,"tmp");
      			}
      			if ($k === 'updatesmartycache'){
      			$d = XOOPS_VAR_PATH . '/caches/smarty_cache';
      			updatecache($d,"html");
      			updatecache($d,"tmp");
      			}
      			if ($k === 'updatesmartycompile'){
      			$d = XOOPS_VAR_PATH . '/caches/smarty_compile';
      			updatecache($d,"php");
      			}
      	}
      
      	redirect_header('./admin.php?fct=tools', 3, _AM_TOOLS_UPDATECACHESUCCESS);
    }
      
    break;

}


xoops_cp_footer();
   

?>