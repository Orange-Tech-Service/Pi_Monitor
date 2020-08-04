<?PHP
function execPrint($command) {
    $result = array();
    exec($command, $result);
    foreach ($result as $line) {
        print($line . "\n");
    }
}
// Print the exec output inside of a pre element

print("<pre>" . execPrint("git pull") . "</pre>");

//echo exec("file data");
 echo "TEST";
 echo "TEST-2";

?>