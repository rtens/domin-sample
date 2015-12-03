<?php namespace rtens\blog\model\commands\demo;

use rtens\domin\delivery\DelayedOutput;

class DemoDelayedOutput {

    public function execute() {
        return new DelayedOutput(function (DelayedOutput $output) {
            $output->writeLine("Counting until 30...");
            for ($i = 1; $i <= 30; $i++) {
                $output->writeLine("Number $i");
                usleep(500000);
            }
            $output->write('Done');
        });
    }
}