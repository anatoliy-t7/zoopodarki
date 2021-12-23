<?php

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Str;

if (!function_exists('blockToHtml')) {
    function blockToHtml($blocks)
    {
        $content = '';
        // dd($blocks['blocks']);
        foreach ($blocks['blocks'] as $block) {
            if ($block['type'] == 'paragraph') {
                $content .= '<p>' . $block['data']['text'] . "</p>\n";
            } elseif ($block['type'] == 'header') {
                $content .= '<h' . $block['data']['level'] . '>' . $block['data']['text'] . '</h' . $block['data']['level'] . ">\n";
            } elseif ($block['type'] == 'list') {
                $listTag = ($block['data']['style'] == 'ordered') ? 'ol' : 'ul';
                $content .= '<' . $listTag . ">\n";

                foreach ($block['data']['items'] as $itemText) {
                    $content .= "\t<li>" . $itemText . "</li>\n";
                }
                $content .= '</' . $listTag . ">\n";
            } elseif ($block['type'] == 'checklist') {
                foreach ($block['data']['items'] as $itemText) {
                    $content .= "<label>\n";
                    $content .= '<input ' . ($itemText['checked'] ? 'checked' : '') . " type=\"checkbox\" disabled=\"true\" />\n";
                    $content .= $itemText['text'] . "</label>\n<br/>";
                }
            } elseif ($block['type'] == 'image') {
                $stretched = ($block['data']['stretched']) ? 'w-full' : 'w-auto';
                $content .= '<img src=' . $block['data']['file']['url'] . ' class="' . $stretched . '" />' . "\n";
            } elseif ($block['type'] == 'table') {
                $rows = '';
                foreach ($block['data']['content'] as $key => $row) {
                    $rows .= '<tr>';
                    if ($block['data']['withHeadings'] === true && $key == 0) {
                        foreach ($row as $col) {
                            $rows .= '<td class="p-4 pl-8 text-base font-semibold text-left text-gray-500 border-b border-gray-100">';
                            $rows .= $col;
                            $rows .= '</td>';
                        }
                    } else {
                        foreach ($row as $col) {
                            $rows .= '<td class="p-4 pl-8 text-left text-gray-500 border-b border-gray-100">';
                            $rows .= $col;
                            $rows .= '</td>';
                        }
                    }
                    $rows .= '</tr>';
                }
                $content .= '<table  class="w-full text-sm border-collapse table-fixed"><tbody class="bg-white dark:bg-gray-800">' . $rows . '</tbody></table>' . "\n";
            }
        }

        return $content;
    }
}

if (!function_exists('discount')) {
    function discount($price, $procent)
    {
        $discount = $price - ($price * $procent / 100);

        return ceil($discount);
    }
}

if (!function_exists('RUB')) {
    function RUB($number)
    {
        if ($number !== null) {
            return number_format($number, 0, '', ' ') . ' ₽';
        }

        return $number;
    }
}

if (!function_exists('simpleDate')) {
    function simpleDate($datetime)
    {
        return Carbon::parse($datetime)->format('d.m.Y');
    }
}

if (!function_exists('dataYmd')) {
    function dataYmd($datetime)
    {
        return Carbon::parse($datetime)->format('Y.m.d');
    }
}

if (!function_exists('dataAndTime')) {
    function dataAndTime($datetime)
    {
        return Carbon::parse($datetime)->toDayDateTimeString();
    }
}

if (!function_exists('getNextOrderNumber')) {
    function getNextOrderNumber()
    {
        // Get the last created order
        $lastOrder = Order::latest()->first();

        if (!$lastOrder) {
            // We get here if there is no order at all
            // If there is no number set it to 0, which will be 1 at the end.
            $number = 0;
        } else {
            $number = substr($lastOrder->order_number, 3);
        }

        // If we have W000001 in the database then we only want the number
        // So the substr returns this 000001

        // Add the string in front and higher up the number.
        // the %05d part makes sure that there are always 6 numbers in the string.
        // so it adds the missing zero's when needed.

        return 'ZOO' . sprintf('%06d', intval($number) + 1);
    }
}

