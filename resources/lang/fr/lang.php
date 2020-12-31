
<?php
        $file = base_path() . '/resources/lang/' . "fr" . '/'. "fr". ".json";
        $array =  json_decode(file_get_contents($file), true);
        return $array;