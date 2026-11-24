<?php

class IndukData
{
    private $batasAman = 5;

    protected function hitungTotal($hrg, $jml)
    {
        return $hrg * $jml;
    }

    public function getBatasAman()
    {
        return $this->batasAman;
    }

    public static function statusStok($jml)
    {
        if ($jml <= 5) {
            return "Menipis";
        } else {
            return "Aman";
        }
    }
}