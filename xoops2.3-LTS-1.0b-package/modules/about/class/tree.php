<?php

if (!defined("XOOPS_ROOT_PATH")) {
    exit();
}
require_once XOOPS_ROOT_PATH . "/class/tree.php";

if (!class_exists("aboutTree")) {
class aboutTree extends XoopsObjectTree {

    function aboutTree(&$objectArr, $rootId = null)
    {
        $this->XoopsObjectTree($objectArr, "page_id", "page_pid", $rootId);
    }

    function _makeTreeItems($key, &$ret, $prefix_orig, $prefix_curr = '', $tags = null)
    {
        if ($key > 0) {
            if (count($tags)>0) foreach($tags as $tag) {
                $ret[$key][$tag] = $this->_tree[$key]['obj']->getVar($tag);
            } else {
                $ret[$key]["page_title"] = $this->_tree[$key]['obj']->getVar("page_title");
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
        $this->_makeSelBoxOptions("page_title", $selected, $key, $ret, $prefix);
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
                    $ret['child'][$childkey]["page_title"] = $this->_tree[$childkey]['obj']->getVar("page_title");
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
