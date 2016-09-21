/*global angular*/
(function () {
    'use strict';
    angular.module('wxApp').controller('forecastController', [
        '$scope', '$http',
        function ($scope, $http) {
            $scope.loading = true;

            $scope.page.setTitle('Forecast');
            $scope.page.setDescription(
                '7-day weather forecast for Darfield, New Zealand.'
            );

            var apiKey = 'YOUR_API_KEY',
                iconDir = 'data/forecast/images/',// dir for carterlake icons
                iconType = '.gif',
                units = "_metric",
                wuStation = 'zmw:00000.10.93781.json',
                wuUrl = 'https://api.wunderground.com/api/',
                forecatName = 'forecast10day',
                requestUrl = wuUrl + apiKey + '/' + forecatName + '/q/' +
                    wuStation,
                maxDays = 7;
            $scope.uomTemp = "&degC";
            $scope.forecastData = [];

            $scope.iconlist = {
                // WU Icon name => NWS icon name // WU meaning
                'chanceflurries': ['sn', 'Chance flurries'],
                'chancerain': ['hi_shwrs', 'Chance rain'],
                'chancesleet': ['ip', 'Chance sleet'],
                'chancesnow': ['sn', 'Chance snow'],
                'chancetstorms': ['hi_tsra', 'Chance thunderstorms'],
                'clear': ['skc', 'Clear'],
                'cloudy': ['ovc', 'Cloudy'],
                'flurries': ['sn', 'Flurries'],
                'fog': ['fg', 'Fog'],
                'hazy': ['fg', 'Hazy'],
                'mostlycloudy': ['bkn', 'Mostly cloudy'],
                'mostlysunny': ['sct', 'Partly cloudy'],
                'partlycloudy': ['sct', 'Partly cloudy'],
                'partlysunny': ['bkn', 'Mostly sunny'],
                'rain': ['ra', 'Rain'],
                'sleet': ['ip', 'Sleet'],
                'sleat': ['ip', 'Sleet'],
                'snow': ['sn', 'Snow'],
                'sunny': ['skc', 'Sunny'],
                'tstorms': ['tsra', 'Thunderstorms'],
                'unknown': ['na', ''],
                'nt_chanceflurries': ['nsn', 'Chance flurries'],
                'nt_chancerain': ['hi_nshwrs', 'Chance rain'],
                'nt_chancesleet': ['ip', 'Chance sleet'],
                'nt_chancesnow': ['nsn', 'Chance snow'],
                'nt_chancetstorms': ['hi_ntsra', 'Chance thunderstorms'],
                'nt_clear': ['nskc', 'Clear'],
                'nt_cloudy': ['novc', 'Cloudy'],
                'nt_flurries': ['nsn', 'Flurries'],
                'nt_fog': ['nfg', 'Fog'],
                'nt_hazy': ['nfg', 'Hazy'],
                'nt_mostlycloudy': ['nbkn', 'Mostly cloudy'],
                'nt_mostlysunny': ['nsct', 'Partly cloudy'],
                'nt_partlycloudy': ['nsct', 'Partly cloudy'],
                'nt_partlysunny': ['nbkn', 'Mostly cloudy'],
                'nt_rain': ['nra', 'Rain'],
                'nt_sleet': ['ip', 'Sleet'],
                'nt_sleat': ['ip', 'Sleet'],
                'nt_snow': ['nsn', 'Snow'],
                'nt_sunny': ['nskc', 'Sunny'],
                'nt_tstorms': ['ntsra', 'Thunderstorms'],
                'nt_unknown': ['na', ''],
                '': ['na', '']
            };

            $scope.getIcon = function (forecast) {
                var fileName;
                if (forecast.pop !== "0" && forecast.icon !== 'clear') {
                    fileName = $scope.iconlist[forecast.icon][0] + forecast.pop;
                } else {
                    fileName = $scope.iconlist[forecast.icon][0];
                }

                return iconDir + fileName + iconType;
            };
            $scope.getIconText = function (forecast) {

                return $scope.iconlist[forecast.icon][1];

            };

            $scope.getForecastDescp = function (forecast) {
                return forecast["fcttext" + units];
            };

            function getWUforecast() {

                $http.get(requestUrl, {cache: true})
                    .success(function (response) {
                        var txt_forecast =
                                response.forecast.txt_forecast.forecastday,
                            forecastday =
                                response.forecast.simpleforecast.forecastday,
                            idx = 0;
                        angular.forEach(forecastday, function (day) {

                            if (day.period <= maxDays) {
                                txt_forecast[idx].temp = day.high.celsius;
                                txt_forecast[idx + 1].temp = day.low.celsius;
                                $scope.forecastData.push({
                                    forecastday: day,
                                    txt_forecast:
                                        [txt_forecast[idx],
                                            txt_forecast[idx + 1]]
                                });
                            }
                            idx += 2;
                        });
                        $scope.loading = false;
                    });
            }

            getWUforecast();

        }]);
}());