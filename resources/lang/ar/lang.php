<?php
                                                    $jsonFile =  base_path() ."/resources/lang/ar/ar.json";
                                                    $array =  json_decode(file_get_contents($jsonFile), true);
                                                    return $array;