use wayscoma_campaign_dev;

drop table campaign;
create table campaign (

  campaign_id int NOT NULL AUTO_INCREMENT,
  campaign_ref varchar(10) NOT NULL,
  title varchar(80) NOT NULL,
  description varchar(1000) NOT NULL,
  start datetime NOT NULL,
  finish datetime NOT NULL,
  location varchar(100) NOT NULL,

  PRIMARY KEY (campaign_id)
);

drop table event_registration;
create table event_registration (

  id int NOT NULL AUTO_INCREMENT,
  campaign_id int NOT NULL,
  name varchar(100) NOT NULL,
  email varchar(100) NOT NULL,
  phone varchar(20) NOT NULL,
  date_of_registration datetime NOT NULL,
  PRIMARY KEY (id)
);

drop table event_email;
create table event_email (
  campaign_id int NOT NULL,
  subject varchar(200),
  body text,
  PRIMARY KEY (campaign_id)
);

drop table systememail;
create table systememail (

  email_before_subject varchar(10000),
  email_after_subject varchar(1000),
  email_after_salutation varchar(1000),
  email_after_message varchar(1000)

);

insert into systememail (  email_before_subject, email_after_subject, email_after_salutation, email_after_message) values ("

<html><head>
    <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'><style>
    body {
        font-family: 'Calibri','Verdana', 'Arial', sans-serif;
        background: #white;
        font-color: #blue;
        margin:-4px;
    }

    h1 {
        font-size: 300%%;
        color:#5d73b6
    }

    h2 {
        font-size: 140%%;
    }

    h4 {
        color: #1F3D99;
        font-style: italic;
    }


    table.nice {

        color:#333333;
        border-width: 1px;
        border-color: #666666;
        border-collapse: collapse;
        line-height: 6px;
    }
    table.nice th {
        border-width: 1px;
        padding: 8px;
        border-style: solid;
        border-color: #666666;
        background-color: #dedede;
        line-height: 8px;
    }
    table.nice td {
        border-width: 1px;
        padding: 6px;
        border-style: solid;
        border-color: #666666;
        background-color: #ffffff;
        text-align: right;
    }


    a {
        text-decoration: none;
    }
    a:link, a:visited {
        color: #091e5e;
    }
    a:hover {
        color: #5d73f6;
    }
    .bord {
        background: #4D4D4D;
        width: 15%%;
    }
    .bot {
        background: #333333;
        color: white;
        width: 100%%;
        height: 80px;
    }

    .top {
        background: #333333;
        width: 100%%;
        height: 60px;
    }

    </style>
    </head>
    <body>
    <table align='left' cellpadding='0' cellspacing='0' border='0' style='border:none; mso-table-lspace:0pt; mso-table-rspace:0pt; border-collapse:collapse;'>

    <tr>
    <td class='top' colspan=3 style='font-size: 60%%;'>
    </tr>

        <tr>
        <td class='bord'>
    <td>
    <img width='120px' src='http://17ways.com.au/mailout/002/getimg.php?token=%s&file=logo_slogan.png' alt='Image not loaded. 17 Ways'>
    <br>
    <font size=96 color=''#5d73b6''><center>", "</center></font>
    <hr><p style='margin: 20px;'>","","<br><br>
    <p style='margin-left: 100px; margin-top: 0; margin-bottom: 0;'>
    <b>Mark, Tim and John
    <br>
    The 17 Ways team.</b>
    <br>
    <br>
        <td class='bord'>
    </tr>
    <tr>
    <td class='bot' colspan=3 style='font-size: 60%%;'>

    <center>17 Ways Pty Ltd &copy; 2015 | Registered in Australia ACN 603890179 </center>

    </tr></table>
    </body></html>");