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

        function buildPage($arr) {
            if (isset($_POST) && !empty($_POST)) {
                if ($_POST['exam'] === 'noval')
                    die('Du musst schon was auswählen!');
                if (is_array($arr[$_POST['exam']])) {
                    foreach ($arr[$_POST['exam']] as $aKey => $aVal) {
                        if (is_array($aVal)) {
                            foreach ($aVal as $bKey => $bVal) {
                                if ($bKey === 'chapter') {
                                    echo "<b>" . $bVal . "</b><br /><br />";
                                }
                                if ($bKey === 'question') {
                                    echo "<br /><i>" . $bVal . "</i><br /><br />";
                                }
                                //TODO: Return random value (either correct answer or a normal answer)
                                if ($bKey === 'correctAnwer') {
                                    shuffle($bVal);
                                    foreach ($bVal as $cKey => $cVal) {
                                        echo $cVal . "<br />";
                                    }
                                }
                                if ($bKey === 'answers') {
                                    shuffle($bVal);
                                    foreach ($bVal as $cKey => $cVal) {
                                        echo $cVal . "<br />";
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        function removeDoubleAnswers($arr) {
            //remove double answers
            foreach ($arr as $contentOuterKey => $contentOuterValue) {
                foreach ($contentOuterValue as $contentKey => $contentValue) {
                    foreach ($contentValue as $key => $val) {
                        if (is_array($val)) {
                            $cV = "";
                            foreach ($val as $k => $v) {
                                if ($v === $cV) {
                                    unset($arr[$contentOuterKey][$contentKey][$key][$k]);
                                }
                                $cV = $v;
                            }
                        }
                    }
                }
            }
            return $arr;
        }

        function buildSelectForm($arr) {
            echo '<form action="index.php" method="POST">';
            echo '<select onchange="this.form.submit()" name="exam">';
            echo "<option selected value='noval'>Bitte auswählen</option>";
            foreach ($arr as $aKey => $aVal) {
                if (isset($_POST) && !empty($_POST)) {
                    if ($_POST['exam']) {
                        if ($aKey === $_POST['exam']) {
                            echo "<option selected value='" . $aKey . "'>" . $aKey . "</option>";
                        } else {
                            echo "<option value='" . $aKey . "'>" . $aKey . "</option>";
                        }
                    }
                }
            }
            echo "</select>";
            echo '<input type="submit" value="Absenden">';
            echo "</form>";
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
                            $arrContent[$currentFile][$i]["chapter"] = $element->nodeValue;
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
                                if (!strpos($element->nodeValue, "?")) {
                                    $style = $element->getAttribute('style');
                                    if (stristr($style, "font-family: comic sans ms,sans-serif; font-size: 12px; background-color: #ffff00;") || stristr($style, "background-color: #ffff00;")) {
                                        //is correct answer
                                        $arrContent[$currentFile][$i]["correctAnswer"][] = (string) $element->nodeValue;
                                    }
                                    if (!strpos($element->nodeValue, "IT Essentials")) {
                                        $arrContent[$currentFile][$i]["answers"][] = $element->nodeValue;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $arrContent = removeDoubleAnswers($arrContent);
        buildSelectForm($arrContent);
        buildPage($arrContent);
        ?>
    </body>
</html>
