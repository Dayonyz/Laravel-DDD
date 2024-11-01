<?php

use App\Domains\IdentityAccess\Domain\Enums\UserTitleEnum;
use App\Domains\IdentityAccess\Application\Bus\Command\CreateBusinessAccountCommand;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'identity-access', 'as' => 'identity-access.'], function () {
    Route::get('/', function () {

        $businessDto = new \App\Domains\IdentityAccess\Application\Bus\Command\Dto\CreateBusinessAccountDto(
            'My new business',
            'https://img.freepik.com/9cd18345f33e5155330d7692&w=2000',
            'new.business@gmail.com',
            '380',
            '684930506',
            '64100',
            'UA',
            'Kharkiv',
            'st. Naukova lane, 68, ap.129',
            'https://pixabay.com/',
            false,
            UserTitleEnum::MR->value,
            'Denys',
            'Pisklov',
            'Qq12345678'
        );

        CreateBusinessAccountCommand::dispatch(
            $businessDto
        );

        return view('identity-access.welcome');
    });

    Route::get('/fibonacci', function () {

        $buffer = [];

       function fibonacci(int $n, array &$buffer) {
           if (isset($buffer[$n])) {
               return $buffer[$n];
           }

           if ($n === 0) {
               $buffer[$n] = 0;
               return 0;
           } else if($n === 1 || $n === 2) {
               $buffer[$n] = 1;
               return 1;
           } else if ($n > 2) {
               $half = (int)floor($n/2);

               if ($n & 1) {
                   $buffer[$n] = pow(fibonacci($half + 1, $buffer), 2) + pow(fibonacci($half, $buffer), 2);

               } else {
                   $buffer[$n] = fibonacci($half, $buffer)*(2*fibonacci($half - 1, $buffer)+ fibonacci($half, $buffer));
               }

               return $buffer[$n];
           }
        }

       dd(fibonacci(18, $buffer));
    });

    Route::get('/max-values-with-same-diff', function () {
        $test = [1, 4, 7, 5, 3, 9, 10, 13];
        $output = [];

        function maxValuesWithSameDiff(array $array, &$output): array {
            asort($array);
            $first = array_shift($array);

            if (!count($array)) {
                uksort($output, function ($a, $b) use ($output) {
                    return count($output[$b]) <=> count($output[$a]);
                });

                $incrementKeys = array_keys($output);
                $firstMax = $output[$incrementKeys[0]];
                array_shift($incrementKeys);

                $output = array_filter($output, function ($value) use ($firstMax) {
                    return count($firstMax) === count($value);
                });

                return $output;
            }

            foreach ($array as $key => $second) {
                $diff = $second - $first;
                $strDiff = (string)$diff;

                if (!isset($output[$strDiff])) {
                    $output[$strDiff] = [];
                    $output[$strDiff][$first] = $first;
                    $output[$strDiff][$second] = $second;
                } else if ($first - max($output[$strDiff]) === $diff) {
                    $output[$strDiff][$first] = $first;
                    $output[$strDiff][$second] = $second;
                } else if ($second - max($output[$diff]) === $diff) {
                    $output[$strDiff][$second] = $second;
                }
            }

            return maxValuesWithSameDiff($array, $output);
        }

        dd(maxValuesWithSameDiff($test, $output));
    });

    Route::get('/test-three-colt', function () {
        function countUniqueUrls(array $urls): int
        {
            if (! (isset($urls[0]) && is_string($urls[0]))) {
                throw new \Exception('Parameter is not array of strings');
            }

            $isTopLevelDomain = function (string $url) {
                $chunks  = parse_url($url);
                $host = $chunks['host'] ?? '';

                $topDomainLevel = explode('.', $url);

                return count($topDomainLevel) === 2;
            };

            $normalizedUrl = function (string $url): string {
                $chunks = parse_url($url);
                $protocol = $chunks['scheme'] ?? '';
                $host = $chunks['host'] ?? '';

                $path = rtrim($chunks['path'] ?? '', '/');

                $uri = $chunks['query'] ?? '';
                $params = [];
                if ($uri !== '') {
                    parse_str($uri, $params);
                    ksort($params);
                    $uri = '';
                    foreach ($params as $param => $value) {
                        $uri .= (strlen($uri) ? '&' : '') . $param . '=' . $value;
                    }

                }

                return $protocol . '://' . $host . $path . (count($params) ? '?' . $uri : '');
            };

            foreach ($urls as $key => $url) {
                if ($isTopLevelDomain($url)) {
                    $urls[$key] = $normalizedUrl($url);
                }
            }

            return count(array_unique($urls));
        }

        dd(countUniqueUrls(['https://norma.test.com?a=1&b=2', 'https://test.com?b=2&a=1']));

        dd('test');
    });
});
