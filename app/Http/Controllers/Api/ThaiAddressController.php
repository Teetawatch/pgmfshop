<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ThaiAddressController extends Controller
{
    private static $data = null;

    private function getData()
    {
        if (self::$data === null) {
            $path = database_path('data/thai_addresses.json');
            if (file_exists($path)) {
                self::$data = json_decode(file_get_contents($path), true) ?? [];
            } else {
                self::$data = [];
            }
        }
        return self::$data;
    }

    public function search(Request $request)
    {
        $q = mb_strtolower(trim($request->input('q', '')));
        $field = $request->input('field', 'district'); // district, province, postal_code
        $province = $request->input('province', '');
        $district = $request->input('district', '');

        if (mb_strlen($q) < 1) {
            return response()->json([]);
        }

        $data = $this->getData();
        $results = [];
        $seen = [];

        foreach ($data as $row) {
            // Filter by province if provided
            if ($province && mb_strtolower($row['province']) !== mb_strtolower($province)) {
                continue;
            }
            // Filter by district if provided
            if ($district && mb_strtolower($row['district']) !== mb_strtolower($district)) {
                continue;
            }

            $value = $row[$field] ?? '';
            $matchValue = mb_strtolower($value);

            if (mb_strpos($matchValue, $q) !== false) {
                $key = $row['district'] . '|' . $row['province'] . '|' . $row['postal_code'];
                if (!isset($seen[$key])) {
                    $seen[$key] = true;
                    $results[] = [
                        'district' => $row['district'],
                        'province' => $row['province'],
                        'postal_code' => $row['postal_code'],
                    ];
                }
            }

            if (count($results) >= 20) {
                break;
            }
        }

        return response()->json($results);
    }
}
