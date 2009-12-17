<?php
/** Get item fields: title, content, time, link, uid, uname, tags **/
function ilog_tag_iteminfo(&$items)
{
	if (empty($items) || !is_array($items)) {
        return false;
    }
    $items_id = array();
    /**/
    foreach (array_keys($items) as $cat_id) {
        // Some handling here to build the link upon catid
            // If catid is not used, just skip it
        foreach (array_keys($items[$cat_id]) as $item_id) {
            // In article, the item_id is "art_id"
            $items_id[] = intval($item_id);
        }
    }
    $item_handler =& xoops_getmodulehandler("article", "ilog");
    $items_obj = $item_handler->getObjects(new Criteria("id", "(" . implode(", ", $items_id) . ")", "IN"), true);
    
    foreach (array_keys($items) as $cat_id) {
        foreach (array_keys($items[$cat_id]) as $item_id) {
            $item_obj =& $items_obj[$item_id];
            $items[$cat_id][$item_id] = array(
                "title"     => $item_obj->getVar("title"),
                "uid"       => $item_obj->getVar("uid"),
                "link"      => "view.php?id={$item_id}",
                "time"      => $item_obj->getVar("time_publish"),
                "tags"      => tag_parse_tag($item_obj->getVar("keywords", "n")), // optional
                "content"   => "",
                );
        }
    }
    unset($items_obj);    
}
/** Remove orphan tag-item links **/
function ilog_tag_synchronization($mid) 
{
    $item_handler =& xoops_getmodulehandler("article", "ilog");
    $link_handler =& xoops_getmodulehandler("link", "tag");
        
    /* clear tag-item links */
    if (version_compare( mysql_get_server_info(), "4.1.0", "ge" )):
    $sql =  "    DELETE FROM {$link_handler->table}" .
            "    WHERE " .
            "        tag_modid = {$mid}" .
            "        AND " .
            "        ( tag_itemid NOT IN " .
            "            ( SELECT DISTINCT {$item_handler->keyName} " .
            "                FROM {$item_handler->table} " .
            "                WHERE {$item_handler->table}.time_publish > 0" .
            "            ) " .
            "        )";
    else:
    $sql =  "    DELETE {$link_handler->table} FROM {$link_handler->table}" .
            "    LEFT JOIN {$item_handler->table} AS aa ON {$link_handler->table}.tag_itemid = aa.{$item_handler->keyName} " .
            "    WHERE " .
            "        tag_modid = {$mid}" .
            "        AND " .
            "        ( aa.{$item_handler->keyName} IS NULL" .
            "            OR aa.time_publish < 1" .
            "        )";
    endif;
    if (!$result = $link_handler->db->queryF($sql)) {
        xoops_error($link_handler->db->error());
    }
}
?>