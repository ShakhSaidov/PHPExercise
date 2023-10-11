<?php
require_once(__DIR__ . '/../src/Train.php');
require_once(__DIR__ . '/../src/TrainData.php');
use PHPUnit\Framework\TestCase;

class TrainTest extends TestCase
{
    public function testReadDataFromFile()
    {
        $trainData = new TrainData();
        $trainData->readDataFromFile(__DIR__ . '/../src/data/test.csv');

        $expectedTrain1 = new Train('Train Line 1', 'Route 1', '101', 'Operator 1');
        $expectedTrain2 = new Train('Train Line 2', 'Route 2', '102', 'Operator 2');

        //testing that the data was read properly from the csv
        $this->assertCount(3,  $trainData->getData());
        $this->assertEquals($expectedTrain1, $trainData->getData()[1]);
        $this->assertEquals($expectedTrain2, $trainData->getData()[2]);
    }

    public function testSortData()
    {
        $trainData = new TrainData();
        $train1 = new Train('Train Line 1', 'Route 1', '1001', 'Operator 1');
        $train2 = new Train('Train Line 2', 'Route 2', '1002', 'Operator 2');
        $train3 = new Train('Train Line 3', 'Route 3', '1003', 'Operator 3');
        $trainData->setData([$train2, $train3, $train1]);

        // Testing when data is sorted by Train Line in ascending order
        $sortedData = $trainData->sortData($trainData->getData(), 'getTrainLine', 'asc');
        $expectedSortedData = [$train1, $train2, $train3];
        $this->assertEquals($expectedSortedData, $sortedData);

        // Testing when data is sorted by Run Number in descending order
        $sortedData = $trainData->sortData($trainData->getData(), 'getRunNumber', 'desc');
        $expectedSortedData = [$train3, $train2, $train1];
        $this->assertEquals($expectedSortedData, $sortedData);
    }

    public function testRemoveDuplicates()
    {
        $train1 = new Train('Line A', 'Route 1', '123', 'Operator 1');
        $train2 = new Train('Line A', 'Route 2', '456', 'Operator 2');
        $train3 = new Train('Line B', 'Route 1', '789', 'Operator 3');
        $train4 = new Train('Line A', 'Route 1', '123', 'Operator 1'); // Duplicate of $train1

  
        $arrayWithDuplicates = [$train1, $train2, $train3, $train4];
        $trainData = new TrainData();
        $result = $trainData->removeDuplicates($arrayWithDuplicates);

        // Checking that the result doesn't contain the duplicate data
        $this->assertCount(3, $result); 

        // Checking the rest of the data
        $this->assertContains($train1, $result);
        $this->assertContains($train2, $result);
        $this->assertContains($train3, $result);
        $this->assertNotContains($train4, $result);
    }
}