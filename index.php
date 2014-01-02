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
            $elem = $finder->query('//p | //ul | //span');
            if ($elem->length <> 0) {
                $i = 0;
                foreach ($elem as $ele) {
                    $element = $ele;
                    //preadd some data, they will later on be thrown away anyway
                    $arrContent[$currentFile][$i]["question"] = null;
                    $arrContent[$currentFile][$i]["answers"] = null;
                    $arrContent[$currentFile][$i]["correctAnswer"] = null;
                    if ($element->tagName == 'p') {
                        if (strstr($element->nodeValue, "IT Essentials")) {
                            $arrContent[$currentFile][$i]["chapters"] = $element->nodeValue;
                        } else {
                            $arrContent[$currentFile][$i]["question"] = $element->nodeValue;
                        }
                        $i++;
                    }
//                    if ($element->tagName == 'ul') {
//                        if ($element->firstChild == 'li') {
//                            //TODO: Check if span is inside li. Then its an answer!
//                            if ($element->hasAttribute('style')) {
//                                //is answer
//                                $arrContent[$currentFile][$i]["answers"] = $element->nodeValue;
//
//                                //check for correct answer
//                                $style = $element->getAttribute('style');
//                                if (stristr($style, "font-family: comic sans ms,sans-serif; font-size: 12px; background-color: #ffff00;")) {
//                                    //is correct answer
//                                    $arrContent[$currentFile][$i]["correctAnswer"] = (string) $element->nodeValue;
////                                var_dump($element->nodeValue);
//                                }
//                            }
//                        }
//                    }
//                    TODO: 1 question can have more than one answer! Fix that!
                    if ($element->tagName == 'span') {
                        if ($element->hasAttribute('style')) {
                            //is answer
//                            var_dump($element->nodeValue);
                            $arrContent[$currentFile][$i]["answers"] = $element->nodeValue;
                            $style = $element->getAttribute('style');
                            if (stristr($style, "font-family: comic sans ms,sans-serif; font-size: 12px; background-color: #ffff00;")) {
                                //is correct answer
                                $arrContent[$currentFile][$i]["correctAnswer"] = (string) $element->nodeValue;
//                                var_dump($element->nodeValue);
                            }
                        }
                    }
                }
            }
        }
        //remove empty values from array, as they are unneded
        //yes, i hate this 'function'
        foreach ($arrContent as $contentOuterKey => $contentOuterValue) {
            foreach ($contentOuterValue as $contentKey => $contentValue) {
                foreach ($contentValue as $key => $val) {
                    //first remove empty value
                    if (empty($val)) {
                        unset($arrContent[$contentOuterKey][$contentKey][$key]);
                    }
                    //check if complete key is empty, if so, remove it from array aswell
                    if (empty($arrContent[$contentOuterKey][$contentKey])) {
                        unset($arrContent[$contentOuterKey][$contentKey]);
                    }
                }
            }
        }
        //reindex array
        var_dump($arrContent);
        ?>
    </body>
</html>
