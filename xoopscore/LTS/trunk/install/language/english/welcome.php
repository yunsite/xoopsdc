<?php
// $Id: welcome.php 2843 2009-02-21 10:31:52Z phppp $
// _LANGCODE: en
// _CHARSET : UTF-8
// Translator: XOOPS Translation Team

$content .= '
<p>
    <acronym title="eXtensible Object-Oriented Portal System">XOOPS</acronym> is an open-source
    Object-Oriented Web publishing system written in PHP. It is an ideal tool for
    developing small to large dynamic community websites, intra company portals, corporate portals, weblogs and much more.
</p>
<p>
    XOOPS is released under the terms of the
    <a href="http://www.gnu.org/copyleft/gpl.html" rel="external">GNU General Public License (GPL)</a>
    and is free to use and modify.
    It is free to redistribute as long as you abide by the distribution terms of the GPL.
</p>
<h3>Requirements</h3>
<ul>
    <li>WWW Server (<a href="http://www.apache.org/" rel="external">Apache</a>, IIS, Roxen, etc)</li>
    <li><a href="http://www.php.net/" rel="external">PHP</a> 4.3.0 or higher (5.2 or higher recommended)</li>
    <li><a href="http://www.mysql.com/" rel="external">MySQL</a> 3.23 or higher (4.1 or higher recommended)</li>
</ul>
<h3>Before you install</h3>
<ol>
    <li>Setup WWW server, PHP and database server properly.</li>
    <li>Prepare a database for your XOOPS site.</li>
    <li>Prepare user account and grant the user the access to the database.</li>
    <li>Make the directories and the files writable: %s</li>
    <li>If you need to install <em>protector</em> module, <div class="confirmMsg">Replace the file <em>mainfile.dist.php</em> in your XOOPS root directory with <em>/extras/mainfile.dist.php.protector</em>.</div></li>
    <li>For security considerations, you are strongly advised to move the two directories below out of <a href="http://phpsec.org/projects/guide/3.html" rel="external">document root</a> and change the folder names: %s</li>
    <li>Create (if not already present) and make the directories writable: %s</li>
    <li>Turn cookie and JavaScript of your browser on.</li>
</ol>
';
?>