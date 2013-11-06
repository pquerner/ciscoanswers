<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        // Get all the HTML files from within the 2013 folder. (07 and 09)
        $filetypes = array("html");
        $path = './html/html/aiubproblemsolver.blogspot.de/2013/';
        $di = new RecursiveDirectoryIterator($path);
        foreach (new RecursiveIteratorIterator($di) as $filename => $file) {
            $filetype = pathinfo($file, PATHINFO_EXTENSION);
            if (in_array(strtolower($filetype), $filetypes)) {
                $files[] = (string) $file;
            }
        }

        // Now get me my information!
        foreach ($files as $file) {
            $folderName = explode('2013', $file);
            $stringToSeachIn = $folderName[1];
            $stringToSeachFor = '\\';
            $pos = strpos($stringToSeachIn, $stringToSeachFor, 1);
            $rawFolderName = substr($stringToSeachIn, 1, $pos);
            $rawFolderName = str_replace("\\", "/", $rawFolderName);
            $rawFileName = substr($stringToSeachIn, $pos + 1);
            $dom = new DOMDocument();
            @$dom->loadHTMLFile($path . $rawFolderName . $rawFileName); // Deactivate error messages
                                                                        // since this is no valid HTML
                                                                        // we're trying to read from! (@)
            $finder = new DomXPath($dom);
            $classname = "post hentry"; // Only get me this class! Its the post itself, only!
            $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
            foreach ($nodes as $node) {
                $htmlContent[] = (string)$node->nodeValue;
            }
        }
        var_dump($htmlContent);
        ?>
    </body>
</html>
