<?php

namespace App\Jobs;

use App\Models\AttributeItem;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $timeout = 300;

    public $count = 0;
    public $tries = 1;

    public $collect = [];

    public $data = [
        0 => [
            'attribute_id' => 521,
            'id' => 64617,
            'product_id' => 5371,
        ],
        1 => [
            'attribute_id' => 1273,
            'id' => 64618,
            'product_id' => 5371,
        ],
        2 => [
            'attribute_id' => 85,
            'id' => 64619,
            'product_id' => 5372,
        ],
        3 => [
            'attribute_id' => 304,
            'id' => 64620,
            'product_id' => 5372,
        ],
        4 => [
            'attribute_id' => 85,
            'id' => 64621,
            'product_id' => 5373,
        ],
        5 => [
            'attribute_id' => 304,
            'id' => 64622,
            'product_id' => 5373,
        ],
        6 => [
            'attribute_id' => 85,
            'id' => 64623,
            'product_id' => 5375,
        ],
        7 => [
            'attribute_id' => 304,
            'id' => 64624,
            'product_id' => 5375,
        ],
        8 => [
            'attribute_id' => 1532,
            'id' => 64625,
            'product_id' => 5375,
        ],
        9 => [
            'attribute_id' => 304,
            'id' => 64626,
            'product_id' => 5381,
        ],
        10 => [
            'attribute_id' => 274,
            'id' => 64627,
            'product_id' => 5382,
        ],
        11 => [
            'attribute_id' => 201,
            'id' => 64628,
            'product_id' => 5383,
        ],
        12 => [
            'attribute_id' => 319,
            'id' => 64629,
            'product_id' => 5383,
        ],
        13 => [
            'attribute_id' => 521,
            'id' => 64630,
            'product_id' => 5384,
        ],
        14 => [
            'attribute_id' => 1533,
            'id' => 64631,
            'product_id' => 5384,
        ],
        15 => [
            'attribute_id' => 521,
            'id' => 64632,
            'product_id' => 5385,
        ],
        16 => [
            'attribute_id' => 1533,
            'id' => 64633,
            'product_id' => 5385,
        ],
        17 => [
            'attribute_id' => 521,
            'id' => 64634,
            'product_id' => 5386,
        ],
        18 => [
            'attribute_id' => 1533,
            'id' => 64635,
            'product_id' => 5386,
        ],
        19 => [
            'attribute_id' => 274,
            'id' => 64636,
            'product_id' => 5387,
        ],
        20 => [
            'attribute_id' => 201,
            'id' => 64637,
            'product_id' => 5389,
        ],
        21 => [
            'attribute_id' => 274,
            'id' => 64638,
            'product_id' => 5391,
        ],
        22 => [
            'attribute_id' => 85,
            'id' => 64639,
            'product_id' => 5392,
        ],
        23 => [
            'attribute_id' => 304,
            'id' => 64640,
            'product_id' => 5392,
        ],
        24 => [
            'attribute_id' => 304,
            'id' => 64641,
            'product_id' => 5393,
        ],
        25 => [
            'attribute_id' => 201,
            'id' => 64642,
            'product_id' => 5394,
        ],
        26 => [
            'attribute_id' => 201,
            'id' => 64643,
            'product_id' => 5395,
        ],
        27 => [
            'attribute_id' => 282,
            'id' => 64644,
            'product_id' => 5395,
        ],
        28 => [
            'attribute_id' => 201,
            'id' => 64645,
            'product_id' => 5396,
        ],
        29 => [
            'attribute_id' => 283,
            'id' => 64646,
            'product_id' => 5396,
        ],
        30 => [
            'attribute_id' => 304,
            'id' => 64647,
            'product_id' => 5397,
        ],
        31 => [
            'attribute_id' => 274,
            'id' => 64648,
            'product_id' => 5400,
        ],
        32 => [
            'attribute_id' => 305,
            'id' => 64649,
            'product_id' => 5400,
        ],
        33 => [
            'attribute_id' => 351,
            'id' => 64651,
            'product_id' => 5405,
        ],
        34 => [
            'attribute_id' => 1152,
            'id' => 64652,
            'product_id' => 5405,
        ],
        35 => [
            'attribute_id' => 351,
            'id' => 64653,
            'product_id' => 5406,
        ],
        36 => [
            'attribute_id' => 1152,
            'id' => 64654,
            'product_id' => 5406,
        ],
        37 => [
            'attribute_id' => 90,
            'id' => 64657,
            'product_id' => 5407,
        ],
        38 => [
            'attribute_id' => 643,
            'id' => 64658,
            'product_id' => 5407,
        ],
        39 => [
            'attribute_id' => 646,
            'id' => 64659,
            'product_id' => 5407,
        ],
        40 => [
            'attribute_id' => 656,
            'id' => 64660,
            'product_id' => 5407,
        ],
        41 => [
            'attribute_id' => 2,
            'id' => 64662,
            'product_id' => 5408,
        ],
        42 => [
            'attribute_id' => 60,
            'id' => 64663,
            'product_id' => 5408,
        ],
        43 => [
            'attribute_id' => 643,
            'id' => 64664,
            'product_id' => 5408,
        ],
        44 => [
            'attribute_id' => 646,
            'id' => 64665,
            'product_id' => 5408,
        ],
        45 => [
            'attribute_id' => 656,
            'id' => 64666,
            'product_id' => 5408,
        ],
        46 => [
            'attribute_id' => 2,
            'id' => 64668,
            'product_id' => 5409,
        ],
        47 => [
            'attribute_id' => 643,
            'id' => 64670,
            'product_id' => 5409,
        ],
        48 => [
            'attribute_id' => 644,
            'id' => 64671,
            'product_id' => 5409,
        ],
        49 => [
            'attribute_id' => 645,
            'id' => 64672,
            'product_id' => 5409,
        ],
        50 => [
            'attribute_id' => 656,
            'id' => 64673,
            'product_id' => 5409,
        ],
        51 => [
            'attribute_id' => 751,
            'id' => 64674,
            'product_id' => 5409,
        ],
        52 => [
            'attribute_id' => 643,
            'id' => 64678,
            'product_id' => 5410,
        ],
        53 => [
            'attribute_id' => 645,
            'id' => 64679,
            'product_id' => 5410,
        ],
        54 => [
            'attribute_id' => 656,
            'id' => 64680,
            'product_id' => 5410,
        ],
        55 => [
            'attribute_id' => 751,
            'id' => 64681,
            'product_id' => 5410,
        ],
        56 => [
            'attribute_id' => 2,
            'id' => 64683,
            'product_id' => 5411,
        ],
        57 => [
            'attribute_id' => 643,
            'id' => 64685,
            'product_id' => 5411,
        ],
        58 => [
            'attribute_id' => 656,
            'id' => 64686,
            'product_id' => 5411,
        ],
        59 => [
            'attribute_id' => 691,
            'id' => 64687,
            'product_id' => 5411,
        ],
        60 => [
            'attribute_id' => 697,
            'id' => 64688,
            'product_id' => 5411,
        ],
        61 => [
            'attribute_id' => 90,
            'id' => 64692,
            'product_id' => 5412,
        ],
        62 => [
            'attribute_id' => 643,
            'id' => 64693,
            'product_id' => 5412,
        ],
        63 => [
            'attribute_id' => 645,
            'id' => 64694,
            'product_id' => 5412,
        ],
        64 => [
            'attribute_id' => 643,
            'id' => 64698,
            'product_id' => 5413,
        ],
        65 => [
            'attribute_id' => 646,
            'id' => 64699,
            'product_id' => 5413,
        ],
        66 => [
            'attribute_id' => 691,
            'id' => 64700,
            'product_id' => 5413,
        ],
        67 => [
            'attribute_id' => 8,
            'id' => 64704,
            'product_id' => 5414,
        ],
        68 => [
            'attribute_id' => 643,
            'id' => 64705,
            'product_id' => 5414,
        ],
        69 => [
            'attribute_id' => 645,
            'id' => 64706,
            'product_id' => 5414,
        ],
        70 => [
            'attribute_id' => 691,
            'id' => 64707,
            'product_id' => 5414,
        ],
        71 => [
            'attribute_id' => 742,
            'id' => 64708,
            'product_id' => 5414,
        ],
        72 => [
            'attribute_id' => 2,
            'id' => 64710,
            'product_id' => 5415,
        ],
        73 => [
            'attribute_id' => 60,
            'id' => 64711,
            'product_id' => 5415,
        ],
        74 => [
            'attribute_id' => 643,
            'id' => 64712,
            'product_id' => 5415,
        ],
        75 => [
            'attribute_id' => 645,
            'id' => 64713,
            'product_id' => 5415,
        ],
        76 => [
            'attribute_id' => 2,
            'id' => 64715,
            'product_id' => 5416,
        ],
        77 => [
            'attribute_id' => 60,
            'id' => 64716,
            'product_id' => 5416,
        ],
        78 => [
            'attribute_id' => 643,
            'id' => 64717,
            'product_id' => 5416,
        ],
        79 => [
            'attribute_id' => 646,
            'id' => 64718,
            'product_id' => 5416,
        ],
        80 => [
            'attribute_id' => 2,
            'id' => 64720,
            'product_id' => 5417,
        ],
        81 => [
            'attribute_id' => 60,
            'id' => 64721,
            'product_id' => 5417,
        ],
        82 => [
            'attribute_id' => 643,
            'id' => 64722,
            'product_id' => 5417,
        ],
        83 => [
            'attribute_id' => 646,
            'id' => 64723,
            'product_id' => 5417,
        ],
        84 => [
            'attribute_id' => 3,
            'id' => 64725,
            'product_id' => 5418,
        ],
        85 => [
            'attribute_id' => 12,
            'id' => 64728,
            'product_id' => 5418,
        ],
        86 => [
            'attribute_id' => 643,
            'id' => 64729,
            'product_id' => 5418,
        ],
        87 => [
            'attribute_id' => 645,
            'id' => 64730,
            'product_id' => 5418,
        ],
        88 => [
            'attribute_id' => 720,
            'id' => 64731,
            'product_id' => 5418,
        ],
        89 => [
            'attribute_id' => 1535,
            'id' => 64732,
            'product_id' => 5418,
        ],
        90 => [
            'attribute_id' => 643,
            'id' => 64736,
            'product_id' => 5419,
        ],
        91 => [
            'attribute_id' => 646,
            'id' => 64737,
            'product_id' => 5419,
        ],
        92 => [
            'attribute_id' => 910,
            'id' => 64738,
            'product_id' => 5419,
        ],
        93 => [
            'attribute_id' => 635,
            'id' => 64742,
            'product_id' => 5420,
        ],
        94 => [
            'attribute_id' => 643,
            'id' => 64743,
            'product_id' => 5420,
        ],
        95 => [
            'attribute_id' => 645,
            'id' => 64744,
            'product_id' => 5420,
        ],
        96 => [
            'attribute_id' => 751,
            'id' => 64745,
            'product_id' => 5420,
        ],
        97 => [
            'attribute_id' => 635,
            'id' => 64749,
            'product_id' => 5421,
        ],
        98 => [
            'attribute_id' => 643,
            'id' => 64750,
            'product_id' => 5421,
        ],
        99 => [
            'attribute_id' => 646,
            'id' => 64751,
            'product_id' => 5421,
        ],
        100 => [
            'attribute_id' => 68,
            'id' => 64753,
            'product_id' => 5423,
        ],
        101 => [
            'attribute_id' => 135,
            'id' => 64754,
            'product_id' => 5423,
        ],
        102 => [
            'attribute_id' => 68,
            'id' => 64755,
            'product_id' => 5424,
        ],
        103 => [
            'attribute_id' => 135,
            'id' => 64756,
            'product_id' => 5424,
        ],
        104 => [
            'attribute_id' => 135,
            'id' => 64757,
            'product_id' => 5427,
        ],
        105 => [
            'attribute_id' => 163,
            'id' => 64758,
            'product_id' => 5427,
        ],
        106 => [
            'attribute_id' => 36,
            'id' => 64759,
            'product_id' => 5431,
        ],
        107 => [
            'attribute_id' => 113,
            'id' => 64760,
            'product_id' => 5431,
        ],
        108 => [
            'attribute_id' => 135,
            'id' => 64761,
            'product_id' => 5431,
        ],
        109 => [
            'attribute_id' => 70,
            'id' => 64762,
            'product_id' => 5433,
        ],
        110 => [
            'attribute_id' => 71,
            'id' => 64763,
            'product_id' => 5433,
        ],
        111 => [
            'attribute_id' => 148,
            'id' => 64764,
            'product_id' => 5434,
        ],
        112 => [
            'attribute_id' => 11,
            'id' => 64765,
            'product_id' => 5435,
        ],
        113 => [
            'attribute_id' => 48,
            'id' => 64766,
            'product_id' => 5435,
        ],
        114 => [
            'attribute_id' => 1082,
            'id' => 64767,
            'product_id' => 5436,
        ],
        115 => [
            'attribute_id' => 1082,
            'id' => 64768,
            'product_id' => 5437,
        ],
        116 => [
            'attribute_id' => 1082,
            'id' => 64769,
            'product_id' => 5438,
        ],
        117 => [
            'attribute_id' => 952,
            'id' => 64770,
            'product_id' => 5439,
        ],
        118 => [
            'attribute_id' => 952,
            'id' => 64771,
            'product_id' => 5440,
        ],
        119 => [
            'attribute_id' => 18,
            'id' => 64772,
            'product_id' => 5441,
        ],
        120 => [
            'attribute_id' => 25,
            'id' => 64773,
            'product_id' => 5441,
        ],
        121 => [
            'attribute_id' => 199,
            'id' => 64774,
            'product_id' => 5441,
        ],
        122 => [
            'attribute_id' => 18,
            'id' => 64775,
            'product_id' => 5442,
        ],
        123 => [
            'attribute_id' => 40,
            'id' => 64776,
            'product_id' => 5442,
        ],
        124 => [
            'attribute_id' => 18,
            'id' => 64777,
            'product_id' => 5443,
        ],
        125 => [
            'attribute_id' => 118,
            'id' => 64778,
            'product_id' => 5443,
        ],
        126 => [
            'attribute_id' => 47,
            'id' => 64779,
            'product_id' => 5444,
        ],
        127 => [
            'attribute_id' => 204,
            'id' => 64780,
            'product_id' => 5444,
        ],
        128 => [
            'attribute_id' => 47,
            'id' => 64781,
            'product_id' => 5445,
        ],
        129 => [
            'attribute_id' => 204,
            'id' => 64782,
            'product_id' => 5445,
        ],
        130 => [
            'attribute_id' => 47,
            'id' => 64783,
            'product_id' => 5447,
        ],
        131 => [
            'attribute_id' => 204,
            'id' => 64784,
            'product_id' => 5447,
        ],
        132 => [
            'attribute_id' => 47,
            'id' => 64785,
            'product_id' => 5448,
        ],
        133 => [
            'attribute_id' => 204,
            'id' => 64786,
            'product_id' => 5448,
        ],
        134 => [
            'attribute_id' => 18,
            'id' => 64787,
            'product_id' => 5450,
        ],
        135 => [
            'attribute_id' => 231,
            'id' => 64788,
            'product_id' => 5450,
        ],
        136 => [
            'attribute_id' => 18,
            'id' => 64789,
            'product_id' => 5452,
        ],
        137 => [
            'attribute_id' => 114,
            'id' => 64790,
            'product_id' => 5452,
        ],
        138 => [
            'attribute_id' => 18,
            'id' => 64791,
            'product_id' => 5453,
        ],
        139 => [
            'attribute_id' => 114,
            'id' => 64792,
            'product_id' => 5453,
        ],
        140 => [
            'attribute_id' => 1536,
            'id' => 64793,
            'product_id' => 5453,
        ],
        141 => [
            'attribute_id' => 18,
            'id' => 64794,
            'product_id' => 5454,
        ],
        142 => [
            'attribute_id' => 114,
            'id' => 64795,
            'product_id' => 5454,
        ],
        143 => [
            'attribute_id' => 1536,
            'id' => 64796,
            'product_id' => 5454,
        ],
        144 => [
            'attribute_id' => 18,
            'id' => 64797,
            'product_id' => 5455,
        ],
        145 => [
            'attribute_id' => 114,
            'id' => 64798,
            'product_id' => 5455,
        ],
        146 => [
            'attribute_id' => 1536,
            'id' => 64799,
            'product_id' => 5455,
        ],
        147 => [
            'attribute_id' => 18,
            'id' => 64800,
            'product_id' => 5456,
        ],
        148 => [
            'attribute_id' => 114,
            'id' => 64801,
            'product_id' => 5456,
        ],
        149 => [
            'attribute_id' => 1536,
            'id' => 64802,
            'product_id' => 5456,
        ],
        150 => [
            'attribute_id' => 18,
            'id' => 64803,
            'product_id' => 5457,
        ],
        151 => [
            'attribute_id' => 114,
            'id' => 64804,
            'product_id' => 5457,
        ],
        152 => [
            'attribute_id' => 1537,
            'id' => 64805,
            'product_id' => 5457,
        ],
        153 => [
            'attribute_id' => 1538,
            'id' => 64806,
            'product_id' => 5457,
        ],
        154 => [
            'attribute_id' => 18,
            'id' => 64807,
            'product_id' => 5458,
        ],
        155 => [
            'attribute_id' => 114,
            'id' => 64808,
            'product_id' => 5458,
        ],
        156 => [
            'attribute_id' => 206,
            'id' => 64809,
            'product_id' => 5458,
        ],
        157 => [
            'attribute_id' => 228,
            'id' => 64810,
            'product_id' => 5458,
        ],
        158 => [
            'attribute_id' => 18,
            'id' => 64811,
            'product_id' => 5460,
        ],
        159 => [
            'attribute_id' => 114,
            'id' => 64812,
            'product_id' => 5460,
        ],
        160 => [
            'attribute_id' => 206,
            'id' => 64813,
            'product_id' => 5460,
        ],
        161 => [
            'attribute_id' => 231,
            'id' => 64814,
            'product_id' => 5460,
        ],
        162 => [
            'attribute_id' => 300,
            'id' => 64815,
            'product_id' => 5460,
        ],
        163 => [
            'attribute_id' => 18,
            'id' => 64816,
            'product_id' => 5461,
        ],
        164 => [
            'attribute_id' => 114,
            'id' => 64817,
            'product_id' => 5461,
        ],
        165 => [
            'attribute_id' => 1539,
            'id' => 64818,
            'product_id' => 5461,
        ],
        166 => [
            'attribute_id' => 18,
            'id' => 64819,
            'product_id' => 5462,
        ],
        167 => [
            'attribute_id' => 114,
            'id' => 64820,
            'product_id' => 5462,
        ],
        168 => [
            'attribute_id' => 1539,
            'id' => 64821,
            'product_id' => 5462,
        ],
        169 => [
            'attribute_id' => 18,
            'id' => 64822,
            'product_id' => 5463,
        ],
        170 => [
            'attribute_id' => 114,
            'id' => 64823,
            'product_id' => 5463,
        ],
        171 => [
            'attribute_id' => 1539,
            'id' => 64824,
            'product_id' => 5463,
        ],
        172 => [
            'attribute_id' => 18,
            'id' => 64825,
            'product_id' => 5465,
        ],
        173 => [
            'attribute_id' => 114,
            'id' => 64826,
            'product_id' => 5465,
        ],
        174 => [
            'attribute_id' => 1540,
            'id' => 64827,
            'product_id' => 5465,
        ],
        175 => [
            'attribute_id' => 1541,
            'id' => 64828,
            'product_id' => 5465,
        ],
        176 => [
            'attribute_id' => 18,
            'id' => 64829,
            'product_id' => 5466,
        ],
        177 => [
            'attribute_id' => 114,
            'id' => 64830,
            'product_id' => 5466,
        ],
        178 => [
            'attribute_id' => 1542,
            'id' => 64831,
            'product_id' => 5466,
        ],
        179 => [
            'attribute_id' => 18,
            'id' => 64832,
            'product_id' => 5468,
        ],
        180 => [
            'attribute_id' => 114,
            'id' => 64833,
            'product_id' => 5468,
        ],
        181 => [
            'attribute_id' => 18,
            'id' => 64834,
            'product_id' => 5469,
        ],
        182 => [
            'attribute_id' => 114,
            'id' => 64835,
            'product_id' => 5469,
        ],
        183 => [
            'attribute_id' => 441,
            'id' => 64836,
            'product_id' => 5469,
        ],
        184 => [
            'attribute_id' => 18,
            'id' => 64837,
            'product_id' => 5471,
        ],
        185 => [
            'attribute_id' => 114,
            'id' => 64838,
            'product_id' => 5471,
        ],
        186 => [
            'attribute_id' => 423,
            'id' => 64839,
            'product_id' => 5471,
        ],
        187 => [
            'attribute_id' => 18,
            'id' => 64840,
            'product_id' => 5478,
        ],
        188 => [
            'attribute_id' => 114,
            'id' => 64841,
            'product_id' => 5478,
        ],
        189 => [
            'attribute_id' => 442,
            'id' => 64842,
            'product_id' => 5478,
        ],
        190 => [
            'attribute_id' => 1543,
            'id' => 64843,
            'product_id' => 5478,
        ],
        191 => [
            'attribute_id' => 18,
            'id' => 64844,
            'product_id' => 5482,
        ],
        192 => [
            'attribute_id' => 205,
            'id' => 64845,
            'product_id' => 5482,
        ],
        193 => [
            'attribute_id' => 451,
            'id' => 64846,
            'product_id' => 5482,
        ],
        194 => [
            'attribute_id' => 18,
            'id' => 64847,
            'product_id' => 5483,
        ],
        195 => [
            'attribute_id' => 40,
            'id' => 64848,
            'product_id' => 5483,
        ],
        196 => [
            'attribute_id' => 114,
            'id' => 64849,
            'product_id' => 5483,
        ],
        197 => [
            'attribute_id' => 434,
            'id' => 64850,
            'product_id' => 5483,
        ],
        198 => [
            'attribute_id' => 18,
            'id' => 64851,
            'product_id' => 5486,
        ],
        199 => [
            'attribute_id' => 114,
            'id' => 64852,
            'product_id' => 5486,
        ],
        200 => [
            'attribute_id' => 217,
            'id' => 64853,
            'product_id' => 5486,
        ],
        201 => [
            'attribute_id' => 218,
            'id' => 64854,
            'product_id' => 5486,
        ],
        202 => [
            'attribute_id' => 18,
            'id' => 64855,
            'product_id' => 5487,
        ],
        203 => [
            'attribute_id' => 114,
            'id' => 64856,
            'product_id' => 5487,
        ],
        204 => [
            'attribute_id' => 207,
            'id' => 64857,
            'product_id' => 5487,
        ],
        205 => [
            'attribute_id' => 1543,
            'id' => 64858,
            'product_id' => 5487,
        ],
        206 => [
            'attribute_id' => 18,
            'id' => 64859,
            'product_id' => 5488,
        ],
        207 => [
            'attribute_id' => 169,
            'id' => 64860,
            'product_id' => 5488,
        ],
        208 => [
            'attribute_id' => 451,
            'id' => 64861,
            'product_id' => 5488,
        ],
        209 => [
            'attribute_id' => 18,
            'id' => 64862,
            'product_id' => 5489,
        ],
        210 => [
            'attribute_id' => 451,
            'id' => 64863,
            'product_id' => 5489,
        ],
        211 => [
            'attribute_id' => 18,
            'id' => 64864,
            'product_id' => 5490,
        ],
        212 => [
            'attribute_id' => 114,
            'id' => 64865,
            'product_id' => 5490,
        ],
        213 => [
            'attribute_id' => 115,
            'id' => 64866,
            'product_id' => 5490,
        ],
        214 => [
            'attribute_id' => 1536,
            'id' => 64867,
            'product_id' => 5490,
        ],
        215 => [
            'attribute_id' => 18,
            'id' => 64868,
            'product_id' => 5491,
        ],
        216 => [
            'attribute_id' => 451,
            'id' => 64869,
            'product_id' => 5491,
        ],
        217 => [
            'attribute_id' => 18,
            'id' => 64870,
            'product_id' => 5495,
        ],
        218 => [
            'attribute_id' => 450,
            'id' => 64871,
            'product_id' => 5495,
        ],
        219 => [
            'attribute_id' => 18,
            'id' => 64872,
            'product_id' => 5496,
        ],
        220 => [
            'attribute_id' => 376,
            'id' => 64873,
            'product_id' => 5496,
        ],
        221 => [
            'attribute_id' => 18,
            'id' => 64874,
            'product_id' => 5498,
        ],
        222 => [
            'attribute_id' => 114,
            'id' => 64875,
            'product_id' => 5498,
        ],
        223 => [
            'attribute_id' => 1537,
            'id' => 64876,
            'product_id' => 5498,
        ],
        224 => [
            'attribute_id' => 18,
            'id' => 64877,
            'product_id' => 5499,
        ],
        225 => [
            'attribute_id' => 114,
            'id' => 64878,
            'product_id' => 5499,
        ],
        226 => [
            'attribute_id' => 423,
            'id' => 64879,
            'product_id' => 5499,
        ],
        227 => [
            'attribute_id' => 18,
            'id' => 64880,
            'product_id' => 5500,
        ],
        228 => [
            'attribute_id' => 439,
            'id' => 64881,
            'product_id' => 5500,
        ],
        229 => [
            'attribute_id' => 18,
            'id' => 64882,
            'product_id' => 5501,
        ],
        230 => [
            'attribute_id' => 114,
            'id' => 64883,
            'product_id' => 5501,
        ],
        231 => [
            'attribute_id' => 1536,
            'id' => 64884,
            'product_id' => 5501,
        ],
        232 => [
            'attribute_id' => 18,
            'id' => 64885,
            'product_id' => 5502,
        ],
        233 => [
            'attribute_id' => 114,
            'id' => 64886,
            'product_id' => 5502,
        ],
        234 => [
            'attribute_id' => 1536,
            'id' => 64887,
            'product_id' => 5502,
        ],
        235 => [
            'attribute_id' => 18,
            'id' => 64888,
            'product_id' => 5503,
        ],
        236 => [
            'attribute_id' => 42,
            'id' => 64889,
            'product_id' => 5503,
        ],
        237 => [
            'attribute_id' => 114,
            'id' => 64890,
            'product_id' => 5503,
        ],
        238 => [
            'attribute_id' => 1536,
            'id' => 64891,
            'product_id' => 5503,
        ],
        239 => [
            'attribute_id' => 1544,
            'id' => 64892,
            'product_id' => 5503,
        ],
        240 => [
            'attribute_id' => 18,
            'id' => 64893,
            'product_id' => 5504,
        ],
        241 => [
            'attribute_id' => 114,
            'id' => 64894,
            'product_id' => 5504,
        ],
        242 => [
            'attribute_id' => 423,
            'id' => 64895,
            'product_id' => 5504,
        ],
        243 => [
            'attribute_id' => 1536,
            'id' => 64896,
            'product_id' => 5504,
        ],
        244 => [
            'attribute_id' => 18,
            'id' => 64897,
            'product_id' => 5505,
        ],
        245 => [
            'attribute_id' => 114,
            'id' => 64898,
            'product_id' => 5505,
        ],
        246 => [
            'attribute_id' => 1536,
            'id' => 64899,
            'product_id' => 5505,
        ],
        247 => [
            'attribute_id' => 18,
            'id' => 64900,
            'product_id' => 5506,
        ],
        248 => [
            'attribute_id' => 114,
            'id' => 64901,
            'product_id' => 5506,
        ],
        249 => [
            'attribute_id' => 1536,
            'id' => 64902,
            'product_id' => 5506,
        ],
        250 => [
            'attribute_id' => 18,
            'id' => 64903,
            'product_id' => 5507,
        ],
        251 => [
            'attribute_id' => 42,
            'id' => 64904,
            'product_id' => 5507,
        ],
        252 => [
            'attribute_id' => 114,
            'id' => 64905,
            'product_id' => 5507,
        ],
        253 => [
            'attribute_id' => 1536,
            'id' => 64906,
            'product_id' => 5507,
        ],
        254 => [
            'attribute_id' => 1544,
            'id' => 64907,
            'product_id' => 5507,
        ],
        255 => [
            'attribute_id' => 18,
            'id' => 64908,
            'product_id' => 5508,
        ],
        256 => [
            'attribute_id' => 114,
            'id' => 64909,
            'product_id' => 5508,
        ],
        257 => [
            'attribute_id' => 1536,
            'id' => 64910,
            'product_id' => 5508,
        ],
        258 => [
            'attribute_id' => 18,
            'id' => 64911,
            'product_id' => 5509,
        ],
        259 => [
            'attribute_id' => 114,
            'id' => 64912,
            'product_id' => 5509,
        ],
        260 => [
            'attribute_id' => 1536,
            'id' => 64913,
            'product_id' => 5509,
        ],
        261 => [
            'attribute_id' => 18,
            'id' => 64914,
            'product_id' => 5510,
        ],
        262 => [
            'attribute_id' => 114,
            'id' => 64915,
            'product_id' => 5510,
        ],
        263 => [
            'attribute_id' => 1536,
            'id' => 64916,
            'product_id' => 5510,
        ],
        264 => [
            'attribute_id' => 18,
            'id' => 64917,
            'product_id' => 5512,
        ],
        265 => [
            'attribute_id' => 114,
            'id' => 64918,
            'product_id' => 5512,
        ],
        266 => [
            'attribute_id' => 1536,
            'id' => 64919,
            'product_id' => 5512,
        ],
        267 => [
            'attribute_id' => 18,
            'id' => 64920,
            'product_id' => 5513,
        ],
        268 => [
            'attribute_id' => 114,
            'id' => 64921,
            'product_id' => 5513,
        ],
        269 => [
            'attribute_id' => 1536,
            'id' => 64922,
            'product_id' => 5513,
        ],
        270 => [
            'attribute_id' => 18,
            'id' => 64923,
            'product_id' => 5514,
        ],
        271 => [
            'attribute_id' => 40,
            'id' => 64924,
            'product_id' => 5514,
        ],
        272 => [
            'attribute_id' => 114,
            'id' => 64925,
            'product_id' => 5514,
        ],
        273 => [
            'attribute_id' => 423,
            'id' => 64926,
            'product_id' => 5514,
        ],
        274 => [
            'attribute_id' => 1538,
            'id' => 64927,
            'product_id' => 5514,
        ],
        275 => [
            'attribute_id' => 18,
            'id' => 64928,
            'product_id' => 5515,
        ],
        276 => [
            'attribute_id' => 40,
            'id' => 64929,
            'product_id' => 5515,
        ],
        277 => [
            'attribute_id' => 114,
            'id' => 64930,
            'product_id' => 5515,
        ],
        278 => [
            'attribute_id' => 18,
            'id' => 64931,
            'product_id' => 5516,
        ],
        279 => [
            'attribute_id' => 40,
            'id' => 64932,
            'product_id' => 5516,
        ],
        280 => [
            'attribute_id' => 114,
            'id' => 64933,
            'product_id' => 5516,
        ],
        281 => [
            'attribute_id' => 1335,
            'id' => 64934,
            'product_id' => 5516,
        ],
        282 => [
            'attribute_id' => 18,
            'id' => 64935,
            'product_id' => 5517,
        ],
        283 => [
            'attribute_id' => 114,
            'id' => 64936,
            'product_id' => 5517,
        ],
        284 => [
            'attribute_id' => 1543,
            'id' => 64937,
            'product_id' => 5517,
        ],
        285 => [
            'attribute_id' => 1545,
            'id' => 64938,
            'product_id' => 5517,
        ],
        286 => [
            'attribute_id' => 18,
            'id' => 64939,
            'product_id' => 5518,
        ],
        287 => [
            'attribute_id' => 42,
            'id' => 64940,
            'product_id' => 5518,
        ],
        288 => [
            'attribute_id' => 114,
            'id' => 64941,
            'product_id' => 5518,
        ],
        289 => [
            'attribute_id' => 1543,
            'id' => 64942,
            'product_id' => 5518,
        ],
        290 => [
            'attribute_id' => 1544,
            'id' => 64943,
            'product_id' => 5518,
        ],
        291 => [
            'attribute_id' => 1545,
            'id' => 64944,
            'product_id' => 5518,
        ],
        292 => [
            'attribute_id' => 18,
            'id' => 64945,
            'product_id' => 5519,
        ],
        293 => [
            'attribute_id' => 42,
            'id' => 64946,
            'product_id' => 5519,
        ],
        294 => [
            'attribute_id' => 114,
            'id' => 64947,
            'product_id' => 5519,
        ],
        295 => [
            'attribute_id' => 1544,
            'id' => 64948,
            'product_id' => 5519,
        ],
        296 => [
            'attribute_id' => 1545,
            'id' => 64949,
            'product_id' => 5519,
        ],
        297 => [
            'attribute_id' => 18,
            'id' => 64950,
            'product_id' => 5520,
        ],
        298 => [
            'attribute_id' => 114,
            'id' => 64951,
            'product_id' => 5520,
        ],
        299 => [
            'attribute_id' => 115,
            'id' => 64952,
            'product_id' => 5520,
        ],
        300 => [
            'attribute_id' => 18,
            'id' => 64953,
            'product_id' => 5521,
        ],
        301 => [
            'attribute_id' => 114,
            'id' => 64954,
            'product_id' => 5521,
        ],
        302 => [
            'attribute_id' => 115,
            'id' => 64955,
            'product_id' => 5521,
        ],
        303 => [
            'attribute_id' => 18,
            'id' => 64956,
            'product_id' => 5522,
        ],
        304 => [
            'attribute_id' => 114,
            'id' => 64957,
            'product_id' => 5522,
        ],
        305 => [
            'attribute_id' => 115,
            'id' => 64958,
            'product_id' => 5522,
        ],
        306 => [
            'attribute_id' => 1537,
            'id' => 64959,
            'product_id' => 5522,
        ],
        307 => [
            'attribute_id' => 18,
            'id' => 64960,
            'product_id' => 5523,
        ],
        308 => [
            'attribute_id' => 114,
            'id' => 64961,
            'product_id' => 5523,
        ],
        309 => [
            'attribute_id' => 115,
            'id' => 64962,
            'product_id' => 5523,
        ],
        310 => [
            'attribute_id' => 442,
            'id' => 64963,
            'product_id' => 5523,
        ],
        311 => [
            'attribute_id' => 18,
            'id' => 64964,
            'product_id' => 5524,
        ],
        312 => [
            'attribute_id' => 114,
            'id' => 64965,
            'product_id' => 5524,
        ],
        313 => [
            'attribute_id' => 115,
            'id' => 64966,
            'product_id' => 5524,
        ],
        314 => [
            'attribute_id' => 442,
            'id' => 64967,
            'product_id' => 5524,
        ],
        315 => [
            'attribute_id' => 18,
            'id' => 64968,
            'product_id' => 5525,
        ],
        316 => [
            'attribute_id' => 114,
            'id' => 64969,
            'product_id' => 5525,
        ],
        317 => [
            'attribute_id' => 1542,
            'id' => 64970,
            'product_id' => 5525,
        ],
        318 => [
            'attribute_id' => 1543,
            'id' => 64971,
            'product_id' => 5525,
        ],
        319 => [
            'attribute_id' => 18,
            'id' => 64972,
            'product_id' => 5526,
        ],
        320 => [
            'attribute_id' => 114,
            'id' => 64973,
            'product_id' => 5526,
        ],
        321 => [
            'attribute_id' => 1542,
            'id' => 64974,
            'product_id' => 5526,
        ],
        322 => [
            'attribute_id' => 1543,
            'id' => 64975,
            'product_id' => 5526,
        ],
        323 => [
            'attribute_id' => 18,
            'id' => 64976,
            'product_id' => 5527,
        ],
        324 => [
            'attribute_id' => 114,
            'id' => 64977,
            'product_id' => 5527,
        ],
        325 => [
            'attribute_id' => 1335,
            'id' => 64978,
            'product_id' => 5527,
        ],
        326 => [
            'attribute_id' => 18,
            'id' => 64979,
            'product_id' => 5529,
        ],
        327 => [
            'attribute_id' => 114,
            'id' => 64980,
            'product_id' => 5529,
        ],
        328 => [
            'attribute_id' => 1335,
            'id' => 64981,
            'product_id' => 5529,
        ],
        329 => [
            'attribute_id' => 1543,
            'id' => 64982,
            'product_id' => 5529,
        ],
        330 => [
            'attribute_id' => 18,
            'id' => 64983,
            'product_id' => 5531,
        ],
        331 => [
            'attribute_id' => 56,
            'id' => 64984,
            'product_id' => 5531,
        ],
        332 => [
            'attribute_id' => 114,
            'id' => 64985,
            'product_id' => 5531,
        ],
        333 => [
            'attribute_id' => 206,
            'id' => 64986,
            'product_id' => 5531,
        ],
        334 => [
            'attribute_id' => 18,
            'id' => 64987,
            'product_id' => 5532,
        ],
        335 => [
            'attribute_id' => 114,
            'id' => 64988,
            'product_id' => 5532,
        ],
        336 => [
            'attribute_id' => 206,
            'id' => 64989,
            'product_id' => 5532,
        ],
        337 => [
            'attribute_id' => 301,
            'id' => 64990,
            'product_id' => 5532,
        ],
        338 => [
            'attribute_id' => 496,
            'id' => 64991,
            'product_id' => 5532,
        ],
        339 => [
            'attribute_id' => 18,
            'id' => 64992,
            'product_id' => 5533,
        ],
        340 => [
            'attribute_id' => 114,
            'id' => 64993,
            'product_id' => 5533,
        ],
        341 => [
            'attribute_id' => 206,
            'id' => 64994,
            'product_id' => 5533,
        ],
        342 => [
            'attribute_id' => 301,
            'id' => 64995,
            'product_id' => 5533,
        ],
        343 => [
            'attribute_id' => 496,
            'id' => 64996,
            'product_id' => 5533,
        ],
        344 => [
            'attribute_id' => 18,
            'id' => 64997,
            'product_id' => 5534,
        ],
        345 => [
            'attribute_id' => 114,
            'id' => 64998,
            'product_id' => 5534,
        ],
        346 => [
            'attribute_id' => 206,
            'id' => 64999,
            'product_id' => 5534,
        ],
        347 => [
            'attribute_id' => 228,
            'id' => 65000,
            'product_id' => 5534,
        ],
        348 => [
            'attribute_id' => 18,
            'id' => 65001,
            'product_id' => 5535,
        ],
        349 => [
            'attribute_id' => 114,
            'id' => 65002,
            'product_id' => 5535,
        ],
        350 => [
            'attribute_id' => 206,
            'id' => 65003,
            'product_id' => 5535,
        ],
        351 => [
            'attribute_id' => 228,
            'id' => 65004,
            'product_id' => 5535,
        ],
        352 => [
            'attribute_id' => 18,
            'id' => 65005,
            'product_id' => 5536,
        ],
        353 => [
            'attribute_id' => 114,
            'id' => 65006,
            'product_id' => 5536,
        ],
        354 => [
            'attribute_id' => 115,
            'id' => 65007,
            'product_id' => 5536,
        ],
        355 => [
            'attribute_id' => 206,
            'id' => 65008,
            'product_id' => 5536,
        ],
        356 => [
            'attribute_id' => 228,
            'id' => 65009,
            'product_id' => 5536,
        ],
        357 => [
            'attribute_id' => 18,
            'id' => 65010,
            'product_id' => 5537,
        ],
        358 => [
            'attribute_id' => 114,
            'id' => 65011,
            'product_id' => 5537,
        ],
        359 => [
            'attribute_id' => 176,
            'id' => 65012,
            'product_id' => 5537,
        ],
        360 => [
            'attribute_id' => 206,
            'id' => 65013,
            'product_id' => 5537,
        ],
        361 => [
            'attribute_id' => 228,
            'id' => 65014,
            'product_id' => 5537,
        ],
        362 => [
            'attribute_id' => 18,
            'id' => 65015,
            'product_id' => 5538,
        ],
        363 => [
            'attribute_id' => 114,
            'id' => 65016,
            'product_id' => 5538,
        ],
        364 => [
            'attribute_id' => 206,
            'id' => 65017,
            'product_id' => 5538,
        ],
        365 => [
            'attribute_id' => 231,
            'id' => 65018,
            'product_id' => 5538,
        ],
        366 => [
            'attribute_id' => 300,
            'id' => 65019,
            'product_id' => 5538,
        ],
        367 => [
            'attribute_id' => 18,
            'id' => 65020,
            'product_id' => 5540,
        ],
        368 => [
            'attribute_id' => 114,
            'id' => 65021,
            'product_id' => 5540,
        ],
        369 => [
            'attribute_id' => 1539,
            'id' => 65022,
            'product_id' => 5540,
        ],
        370 => [
            'attribute_id' => 18,
            'id' => 65023,
            'product_id' => 5541,
        ],
        371 => [
            'attribute_id' => 114,
            'id' => 65024,
            'product_id' => 5541,
        ],
        372 => [
            'attribute_id' => 1539,
            'id' => 65025,
            'product_id' => 5541,
        ],
        373 => [
            'attribute_id' => 18,
            'id' => 65026,
            'product_id' => 5542,
        ],
        374 => [
            'attribute_id' => 114,
            'id' => 65027,
            'product_id' => 5542,
        ],
        375 => [
            'attribute_id' => 1539,
            'id' => 65028,
            'product_id' => 5542,
        ],
        376 => [
            'attribute_id' => 18,
            'id' => 65029,
            'product_id' => 5543,
        ],
        377 => [
            'attribute_id' => 40,
            'id' => 65030,
            'product_id' => 5543,
        ],
        378 => [
            'attribute_id' => 114,
            'id' => 65031,
            'product_id' => 5543,
        ],
        379 => [
            'attribute_id' => 1539,
            'id' => 65032,
            'product_id' => 5543,
        ],
        380 => [
            'attribute_id' => 18,
            'id' => 65033,
            'product_id' => 5544,
        ],
        381 => [
            'attribute_id' => 114,
            'id' => 65034,
            'product_id' => 5544,
        ],
        382 => [
            'attribute_id' => 163,
            'id' => 65035,
            'product_id' => 5544,
        ],
        383 => [
            'attribute_id' => 423,
            'id' => 65036,
            'product_id' => 5544,
        ],
        384 => [
            'attribute_id' => 1538,
            'id' => 65037,
            'product_id' => 5544,
        ],
        385 => [
            'attribute_id' => 18,
            'id' => 65038,
            'product_id' => 5545,
        ],
        386 => [
            'attribute_id' => 114,
            'id' => 65039,
            'product_id' => 5545,
        ],
        387 => [
            'attribute_id' => 163,
            'id' => 65040,
            'product_id' => 5545,
        ],
        388 => [
            'attribute_id' => 1542,
            'id' => 65041,
            'product_id' => 5545,
        ],
        389 => [
            'attribute_id' => 18,
            'id' => 65042,
            'product_id' => 5546,
        ],
        390 => [
            'attribute_id' => 114,
            'id' => 65043,
            'product_id' => 5546,
        ],
        391 => [
            'attribute_id' => 439,
            'id' => 65044,
            'product_id' => 5546,
        ],
        392 => [
            'attribute_id' => 18,
            'id' => 65045,
            'product_id' => 5547,
        ],
        393 => [
            'attribute_id' => 114,
            'id' => 65046,
            'product_id' => 5547,
        ],
        394 => [
            'attribute_id' => 439,
            'id' => 65047,
            'product_id' => 5547,
        ],
        395 => [
            'attribute_id' => 18,
            'id' => 65048,
            'product_id' => 5548,
        ],
        396 => [
            'attribute_id' => 114,
            'id' => 65049,
            'product_id' => 5548,
        ],
        397 => [
            'attribute_id' => 439,
            'id' => 65050,
            'product_id' => 5548,
        ],
        398 => [
            'attribute_id' => 18,
            'id' => 65051,
            'product_id' => 5549,
        ],
        399 => [
            'attribute_id' => 114,
            'id' => 65052,
            'product_id' => 5549,
        ],
        400 => [
            'attribute_id' => 1543,
            'id' => 65053,
            'product_id' => 5549,
        ],
        401 => [
            'attribute_id' => 18,
            'id' => 65054,
            'product_id' => 5550,
        ],
        402 => [
            'attribute_id' => 114,
            'id' => 65055,
            'product_id' => 5550,
        ],
        403 => [
            'attribute_id' => 18,
            'id' => 65056,
            'product_id' => 5551,
        ],
        404 => [
            'attribute_id' => 114,
            'id' => 65057,
            'product_id' => 5551,
        ],
        405 => [
            'attribute_id' => 18,
            'id' => 65058,
            'product_id' => 5553,
        ],
        406 => [
            'attribute_id' => 114,
            'id' => 65059,
            'product_id' => 5553,
        ],
        407 => [
            'attribute_id' => 18,
            'id' => 65060,
            'product_id' => 5554,
        ],
        408 => [
            'attribute_id' => 41,
            'id' => 65061,
            'product_id' => 5554,
        ],
        409 => [
            'attribute_id' => 114,
            'id' => 65062,
            'product_id' => 5554,
        ],
        410 => [
            'attribute_id' => 1542,
            'id' => 65063,
            'product_id' => 5554,
        ],
        411 => [
            'attribute_id' => 18,
            'id' => 65064,
            'product_id' => 5555,
        ],
        412 => [
            'attribute_id' => 41,
            'id' => 65065,
            'product_id' => 5555,
        ],
        413 => [
            'attribute_id' => 114,
            'id' => 65066,
            'product_id' => 5555,
        ],
        414 => [
            'attribute_id' => 1335,
            'id' => 65067,
            'product_id' => 5555,
        ],
        415 => [
            'attribute_id' => 18,
            'id' => 65068,
            'product_id' => 5556,
        ],
        416 => [
            'attribute_id' => 41,
            'id' => 65069,
            'product_id' => 5556,
        ],
        417 => [
            'attribute_id' => 114,
            'id' => 65070,
            'product_id' => 5556,
        ],
        418 => [
            'attribute_id' => 18,
            'id' => 65071,
            'product_id' => 5558,
        ],
        419 => [
            'attribute_id' => 114,
            'id' => 65072,
            'product_id' => 5558,
        ],
        420 => [
            'attribute_id' => 228,
            'id' => 65073,
            'product_id' => 5558,
        ],
        421 => [
            'attribute_id' => 18,
            'id' => 65074,
            'product_id' => 5559,
        ],
        422 => [
            'attribute_id' => 42,
            'id' => 65075,
            'product_id' => 5559,
        ],
        423 => [
            'attribute_id' => 114,
            'id' => 65076,
            'product_id' => 5559,
        ],
        424 => [
            'attribute_id' => 1544,
            'id' => 65077,
            'product_id' => 5559,
        ],
        425 => [
            'attribute_id' => 1545,
            'id' => 65078,
            'product_id' => 5559,
        ],
        426 => [
            'attribute_id' => 18,
            'id' => 65079,
            'product_id' => 5560,
        ],
        427 => [
            'attribute_id' => 114,
            'id' => 65080,
            'product_id' => 5560,
        ],
        428 => [
            'attribute_id' => 316,
            'id' => 65081,
            'product_id' => 5560,
        ],
        429 => [
            'attribute_id' => 18,
            'id' => 65082,
            'product_id' => 5562,
        ],
        430 => [
            'attribute_id' => 36,
            'id' => 65083,
            'product_id' => 5562,
        ],
        431 => [
            'attribute_id' => 114,
            'id' => 65084,
            'product_id' => 5562,
        ],
        432 => [
            'attribute_id' => 18,
            'id' => 65085,
            'product_id' => 5563,
        ],
        433 => [
            'attribute_id' => 114,
            'id' => 65086,
            'product_id' => 5563,
        ],
        434 => [
            'attribute_id' => 18,
            'id' => 65087,
            'product_id' => 5564,
        ],
        435 => [
            'attribute_id' => 114,
            'id' => 65088,
            'product_id' => 5564,
        ],
        436 => [
            'attribute_id' => 18,
            'id' => 65089,
            'product_id' => 5566,
        ],
        437 => [
            'attribute_id' => 114,
            'id' => 65090,
            'product_id' => 5566,
        ],
        438 => [
            'attribute_id' => 228,
            'id' => 65091,
            'product_id' => 5566,
        ],
        439 => [
            'attribute_id' => 441,
            'id' => 65092,
            'product_id' => 5566,
        ],
        440 => [
            'attribute_id' => 18,
            'id' => 65093,
            'product_id' => 5567,
        ],
        441 => [
            'attribute_id' => 114,
            'id' => 65094,
            'product_id' => 5567,
        ],
        442 => [
            'attribute_id' => 228,
            'id' => 65095,
            'product_id' => 5567,
        ],
        443 => [
            'attribute_id' => 441,
            'id' => 65096,
            'product_id' => 5567,
        ],
        444 => [
            'attribute_id' => 18,
            'id' => 65097,
            'product_id' => 5568,
        ],
        445 => [
            'attribute_id' => 114,
            'id' => 65098,
            'product_id' => 5568,
        ],
        446 => [
            'attribute_id' => 210,
            'id' => 65099,
            'product_id' => 5568,
        ],
        447 => [
            'attribute_id' => 1544,
            'id' => 65100,
            'product_id' => 5568,
        ],
        448 => [
            'attribute_id' => 1545,
            'id' => 65101,
            'product_id' => 5568,
        ],
        449 => [
            'attribute_id' => 18,
            'id' => 65102,
            'product_id' => 5569,
        ],
        450 => [
            'attribute_id' => 114,
            'id' => 65103,
            'product_id' => 5569,
        ],
        451 => [
            'attribute_id' => 376,
            'id' => 65104,
            'product_id' => 5569,
        ],
        452 => [
            'attribute_id' => 1542,
            'id' => 65105,
            'product_id' => 5569,
        ],
        453 => [
            'attribute_id' => 18,
            'id' => 65106,
            'product_id' => 5570,
        ],
        454 => [
            'attribute_id' => 114,
            'id' => 65107,
            'product_id' => 5570,
        ],
        455 => [
            'attribute_id' => 376,
            'id' => 65108,
            'product_id' => 5570,
        ],
        456 => [
            'attribute_id' => 1542,
            'id' => 65109,
            'product_id' => 5570,
        ],
        457 => [
            'attribute_id' => 18,
            'id' => 65110,
            'product_id' => 5571,
        ],
        458 => [
            'attribute_id' => 114,
            'id' => 65111,
            'product_id' => 5571,
        ],
        459 => [
            'attribute_id' => 376,
            'id' => 65112,
            'product_id' => 5571,
        ],
        460 => [
            'attribute_id' => 423,
            'id' => 65113,
            'product_id' => 5571,
        ],
        461 => [
            'attribute_id' => 18,
            'id' => 65114,
            'product_id' => 5572,
        ],
        462 => [
            'attribute_id' => 114,
            'id' => 65115,
            'product_id' => 5572,
        ],
        463 => [
            'attribute_id' => 18,
            'id' => 65116,
            'product_id' => 5573,
        ],
        464 => [
            'attribute_id' => 25,
            'id' => 65117,
            'product_id' => 5573,
        ],
        465 => [
            'attribute_id' => 114,
            'id' => 65118,
            'product_id' => 5573,
        ],
        466 => [
            'attribute_id' => 1335,
            'id' => 65119,
            'product_id' => 5573,
        ],
        467 => [
            'attribute_id' => 18,
            'id' => 65120,
            'product_id' => 5574,
        ],
        468 => [
            'attribute_id' => 25,
            'id' => 65121,
            'product_id' => 5574,
        ],
        469 => [
            'attribute_id' => 114,
            'id' => 65122,
            'product_id' => 5574,
        ],
        470 => [
            'attribute_id' => 153,
            'id' => 65123,
            'product_id' => 5574,
        ],
        471 => [
            'attribute_id' => 18,
            'id' => 65124,
            'product_id' => 5575,
        ],
        472 => [
            'attribute_id' => 114,
            'id' => 65125,
            'product_id' => 5575,
        ],
        473 => [
            'attribute_id' => 115,
            'id' => 65126,
            'product_id' => 5575,
        ],
        474 => [
            'attribute_id' => 423,
            'id' => 65127,
            'product_id' => 5575,
        ],
        475 => [
            'attribute_id' => 18,
            'id' => 65128,
            'product_id' => 5576,
        ],
        476 => [
            'attribute_id' => 42,
            'id' => 65129,
            'product_id' => 5576,
        ],
        477 => [
            'attribute_id' => 114,
            'id' => 65130,
            'product_id' => 5576,
        ],
        478 => [
            'attribute_id' => 423,
            'id' => 65131,
            'product_id' => 5576,
        ],
        479 => [
            'attribute_id' => 18,
            'id' => 65132,
            'product_id' => 5577,
        ],
        480 => [
            'attribute_id' => 40,
            'id' => 65133,
            'product_id' => 5577,
        ],
        481 => [
            'attribute_id' => 114,
            'id' => 65134,
            'product_id' => 5577,
        ],
        482 => [
            'attribute_id' => 423,
            'id' => 65135,
            'product_id' => 5577,
        ],
        483 => [
            'attribute_id' => 18,
            'id' => 65136,
            'product_id' => 5578,
        ],
        484 => [
            'attribute_id' => 114,
            'id' => 65137,
            'product_id' => 5578,
        ],
        485 => [
            'attribute_id' => 423,
            'id' => 65138,
            'product_id' => 5578,
        ],
        486 => [
            'attribute_id' => 18,
            'id' => 65139,
            'product_id' => 5579,
        ],
        487 => [
            'attribute_id' => 114,
            'id' => 65140,
            'product_id' => 5579,
        ],
        488 => [
            'attribute_id' => 423,
            'id' => 65141,
            'product_id' => 5579,
        ],
        489 => [
            'attribute_id' => 1543,
            'id' => 65142,
            'product_id' => 5579,
        ],
        490 => [
            'attribute_id' => 18,
            'id' => 65143,
            'product_id' => 5580,
        ],
        491 => [
            'attribute_id' => 115,
            'id' => 65144,
            'product_id' => 5580,
        ],
        492 => [
            'attribute_id' => 423,
            'id' => 65145,
            'product_id' => 5580,
        ],
        493 => [
            'attribute_id' => 440,
            'id' => 65146,
            'product_id' => 5580,
        ],
        494 => [
            'attribute_id' => 18,
            'id' => 65147,
            'product_id' => 5581,
        ],
        495 => [
            'attribute_id' => 114,
            'id' => 65148,
            'product_id' => 5581,
        ],
        496 => [
            'attribute_id' => 423,
            'id' => 65149,
            'product_id' => 5581,
        ],
        497 => [
            'attribute_id' => 1543,
            'id' => 65150,
            'product_id' => 5581,
        ],
        498 => [
            'attribute_id' => 18,
            'id' => 65151,
            'product_id' => 5582,
        ],
        499 => [
            'attribute_id' => 114,
            'id' => 65152,
            'product_id' => 5582,
        ],
    ];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->data as $key => $value) {
            if (!AttributeItem::where('id', $value['attribute_id'])->first()) {
                array_push($this->collect, $value['attribute_id']);
            }
        }

        // try {
        //     foreach ($attributs as $attribute) {
        //         foreach ($attribute->products as $key => $product) {
        //             if (!Product::find($product->id)) {
        //                 $attribute->products()->detach($product->id);
        //                 $this->count = $this->count + 1;
        //             }
        //         }
        //     }
        // } catch (\Throwable $th) {
        //     logger($th);
        // }


        logger($this->collect);
        logger('done');
    }

    public function addAttributeItem($attribute)
    {
        $products = Product::has('attributes')->get();

        foreach ($products as $key => $product) {
            if (!$attribute->where('product_attribute.product_id', $product->id)) {
                $attribute->products()->detach($product->id);
                $this->count = $this->count + 1;
            }
        }

        unset($products, $attribute);

        // if ($product->country !== null) {
        //     if ($attribute->items()
        //         ->where('name', trim($product->country))
        //         ->first()) {
        //         $attributeItem = AttributeItem::where('name', trim($product->country))->first();
        //     } else {
        //         $attributeItem = AttributeItem::create([
        //             'name' => trim($product->country),
        //             'attribute_id' => $attribute->id,
        //         ]);
        //     }

        //     if (!$product->attributes()
        //         ->where('attribute_item.name', trim($product->country))
        //         ->first()) {
        //         $product->attributes()->attach($attributeItem->id);
        //     }

        //     unset($attributeItem);
        // }
    }
}
