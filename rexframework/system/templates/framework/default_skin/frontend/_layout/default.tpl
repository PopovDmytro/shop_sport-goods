<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">

        {*META*}
        <title>{page type='title'}</title>
        <meta name="description" content="{page type='description'}">

        {css src="styles.css" media="screen" title="no title" charset="utf-8"}
        
        {js src="jquery-1.7.1.min.js"}
        {js src="jquery.rex-0.0.9.js"}
        {*include file="_block/init.js.tpl"*}
        {js src="jquery.ajax-location.js"}
    </head>

    <body>                    
        <div class="container">
            <div class="menu">
            </div>
            <div class="header">
                <div class="logo-box"></div>
                <div class="hd-right"></div>
            </div>

            <div class="clear"></div>
            <div class="main">
                <div class="sidebar">
                    <div class="sidebar-box">
                        <div class="sidebar-box-title">
                            Menu
                        </div>
                        <div class="sidebar-box-content">
                            {workspace section=menu}
                        </div>
                    </div>
                </div>
                <div class="content">
                    {page type='getRenderedMessages'}
                    {page type='getRenderedErrors'}

                    {workspace}
                </div>
            </div>

            <div class="clear"></div>

            <div class="footer">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tbody>
                        <tr>

                            <td class="left_foter"></td>
                            <td class="footer-logo-box">
                                <a href="javascript: void(0);">
                                    {img src="footer-logo.jpg" alt="illusix"}
                                </a>
                                <span>&copy; 2011, Illusix</span>
                            </td>
                            <td class="footer-center">
                                <ul>
                                    <li></li>
                                </ul>
                            </td>
                            <td class="footer-right">

                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
        </div>
    </body>
</html>