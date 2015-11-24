<!DOCTYPE html>
<html>
<body>
<?php
$endpoint = $argv[1];

echo "Try Connecting the DB"; 
$link = mysqli_connect($endpoint,"SukanyaN","SukanyaNDB","itmo544SNDB",3306) or die("Error " . mysqli_error()); 

$sql = "CREATE TABLE items 
(
ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
UName VARCHAR(20),
Email Varchar(20),
Phone Varchar(20),
RawS3Url  Varchar(256),
FinalS3Url  Varchar(256),
JpgFileName    Varchar(256),
Status    TinyInt(3),
Issubscribed TinyInt(3),
CreationTime  Timestamp DEFAULT CURRENT_TIMESTAMP
)";

print($sql);


if ($link->query($sql) === TRUE) {
    echo "Table items created successfully";
} else {
    echo "Error creating table: " . $link->error;
}

$sql = "CREATE TABLE topic
(ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
topicarn Varchar(256),
topicName Varchar(256)
)";
if ($link->query($sql) === TRUE) {
    echo "Table topic created successfully";
} else {
    echo "Error creating table: " . $link->error;
}

$link->close();


echo "done";

?>

</body>
</html>
