<?php

    if (count($items) > 0) {
        foreach ($items as $item) {
            $pos = strpos($item['link'], 'http://');
            if ($pos === false) {
                $link = Option::get('siteurl').$item['link'];
            } else {
                $link = $item['link'];
            }

            echo '<li><a href="'.$link.'"';
            if (isset($uri[1])) {
                $child_link = explode("/",$item['link']);
                if (isset($child_link[1])) {
                    if (in_array($child_link[1], $uri)) {
                        echo ' class="current" ';
                    }
                }
            }

            if (isset($uri[0]) && $uri[0] !== '') {
                if (in_array($item['link'], $uri)) {
                    echo ' class="current" ';
                }
            } else {
                if ($defpage == trim($item['link'])) {
                    echo ' class="current" ';
                }
            }

            if (trim($item['target']) !== '') {
                echo ' target="'.$item['target'].'" ';
            }

            echo '>'.$item['name'].'</a></li>'."\n";
        }
    }