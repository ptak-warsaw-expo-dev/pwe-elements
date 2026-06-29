<?php

/**
 * Parses CAP day labels to stable date keys.
 */
final class PWE_Conference_Cap_Date_Parser {

    /**
     * Convert labels like "2 kwietnia 2025" or "02.04.2025" to key/display values.
     */
    public static function parse_date_to_standard(string $input): ?array {
        $input = trim($input);

        foreach (explode(';;', $input) as $segment) {
            $segment = trim($segment);

            if (preg_match('/(\d{1,2}[.\-\/]\d{1,2}[.\-\/]\d{2,4})/', $segment, $matches)) {
                $numeric = str_replace(array('/', '-'), '.', $matches[1]);
                $parts = explode('.', $numeric);
                $year_part = end($parts);
                $format = strlen($year_part) === 4 ? 'd.m.Y' : 'd.m.y';
                $date = DateTime::createFromFormat($format, $numeric);

                if ($date instanceof DateTime) {
                    return array('key' => $date->format('Y-m-d'), 'display' => $date->format('d.m.y'));
                }
            }

            $pl_months = array(
                'stycznia' => '01',
                'lutego' => '02',
                'marca' => '03',
                'kwietnia' => '04',
                'maja' => '05',
                'czerwca' => '06',
                'lipca' => '07',
                'sierpnia' => '08',
                'wrzesnia' => '09',
                'września' => '09',
                'pazdziernika' => '10',
                'października' => '10',
                'listopada' => '11',
                'grudnia' => '12',
            );

            foreach ($pl_months as $month_name => $month_number) {
                if (stripos($segment, $month_name) !== false) {
                    $normalized = str_ireplace($month_name, $month_number, $segment);
                    $normalized = preg_replace('/\s+/', ' ', $normalized);
                    $normalized = str_replace(' ', '.', (string) $normalized);
                    $date = DateTime::createFromFormat('d.m.Y', $normalized);

                    if ($date instanceof DateTime) {
                        return array('key' => $date->format('Y-m-d'), 'display' => $date->format('d.m.y'));
                    }
                }
            }
        }

        return null;
    }
}

