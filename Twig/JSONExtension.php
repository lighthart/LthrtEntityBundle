<?php

namespace Lthrt\EntityBundle\Twig;

class JSONExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('json_pretty', [$this, 'jsonPretty']),
            new \Twig_SimpleFilter('json_decode', [$this, 'jsonDecode']),
        ];
    }

    public function getName()
    {
        return 'lthrt.entity.twig_extension';
    }

    public function jsonPretty($string, $pad = 4)
    {
        $json   = "<pre>";
        $strlen = strlen($string);
        $string = str_replace("\\\\", "\\", $string);
        $indent = 0;
        for ($i = 0; $i <= $strlen; $i++) {
            $char = substr($string, $i, 1);
            if (in_array($char, ["{", "["])) {
                $json .= "";
                $json .= $char;
                $indent++;
                if ("]" != substr($string, $i + 1, 1)) {
                    $json .= "\n";
                    $json .= str_repeat(" ", $pad * $indent);
                }
            } elseif (in_array($char, ["}", "]"])) {
                $indent--;
                if ("[" != substr($string, $i - 1, 1)) {
                    $json .= "\n";
                    $json .= str_repeat(" ", $pad * $indent);
                }
                $json .= $char;
            } elseif ("," == $char) {
                $json .= $char;
                $json .= "\n";
                $json .= str_repeat(" ", $pad * $indent);
            } else {
                $json .= $char;
            }
        }
        $json .= "</pre>";

        return $json;
    }

    public function jsonDecode($string)
    {
        return json_decode($string, true);
    }
}
