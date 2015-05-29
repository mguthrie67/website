<html>
<head>
<title>Campaign Manager</title>
    <!-- Stylesheets -->
    <link href="../css/bootstrap.css" rel="stylesheet">
    <link href="../css/site.css" rel="stylesheet">
    <link href="../css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../css/reports.css" rel="stylesheet">

    <!-- Font -->
    <link href='http://fonts.googleapis.com/css?family=Muli' rel='stylesheet' type='text/css'>
</head>
<body>


<?php

$excludeip="122.109.192.180";
$excludeip="122.109.192.181";   # fake


$campaign = $_GET["campaign"];

if ($campaign=="") {
   die("<h1>No campaign specified.</h1>Please add ?campaign=xxx to your URL.");
}

$purpose=file_get_contents("../mailout/" . $campaign . "/purpose.txt");

echo "<h1>Campaign " . $campaign . " - " . $purpose . "</h1>";

# Load Data

$users=file("../mailout/" . $campaign . "/users.txt");
$log=file("../mailout/" . $campaign . "/logfile.txt");
array_shift($log);

# Clean log data - remove our entries - excludeip

$newlog=array();

foreach ($log as &$l) {
    $pos = strpos($l, $excludeip);
    if ($pos === false) {
        array_push($newlog, $l);
    }
}

$log=$newlog;

# pre-process data

   # hits per user

      # initialise empty array with names

$hits=array();
$hits["UNKNOWN"]=0;

foreach ($users as &$u) {
   $pieces=explode(":", $u);
   $name=$pieces[1];
   $name=str_replace(array("\n","\r"), '', $name);

   $hits[$name]=0;
}

      # enter hits

foreach ($log as &$i) {
   $pieces=explode(":", $i);
   $name=$pieces[1];
   $hits[$name]=$hits[$name]+1;
}

   # count percentage hits

$hit=0;
$miss=0;

foreach ($hits as $k => $v) {
    if ($v==0) {
       $miss=$miss+1;
    } else {
       $hit=$hit+1;
    }

}

    # count multiple hits

$mhit=0;
$mmiss=0;

foreach ($hits as $k => $v) {
    if ($v<=1) {
       $mmiss=$mmiss+1;
    } else {
       $mhit=$mhit+1;
    }

}

### Look for multiple days

# empty arrays
$multidayscnt=array();   # count
$multidaysdate=array();  # date

foreach ($log as &$l) {
   $pieces=explode(":", $l);
   $day=$pieces[0];
   $who=$pieces[1];
   $bits=explode(" ", $day);
   $d=$bits[0];

   $multidayscnt[$who]=0;
   $multidaysdate[$who]=$d;   # ends up set to last day so we over count by 1
}

# go through log and check for multiple days
foreach ($log as &$l) {
   $pieces=explode(":", $l);
   $day=$pieces[0];
   $who=$pieces[1];
   $bits=explode(" ", $day);
   $d=$bits[0];

   if ($multidaysdate[$who]<>$d) {    # found another day
          $multidayscnt[$who]=$multidayscnt[$who]+1;
          $multidaysdate[$who]=$d;      # set check to this day
    }
}

$multiday=0;

foreach ($multidayscnt as $k => $v) {
    if ($v>1) {
       $multiday=$multiday+1;
    }

}

# Summary

echo "<h2>Summary</h2>";

echo "<center><table class='campaign'>";

# all users

$totusers = count($users);

# get hits

$percentread = round($hit * 100 / $totusers, 2);
$mpercentread = round($mhit * 100 / $totusers, 2);
$percentreadday = round($multiday * 100 / $totusers, 2);

echo "<tr><td>Total targets<td>" . $totusers . "</tr>";
echo "<tr><td>Percentage who have read our email<td>" . $percentread . "%</tr>";
echo "<tr><td>Percentage who have read it multiple times<sup>*</sup><td>" . $mpercentread . "%</tr>";
echo "<tr><td>Percentage who have read it on multiple days<td>" . $percentreadday . "%</tr>";

echo "</table></center>";

echo "<br>* - Outlook and other mail clients often read the email multiple times. The multiple day value at the bottom of the table is a more accurate assessment of human interaction.";

echo "<h2>Recent Reads</h2>";

# ok... back to the logs we go...

# get the end of the log and reverse it

