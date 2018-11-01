
<?php
    // force download of CSV
    // simulate file handle w/ php://output, direct to output (from http://www.php.net/manual/en/function.fputcsv.php#72428)
    // (could alternately write to memory handle & read from stream, this seems more direct)
    // headers from http://us3.php.net/manual/en/function.readfile.php
    header('Content-Description: File Transfer');
    header('Content-Type: application/csv');
    header("Content-Disposition: attachment; filename=page-data-export.csv");
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    $handle = fopen('php://output', 'w');
    ob_clean(); // clean slate
      // [given some database query object $result]...
        $arr[] = ['Header1','Header2'];
        $arr[] = ['Header1','Header2'];
        $arr[] = ['Header1','Header2'];
        foreach($arr as $ar){
            fputcsv($handle, $ar); 
            }
      
    ob_flush(); // dump buffer
    fclose($handle);
    die();		
    // client should see download prompt and page remains where it was  
?>
