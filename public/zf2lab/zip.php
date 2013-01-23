<meta charset="gb2312" />
<?php
require_once './autoloader.php';

$path = 'D:\xampp\htdocs\\';
$filename = 'test.zip';

$file = $path . $file = iconv("UTF-8", "gb2312", $filename);
$zip = new ZipArchive();
$res = $zip->open($file);

//list all files within a zip
if ($res) {
    $i = 0;
    $numFiles = $zip->numFiles;
    for($i; $i < $numFiles; $i++){
        $stat = $zip->statIndex($i);
    }
}

//read a file without unzip
if ($res) {
    $i = 1;
    $stat = $zip->statIndex($i);

    $fp = $zip->getStream($stat['name']);
    if(!$fp){
        exit("failed\n");
    }

    $contents = '';
    while (!feof($fp)) {
        $contents .= fread($fp, 2);
    }
    fclose($fp);
    //header('Content-Type: image/jpg');
    //echo $contents;
}
$zip->close();

/*
if ($res === TRUE) {
    echo 'ok';
    $zip->extractTo('test');
    $zip->close();
} else {
    echo 'failed, code:' . $res;
}
*/


/*
foreach (glob($path . '*.zip') as $filename) {
    echo "$filename size " . filesize($filename) . "\n";
    exit;
}
*/