// Convert grams to human readable units
if (!function_exists('kg')) {
    function kg($value)
    {
        if ($value !== 0 && $value !== null) {
            if (is_string($value)) {
                if (Str::contains($value, 'на развес')) {
                    return $value;
                }

                $value = Str::replace('гр', '', $value);
                $value = trim($value);
            }

            if (strlen((string) $value) > 3) {
                $value = intval($value) / 1000;
                $value = round($value, 1);
                $value .= ' кг';
            } else {
                $value .= ' гр';
            }
        }

        return $value;
    }
}

if (!function_exists('tagP')) {
    function tagP($comment)
    {
        return '<p>' . implode('</p><p>', array_filter(explode("\n", $comment))) . '</p>';
    }
}

// Англо-русская замена
if (!function_exists('switcher_ru')) {
    function switcher_ru($value)
    {
        $converter = [
            'f' => 'а', ',' => 'б', 'd' => 'в', 'u' => 'г', 'l' => 'д', 't' => 'е', '`' => 'ё',
            ';' => 'ж', 'p' => 'з', 'b' => 'и', 'q' => 'й', 'r' => 'к', 'k' => 'л', 'v' => 'м',
            'y' => 'н', 'j' => 'о', 'g' => 'п', 'h' => 'р', 'c' => 'с', 'n' => 'т', 'e' => 'у',
            'a' => 'ф', '[' => 'х', 'w' => 'ц', 'x' => 'ч', 'i' => 'ш', 'o' => 'щ', 'm' => 'ь',
            's' => 'ы', ']' => 'ъ', "'" => 'э', '.' => 'ю', 'z' => 'я',

            'F' => 'А', '<' => 'Б', 'D' => 'В', 'U' => 'Г', 'L' => 'Д', 'T' => 'Е', '~' => 'Ё',
            ':' => 'Ж', 'P' => 'З', 'B' => 'И', 'Q' => 'Й', 'R' => 'К', 'K' => 'Л', 'V' => 'М',
            'Y' => 'Н', 'J' => 'О', 'G' => 'П', 'H' => 'Р', 'C' => 'С', 'N' => 'Т', 'E' => 'У',
            'A' => 'Ф', '{' => 'Х', 'W' => 'Ц', 'X' => 'Ч', 'I' => 'Ш', 'O' => 'Щ', 'M' => 'Ь',
            'S' => 'Ы', '}' => 'Ъ', '"' => 'Э', '>' => 'Ю', 'Z' => 'Я',

            '@' => '"', '#' => '№', '$' => ';', '^' => ':', '&' => '?', '/' => '.', '?' => ',',
        ];

        return strtr($value, $converter);
    }
}

// Русско-английская замена
if (!function_exists('switcher_en')) {
    function switcher_en($value)
    {
        $converter = [
            'а' => 'f', 'б' => ',', 'в' => 'd', 'г' => 'u', 'д' => 'l', 'е' => 't', 'ё' => '`',
            'ж' => ';', 'з' => 'p', 'и' => 'b', 'й' => 'q', 'к' => 'r', 'л' => 'k', 'м' => 'v',
            'н' => 'y', 'о' => 'j', 'п' => 'g', 'р' => 'h', 'с' => 'c', 'т' => 'n', 'у' => 'e',
            'ф' => 'a', 'х' => '[', 'ц' => 'w', 'ч' => 'x', 'ш' => 'i', 'щ' => 'o', 'ь' => 'm',
            'ы' => 's', 'ъ' => ']', 'э' => "'", 'ю' => '.', 'я' => 'z',

            'А' => 'F', 'Б' => '<', 'В' => 'D', 'Г' => 'U', 'Д' => 'L', 'Е' => 'T', 'Ё' => '~',
            'Ж' => ':', 'З' => 'P', 'И' => 'B', 'Й' => 'Q', 'К' => 'R', 'Л' => 'K', 'М' => 'V',
            'Н' => 'Y', 'О' => 'J', 'П' => 'G', 'Р' => 'H', 'С' => 'C', 'Т' => 'N', 'У' => 'E',
            'Ф' => 'A', 'Х' => '{', 'Ц' => 'W', 'Ч' => 'X', 'Ш' => 'I', 'Щ' => 'O', 'Ь' => 'M',
            'Ы' => 'S', 'Ъ' => '}', 'Э' => '"', 'Ю' => '>', 'Я' => 'Z',

            '"' => '@', '№' => '#', ';' => '$', ':' => '^', '?' => '&', '.' => '/', ',' => '?',
        ];

        return strtr($value, $converter);
    }
}
