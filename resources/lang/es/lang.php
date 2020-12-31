
<?php
        $file = base_path() . '/resources/lang/' . "es" . '/'. "es". ".json";
        $array =  json_decode(file_get_contents($file), true);
        return $array;