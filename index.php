<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        // Get all the HTML files from within IT Essentials only!
        $filetypes = array("html");
        $path = './ciscoanswers/ccna.smarthand.ro/';
        $di = new RecursiveDirectoryIterator($path);
        foreach (new RecursiveIteratorIterator($di) as $filename => $file) {
            $filetype = pathinfo($file, PATHINFO_EXTENSION);
            if (in_array(strtolower($filetype), $filetypes)) {
                if (strstr($file, "it-essentials-chapter"))
                    $files[] = (string) $file;
            }
        }
        // Now get me my information!
        foreach ($files as $file) {
            $dom = new DOMDocument();
            @$dom->loadHTMLFile($file);
            /*
             *  // Deactivate error messages
             *  // since this is no valid HTML
             *  // we're trying to read from! (@) 
             */
            $finder = new DomXPath($dom);
            $elem = $finder->query('//p | //ul | //strong');
            if ($elem->length <> 0) {
                $i = 0;
//                var_dump($elem);
//                die();
                foreach ($elem as $ele) {
//                    var_dump($ele);
//                    die();
                    $element = $ele;
                    $arrContent[$i]["question"] = null;
                    $arrContent[$i]["answers"] = null;
                    $arrContent[$i]["correctAnswer"] = null;
                    if ($element->tagName == 'p') {
                        if (strstr($element->nodeValue, "IT Essentials")) {
                            $arrContent[$i]["chapters"] = $element->nodeValue;
                        } else {
                            $arrContent[$i]["question"] = $element->nodeValue;
                        }
                    }
                    if ($element->tagName == 'ul') {
                        $arrContent[$i]["answers"] = $element->nodeValue;
                    }
                    if ($element->tagName == 'strong') {
                        $arrContent[$i]["correctAnswer"] = $element->nodeValue;
                    }
                    $i++;
                }
            }
        }
        var_dump($arrContent);
        die();
        unset($arrContent[0]);
        foreach (range(1, count($arrContent), 3) as $key) {
            unset($arrContent[$key]);
        }
        ?>
    </body>
</html>
