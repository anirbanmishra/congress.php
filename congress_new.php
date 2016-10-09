<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
    <title>Congress Search Page</title>
    <style type="text/css">
        #output{
            margin-top:auto;

        }
        #form_div{
            margin-left: auto;
            margin-right: auto;
            width:400px;
        }
        .required:after { content:" *"; }
        td{
            text-align: center;
        }
        h1{
            font-size: 26px;
        }
        table {border:1px solid #000;
            font-size: 17px;
        }
    </style>
    <script language="javascript" type="text/javascript">
        function validateForm(form){
            var error_message="";
            var db=document.getElementById("db");
            var search=document.getElementById("search");
            var congress="";
            for(var j = 0 ; j < form.congress.length ; ++j)
                if(form.congress[j].checked) {
                    congress = form.congress[j].value; break;}
            if(db.options[db.selectedIndex].value =="")
                error_message+="database,";
            if(congress=="")
                error_message+="congress,";
            if(search.value=="")
                error_message+="keyword,";
            if(error_message!=""){
                error_message=error_message.substring(0,error_message.length-1);
                error_message="Please enter the following missing information: "+error_message;
                alert(error_message);
            }
        }
        function changeText(){
            var db=document.getElementById("db");
            document.getElementById("keyword").innerHTML=db.options[db.selectedIndex].value;
        }
        function display (image,title,name,term_end,website,office,fb_id,tw_id){
            var html_text="";
            html_text+= '<table border ="0px" id="extraInfo"><col width="350"><col width="360">';
            html_text+= '<tr><td colspan="2" style="text-align: center"><img src="' + image + '"></td></tr>';
            html_text+= '<tr><td>Full Name</td><td style="text-align: left">' + title + ' ' + name + '</td></tr>';
            html_text+= '<tr><td>Term Ends On</td><td style="text-align: left">' + term_end + '</td></tr>';
            html_text+= '<tr><td>Website</td><td style="text-align: left">' + website  +'</td></tr>';
            html_text+= '<tr><td>Office</td><td style="text-align: left">' + office + '</td></tr>';
            html_text+= '<tr><td>Facebook</td><td style="text-align: left"><a href="' + fb_id + '">' + name + '</a></td></tr>';
            html_text+= '<tr><td>Twitter</td><td style="text-align: left"><a href="' + tw_id + '">' + name + '</a></td></tr>';
            html_text+= '</table>';
            document.getElementById('main_output').innerHTML="";
            document.getElementById('output').innerHTML=html_text;
        }
        function displayBill(bill_id,short_title,sponsor,introduced_on,version_name,pdf_link,last_action_at) {
            html_text="";
            html_text+= '<table border ="0px" id="viewDetails"><col width="350"><col width="350">';
            html_text+= '<tr><td style="text-align: left">Bill ID</td><td style="text-align: left">' + bill_id + '</td></tr>';
            html_text+= '<tr><td style="text-align: left">Bill Title</td><td style="text-align: left">' + short_title + '</td></tr>';
            html_text+= '<tr><td style="text-align: left">Sponsor</td><td style="text-align: left">' + sponsor + '</td></tr>';
            html_text+= '<tr><td style="text-align: left">Introduced On</td><td style="text-align: left">' + introduced_on + '</td></tr>';
            html_text+= '<tr><td style="text-align: left">Last action with date</td><td style="text-align: left">' + version_name+','+last_action_at + '</td></tr>';
            html_text+= '<tr><td style="text-align: left">Bill URL</td><td style="text-align: left"><a href="' + pdf_link + '">' + short_title + '</a></td></tr>';
            html_text+= '</table>';
            document.getElementById('main_output').innerHTML="";
            document.getElementById('output').innerHTML=html_text;
        }
    </script>
<body>

<div id="form_div">
    <h1>Congress Information Search</h1>
    <form id="my_form" method="post">
        <table style="margin-left: 18px;">
            <col width="160">
            <col width="80">
            <tr>
                <td><label for="db">Congress Database</label></td>
                <td><select name="db" id="db" onchange="changeText()">
                        <option value="" disabled selected>Select your option</option>
                        <option value="State/Representative">Legislators</option>
                        <option value="Committee ID">Committees</option>
                        <option value="Bill ID">Bills</option>
                        <option value="Amendment ID">Amendments</option>
                    </select></td>
            </tr>
            <tr><td><label for="congress">Chamber</label></td>
                <td><input type="radio" value="senate" name="chamber" id="congress"> Senate
                    <input type="radio" value="house" name="chamber" id="congress"> House</td></tr>
            <tr>
                <td><label class="required" id="keyword" for="search">&nbsp;&nbsp;Keyword</label></td>
                <td><input type="text" name="search" id="search"></td>
            </tr>
            <tr><td></td><td>
                    <input type="submit" name="Search" value="Search" onclick="validateForm(this.form)">
                    <input type="button" name="clear" value="Clear" onclick="this.form.reset()"></td>
            </tr>
            <tr><td colspan="2"><a href="http://sunlightfoundation.com/" target="_blank">Powered by Sunlight Foundation</a></td></tr>
        </table>
    </form>
