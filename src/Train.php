<?php
class Train
{
    private $trainLine;
    private $routeName;
    private $runNumber;
    private $operatorId;

    public function __construct($trainLine, $routeName, $runNumber, $operatorId)
    {
        $this->trainLine = $trainLine;
        $this->routeName = $routeName;
        $this->runNumber = $runNumber;
        $this->operatorId = $operatorId;
    }

    public function getTrainLine()
    {
        return $this->trainLine;
    }

    public function getRouteName()
    {
        return $this->routeName;
    }

    public function getRunNumber()
    {
        return $this->runNumber;
    }

    public function getOperatorId()
    {
        return $this->operatorId;
    }
}

?>