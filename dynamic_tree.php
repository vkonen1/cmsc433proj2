<?php
function generateTreeList($classes) {
    global $classReqs;
    $inTree = array();

    //initialize to no classes in the tree
    foreach ($classes as $class) {
        $inTree[$class[0]] = false;
    }

    echo "<ul>";

    foreach ($classes as $class) {
        if (!$inTree[$class[0]]) {
            echo "<li><a id=\"" . $class[0] . "\" href=\"#\">" . $class[0] . "</a>";
            $inTree[$class[0]] = true;

            if ($class[0] == "CMSC447") {
                return;
            }

            if (!empty($class[1][0])) {
                generateTreeList(getClassReqs($class[1][0]));                
            }
            echo "</li>";
        }
    }

    echo "</ul>";
}

function getClassReqs($classNames) {
    global $classReqs;

    $classes = array();

    foreach ($classNames as $className) {
        $classes[] = getClass($className);
    }

    return $classes;
}

function getClass($className) {
    global $classReqs;

    foreach ($classReqs as $classReq) {
        if ($classReq[0] == $className) {
            return $classReq;
        }
    }

    return [];
}