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
{$mailing['content']}
</body>
</html>