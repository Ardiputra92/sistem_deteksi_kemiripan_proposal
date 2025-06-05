<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FingerprintPdf extends Model
{
    use HasFactory;

    private $word1 = '';

    //input properties
    private $prime_number = 23;
    private $n_gram_value = 8;
    private $n_window_value = 6;

    //output properties
    private $arr_n_gram1;
    private $arr_rolling_hash1;
    private $arr_window1;
    private $arr_fingerprints1;

    public function SetPrimeNumber($value)
    {
        $this->prime_number = $value;
    }

    public function SetNGramValue($value)
    {
        $this->n_gram_value = $value;
    }

    public function SetNWindowValue($value)
    {
        $this->n_window_value = $value;
    }

    public function GetNGramFirst()
    {
        return $this->arr_n_gram1;
    }

    public function GetRollingHashFirst()
    {
        return $this->arr_rolling_hash1;
    }

    public function GetWindowFirst()
    {
        return $this->arr_window1;
    }

    public function GetFingerprintsFirst()
    {
        return $this->arr_fingerprints1;
    }

    function __construct($w1)
    {
        $this->word1 = $w1;
    }

    public function process()
    {
        if ($this->word1 == '') exit;

        //langkah 1 : buang semua huruf yang bukan kelompok [a-z A-Z 0-9] dan ubah menjadi huruf kecil semua (lowercase)
        $this->word1 = strtolower(str_replace(' ', '', preg_replace("/[^a-zA-Z0-9\s-]/", "", $this->word1)));

        //langkah 2 : buat N-Gram
        $this->arr_n_gram1 = $this->n_gram($this->word1, $this->n_gram_value);


        //langkah 3 : rolling hash untuk masing-masing n gram
        $this->arr_rolling_hash1 = $this->rolling_hash($this->arr_n_gram1);


        //langkah 4 : buat windowing untuk masing-masing tabel hash
        $this->arr_window1 = $this->windowing($this->arr_rolling_hash1, $this->n_window_value);


        //langkah 5 : cari nilai minimum masing-masing window table (fingerprints)
        $this->arr_fingerprints1 = $this->fingerprints($this->arr_window1);
    }

    private function n_gram($word, $n)
    {
        $ngrams = array();
        $length = strlen($word);
        for ($i = 0; $i < $length; $i++) {
            if ($i > ($n - 2)) {
                $ng = '';
                for ($j = $n - 1; $j >= 0; $j--) {
                    $ng .= $word[$i - $j];
                }
                $ngrams[] = $ng;
            }
        }
        return $ngrams;
    }

    private function char2hash($string)
    {
        if (strlen($string) == 1) {
            return ord($string);
        } else {
            $result = 0;
            $length = strlen($string);
            for ($i = 0; $i < $length; $i++) {
                $result += ord(substr($string, $i, 1)) * pow($this->prime_number, $length - $i);
            }
            return $result;
        }
    }

    private function rolling_hash($ngram)
    {
        $roll_hash = array();
        foreach ($ngram as $ng) {
            $roll_hash[] = $this->char2hash($ng);
        }
        return $roll_hash;
    }

    private function windowing($rolling_hash, $n)
    {
        $ngram = array();
        $length = count($rolling_hash);
        $x = 0;
        for ($i = 0; $i < $length; $i++) {
            if ($i > ($n - 2)) {
                $ngram[$x] = array();
                $y = 0;
                for ($j = $n - 1; $j >= 0; $j--) {
                    $ngram[$x][$y] = $rolling_hash[$i - $j];
                    $y++;
                }
                $x++;
            }
        }
        //echo $x.' '.$y;
        return $ngram;
    }

    // private function fingerprints($window_table)
    // {
    //     $fingers = array();
    //     for ($i = 0; $i < count($window_table); $i++) {
    //         $min = $window_table[$i][0];
    //         for ($j = 1; $j < $this->n_window_value; $j++) {
    //             if ($min > $window_table[$i][$j])
    //                 $min = $window_table[$i][$j];
    //         }
    //         $fingers[] = $min;
    //     }
    //     return $fingers;
    // }

    private function fingerprints($window_table)
    {
        $fingers = array();
        for ($i = 0; $i < count($window_table); $i++) {
            if (count($window_table[$i]) == 0) {
                continue; // skip jika array kosong
            }

            $min = $window_table[$i][0];
            for ($j = 1; $j < count($window_table[$i]); $j++) {
                if ($min > $window_table[$i][$j])
                    $min = $window_table[$i][$j];
            }
            $fingers[] = $min;
        }
        return $fingers;
    }

    // Add the new function for highlighting matches
    public function highlightMatches($text2)
    {
        $words1 = explode(' ', $this->word1);
        $words2 = explode(' ', $text2);

        foreach ($words1 as &$word1) {
            if (in_array($word1, $words2)) {
                $word1 = '<strong>' . $word1 . '</strong>';
            }
        }

        return implode(' ', $words1);
    }
}
