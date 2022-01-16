<?php

namespace App\Traits;

trait Delivery
{
    public function getDeliveryCostsByBoxberry($totalWeight, $zip)
    {
        $boxberryToken = config('constants.boxberry_token');

        if ($this->checkZip($zip, $boxberryToken)) {
            $url = 'http://api.boxberry.ru/json.php?token='
            . $boxberryToken
            . '&method=DeliveryCosts&weight='
            . $totalWeight
            . '&target=010&targetstart=010&zip='
            . $zip;

            $handle = fopen($url, 'rb');
            $contents = stream_get_contents($handle);
            fclose($handle);
            $data = json_decode($contents, true);

            if (count($data) <= 0 || array_key_exists('err', $data)) {
                logger($data['err']);
            } else {
                logger($data);
            }
        }
    }

    public function checkZip($zip, $boxberryToken)
    {

        // TODO неработает, возможно заблокировали
        $url = 'https://api.boxberry.ru/json.php?token=' . $boxberryToken . '&method=ZipCheck&Zip=' . $zip;

        $handle = fopen($url, 'rb');
        $contents = stream_get_contents($handle);
        fclose($handle);
        $data = json_decode($contents, true);

        if (count($data) <= 0 || array_key_exists('err', $data)) {
            logger($data['err']);

            return false;
        } else {
            logger($data);

            return true;
        }
    }

    public function checkIfAddressInCad($latTo, $lngTo)
    {
        $point = [[$latTo, $lngTo]];

        if ($this->pointInPolygon($point, $this->kadSpb) === false) {
            return false;
        } else {
            return true;
        }
    }

    public function getDeliveryCostsByStore($amount, $zone)
    {
        if ($zone === 0) {
            return 0;
        }

        //Zone 1 Радиус 1.5 до 3 км
        if ($zone === 1) {
            if ($amount >= 1000) {
                // бесплатная доставка от 1000 руб.,
                return 0.1;
            } else {
                // если меньше 1000, то доставка стоит 300 руб.
                return 300;
            }
        }

        //Zone 2 Радиус 1.5-10 км
        if ($zone === 2) {
            if ($amount >= 1500) {
                // бесплатная доставка от 1500,
                return 0.1;
            } else {
                // если меньше 1500, то доставка стоит 300 руб.
                return 300;
            }
        }

        //Zone 3 Радиус 10-15 км
        if ($zone === 3) {
            if ($amount >= 2000) {
                // бесплатная доставка от 2000,
                return 0.1;
            } else {
                // если меньше 2000, то доставка стоит 500 руб.
                return 500;
            }
        }

        //Zone 4 Радиус от 15 км
        if ($zone === 4) {
            if ($amount >= 3500) {
                // бесплатная доставка от 3500,
                return 0.1;
            } else {
                // если меньше 3500, то доставка стоит 700 руб.
                return 700;
            }
        }
    }

    public function getDistance($latTo, $lngTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad(config('constants.lat_departure'));
        $lngFrom = deg2rad(config('constants.lng_departure'));
        $latTo = deg2rad($latTo);
        $lngTo = deg2rad($lngTo);

        $latDelta = $latTo - $latFrom;
        $lngDelta = $lngTo - $lngFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
        cos($latFrom) * cos($latTo) * pow(sin($lngDelta / 2), 2)));

        return intval($angle * $earthRadius);
    }

    public function pointInPolygon($p, $polygon)
    {
        //if you operates with (hundred)thousands of points
        $p = $p[0];
        $c = 0;
        $p1 = $polygon[0];
        $n = count($polygon);
        $found = false;
        for ($i = 1; $i <= $n; $i++) {
            $p2 = $polygon[$i % $n];

            if ($p[1] > min($p1[1], $p2[1])
            && ($p[1] <= max($p1[1], $p2[1]))
            && ($p[0] <= max($p1[0], $p2[0]))
            && ($p1[1] != $p2[1])) {
                $xinters = ($p[1] - $p1[1]) * ($p2[0] - $p1[0]) / ($p2[1] - $p1[1]) + $p1[0];

                if ($p1[0] == $p2[0] || $p[0] <= $xinters) {
                    $c++;
                }
            }

            $p1 = $p2;
        }
        // if the number of edges we passed through is even, then it's not in the poly.
        return $c % 2 != 0; // here is modified code. before was: $c%2!=0; I do not know why, but now works fine! //$c == 2
    }
}
