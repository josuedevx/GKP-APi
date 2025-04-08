<?php
require_once __DIR__ . '/vendor/autoload.php';

use Google\Ads\GoogleAds\Examples\Targeting\GetGeoTargetConstantsByNames;
use Google\Ads\GoogleAds\Lib\V17\GoogleAdsClientBuilder;
use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\V17\Services\GenerateKeywordIdeasRequest;
use Google\Ads\GoogleAds\V17\Services\KeywordAndUrlSeed;
use Google\Ads\GoogleAds\V17\Services\KeywordSeed;
use Google\Ads\GoogleAds\V17\Common\KeywordPlanAggregateMetrics;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('google_ads');
$log->pushHandler(new StreamHandler(__DIR__ . '/config/info.log', Logger::DEBUG));

$config = parse_ini_file("google_ads_php.ini", true);

if ($config === false) {
    $log->error("Error al leer el archivo de configuración.");
    exit(1);
}

function getCompetitionLevel($index)
{
    if ($index <= 25)
        return 'Baja';
    if ($index <= 66)
        return 'Media';
    return 'Alta';
}

try {
    $oAuth2Credential = (new OAuth2TokenBuilder())->fromFile()->build();
    $googleAdsClient = (new GoogleAdsClientBuilder())->fromFile()
        ->withOAuth2Credential($oAuth2Credential)
        ->build();

    $keywordSeeds = [
        'cambio de aceite',
        'cambiar aceite coche',
        'walmart cambio de aceite',
    ];

    $url = '';

    $customerId = trim($config['CONNECTION']['customerId'], '"');
    $languageId = trim($config['CONNECTION']['languageId'], '"');
    $locationId = trim($config['CONNECTION']['locationId'], '"');

    // $combinedSeed = new KeywordAndUrlSeed();
    // $combinedSeed->setUrl($url);
    // $combinedSeed->setKeywords($keywordSeeds);

    $keywordSeed = new KeywordSeed();
    $keywordSeed->setKeywords($keywordSeeds);

    $keywordPlanIdeaServiceClient = $googleAdsClient->getKeywordPlanIdeaServiceClient();
    $aggregateMetrics = new KeywordPlanAggregateMetrics();

    $request = (new GenerateKeywordIdeasRequest())
        ->setCustomerId($customerId)
        ->setLanguage($languageId)
        ->setGeoTargetConstants([$locationId])
        ->setKeywordSeed($keywordSeed)
        // ->setKeywordAndUrlSeed($combinedSeed)
        ->setKeywordPlanNetwork(2)
        ->setIncludeAdultKeywords(false)
        ->setAggregateMetrics($aggregateMetrics);

    $response = $keywordPlanIdeaServiceClient->generateKeywordIdeas($request);

    $keywords = [];
    foreach ($response as $result) {
        $metrics = $result->getKeywordIdeaMetrics();

        $keywords[] = [
            'keyword' => $result->getText(),
            'avgMonthlySearches' => $metrics ? $metrics->getAvgMonthlySearches() : 0,
            'competition' => $metrics ? getCompetitionLevel($metrics->getCompetitionIndex()) : 'Desconocida'
        ];
    }

    $keywords = array_map("unserialize", array_unique(array_map("serialize", $keywords)));

    usort($keywords, function ($a, $b) {
        return $b['avgMonthlySearches'] - $a['avgMonthlySearches'];
    });

    // $keywords = array_slice($keywords, 0, 10);

    echo "Ideas de Keywords generadas exitosamente: ✅\n\n";
    echo str_pad("Keyword", 40) . str_pad("Búsquedas Mensuales", 25) . "Competencia\n";
    echo str_repeat("-", 90) . "\n";

    foreach ($keywords as $keyword) {

        echo str_pad($keyword['keyword'], 40);
        echo str_pad($keyword['avgMonthlySearches'], 25);
        echo $keyword['competition'] . "\n";

    }
    exportResultsToFiles($keywords);

} catch (Exception $e) {
    $log->error($e->getMessage());
    echo "❌ Error al generar las ideas de keywords: " . $e->getMessage();
}

function exportResultsToFiles(array $keywords): void
{
    $csvPath = __DIR__ . '/config/keywords_result.csv';
    $jsonPath = __DIR__ . '/config/keywords_result.json';

    $csvFile = fopen($csvPath, 'w');
    fputcsv($csvFile, ['Keyword', 'Búsquedas Mensuales', 'Nivel de Competencia']);
    foreach ($keywords as $keyword) {
        fputcsv($csvFile, [$keyword['keyword'], $keyword['avgMonthlySearches'], $keyword['competition']]);
    }
    fclose($csvFile);

    file_put_contents($jsonPath, json_encode($keywords, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}



