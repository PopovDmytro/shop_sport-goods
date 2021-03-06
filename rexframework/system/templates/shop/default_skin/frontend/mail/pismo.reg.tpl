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
            width: 620px;
            margin:0 auto; 
            background-color:#FFF;
            border-right: 1px solid #A0A0A0;
            border-left: 1px solid #A0A0A0;
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
    <a style="width:100%; display:block" href="http://www.rexshop.rexframework.ru"><img style="margin:0 auto; display: block;" src="http://rexshop.rexframework.ru/content/mail/logo.jpg" /></a>
    <div class="conteni-text-mail">
        <h2>Здравствуйте, {$pismomes->login}</h2>
        <hr />
        <p>Cпасибо за регистрацию на сайте <b>{$sysname}!</b></p>
        <div class="blue" style="background-color: #F1F1F1;">
            <ul style="padding-left: 5px; line-height: 25px; list-style: none;">
                <li><b>Данные для доступа</b></li>
                <li>E-mail: {$pismomes->email}</li>
                <li>Пароль: {$pismomes->password}</li>
            </ul>
        </div>
        <p>Для того, чтобы оформить заказ - необходимо заполнить данные о доставке в профиле, который будет доступен после авторизации:</p>

    </div>
    <div style="width:98%; overflow:hidden; padding:10px 1%; background-color: #F1F1F1;">
        <div class="contact-mail" style="float:left;">
            <ul style="margin-top:10px;">
                <li>Москва: 007 (495) 668-09-88</li>
                <li>Киев: 0038 (044) 393-40-25 </li>
            </ul>
        </div> 
        <a style="float:right; display:block" href="http://www.rexshop.rexframework.ru"><img src="http://rexshop.rexframework.ru/content/mail/logo.jpg" /></a>
    </div>
</div>
</body>
</html>