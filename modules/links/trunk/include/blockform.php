<?php


if (!defined('XOOPS_ROOT_PATH')) {
	die("XOOPS root path not defined");
}

include_once XOOPS_ROOT_PATH."/class/xoopsform/form.php";

class XoopsBlockForm extends XoopsForm
{

	/**
	 * create HTML to output the form as a table
	 * 
     * @return	string
	 */
	function render()
	{
$ele_name = $this->getName();
		$ret = "
				<div>
		";
		$hidden = '';
		foreach ( $this->getElements() as $ele ) {
			if (!is_object($ele)) {
				$ret .= $ele;
			} elseif ( !$ele->isHidden() ) {
				if ( ($caption = $ele->getCaption()) != '' ) {
				    $ret .= 
				        "<div class='xoops-form-element-caption" . ($ele->isRequired() ? "-required" : "" ) . "'>".
				        "<span class='caption-text'>{$caption}</span>".
				        "<span class='caption-marker'>*</span>".
				        "</div>";
			    }
				
				$ret .= "<div style='margin:5px 0 8px 0; '>".$ele->render()."</div>\n";
			} else {
				$hidden .= $ele->render();
			}
		}
		$ret .= "</div>";
		$ret .= $this->renderValidationJS( true );
		return $ret;
	}
}
?>