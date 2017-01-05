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
print  "<p><h2><center>$sitename</center></h2></p>";
print "<center>$sitedesc</center><br>";
print "<center>Page loaded on ";
print date("l, F j Y \a\\t h:i a</center>", $now);

echo "<br><hr>";

// Create connection
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbbook);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT r.ROWKEY, r.TITLE, r.AUTHOR, r.RELEASE_DATE, r.PAGES, r.IMAGE1_DATA, r.PRODUCT_INFO, c.NAME, c.SORT_NAME FROM READERWARE AS r INNER JOIN CONTRIBUTOR AS c ON  r.AUTHOR = c.ROWKEY";

if ($_GET['sort'] == 'title')
{ $sql .= " ORDER BY TITLE";
}
elseif ($_GET['sort'] == 'author')
{ $sql .= " ORDER BY SORT_NAME";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<center><table>
        <tr class='header'>
        <th align='left'><a href='index.php?sort=title'>TITLE</a></th>
        <th align='left'><a href='index.php?sort=author'>AUTHOR</a></th>
        <th align='left'>DESCRIPTION</th>
        <th>RELEASED</th>
        <th>PAGES</th>
        </tr>";
    // output data of each row

    while($row = $result->fetch_assoc()) {
        echo "<tr>
        <td width='30%'><b>
                <div id='popup'>
                <a class='anchor' name=".$row["ROWKEY"]."></a>
                <a class='book' href='index.php?sort=".($_GET[sort])."#".$row["ROWKEY"]."'>".$row["TITLE"]."
                <span><img src='data:image/jpeg;base64,".base64_encode($row["IMAGE1_DATA"])."'></span>
                </div></b></td>
        <td width='20%'><i>".$row["SORT_NAME"]."&nbsp</i></td>
        <td>".$row["PRODUCT_INFO"]."&nbsp</td>
        <td>".$row["RELEASE_DATE"]."&nbsp</td>
        <td>".$row["PAGES"]."</td>
        </tr>" ;

    }
    echo "</table></center>";
} else {
    echo "0 results";
}
$conn->close();

echo "<br><br><br><br><br>"
?>
</html>
