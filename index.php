<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php

        function getFileNameWithoutExtension($file) {
            return removeExtensionFromString(substr(strstr($file, '\\'), 1));
        }

        function removeExtensionFromString($string) {
            return preg_replace("/\\.[^.\\s]{3,4}$/", "", $string);
        }

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
            $currentFile = getFileNameWithoutExtension($file);
            $dom = new DOMDocument();
            @$dom->loadHTMLFile($file);
            /*
             *  // Deactivate error messages
             *  // since this is no valid HTML
             *  // we're trying to read from! (@) 
             */
            $finder = new DomXPath($dom);
            $elem = $finder->query('//p | //span');
            if ($elem->length <> 0) {
                $i = 0;
                foreach ($elem as $ele) {
                    $element = $ele;
                    if ($element->tagName == 'p') {
                        if (strstr($element->nodeValue, "IT Essentials")) {
                            $arrContent[$currentFile][$i]["chapters"] = $element->nodeValue;
                        } else {
                            $firstChars = trim($element->nodeValue);
                            $firstChars = substr($firstChars, 0, 3);
                            $firstChars = str_replace(".", "", $firstChars);
                            $firstChars = trim($firstChars);
                            //with all this, it must be a question. otherwise I would eat a broomstick!
                            if (is_numeric($firstChars) && !empty($firstChars)) {
                                $arrContent[$currentFile][$i + 1]["question"] = $element->nodeValue;
                            }
                        }
                        $i++;
                    }
                    if ($element->tagName == 'span') {
                        if ($element->hasAttribute('style')) {
                            if (!empty($element->nodeValue)) {
                                if (!stristr($element->nodeValue, "?")) {
                                    $style = $element->getAttribute('style');
                                    if (stristr($style, "font-family: comic sans ms,sans-serif; font-size: 12px; background-color: #ffff00;")) {
                                        //is correct answer
                                        $arrContent[$currentFile][$i]["correctAnswer"][] = (string) $element->nodeValue;
                                    }
                                    if ($element->nodeValue === $curVal) {
                                        $arrContent[$currentFile][$i]["answers"][] = (string) $element->nodeValue;
                                    }
                                    $curVal = (string) $element->nodeValue;
                                }
                            }


                        }
                    }
                }
            }
            var_dump($arrContent);
            die();
        }
        var_dump($arrContent);
        ?>
    </body>
</html>
