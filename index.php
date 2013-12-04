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
            $elem = $finder->query('//p | //ul');
            if ($elem->length <> 0) {
                for ($i = 0; $i < $elem->length; $i++) {
                    $element = $elem->item($i);
                    if ($element->tagName == 'p') {
                        if (strstr($element->nodeValue, "IT Essentials")) {
                            $arrContent[$i]["chapters"] = $element->nodeValue;
                            $arrContent[$i]["question"] = null;
                            $arrContent[$i]["answers"] = null;
                        } else {
                            $arrContent[$i]["question"] = $element->nodeValue;
                        }
                    } else {
                        $arrContent[$i]["question"] = null;
                    }
                    if ($element->tagName == 'ul') {
                        //is correct answer, append ###CORRECT### to the string
                        if($element->firstChild == 'span') {
                            $arrContent[$i]["answers"] = $element->nodeValue."###CORRECT###";
                        } else {
                            $arrContent[$i]["answers"] = $element->nodeValue;
                        }
                    } else {
                        $arrContent[$i]["answers"] = null;
                    }
//                    if ($element->tagName == 'p') {
//                        if ($element->nodeValue != "") {
//                            $arrContent[$i]["question"] = $element->nodeValue;
//                            $arrContent[$i]["answers"] = null;
//                        }
//                        
//                    } elseif ($element->tagName == 'ul') {
//                        if ($element->nodeValue != "")
//                            $arrContent[$i]["answers"] = $element->nodeValue;
//                    }
                }
            }
        }
        unset($arrContent[0]);
        var_dump($arrContent);
        die();
        ?>
    </body>
</html>
