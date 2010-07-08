<?php
if (!defined("XOOPS_ROOT_PATH")) {
	exit();
}
function xoops_confirm_notes($hiddens, $action, $msg, $submit = '', $addtoken = true)
{
    $submit = ($submit != '') ? trim($submit) : _SUBMIT;
    $val = '<div class="confirmMsg">' . $msg . '<br />
      <form method="post" action="' . $action . '">
    ';
    foreach ($hiddens as $name => $value) {
        if (is_array($value)) {
            foreach ($value as $caption => $newvalue) {
                $val .= '<input type="radio" name="' . $name . '" value="' . htmlspecialchars($newvalue) . '" /> ' . $caption;
            }
            $val .= '<br />';
        } else {
            $val .= '<input type="hidden" name="' . $name . '" value="' . htmlspecialchars($value) . '" />';
        }
    }
    if ($addtoken != false) {
        $val .= $GLOBALS['xoopsSecurity']->getTokenHTML();
    }
    $val .= '
        <input type="submit" name="confirm_submit" value="' . $submit . '" title="' . $submit . '"/> 
      </form>
    </div>
    ';
    return $val;
}
?>