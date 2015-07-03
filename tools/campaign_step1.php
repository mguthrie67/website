<?php include('_header.html');?>


<h1>Create New Campaign - Step 1</h1>



    <div id="fields">
        <form action="campaign_step2.php" method="post">

<?php

// Get next campaign number by looking in the file system

// items in the mailout directory
$files=array_diff(scandir("../mailout"), array('..','.','dev'));
$top=intval(max($files));
$top=$top+1;
$next=sprintf("%'.03d", $top);

echo '<input type="hidden" name="campaign" value="' . $next . '">';
?>


<table>
    <tr>
        <td>Title
        <td><input class="span7" type="text" name="title" value=""  required/>
    </tr>
    <tr>
        <td>Description
        <td><textarea input class="span7" name="description" value=""  rows="6" cols="30" required/></textarea>
    </tr>
    <tr>
    <tr>
        <td>When
        <td><input class="span7" type="text" name="when" value="" placeholder="Change me to a date and time  picker" required/>
    </tr>
        <td>Where
        <td><input class="span7" type="text" name="where" value=""  required/>
    </tr>
</table>

<div id="map"><iframe width="100%" height="310" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
                      src="https://www.google.com.au/maps/place/The+Royal+George,+320+George+St,+Sydney+NSW+2000"></iframe><br />
</div>


            <div class="clear"></div>
            <input type="submit" class="contact_btn" value="Submit" />
            <div class="clear"></div>
        </form>
    </div>

<?php include('_footer.html');?>