$endlog = array_slice($log, -5);

$endlog = array_reverse($endlog);

echo "<center><table class='campaign'>";

foreach ($endlog as &$i) {
   $pieces=explode(":", $i);
   $name=$pieces[1];
   $ts=$pieces[0];
   $ts=str_replace("-", ":", $ts);

   # check the dates
   date_default_timezone_set('Australia/Sydney');
   $parts=explode(" ", $ts);
   $datebit=$parts[0];
   $timebit=$parts[1];

   $format = "d/m/Y";
   $date1  = DateTime::createFromFormat($format, $datebit);
   $date2  = date("d/m/Y");
   $date3  = DateTime::createFromFormat($format, $date2);
   $date4  = date("d/m/Y", time() - 60 * 60 * 24);
   $date5  = DateTime::createFromFormat($format, $date4);

   if ($date1 == $date3) {
      $ts="Today " . $timebit;
   }

   if ($date1 == $date5) {
      $ts="Yesterday " . $timebit;
   }

   echo "<tr><td>" . $name . "<td><span style='float: right;'>" . $ts . "</span></tr>";
}

echo "</table></center>";

echo "<h2>Who's Read It</h2>";

echo "<center><table class='campaign'>";

foreach ($hits as $k => $v) {
    if ($v>0) {
        echo "<tr><td>" . $k . "<td><span style='float: right;'>";
        if ($v===1) {
            echo "Once";
        } elseif ($v===2) {
            echo "Twice";
        } else {
            echo $v . " times";
        }
        echo "</span></tr>";
    }
}

echo "</table></center>";

echo "<h2>Who Hasn't Read It (Cunts)</h2>";


echo "<center><table class='campaign'>";

foreach ($hits as $k => $v) {
    if ($v===0) {
        if ($k <> "UNKNOWN") {
            echo "<tr><td>" . $k . "</tr>";
        }
    }
}

echo "</table></center>";

# Graph by day

# get the data

      # initialise empty array with dates

$days=array();

foreach ($log as &$l) {
   $pieces=explode(":", $l);
   $day=$pieces[0];
   $bits=explode(" ", $day);
   $d=$bits[0];

   $days[$d]=0;
}

      # enter hits per day

foreach ($log as &$l) {
   $pieces=explode(":", $l);
   $day=$pieces[0];
   $bits=explode(" ", $day);
   $d=$bits[0];
   $days[$d]=$days[$d]+1;
}

echo "<h2>Historic View</h2>";

echo "<center>";

###### Google chart thingo

echo '<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1.1", {packages:["bar"]});
      google.setOnLoadCallback(drawStuff);

      function drawStuff() {
        var data = new google.visualization.arrayToDataTable([
          ["Date", "Reads"],';

##### insert data

foreach ($days as $k => $v) {
    echo "[\"" . $k . "\", " . $v . "],";

}

##### carry on...

        echo ']);

        var options = {
          title: "Reads by Day",
          width: 900,
          legend: { position: "none" },
          chart: { subtitle: "Total number of email reads over time" },
          axes: {
            x: {
              0: { side: "top", label: "Reads excluding 17 Ways IP Address"} // Top x-axis.
            }
          },
          bar: { groupWidth: "90%" }
        };

        var chart = new google.charts.Bar(document.getElementById("top_x_div"));
        // Convert the Classic options to Material options.
        chart.draw(data, google.charts.Bar.convertOptions(options));
      };
    </script>

    <div id="top_x_div" style="width: 900px; height: 500px;"></div>
';

echo "</center><br><br>";

echo "<h3>Download Raw Files</h3>";

echo "<center><a href='../mailout/" . $campaign . "/logfile.txt'>Log File</a></center><br>";
echo "<center><a href='../mailout/" . $campaign . "/users.txt'>User File</a></center><br>";

echo "<br><br>";

?>



<script src="../js/jquery.min.js"></script>
<script type="text/javascript" src="../js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="../js/jquery.mobile.customized.min.js"></script>
<script type="text/javascript" src="../js/camera.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../js/superfish.js"></script>
        <script type="text/javascript" src="../js/jquery.prettyPhoto.js"></script>
<script src="../js/htweet.js" type="text/javascript"></script>
        <script type="text/javascript" src="../js/custom.js"></script>
</body>
</html>