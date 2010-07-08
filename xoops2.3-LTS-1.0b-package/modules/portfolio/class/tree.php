<?php

if (!defined("XOOPS_ROOT_PATH")) {
    exit();
}
require_once XOOPS_ROOT_PATH . "/class/tree.php";

if (!class_exists("portfolioTree")) {
class portfolioTree extends XoopsObjectTree {

    function portfolioTree(&$objectArr, $rootId = null)
    {
        $this->XoopsObjectTree($objectArr, "service_id", "service_pid", $rootId);
    }

    function _makeTreeItems($key, &$ret, $prefix_orig, $prefix_curr = '', $tags = null)
    {
        if ($key > 0) {
            if (count($tags)>0) foreach($tags as $tag) {
                $ret[$key][$tag] = $this->_tree[$key]['obj']->getVar($tag);
            } else {
                $ret[$key]["service_name"] = $this->_tree[$key]['obj']->getVar("service_name");
            }
            $ret[$key]["prefix"] = $prefix_curr;
            $prefix_curr .= $prefix_orig;
        }
        if (isset($this->_tree[$key]['child']) && !empty($this->_tree[$key]['child'])) {
            foreach ($this->_tree[$key]['child'] as $childkey) {
                $this->_makeTreeItems($childkey, $ret, $prefix_orig, $prefix_curr, $tags);
            }
        }
    }

    function &makeTree($prefix = '-', $key = 0, $tags = null)
    {
        $ret = array();
        $this->_makeTreeItems($key, $ret, $prefix, '', $tags);
        return $ret;
    }

    function &makeSelBox($name, $prefix = '-', $selected = '', $EmptyOption = false, $key = 0)
    {
        $ret = '<select name=' . $name . '>';
        if (!empty($addEmptyOption)) {
            $ret .= '<option value="0">' . (is_string($EmptyOption) ? $EmptyOption : '') . '</option>';
        }
        $this->_makeSelBoxOptions("service_name", $selected, $key, $ret, $prefix);
        $ret .= '</select>';
        return $ret;
    }

    function getAllChild_array($key, &$ret, $tags = array(), $depth = 0)
    {
        if (-- $depth == 0) {
            return;
        }
        
        if (isset($this->_tree[$key]['child'])) {
            foreach ($this->_tree[$key]['child'] as $childkey) {
                if (isset($this->_tree[$childkey]['obj'])):
                if (count($tags) > 0) {
                    foreach ($tags as $tag) {
                        $ret['child'][$childkey][$tag] = $this->_tree[$childkey]['obj']->getVar($tag);
                    }
                } else {
                    $ret['child'][$childkey]["service_name"] = $this->_tree[$childkey]['obj']->getVar("service_name");
                }
                endif;
                
                $this->getAllChild_array($childkey, $ret['child'][$childkey], $tags, $depth);
            }
        }
    }

    function &makeArrayTree($key = 0, $tags = null, $depth = 0)
    {
        $ret = array();
        if ($depth > 0) $depth++;
        $this->getAllChild_array($key, $ret, $tags, $depth);
        return $ret;
    }
}
}
?>
