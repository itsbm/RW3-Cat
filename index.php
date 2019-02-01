<html>
<head>
<link rel="stylesheet" href="main.css" type="text/css">
<link rel="icon" type="img/ico" href="favicon.ico">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<?php
//PHP7 script to allow public user interface with readerware mysql database
//2019.02.01

require 'config.php';
$now = time();

echo  '<center><div class="col-12 col-s-12">';
print "<title> $sitetitle </title>";

print  "<div class='headr'><p><h2><center>$sitename</center></h2></p></div>";

print "<center><h3>$sitedesc</h3></center><br>";
print "<center><a class='book, buttonl' href='dvds.php'>DVDs</a>&nbsp&nbsp<a cla                                                                                                                                                                                        ss='book, buttonl' href='cds.php'>CDs</a></center>";


$gauthor = $_GET['author'];
echo $gauthor;

echo "<hr>";

echo'<p><center>
<form action="index.php?go" method="post">
Search the Library<br />
<input type="radio" name="field" value="T" checked="checked" />Title
<input type="radio" name="field" value="A" />Author
<input type="radio" name="field" value="D" />Description<br />
<input class="searchtext" placeholder="Search.." type="text" name="search"  />
<input type="submit" name="submit" value="Search" />
</form>';



if (isset($_POST['submit'])){
if ((isset($_GET['go'])) or (isset($_GET['sort']))){

if (ctype_alnum($_POST['search'])){
        $title=$_POST['search'];


if ($_POST['field'] == 'T') {

$squery = "AND (r.TITLE LIKE '%".$title."%') ORDER BY TITLE";
}
if ($_POST['field'] == 'A') {

$squery = "AND (c.SORT_NAME LIKE '%".$title."%') ORDER BY SORT_NAME";
}
if ($_POST['field'] == 'D') {

$squery = "AND (r.PRODUCT_INFO LIKE '%".$title."%') ORDER BY TITLE";
}
if ($_POST['showall']) {
echo 'it was S';
$squery = "AND () ORDER BY TITLE";
}


// Create connection
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbbook);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT r.ROWKEY, r.TITLE, r.AUTHOR, r.AUTHOR2, r.RELEASE_DATE, r.DATE_EN                                                                                                                                                                                        TERED,
r.PAGES, r.IMAGE1_DATA, r.PRODUCT_INFO, c.NAME, c.SORT_NAME
FROM READERWARE AS r LEFT JOIN CONTRIBUTOR AS c ON  r.AUTHOR = c.ROWKEY
WHERE (r.STATUS = 1)  $squery";



if ($_GET['sort'] == 'title')
{ $sql .= " ORDER BY TITLE";
}
elseif ($_GET['sort'] == 'author')
{ $sql .= " ORDER BY SORT_NAME";
}
elseif ($_GET['sort'] == 'enter')
{ $sql .= " ORDER BY DATE_ENTERED DESC";
}
elseif ($gauthor)

{ $sql = "SELECT r.ROWKEY, r.TITLE, r.AUTHOR, r.AUTHOR2, r.RELEASE_DATE, r.DATE_                                                                                                                                                                                        ENTERED,
r.PAGES, r.IMAGE1_DATA, r.PRODUCT_INFO, c.NAME, c.SORT_NAME
FROM READERWARE AS r LEFT JOIN CONTRIBUTOR AS c ON  r.AUTHOR = c.ROWKEY
WHERE r.AUTHOR = $gauthor OR r.AUTHOR2 = $gauthor";
}

}

}}


$result = $conn->query($sql);


$number_rows = mysqli_num_rows($result);
echo "Your search returned $number_rows results. <p>";


if ($result->num_rows > 0) {
    echo "<center><table>
        <tr class='header'>
        <th align='left'><a href='index.php?sort=title'>TITLE</a></th>
        <th align='left'><a href='index.php?sort=author'>AUTHOR</a></th>
        <th class='m01' align='left'>DESCRIPTION</th>
        <th class='m01'>RELEASED</th>
        <th>PAGES</th>
        </tr>";


    // output data of each row


    while($row = $result->fetch_assoc()) {

        echo "<tr>

        <td class='m02'><b>
                <div id='popup'>
                <a class='anchor' name=".$row["ROWKEY"]."></a>
                <a class='book' href='book.php?book=".$row["ROWKEY"]."'>".$row["                                                                                                                                                                                        TITLE"]."
                <span><img src='data:image/jpeg;base64,".base64_encode($row["IMA                                                                                                                                                                                        GE1_DATA"])."'></span>
                </div></b></td>
        <td width='20%'><i><a class='book' href='?author=".$row["AUTHOR"]."' >".                                                                                                                                                                                        $row["SORT_NAME"]."&nbsp</a></i></td>
        <td class='m01'>".substr($row["PRODUCT_INFO"],0,300 )."&nbsp</td>
        <td class='m01'>".$row["RELEASE_DATE"]."&nbsp</td>
        <td>".$row["PAGES"]."</td>
        </tr>" ;
    }
    echo "</table></center>";
} else {
    echo "0 results";
}
$conn->close();

echo "<br><br><br><br><br>";
echo "<br><br><br><br><br>";
echo "<br><br><br><br><br>";
echo "<br><br><br><br><br>";
echo "</div></center>";


?>
</html>
