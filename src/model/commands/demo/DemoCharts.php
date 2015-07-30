<?php
namespace rtens\blog\model\commands\demo;

use rtens\domin\delivery\web\renderers\charting\charts\BarChart;
use rtens\domin\delivery\web\renderers\charting\charts\LineChart;
use rtens\domin\delivery\web\renderers\charting\charts\PieChart;
use rtens\domin\delivery\web\renderers\charting\charts\PolarAreaChart;
use rtens\domin\delivery\web\renderers\charting\charts\RadarChart;
use rtens\domin\delivery\web\renderers\charting\data\DataPoint;
use rtens\domin\delivery\web\renderers\charting\data\DataSet;

/**
 * Demonstrates the different Chart types
 */
class DemoCharts {

    private $lineChart;

    private $pieChart;

    public function __construct(LineChart $lineChart = null, PieChart $pieChart = null) {
        $this->lineChart = $lineChart ?: new LineChart(['one', 'two', 'three', 'four', 'five'], [
            new DataSet('Foo', [12, 16, 30, 6, 0]),
            new DataSet('Bar', [5, 2, 28, 26, 28]),
        ]);
        $this->pieChart = $pieChart ?: new PieChart([
            new DataPoint(11, 'Foo'),
            new DataPoint(8, 'Foo'),
            new DataPoint(5, 'Foo'),
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

}