</div>
<div id="output"> </div>
<?php
header("Access-Control-Allow-Origin: *");
$a="";
$b="";
$c="";
$id ="";
if(isset($_POST["Search"])) {
    $search = $_POST["search"];
    $chamber = $_POST["chamber"];
    $db = $_POST["db"];
    $state="";
    $state = convertState($search);
    if($db == "State/Representative") {
        if ($state != "")
            $url = "https://congress.api.sunlightfoundation.com/legislators?chamber=" . $chamber . "&state=" . $state . "&apikey=f0ec4ae1648f4b5bb7b0c2a14abb10d6";
        else
            $url = "https://congress.api.sunlightfoundation.com/legislators?chamber=" . $chamber . "&query=" . $search . "&apikey=f0ec4ae1648f4b5bb7b0c2a14abb10d6";
        $response = file_get_contents($url);
        $response_json = json_decode($response, true);
        echo '<div id="main_output">';
        echo '<table border ="1px" id="infoTable" style="text-align: center; border-collapse: collapse;"><col width="160"><col width="160"><col width="80"><col width="120">';
        echo '<tr><th>Name</th><th>State</th><th>Chamber</th><th>Details</th></tr>';
        $senate = $response_json["results"];
        for ($i = 0; $i < sizeof($senate); $i++) {
            $candidate = "";
            $name = "";
            $state_name = "";
            $chamber = "";
            $details = "";
            $candidate = $response_json["results"][$i];
            foreach ($candidate as $k => $v) {
                if ($k == "first_name")
                    $name = $v . ' ';
                if ($k == "last_name")
                    $name = $name . $v;
                if ($k == "state_name")
                    $state_name = $v;
                if ($k == "chamber")
                    $chamber = $v;
                if ($k == "bioguide_id")
                    $details = $v;
                if ($k == "title")
                    $title = $v;
                if ($k == "term_end")
                    $term_end = $v;
                if ($k == "website")
                    $website = $v;
                if ($k == "office")
                    $office = $v;
                if ($k == "facebook_id")
                    $fb_id = "https://www.facebook.com/" . $v;
                if ($k == "twitter_id")
                    $tw_id = "https://twitter.com/" . $v;
            }
            $legislator_image = "https://theunitedstates.io/images/congress/225x275/" . $details . ".jpg";
            echo '<tr><td>' . $name . '</td>';
            echo '<td>' . $state_name . '</td>';
            echo '<td>' . $chamber . '</td>';
            echo '<td><a href="congress_new.php" onclick="'."display('$legislator_image','$title', '$name', '$term_end','$website','$office','$fb_id','$tw_id'); return false;".'">View Details</a></td></tr>';

            //echo '<td><a href="congress.php?chamber=' . $chamber . '&state=' . $state . '&bioguide=' . $details . '"' . 'onclick="this.form.reset()">More Details</a></td></tr>';
        }
        echo '</table></div>';
    }
    if ($db == "Committee ID"){
        $url = "https://congress.api.sunlightfoundation.com/committees?committee_id=" . $search . "&chamber=" . $chamber . "&apikey=f0ec4ae1648f4b5bb7b0c2a14abb10d6";
        $response = file_get_contents($url);
        $response_json = json_decode($response, true);
        $committee=$response_json['results'];
        echo '<table border ="1px" id="infoTable" style="text-align: center; border-collapse: collapse;"><col width="160"><col width="480"><col width="100">';
        echo '<tr><th>Committee ID</th><th>Committee Name</th><th>Chamber</th></tr>';
        foreach ($committee[0] as $k => $v){
            if ($k == "committee_id")
                $committee_id=$v;
            if ($k == "name")
                $committee_name=$v;
            if ($k == "chamber")
                $committee_chamber=$v;
        }
        echo '<tr><td>' . $committee_id . '</td>';
        echo '<td>' . $committee_name . '</td>';
        echo '<td>' . $chamber . '</td></tr></table>';
    }
    if ($db == "Bill ID") {
        $url = "https://congress.api.sunlightfoundation.com/bills?bill_id=" . $search . "&chamber=" . $chamber . "&apikey=f0ec4ae1648f4b5bb7b0c2a14abb10d6";
        $response = file_get_contents($url);
        $response_json = json_decode($response, true);
        $bills=$response_json['results'][0];
        echo '<div id="main_output">';
        echo '<table border ="1px" id="infoTable" style="text-align: center; border-collapse: collapse;"><col width="160"><col width="480"><col width="100">';
        echo '<tr><th>Bill ID</th><th>Short Title</th><th>Chamber</th><th>Details</th></tr>';
        foreach ($bills as $k => $v){
            if ($k == "bill_id")
                $bill_id=$v;
            if ($k == "short_title")
                $short_title=$v;
            if ($k == "chamber")
                $bill_chamber=$v;
            if ($k == "introduced_on")
                $introduced_on =$v;
            if($k == "last_action_at")
                $last_action_at=$v;
        }
        //var_dump($bill);
        $bill_last_version=$bills["last_version"];
        $sponsor_details=$bills["sponsor"];
        foreach ($sponsor_details as $key => $value){
            if ($key == "title")
                $sponsor_title = $value;
            if ($key == "first_name")
                $sponsor_first=$value;
            if ($key == "last_name")
                $sponsor_last=$value;
        }
        $sponsor=$sponsor_title.' '.$sponsor_first.' '.$sponsor_last;
        foreach ($bill_last_version as $key => $value){
            if ($key == "version_name")
                $version_name=$value;
            if ($key == "urls"){
                $pdf=$value;
                foreach ($pdf as $k => $v)
                    if ($k == "pdf")
                        $pdf_link=$v;}}
        echo '<tr><td>' . $bill_id . '</td>';
        echo '<td>' . $short_title . '</td>';
        echo '<td>' . $bill_chamber . '</td>';
        //echo '<td><a href="congress.php?bill_id=' . $search . '&chamber=' . $chamber .'"' . 'onclick="this.form.reset()">View Details</a></td></tr>';
        echo '<td><a href="congress_new.php" onclick="'."displayBill('$bill_id','$short_title', '$sponsor', '$introduced_on','$version_name','$pdf_link','$last_action_at');return false;".'">View Details</a></td></tr></table></div>';

    }
    if ($db == "Amendment ID"){
        $url = "https://congress.api.sunlightfoundation.com/amendments?amendment_id=" . $search . "&chamber=" . $chamber . "&apikey=f0ec4ae1648f4b5bb7b0c2a14abb10d6";
        $response = file_get_contents($url);
        $response_json = json_decode($response, true);
        //var_dump($response_json);
        echo '<table border ="1px" id="amendmentInfo" style="text-align: center; border-collapse: collapse;"><col width="160"><col width="200"><col width="100"><col width="150">';
        echo '<tr><th>Amendment ID</th><th>Amendment Type</th><th>Chamber</th><th>Introduced On</th></tr>';
        foreach ($response_json["results"][0] as $k => $v){
            if ($k == "amendment_id")
                $amendment_id=$v;
            if ($k == "amendment_type")
                $amendment_type=$v;
            if ($k == "chamber")
                $amendment_chamber=$v;
            if ($k == "introduced_on")
                $introduced_on=$v;}
        echo '<tr><td>' . $amendment_id . '</td>';
        echo '<td>' . $amendment_type . '</td>';
        echo '<td>' . $chamber . '</td>';
        echo '<td>' . $introduced_on . '</td></tr></table>';

    }

}

