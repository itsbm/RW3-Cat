<html>
<head>
<link rel="stylesheet" href="main.css" type="text/css">
</head>

<?php
//PHP7 script to allow public user interface with readerware mysql database
//2017.01.03

require 'config.php';
$now = time();

print "<title> $sitetitle </title>";
print "<center>Page loaded on ";
print date("l, F j Y \a\\t h:i a</center>", $now);

echo "<br><hr></center>";

// Create connection
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbbook);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$book = $_GET['book'];

$sql = "SELECT r.ROWKEY, r.TITLE, r.AUTHOR, r.RELEASE_DATE, r.PUBLISHER, r.ISBN, r.FORMAT,
r.DATE_ENTERED, r.PAGES, r.IMAGE1_DATA, r.MY_RATING, r.CATEGORY1, r.DATE_LAST_UPDATED,
r.PRODUCT_INFO, c.NAME, c.SORT_NAME, p.LISTITEM, cat.LISTITEM AS CATEG,  ppl.LISTITEM AS PUBPLACE, fl.LISTITEM AS FORMT
FROM READERWARE AS r
LEFT JOIN CONTRIBUTOR AS c ON r.AUTHOR = c.ROWKEY
LEFT JOIN PUBLISHER_LIST AS p ON r.PUBLISHER = p.ROWKEY
LEFT JOIN CATEGORY_LIST AS cat on r.CATEGORY1 = cat.ROWKEY
LEFT JOIN PUBLICATION_PLACE_LIST AS ppl on r.PUB_PLACE = ppl.ROWKEY
LEFT JOIN FORMAT_LIST AS fl on r.FORMAT = fl.ROWKEY
WHERE r.ROWKEY = '$book' limit 1 ";

$result = $conn->query($sql);

 if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
    echo "<center><table>
        <tr class='header'>
        <th align='center' colspan='2'>".$row["TITLE"]."</th>
        </tr>";

        echo "<tr>
        <td width='300px'><center><img src='data:image/jpeg;base64,".base64_encode($row["IMAGE1_DATA"])."'></center></td>
        <td>Title: <b>".$row["TITLE"]."</b>&nbsp<br>
        Author: <i>".$row["SORT_NAME"]."&nbsp</i><br>
        Released: ".$row["RELEASE_DATE"]."&nbsp<br>
        Publisher: ".$row["LISTITEM"]." - ".$row["PUBPLACE"]."&nbsp<br>
        ISBN: ".$row["ISBN"]."&nbsp<br>
        Format: ".$row["FORMT"]."&nbsp<br>
        Category: ".$row["CATEG"]."&nbsp<br>
        Last Updated: ".$row["DATE_LAST_UPDATED"]."&nbsp<br>
        Rating: ".$row["MY_RATING"]."&nbsp<br>
        Pages: ".$row["PAGES"]."&nbsp<br>
        <b>Description:</b><br> ".$row["PRODUCT_INFO"]."&nbsp<br>
        </td>
        </tr></table></center>";
}

    echo "&nbsp";
} else {
    echo "0 results";
}
$conn->close();

echo "<br><br><br><br><br>"
?>
</html>
