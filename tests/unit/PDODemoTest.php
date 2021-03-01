<?php
require_once "dbTestCredentials.php";
require_once "PDODemo.php";

class PDODemoTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var \PDODemo
     */
    protected $pdoDemo;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testInsertExistingModelExistingBrand()
    {
        $pdoDemo = new PDODemo();
        $pdoDemo->runInsert('Ford', 'Fiesta');

        $this->tester->seeInDatabase('car_brand', array('make' => 'Ford'));
        $this->tester->seeInDatabase('car_model', array('make' => 'Ford', 'model' => 'Fiesta'));
    }

    public function testInsertExistingModelNewBrand()
    {
        $pdoDemo = new PDODemo();
        $pdoDemo->runInsert('Ford', 'Explorer');

        $this->tester->seeInDatabase('car_brand', array('make' => 'Ford'));
        $this->tester->seeInDatabase('car_model', array('make' => 'Ford', 'model' => 'Explorer'));
    }

    public function testInsertNewModelNewBrand()
    {
        $pdoDemo = new PDODemo();
        $pdoDemo->runInsert('Audi', 'A3');

        $this->tester->seeInDatabase('car_brand', array('make' => 'Audi'));
        $this->tester->seeInDatabase('car_model', array('make' => 'Audi', 'model' => 'A3'));
    }

  public function testComplexUpdate()
    {
        $pdoDemo = new PDODemo();
        $pdoDemo->runComplexUpdate(array('Nordland', 'Troms og Finnmark'));
        $this->tester->seeInDatabase('car_model', array('make' => 'Audi', 'model' => 'A3'));
        $this->tester->seeInDatabase('car_model', array('make' => 'Volkswagen', 'model' => 'Passat'));
        $this->tester->dontSeeInDatabase('car_model', array('make' => 'Audi', 'model' => 'Q3'));
        $this->tester->dontSeeInDatabase('car_model', array('make' => 'Mazda', 'model' => 'CX-3'));
    }

    public function testComplexQuery()
    {
        $pdoDemo = new PDODemo();
        $res = $pdoDemo->runComplexQuery('Volkswagen', 'Audi');

        $this->tester->assertIsArray($res);
        $this->tester->assertCount(2, $res);

        if ($res[0]['dealer_id'] = 'Bdf') {
            $bdfRec = $res[0];
            $elvRec = $res[1];
        } else {
            $bdfRec = $res[1];
            $elvRec = $res[0];
        }

        $this->tester->assertEquals('Bdf', $bdfRec['dealer_id']);
        $this->tester->assertEquals(2, $bdfRec['count']);
        $this->tester->assertEquals('Elv', $elvRec['dealer_id']);
        $this->tester->assertEquals(1, $elvRec['count']);

    }
}