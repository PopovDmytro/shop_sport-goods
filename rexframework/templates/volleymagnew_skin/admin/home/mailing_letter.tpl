<html>
<head>
<style type="text/css">
    {literal}
        body {background-color:#dddddd;
        margin:0px;
        padding:0px;}
        .bold {font-weight:bold}
        .blue {
            background-color:#00BFFF;
            padding: 10px; 
            border-radius:5px;
            overflow:hidden;            
        }
        .link {margin: 5px 5px 0;}
        .content-mail{
            background-color: #FFFFFF;
            border-left: 1px solid #A0A0A0;
            border-right: 1px solid #A0A0A0;
            border-bottom: 1px solid #A0A0A0;
            display: table;
            margin: 0 auto;
            min-width: 620px;
        }
        .blue ul{
            margin:0px !important;
        }
        .link-text{
            float:left;
            width:50%;
        }
        .link button{
             background-color: #ECAE1B;
             border: 1px solid #DF5A05;
             padding:5px;
             border-radius:5px;
        }
        .link a{
             cursor: pointer;
             text-decoration:none;
        }
        .conteni-text-mail{
            padding:0px 15px 5px; 
        }
        .contact-mail{
            width:60%;
        }
        .contact-mail li{
            margin-bottom:5px;
        }
        .conteni-text-mail hr{
           border-color: #dddddd; 
        }
    {/literal}
</style>
</head>
<body>
<div class="content-mail">
    <a style="width:100%; display:block" href="http://www.volleymag.com.ua"><img style="margin:0 auto; display: block; width: 150px" src="http://www.volleymag.com.ua/content/mail/logo.png" /></a>
    <div class="conteni-text-mail">
        <p>{$content}</p>
    </div>
    {*<div style="width:98%; overflow:hidden; padding:10px 1%; background-color: #F1F1F1;">
        <div class="contact-mail" style="float:left;">
            <ul style="margin-top:10px;">
                <li>Киев: 099 254 22 21 </li>
            </ul>
        </div> 
        <a style="float:right; display:block" href="http://www.volleymag.com.ua"><img src="http://www.volleymag.com.ua/content/mail/logo.png" /></a>
    </div>*}
</div>
</body>
</html>