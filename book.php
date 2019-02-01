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
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbbook);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$book = $_GET['book'];

$sql = "SELECT r.ROWKEY, r.TITLE, r.AUTHOR, r.AUTHOR2, r.RELEASE_DATE, r.PUBLISH                                                                                                                                                                                        ER, r.ISBN, r.FORMAT,
r.DEWEY, r.DATE_ENTERED, r.PAGES, r.IMAGE1_LARGE_DATA, r.MY_RATING, r.CATEGORY1,                                                                                                                                                                                         r.DATE_LAST_UPDATED,
r.PRODUCT_INFO, r.SERIES, c2.NAME, c2.SORT_NAME AS A2, c1.NAME, c1.SORT_NAME AS                                                                                                                                                                                         A1, p.LISTITEM, cat.LISTITEM AS CATEG,  ppl.LISTITEM AS PUBPLACE, fl.LISTITEM AS                                                                                                                                                                                         FORMT, ln.LN_STATUS, sl.LISTITEM AS BKSERIES
FROM READERWARE AS r
LEFT JOIN CONTRIBUTOR AS c1 ON r.AUTHOR = c1.ROWKEY
LEFT JOIN CONTRIBUTOR AS c2 ON r.AUTHOR2 = c2.ROWKEY
LEFT JOIN PUBLISHER_LIST AS p ON r.PUBLISHER = p.ROWKEY
LEFT JOIN CATEGORY_LIST AS cat on r.CATEGORY1 = cat.ROWKEY
LEFT JOIN PUBLICATION_PLACE_LIST AS ppl on r.PUB_PLACE = ppl.ROWKEY
LEFT JOIN FORMAT_LIST AS fl on r.FORMAT = fl.ROWKEY
LEFT JOIN LOANS AS ln on r.ROWKEY = ln.LN_ITEM_ROWKEY
LEFT JOIN SERIES_LIST AS sl on r.SERIES = sl.ROWKEY
WHERE r.ROWKEY = '$book' limit 1 ";

$result = $conn->query($sql);


 if ($result->num_rows > 0) {
    // output data of each row

//      '1' => '* * * *',
//      '2' => '* * * * *',
//      '3' => '* * *',
//      '4' => '*',
//      '5' => '* * *',
//      '6' => '* * * *'



    while ($row = $result->fetch_assoc()) {

    $stars = array(
        '3' => '&#9733&#9733&#9733&#9734&#9734',
        '4' => '&#9733&#9734&#9734&#9734&#9734',
        '-1' => '&#9734&#9734&#9734&#9734&#9734',
        '1' => '&#9733&#9733&#9733&#9733&#9734',
        '2' => '&#9733&#9733&#9733&#9733&#9733',
        '5' => '&#9733&#9733&#9733&#9734&#9734',
        '6' => '&#9733&#9733&#9733&#9733&#9734',

     );


    echo "<div class='row'>
            <div class='col-4 col-s-12'>
            <center><img class='rimage' src='data:image/jpeg;base64,".base64_enc                                                                                                                                                                                        ode($row["IMAGE1_LARGE_DATA"])."'></center>
            </div>

            <div class='col-8 col-s-12'><center><table>
            <tr class='header'>
            <th align='center' colspan='1'>".$row["TITLE"]."</th>
            </tr>";


        echo"<tr>
        <td>Title: <b>".$row["TITLE"]."</b>&nbsp<br>
        Author: <i>".$row["A1"]."&nbsp</i>
         <i>" .$row["A2"]."&nbsp</i><br>
        Series: <a class='book' href='index.php?ser=".$row["SERIES"]."'>".$row["                                                                                                                                                                                        BKSERIES"]."</a>&nbsp<br>

        Status: ".$row["LN_STATUS"]."&nbsp<br>
        Released: ".$row["RELEASE_DATE"]."&nbsp<br>
        Publisher: ".$row["LISTITEM"]." - ".$row["PUBPLACE"]."&nbsp<br>
        ISBN:   <a class='book' target='_blank'  href='http://www.directtextbook                                                                                                                                                                                        .com/isbn/".$row["ISBN"]."'>".$row["ISBN"]."</a>&nbsp<br>
        Dewey: ".$row["DEWEY"]."&nbsp<br>
        Format: ".$row["FORMT"]."&nbsp<br>
        Category: ".$row["CATEG"]."&nbsp<br>
        Last Updated: ".$row["DATE_LAST_UPDATED"]."&nbsp<br>
        Rating: ".str_replace(array_keys($stars), $stars, $row["MY_RATING"])."&n                                                                                                                                                                                        bsp<br>
        Pages: ".$row["PAGES"]."&nbsp<br>
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
