<?php
namespace rtens\blog\model\commands\demo;

use rtens\domin\delivery\web\renderers\charting\charts\PieChart;
use rtens\domin\delivery\web\renderers\charting\data\DataPoint;
use rtens\domin\delivery\web\renderers\dashboard\types\ActionPanel;
use rtens\domin\delivery\web\renderers\dashboard\types\Column;
use rtens\domin\delivery\web\renderers\dashboard\types\Dashboard;
use rtens\domin\delivery\web\renderers\dashboard\types\Panel;
use rtens\domin\delivery\web\renderers\dashboard\types\Row;

class DemoDashboard {

    public function execute() {
        return new Dashboard([
            new Row([
                new Column([
                    (new ActionPanel('listPosts'))
                        ->setMaxHeight('20em')
                ], 6),
                new Column([new Panel('Another Panel', ['Some other content'])], 6),
            ]),
            new Row([
                new Column([
                    new Row([
                        new Column([new Panel('Small', [new PieChart([new DataPoint(12), new DataPoint(5)])])], 6),
                        new Column([new Panel('Small', [])], 6)
                    ])
                ], 8),
                new Column([
                    new Column([new Panel('Small', [])], 6),
                    new Column([new Panel('Small', [])], 6)
                ], 4)
            ]),
        ]);
    }

}