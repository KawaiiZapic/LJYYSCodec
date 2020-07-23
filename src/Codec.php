<?php
namespace LJYYSCodec;

/**
 *
 * Encode / decode any data with 8 similar chars.
 *
 */

class Codec {
    private $_map;

    /**
     *
     * Present map,encode any data with ljyys!
     *
     */
    public static $ljyys = [
        "0" => ["ļ", "ĵ", "ý", "ӱ", "ś"],
        "1" => ["ľ", "ǰ", "ÿ", "ӳ", "ŝ"],
        "2" => ["ŀ", "ȷ", "ŷ", "ẏ", "ş"],
        "3" => ["ḷ", "ɉ", "ƴ", "ỳ", "š"],
        "4" => ["ł", "ɟ", "ȳ", "ỵ", "ṣ"],
        "5" => ["ḹ", "ʝ", "у", "ỷ", "ṥ"],
        "6" => ["ḻ", "ϳ", "ў", "γ", "ṧ"],
        "7" => ["ḽ", "ј", "ӯ", "y", "ṩ"],
    ];

    /**
     *
     * Init codec map.
     *
     * @param array Code map
     *
     */
    
    public function __construct(array $map) {
        $this->_map = $map;
    }

    /**
     *
     * Encode data with map.
     *
     * @param string $data Data to encode
     *
     * @return string Encoded data.
     *
     */

    public function encode(string $data): string {
        $data = bin2hex($data);
        preg_match_all("/.{2}/u", $data, $matches);
        foreach ($matches[0] as &$bin) {
            $bin = base_convert($bin, 16, 8);
            while (strlen($bin) < 3) {
                $bin = "0" . $bin;
            }
        }
        unset($bin);
        $p = 0;
        $l = count($this->_map[0]) - 1;
        $r = '';
        foreach ($matches[0] as $v) {
            preg_match_all("/./u", $v, $tmp);
            foreach ($tmp[0] as $n) {
                if ($n < 8) {
                    $r .= $this->_map[intval($n)][$p];
                } else {
                    throw new \Exception("LJYYSNMSL");
                }
                $p += $p < $l ? 1 : -$p;
            }
        }
        return $r;
    }

    /**
     *
     * Decode data with map.
     *
     * @param string $data Data to decode
     *
     * @return string Decoded data.
     *
     */

    public function decode(string $data): string {
        foreach ($this->_map as $n => $g) {
            foreach ($g as $m) {
                $data = str_replace($m, $n, $data);
            }
        }
        preg_match_all("/.{3}/u", $data, $matches);
        $raw = '';
        foreach ($matches[0] as $val) {
            $tmp = base_convert($val, 8, 16);
            while (strlen($tmp) < 2) {
                $tmp = "0" . $tmp;
            }
            $raw .= $tmp;
        }
        return hex2bin($raw);
    }
}
