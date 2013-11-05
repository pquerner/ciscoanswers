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
            $tmp_dom = new DOMDocument();
            foreach ($nodes as $node) {
//                var_dump($node);
//                die();
                $tmp_dom->appendChild($tmp_dom->importNode($node, true));
            }
            $innerHTML[] = trim($tmp_dom->saveHTML());
            var_dump($innerHTML);
            die();
            // TODO: Find out why nodes are more than once in the array
            // (Check line 38, I think there lies the error!)
            // PS: I have to use textNode (or nodeValue)!
        }
        ?>
    </body>
</html>
