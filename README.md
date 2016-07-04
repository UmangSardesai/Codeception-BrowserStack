# Codeception-BrowserStack
Run Codeception tests on BrowserStack. Sequentially and in parallel. 

### Setup a sample Codeception test to run on BrowserStack


1. Add _codecept.phar_ to a sample project folder by excuting the following command: `wget http://codeception.com/codecept.phar` or `curl -O http://codeception.com/codecept.phar`

2. Execute `php codecept.phar bootstrap`

3. Execute `php codecept.phar generate:cept acceptance Welcome`

4. To configure the test, open and edit _./tests/acceptance.suite.yml_ and copy the file from _Sample Tests/acceptance.suite.yml_.

5. Execute `php codecept.phar build`.

6. Lets write a test to search BrowserStack on Google. Open and edit file _./tests/acceptance/WelcomeCept.php_ and copy the file from _Sample Tests/WelcomeCept.php._

7. To run a single test (on say Firefox), execute the command `php codecept.phar run --env firefox`.

### Sequential Runs

1. You can make use of [Environments](http://codeception.com/docs/07-AdvancedUsage#Environments), to execute tests on different browser and OS combinations.

2. Check the _acceptance.suite.yml_ to see how the capabilities and environments are mentioned.

3. To run sequentially of 4 environments, execute the command `php codecept.phar run --env chrome --env firefox --env ie --env safari`

## Parallel Testing

To run sample test in parallel, you need the following two things: 
- [Robo](http://robo.li/), a task runner that executes your tests in parallel
- [robo-paracept](https://github.com/Codeception/robo-paracept) - Codeception tasks for parallel execution.

You can follow these steps:

1. Install 'Composer' if you haven't yet : `curl -sS https://getcomposer.org/installer | php`

2. Add the _composer.json_ file present under _Parallel Testing_ folder to the root directory of the project.

3. Execute the command `php composer.phar install` to install the two components mentioned above.

4. Execute `Robo.phar` and press 'Y' to create an empty _RoboFile.php_.

5. Copy the _RoboFile.php_ file under _Parallel Testing_ to the empty _RoboFile.php_. 

6. Change the env names to `p1`, `p2`... instead of `chrome`, `firefox`..., as given in _acceptance.suite.yml_ file in _Parallel testing_ folder

7. The following function in _RoboFile_ executes tests in parallel:
```
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
```

Execute the commmand `robo.phar parallel:run` to execute tests in parallel.

It would be very confusing to have logs of multiple tests coming at once. Hence it is necessary to merge them. This is where robo-paracept's _MergeReports_ comes into play which merges all results into one file. The following function merges all the results:
```
use \Codeception\Task\MergeReports;

     function parallelMergeResults()
    {
        $merge = $this->taskMergeXmlReports();
        for ($i=1; $i<=4; $i++) {
            $merge->from("tests/_output/tests/_log/result_$i.xml");
        }
        $merge->into("tests/_output/tests/_log/result.xml")
            ->run();
    }
```

Execute the commmand `robo.phar parallel:merge-results` to merge results. 

You can put the two fuctions together in one function as follows:
```
   function parallelAll()
    {
        $result = $this->parallelRun();
        $this->parallelMergeResults();
        return $result;
    }
```
Execute the commmand `robo.phar parallel:all` to execute Codeception tests in parallel as well as merge results. 

More details on parallel execution in Codeception available [here](http://codeception.com/docs/12-ParallelExecution#.VkZCm98rJ7q).


