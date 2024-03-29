<?php
/*
 This file will generate our CSV table. There is nothing to display on this page, it is simply used
 to generate our CSV file and then exit. That way we won't be re-directed after pressing the export
 to CSV button on the previous page.
*/

//First we'll generate an output variable called out. It'll have all of our text for the CSV file.
$out = '';

//Next we'll check to see if our variables posted and if they did we'll simply append them to out.
if (isset($_POST['csv_hdr'])) {
$out .= $_POST['csv_hdr'];
$out .= "\n";
}

if (isset($_POST['csv_output'])) {
$out .= $_POST['csv_output'];
}

$file = $_POST['csv_tipo'];
    

//Now we're ready to create a file. This method generates a filename based on the current date & time.
$filename = " Material_de_Apoyo_".date("Y-m-d_H-i",time());

//Generate the CSV file header
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: csv" . date("Y-m-d") . ".csv");
header("Content-disposition: filename=".$filename.".csv");

//Print the contents of out to the generated file.
print $out;

//Exit the script
exit;
?>