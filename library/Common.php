<?php

class Common
{
    public static function convertArray($variate, $delimiter = ','): array
    {
        if ($variate === '' || $variate === null) {
            return array();
        }
        if (is_string($variate)) {
            $objcet = simplexml_load_string($variate, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOERROR);
            if (is_object($objcet)) {
                return self::convertArray($objcet, $delimiter);
            }
            $array = json_decode($variate, true);
            if (is_array($array)) {
                return self::convertArray($array, $delimiter);
            }
            $array = explode($delimiter, $variate);
            if (is_array($array) && $array[0] != '') {
                return self::convertArray($array, $delimiter);
            }
            return self::convertArray(array($variate), $delimiter);
        } elseif (is_numeric($variate)) {
            if (is_float($variate)) {
                return self::convertArray((string) $variate, $delimiter);
            }
            return array($variate);
        } elseif (is_object($variate)) {
            return self::convertArray(json_encode($variate), $delimiter);
        } else {
            return $variate;
        }
    }
}
