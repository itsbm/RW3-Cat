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

echo  '<center><div class="col-12 col-s-12">';
print "<title> $sitetitle </title>";
print  "<div class='headr'><p><h2><center>$sitename</center></h2></p></div>";
print "<center><h3>$sitedesc</h3></center><br>";
print "<center><a class='book, buttonl' href='index.php'>Books</a>&nbsp&nbsp<a c                                                                                                                                                                                        lass='book, buttonl' href='dvds.php'>DVDs</a></center>";

$gauthor = $_GET['author'];
echo $gauthor;

echo "<hr>";


echo'<p><center>
<form action="cds.php?go" method="post">
Search the Library<br />
<input type="radio" name="field" value="T" checked="checked" />Title
<input type="radio" name="field" value="A" />Publisher
<input type="radio" name="field" value="D" />Description<br />
<input type="text" name="search" placeholder="Search.."  />
<input type="submit" name="submit" value="Search" />
</form>';


if (isset($_POST['submit'])){
if ((isset($_GET['go'])) or (isset($_GET['sort']))){

if (ctype_alnum($_POST['search'])){
        $title=$_POST['search'];


if ($_POST['field'] == 'T') {

$squery = "AND (a.TITLE LIKE '%".$title."%') ORDER BY TITLE";
}
if ($_POST['field'] == 'A') {

$squery = "AND (c.LISTITEM LIKE '%".$title."%') ORDER BY LISTITEM";
}
if ($_POST['field'] == 'D') {

$squery = "AND (a.PRODUCT_INFO LIKE '%".$title."%') ORDER BY TITLE";
}
if ($_POST['showall']) {
echo 'it was S';
$squery = "AND () ORDER BY TITLE";
}




// Create connection
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbaudio);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT a.ROWKEY, a.TITLE, a.ARTIST, a.COMPOSER, a.RELEASE_DATE, a.DATE_E                                                                                                                                                                                        NTERED,
a.RUNNING_TIME, a.IMAGE1_DATA, a.PRODUCT_INFO, c.NAME, c.SORT_NAME
FROM AUDIOWARE AS a LEFT JOIN CONTRIBUTOR AS c ON  a.ARTIST = c.ROWKEY
WHERE (a.STATUS = 1)  $squery";


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

{ $sql = "SELECT v.ROWKEY, v.TITLE, v.AUTHOR, v.RELEASE_DATE, v.DATE_ENTERED,
v.RUNING_TIME, v.IMAGE1_DATA, v.PRODUCT_INFO, c.NAME, c.SORT_NAME
FROM VIDEOWARE AS v LEFT JOIN PUBLISHER_LIST AS c ON  v.AUTHOR = c.ROWKEY
WHERE v.AUTHOR = $gauthor";
}

}

}}


$result = $conn->query($sql);

$number_rows = mysqli_num_rows($result);
echo "Your search returned $number_rows results. <p>";


if ($result->num_rows > 0) {
    echo "<center><table>
        <tr class='header'>
        <th align='left'><a href='cds.php?sort=title'>TITLE</a></th>
        <th class='m01' align='left'><a href='cds.php?sort=author'>PUBLISHER</a>                                                                                                                                                                                        </th>
        <th class='m01' align='left'>DESCRIPTION</th>
        <th class='m01'> RELEASED</th>
        <th>TIME</th>
        </tr>";
    // output data of each row


    while($row = $result->fetch_assoc()) {

        echo "<tr>

        <td  class='m02'><b>
                <div id='popup'>
                <a class='anchor' name=".$row["ROWKEY"]."></a>
                <a class='book' href='cd.php?cd=".$row["ROWKEY"]."'>".$row["TITL                                                                                                                                                                                        E"]."
                <span><img src='data:image/jpeg;base64,".base64_encode($row["IMA                                                                                                                                                                                        GE1_DATA"])."'></span>
                </div></b></td>
        <td  class='m01' width='20%'><i><a class='book' href='?author=".$row["NA                                                                                                                                                                                        ME"]."' >".$row["SORT_NAME"]."&nbsp</a></i></td>
        <td  class='m01'>".substr($row["PRODUCT_INFO"],0,300 )."&nbsp</td>
        <td  class='m01'>".$row["RELEASE_DATE"]."&nbsp</td>
        <td>".$row["RUNNING_TIME"]."</td>
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


?>
</html>