function convertState($stateInput){
    $state=array("Alabama"=>"AL","Alaska"=>"AK","Arizona"=>"AZ","Arkansas"=>"AR","California"=>"CA","Colorado"=>"CO","Connecticut"=>"CT","Delaware"=>"DE","District of Columbia"=>"DC","Florida"=>"FL","Georgia"=>"GA","Hawaii"=>"HI","Idaho"=>"ID","Illinois"=>"IL","Indiana"=>"IN","Iowa"=>"IA","Kansas"=>"KS","Kentucky"=>"KY","Louisiana"=>"LA","Maine"=>"ME","Montana"=>"MT","Nebraska"=>"NE","Nevada"=>"NV","New Hampshire"=>"NH","New Jersey"=>"NJ","New Mexico"=>"NM","New York"=>"NY","North Carolina"=>"NC","North Dakota"=>"ND","Ohio"=>"OH","Oklahoma"=>"OK","Oregon"=>"OR","Maryland"=>"MD","Massachusetts"=>"MA","Michigan"=>"MI","Minnesota"=>"MN","Mississippi"=>"MS","Missouri"=>"MO","Pennsylvania"=>"PA","Rhode Island"=>"RI","South Carolina"=>"SC","South Dakota"=>"SD","Tennessee"=>"TN","Texas"=>"TX","Utah"=>"UT","Vermont"=>"VT","Virginia"=>"VA","Washington"=>"WA","West Virginia"=>"WV","Wisconsin"=>"WI","Wyoming"=>"WY");
    $result=$state[$stateInput];
    return $result;
}
?>

</body>
</head>
</html>