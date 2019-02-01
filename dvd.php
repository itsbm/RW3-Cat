<html>
<head>
<link rel="stylesheet" href="main.css" type="text/css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<?php
//PHP7 script to allow public user interface with readerware mysql database
//2019.02.01

require 'config.php';
$now = time();

print "<title> $sitetitle </title>";

echo "<br><hr></center>";

echo "<center><a class='book' href='index.php'><h3>HOME</h3></a></center>";

// Create connection
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbvideo);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$dvd = $_GET['dvd'];

$sql = "SELECT v.ROWKEY, v.TITLE, v.AUTHOR, v.RELEASE_DATE, v.PUBLISHER, v.ISBN,                                                                                                                                                                                         v.FORMAT,
v.DEWEY, v.DATE_ENTERED, v.RUNNING_TIME, v.IMAGE1_DATA, v.MY_RATING, v.CATEGORY1                                                                                                                                                                                        , v.DATE_LAST_UPDATED,
v.PRODUCT_INFO, c.NAME, c.SORT_NAME, p.LISTITEM, cat.LISTITEM AS CATEG,  ppl.LIS                                                                                                                                                                                        TITEM AS PUBPLACE, fl.LISTITEM AS FORMT
FROM VIDEOWARE AS v
LEFT JOIN CONTRIBUTOR AS c ON v.AUTHOR = c.ROWKEY
LEFT JOIN PUBLISHER_LIST AS p ON v.PUBLISHER = p.ROWKEY
LEFT JOIN CATEGORY_LIST AS cat on v.CATEGORY1 = cat.ROWKEY
LEFT JOIN PUBLICATION_PLACE_LIST AS ppl on v.PUB_PLACE = ppl.ROWKEY
LEFT JOIN FORMAT_LIST AS fl on v.FORMAT = fl.ROWKEY
WHERE v.ROWKEY = '$dvd' limit 1 ";

$result = $conn->query($sql);


 if ($result->num_rows > 0) {
    // output data of each row

    while ($row = $result->fetch_assoc()) {


    echo "<div class='row'>
            <div class='col-4 col-s-12'>
            <center><img class='rimage' src='data:image/jpeg;base64,".base64_enc                                                                                                                                                                                        ode($row["IMAGE1_DATA"])."'></center>
            </div>

        <div class='col-8 col-s-12'>
        <center><table>
        <tr class='header'>
        <th align='center' colspan='1'>".$row["TITLE"]."</th>
        </tr>";


        echo "<tr>
        <td>Title: <b>".$row["TITLE"]."</b>&nbsp<br>
        Author: <i>".$row["SORT_NAME"]."&nbsp</i><br>
        Released: ".$row["RELEASE_DATE"]."&nbsp<br>
        Publisher: ".$row["LISTITEM"]." - ".$row["PUBPLACE"]."&nbsp<br>
        ISBN: ".$row["ISBN"]."&nbsp<br>
        Dewey: ".$row["DEWEY"]."&nbsp<br>
        Format: ".$row["FORMT"]."&nbsp<br>
        Category: ".$row["CATEG"]."&nbsp<br>
        Last Updated: ".$row["DATE_LAST_UPDATED"]."&nbsp<br>
        Rating: ".$row["MY_RATING"]."&nbsp<br>
        Running Time: ".$row["RUNNING_TIME"].' min'."&nbsp<br>
        <hr>
        <b>Description:</b><br> ".nl2br($row["PRODUCT_INFO"])."&nbsp<br>
        </td>
        </tr></table></center>

        </div>
        </div>";

}


    echo "&nbsp";
} else {
    echo "0 results";
}
$conn->close();

echo "<br><br><br><br><br>"
?>
</html>
