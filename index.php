<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        $filetypes = array("html");
        $path = './html/html/aiubproblemsolver.blogspot.de/2013/';
        $di = new RecursiveDirectoryIterator($path);
        foreach (new RecursiveIteratorIterator($di) as $filename => $file) {
            $filetype = pathinfo($file, PATHINFO_EXTENSION);
            if (in_array(strtolower($filetype), $filetypes)) {
                $files[] = (string) $file;
            }
        }
//        var_dump($files);

        foreach ($files as $file) {
            $folderName = explode('2013', $file);
            $stringToSeachIn = $folderName[1];
            $stringToSeachFor = '\\';
            $pos = strpos($stringToSeachIn, $stringToSeachFor, 1);
            $rawFolderName = substr($stringToSeachIn, 1, $pos);
            $rawFolderName = str_replace("\\", "/", $rawFolderName);
            $rawFileName = substr($stringToSeachIn, $pos + 1);
            $dom = new DOMDocument();
//            var_dump($path . $rawFolderName . $rawFileName);
//            die();
            $dom->loadHTMLFile($path . $rawFolderName . $rawFileName);
            $elements = $dom->getElementsByTagName('*');
            if (!is_null($elements)) {
                foreach ($elements as $element) {
                    echo "<br/>" . $element->nodeName . ": ";

                    $nodes = $element->childNodes;
                    foreach ($nodes as $node) {
                        echo $node->nodeValue . "\n";
                    }
                }
            }
        }
        ?>
    </body>
</html>
