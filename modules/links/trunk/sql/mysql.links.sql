INSERT INTO `links_category` (`cat_id`, `cat_name`, `cat_desc`, `cat_order`, `cat_status`) VALUES (1, '友情链接', '', 1, 0);
INSERT INTO `links_link` (`link_id`, `cat_id`, `link_title`, `link_url`, `link_desc`, `link_order`, `link_status`, `link_image`, `link_dir`, `published`, `datetime`, `link_contact`) VALUES
(1, 1, '众锐普斯', 'http://www.xoops.com.cn/', 'XOOPS商业公司', 1, 1, '', 'http://localhost/test/modules/links/images/xoops.com.png', 1251831615, 1251831615, ''),
(2, 1, 'XOOPS中文社区', 'http://xoops.org.cn/', 'Xoops中文支持站', 2, 1, '', 'http://localhost/test/modules/links/images/xoops.org.png', 1251831616, 1251831616, '');
