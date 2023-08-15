<?php


use App\Services\HttpService;
use App\Services\WeatherService;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpClient\HttpClient;

require_once __DIR__.'/../vendor/autoload.php';


$container = new ContainerBuilder();


$container->register(HttpService::class, HttpService::class)
    ->addArgument(HttpClient::create());


$container->setParameter('weather.days' , 3);
$container->setParameter('weather.temperature_unit' , 'celsius');


$container->register(WeatherService::class, WeatherService::class)
    ->addArgument(new Reference(HttpService::class))
    ->addArgument('%weather.days%')
    ->addArgument('%weather.temperature_unit%');


$weatherService = $container->get(WeatherService::class);
$response = $weatherService->getWeather(45.5, 8.2);

displayForecast($response['daily']);

function displayForecast($daily): void
{
    $output = new ConsoleOutput();
    $table = new Table($output);
    $table->setHeaders(['Day', 'Temperature Min', 'Temperature Max']);
    $rows = [];
    foreach ($daily['time'] as $key => $date) {
        $rows[] = [
            $date,
            $daily['temperature_2m_min'][$key],
            $daily['temperature_2m_max'][$key]
        ];

    }
    $table->setRows($rows);
    $table->render();
}