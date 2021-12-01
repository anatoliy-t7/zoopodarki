<?php

namespace App\Traits;

trait Delivery
{
    private $kadSpb = [[59.908456, 29.659262], [59.885888, 29.679213], [59.869938, 29.74622], [59.863651, 29.793783], [59.826761, 29.824958], [59.812481, 29.891431], [59.822211, 29.967316], [59.816563, 30.012207], [59.815913, 30.076178], [59.801366, 30.148366], [59.802641, 30.173356], [59.812262, 30.198768], [59.825935, 30.233399], [59.833165, 30.281853], [59.812214, 30.319813], [59.811782, 30.343651], [59.816016, 30.380928], [59.825793, 30.435141], [59.845107, 30.458015], [59.853578, 30.487691], [59.865128, 30.527404], [59.889652, 30.524301], [59.91036, 30.526205], [59.927852, 30.533823], [59.949187, 30.543342], [59.966752, 30.552995], [59.979217, 30.525869], [59.984258, 30.496359], [59.999108, 30.47677], [60.012867, 30.471708], [60.025186, 30.450557], [60.041604, 30.437535], [60.052483, 30.404479], [60.066082, 30.38521], [60.090189, 30.371481], [60.095391, 30.323246], [60.099381, 30.278742], [60.092028, 30.24515], [60.084021, 30.214452], [60.07635, 30.182448], [60.058877, 30.144609], [60.045573, 30.044505], [60.039066, 29.980322], [60.028953, 29.865704], [60.020797, 29.741338], [60.001108, 29.702873], [59.953468, 29.677893], [59.919501, 29.663687], [59.921746, 29.665453], [59.977605, 29.687746], [60.009721, 29.713948], [60.021747, 29.789382], [60.032313, 29.903546], [60.038669, 29.98853], [60.04767, 30.060998], [60.059475, 30.148734], [60.075817, 30.182008], [60.083311, 30.206698], [60.088991, 30.238442], [60.09824, 30.267886], [60.096657, 30.307648], [60.093328, 30.360716], [60.082841, 30.378695], [60.058328, 30.390824], [60.045216, 30.430975], [60.03704, 30.439842], [60.018014, 30.459175], [60.007799, 30.475729], [59.993812, 30.479226], [59.982967, 30.500841], [59.974702, 30.539727], [59.961722, 30.55371], [59.94598, 30.540709], [59.924615, 30.529632], [59.901791, 30.52606], [59.882038, 30.527136], [59.864911, 30.526546], [59.853961, 30.489979], [59.846814, 30.459001], [59.8266, 30.436387], [59.815504, 30.374037], [59.811184, 30.34099], [59.812692, 30.319242], [59.832677, 30.283679], [59.825372, 30.230186], [59.811914, 30.196219], [59.802316, 30.172293], [59.801151, 30.149569], [59.816053, 30.076195], [59.817837, 30.004635], [59.820049, 29.952982], [59.813559, 29.858431], [59.833553, 29.817951], [59.865404, 29.789727], [59.870485, 29.74308], [59.886735, 29.677497], [59.910198, 29.659425]];

    public function getDeliveryCostsByBoxberry($totalWeight, $zip)
    {
        $boxberryToken = config('constants.boxberry_token');

        if ($this->checkZip($zip, $boxberryToken)) {
            $url = 'http://api.boxberry.ru/json.php?token='
            .$boxberryToken
            .'&method=DeliveryCosts&weight='
            .$totalWeight
            .'&target=010&targetstart=010&zip='
            .$zip;

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
        $url = 'https://api.boxberry.ru/json.php?token='.$boxberryToken.'&method=ZipCheck&Zip='.$zip;

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

    public function getDeliveryCostsByStore($amount, $latTo, $lngTo)
    {

        $point = [[$latTo, $lngTo]];

        if ($this->pointInPolygon($point, $this->kadSpb) === false) {
            return false;
        }

        $distans = $this->getDistance($latTo, $lngTo);

        // Радиус 1.5 до 3 км
        if ($distans >= 1500 && $distans < 3000) {
            if ($amount >= 1000) {
                 // бесплатная доставка от 1000 руб.,
                return 0;
            } else {
                // если меньше 1000, то доставка стоит 300 руб.
                return 300;
            }
        }

        // Радиус 3-5 км
        if ($distans >= 3000 && $distans < 5000) {
            if ($amount >= 1500) {
                 // бесплатная доставка от 1500,
                return 0;
            } else {
                // если меньше 1500, то доставка стоит 200 руб.
                return 200;
            }
        }

        // Радиус 5-10 км
        if ($distans >= 5000 && $distans < 10000) {
            if ($amount >= 1500) {
                 // бесплатная доставка от 1500,
                return 0;
            } else {
                // если меньше 1500, то доставка стоит 300 руб.
                return 300;
            }
        }

        // Радиус 10-15 км
        if ($distans >= 10000 && $distans < 15000) {
            if ($amount >= 2000) {
                 // бесплатная доставка от 2000,
                return 0;
            } else {
                // если меньше 2000, то доставка стоит 500 руб.
                return 500;
            }
        }

        // Радиус от 15 км
        if ($distans >= 15000) {
            if ($amount >= 2000) {
                 // бесплатная доставка от 3500,
                return 0;
            } else {
                // если меньше 3500, то доставка стоит 500 руб.
                return 500;
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
        return $c == 2; // here is modified code. before was: $c%2!=0; I do not know why, but now works fine!
    }
}
