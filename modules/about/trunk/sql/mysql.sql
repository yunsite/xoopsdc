CREATE TABLE `about_page` (
`page_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`cat_url` VARCHAR( 255 ) NOT NULL ,
`page_title` VARCHAR( 255 ) NOT NULL ,
`page_menu_title` VARCHAR( 255 ) NOT NULL ,
`page_text` text NOT NULL ,
`page_author` VARCHAR( 255 ) NOT NULL ,
`page_pushtime` INT( 10 ) NOT NULL ,
`page_blank` INT( 1 ) NOT NULL DEFAULT '0',
`page_menu_status` INT( 1 ) NOT NULL DEFAULT '0',
`page_type` INT( 1 ) NOT NULL DEFAULT '0',
`page_status` INT( 1 ) NOT NULL DEFAULT '0',
`page_order` INT( 2 ) NOT NULL DEFAULT '0',
`page_index`INT( 1 ) NOT NULL DEFAULT '0',
`page_tpl` VARCHAR( 255 ) NOT NULL ,
PRIMARY KEY ( `page_id` )
);

INSERT INTO `about_page` (`page_id`, `cat_url`, `page_title`, `page_menu_title`, `page_text`, `page_author`, `page_pushtime`, `page_blank`, `page_menu_status`, `page_type`, `page_status`, `page_order`, `page_index`, `page_tpl`) VALUES
(1, '', ' XOOPS社区简介', ' XOOPS社区简介', '<p>XOOPS工作坊是由北京众锐普斯信息技术有限公司发起、以推动XOOPS技术普及和应用为使命、以发展线上社区和组织线下活动为主要形式、由互联网爱好者组成的组织。</p>\r\n<p><span style="border-collapse: separate; color: rgb(0, 0, 0); font-family: Simsun; font-size: medium; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px;" class="Apple-style-span"><span style="font-family: Arial,Verdana,sans-serif; font-size: 12px;" class="Apple-style-span">XOOPS中文站：<a href="http://www.xoops.org.cn/">http://www.xoops.org.cn/</a></span></span></p>\r\n<p><span style="border-collapse: separate; color: rgb(0, 0, 0); font-family: Simsun; font-size: medium; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px;" class="Apple-style-span"><span style="font-family: Arial,Verdana,sans-serif; font-size: 12px;" class="Apple-style-span">XOOPS</span></span><span style="border-collapse: separate; color: rgb(0, 0, 0); font-family: Simsun; font-size: medium; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px;" class="Apple-style-span"><span style="font-family: Arial,Verdana,sans-serif; font-size: 12px;" class="Apple-style-span">国际站</span></span><span style="border-collapse: separate; color: rgb(0, 0, 0); font-family: Simsun; font-size: medium; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px;" class="Apple-style-span"><span style="font-family: Arial,Verdana,sans-serif; font-size: 12px;" class="Apple-style-span">：<a href="http://www.xoops.org/">http://www.xoops.org/</a></span></span><a href="http://www.xoops.org/"><span style="border-collapse: separate; color: rgb(0, 0, 0); font-family: Simsun; font-size: medium; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px;" class="Apple-style-span"><span style="font-family: Arial,Verdana,sans-serif; font-size: 12px;" class="Apple-style-span"><br />\r\n</span></span></a></p>', '1', 1257906601, 0, 1, 1, 1, 3, 0, 'default'),
(4, '', '社区的基本活动', '社区的基本活动', '<p><strong>线上活动</strong></p>\r\n<p>分享XOOPS的使用经验</p>\r\n<p>讨论XOOPS的管理技巧</p>\r\n<p>研究XOOPS模块开发技术</p>\r\n<p>XOOPS新版本体验，分享体验感受</p>\r\n<p>XOOPS未来特性规划讨论</p>\r\n<p>&nbsp;</p>\r\n<p><strong>定期举办线下活动</strong></p>\r\n<p>讨论线上活动中的热点话题</p>\r\n<p>分享深度话题</p>', '1', 1257903989, 0, 1, 1, 1, 4, 0, 'default'),
(2, '', 'XOOPS简介', 'XOOPS简介', '<p>XOOPS -eXtensibleObject Oriented Portal System <br />\r\n<br />\r\n利用PHP＋MySQL编写的面向对象的可扩展智能建站门户系统<br />\r\n<br />\r\nXOOPS 的发布按照GPL(GNU General Public License)协议，在遵守GPL条款的前提下可以在任何场合免费使用和修改</p>', '1', 1257903323, 0, 1, 1, 1, 2, 0, 'default'),
(3, '', '北京众锐普斯信息技术有限公司简介', '公司简介', '<p>北京众锐普斯信息技术有限公司（下文简称&ldquo;众锐普斯&rdquo;）成立于2007年3月，旨在以开源项目XOOPS为基础，探索开源软件在国内的开发和应用模式，推动开源项目在国内的发展。<br />\r\n<br />\r\n众锐普斯主要由毕业自清华大学、北京大学、中国人民大学以及中科院的博士、硕士等组成。在技术方面，既有XOOPS国际项目的主开发和管理员，也有Freebsd等操作系统的专家；在网络优化方面，既有专业的用户界面设计师，又有信息资源管理专家。<br />\r\n<br />\r\n长期以来，众锐普斯的成员一直是XOOPS开源社区的活跃成员和重要贡献者。</p>', '1', 1257905347, 0, 1, 1, 1, 1, 1, 'default'),
(5, '', '社区成员构成', '社区成员构成', '<p><strong>众锐普斯工作人员</strong></p>\r\n<p>.成员来源<br />\r\n</p>\r\n<p>北京众锐普斯信息技术有限公司(http://xoops.com.cn/)工作人员<br />\r\n<br />\r\n<br />\r\n.专题活动<br />\r\n<br />\r\nXOOPS定制开发网站技术方案分享<br />\r\n<br />\r\nXOOPS定制开发网站合作信息分享<strong><br />\r\n<br />\r\nXOOPS核心开发、设计团队成员</strong></p>\r\n<p>.成员来源<br />\r\n<br />\r\nXOOPS核心开发、设计团队成员(http://xoops.sourceforge.net, http://www.xoops.org)<br />\r\n<br />\r\n<br />\r\n.专题活动<br />\r\n<br />\r\nXOOPS总站社区建设<br />\r\n<br />\r\nXOOPS核心开发经验分享<strong><br />\r\n<br />\r\nXOOPS中文社区成员</strong></p>\r\n<p>.成员来源<br />\r\n<br />\r\nXOOPS中文社区(http://xoops.org.cn/)热心网友<br />\r\n<br />\r\n<br />\r\n.专题活动<br />\r\n<br />\r\nXOOPS中文社区建设<br />\r\n<br />\r\n讨论成员所参与网站的运营、产品、研发等各环节的方案<br />\r\n<br />\r\n参与新版本开发<strong><br />\r\n<br />\r\n教育机构教师</strong><strong><br />\r\n<br />\r\n</strong>成员来源<br />\r\n<br />\r\n在国民教育序列的大中专院校及研究机构担任XOOPS相关课程的授课教师或相关研究项目的负责人及助理<br />\r\n<br />\r\n<br />\r\n.专题活动<br />\r\n<br />\r\n计算机相关专业课程设置方案研讨<br />\r\n<br />\r\nXOOPS相关课程教学计划研讨<br />\r\n<br />\r\nXOOPS授课经验分享<strong><br />\r\n<br />\r\n培训机构培训师</strong></p>\r\n<p>.成员来源<br />\r\n<br />\r\n在国民教育序列外培训机构中提供XOOPS相关培训服务的授课教师及助理<br />\r\n<br />\r\n<br />\r\n.专题活动<br />\r\n<br />\r\nXOOPS培训经验分享<br />\r\n<br />\r\n围绕产品定位和技术选型讨论学员提出的实际案例<strong><br />\r\n<br />\r\n我学网志愿者<br />\r\n</strong></p>\r\n<p>.成员来源<br />\r\n<br />\r\n按我学网(http://5xue.com/)的志愿者招募流程申请并获得批准的产品、技术和美工志愿者<br />\r\n<br />\r\n<br />\r\n.专题活动<br />\r\n<br />\r\n针对参与的志愿活动制订学习计划<br />\r\n<br />\r\n为网站线上志愿者撰写网站使用手册</p>', '1', 1257905499, 0, 1, 1, 1, 5, 0, 'default'),
(6, '', '联系我们', '联系我们', '<p>北京众锐普斯信息技术有限公司<span class="Apple-style-span" style="border-collapse: separate; color: rgb(0, 0, 0); font-family: Simsun; font-size: medium; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px;"><span class="Apple-style-span" style="font-family: Arial,Verdana,sans-serif; font-size: 12px;">\r\n<ul class="mainTextColor">\r\n    <li>电&nbsp;&nbsp;&nbsp;&nbsp;话： 86 010 62766391</li>\r\n    <li>传&nbsp;&nbsp;&nbsp;&nbsp;真： 86 010 62766391</li>\r\n    <li>地&nbsp;&nbsp;&nbsp;&nbsp;址： 中国 北京 北京市东城区安定路20号院&nbsp; 燕都商务5号楼253室</li>\r\n    <li>公司网址：<a href="http://www.xoops.com.cn/">http://www.xoops.com.cn/</a></li>\r\n    <li>XOOPS中文社区：<a href="http://www.xoops.org.cn/">http://www.xoops.org.cn/</a></li>\r\n</ul>\r\n</span></span></p>', '1', 1257905013, 0, 1, 1, 1, 6, 0, 'default');