<?php
namespace rtens\blog\model\commands\demo;

use rtens\domin\delivery\web\renderers\charting\charts\BarChart;
use rtens\domin\delivery\web\renderers\charting\charts\LineChart;
use rtens\domin\delivery\web\renderers\charting\charts\PieChart;
use rtens\domin\delivery\web\renderers\charting\charts\PolarAreaChart;
use rtens\domin\delivery\web\renderers\charting\charts\RadarChart;
use rtens\domin\delivery\web\renderers\charting\charts\ScatterChart;
use rtens\domin\delivery\web\renderers\charting\data\DataPoint;
use rtens\domin\delivery\web\renderers\charting\data\DataSet;
use rtens\domin\delivery\web\renderers\charting\data\ScatterDataPoint;
use rtens\domin\delivery\web\renderers\charting\data\ScatterDataSet;
use rtens\domin\parameters\Color;

/**
 * Demonstrates the different Chart types
 *
 * You can find the code that is Action is generated from [here](http://github.com/rtens/domin-sample/blob/master/src/model/commands/demo/DemoCharts.php)
 */
class DemoCharts {

    private $lineChart;

    private $pieChart;

    public function __construct(LineChart $lineChart = null, PieChart $pieChart = null) {
        $this->lineChart = $lineChart ?: new LineChart(['one', 'two', 'three', 'four', 'five'], [
            new DataSet([12, 16, 30, 6, 0], 'Foo'),
            new DataSet([5, 2, 28, 26, 28], 'Bar'),
            new DataSet([7, 10, 5, 15, 18], 'Baz'),
        ]);
        $this->pieChart = $pieChart ?: new PieChart([
            new DataPoint(11, 'Foo'),
            new DataPoint(8, 'Bar'),
            new DataPoint(5, 'Baz'),
        ]);
    }

    public function getLineChart() {
        return $this->lineChart;
    }

    public function getBarChart() {
        return new BarChart($this->lineChart->getLabels(), $this->lineChart->getDataSets());
    }

    public function getRadarChart() {
        return new RadarChart($this->lineChart->getLabels(), $this->lineChart->getDataSets());
    }

    public function getPieChart() {
        return $this->pieChart;
    }

    public function getPolarAreaChart() {
        return new PolarAreaChart($this->pieChart->getDataPoints());
    }

    public function getScatterChart() {
        return new ScatterChart([
            new ScatterDataSet([
                new ScatterDataPoint(12, 42, 2),
                new ScatterDataPoint(4, 3, 6),
                new ScatterDataPoint(16, 2, 4),
                new ScatterDataPoint(4, 42),
            ], 'foo', Color::PINK()),
            new ScatterDataSet([
                new ScatterDataPoint(4, 23, 8),
                new ScatterDataPoint(14, 30, 7),
                new ScatterDataPoint(6, 22, 2),
                new ScatterDataPoint(3, 2),
            ], 'bar', Color::ORANGE()),
        ]);
    }

}