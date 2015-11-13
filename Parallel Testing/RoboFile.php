<?php
require_once 'vendor/autoload.php';

class Robofile extends \Robo\Tasks
{
    use \Codeception\Task\MergeReports;

    public function parallelRun()
    {
        $parallel = $this->taskParallelExec();
        for ($i = 1; $i <= 4; $i++) {            
            $parallel->process(
                $this->taskCodecept() // use built-in Codecept task
                ->suite('acceptance') // run acceptance tests
                ->env("p$i")          // in its own environment
                ->xml("tests/_log/result_$i.xml") 
              );
        }
        return $parallel->run();
    }

     function parallelMergeResults()
    {
        $merge = $this->taskMergeXmlReports();
        for ($i=1; $i<=4; $i++) {
            $merge->from("tests/_output/tests/_log/result_$i.xml");
        }
        $merge->into("tests/_output/tests/_log/result.xml")
            ->run();
    }

    function parallelAll()
    {
        $result = $this->parallelRun();
        $this->parallelMergeResults();
        return $result;
    }

}
?>