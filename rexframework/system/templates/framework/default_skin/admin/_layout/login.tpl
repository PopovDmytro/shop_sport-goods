<!DOCTYPE html>
<html dir="ltr" lang="en">
    </head>
        <title>{page type='title'}</title>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
        
        {css src="stylelogin.css"}

        {css src="redmond/jquery-ui-1.8.17.custom.css"}
        {css src="rex-ui-style.css"}
        
        {js src="jquery-1.8.2.min.js"}
        {js src="jquery-ui-1.8.17.custom.min.js"}
        {js src="jquery.rex-0.0.9.js"}
        {js src="admin.js"}
    </head>
    <body>
        <div id="wrapper">
            <div id="login_wrapper">
                <form method="post" action="">
                    <input type="hidden" value="1" name="user[submitlogin]">
                    <fieldset>
                        <h1 id="logo">
                            <a href="#">websitename Administration Panel</a>
                        </h1>
                        <div class="formular">
                            <div class="formular_inner">
                                {if !$confirm_sms}
                                    <label>
                                        <strong>Username:</strong>
                                        <span class="input_wrapper">
                                            <input type="text" name="user[login]">
                                        </span>
                                    </label>
                                    <label>
                                        <strong>Password:</strong>
                                        <span class="input_wrapper">
                                            <input type="password" name="user[password]">
                                        </span>
                                    </label>
                                    <ul class="form_menu">
                                        <li>
                                            <span class="button">
                                                <span>
                                                    <span>Submit</span>
                                                </span>
                                                <input type="submit" name="">
                                            </span>
                                        </li>
                                    </ul>
                                {else}
                                    <label>
                                        <strong>Sms code:</strong>
                                        <span class="input_wrapper">
                                            <input type="text" name="user[code]">
                                        </span>
                                    </label>
                                    <ul class="form_menu">
                                        <li>
                                            <span class="button">
                                                <span>
                                                    <span>Submit</span>
                                                </span>
                                                <input type="submit" name="user[submit]">
                                            </span>
                                        </li>
                                        <li>
                                            <span class="button">
                                                <span>
                                                    <span>Cancel</span>
                                                </span>
                                                <input type="submit" name="user[cancel]">
                                            </span>
                                        </li>
                                    </ul>
                                {/if}
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>

        <div class="clr"></div>
        <div class="footer">
            <div>Copyright <a href="http://www.phpeagles.com/" target="_blank">PHPEagles</a> 2005-{$smarty.now|date_format:"%Y "}</div>
        </div>
    </body>
</html>