<?php
require_once __DIR__ . '/Train.php';

class TrainData {
    private $trainData;
    private $perPage;

    public function __construct()
    {
        $this->trainData = [];
        $this->perPage = 5;
    }

    //Setting formatted data as the trainData
    public function setData($data)
    {
        $this->trainData = $data;
    }

    public function getData()
    {
        return $this->trainData;
    }

    //Reading data from a csv file
    public function readDataFromFile($filename)
    {
        $file = fopen($filename, "r");
        if ($file) {
            while (($data = fgetcsv($file)) !== false) {
                $this->trainData[] = new Train(trim($data[0]), trim($data[1]), trim($data[2]), trim($data[3]));
            }
            fclose($file);
        }
    }

    //Displaying the Train Data as a Table, sortable by any column
    public function displayTrainData()
    {
        // Getting sort information, default is run_number in alphabetical order 
        $sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'getRunNumber'; 
        $sortOrder = isset($_GET['order']) ? $_GET['order'] : 'asc'; 
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

        // Train Data Table
        echo "<table>";
        echo "<caption>Wellspring Train Data</caption>";
        echo "<thead>";
        echo "<tr>";
        echo "<th><a href=\"?page=$currentPage&sort=getTrainLine&order=" . ($sortColumn === 'getTrainLine' && $sortOrder === 'asc' ? 'desc' : 'asc') . "\">Train Line</a></th>";
        echo "<th><a href=\"?page=$currentPage&sort=getRouteName&order=" . ($sortColumn === 'getRouteName' && $sortOrder === 'asc' ? 'desc' : 'asc') . "\">Route Name</a></th>";
        echo "<th><a href=\"?page=$currentPage&sort=getRunNumber&order=" . ($sortColumn === 'getRunNumber' && $sortOrder === 'asc' ? 'desc' : 'asc') . "\">Run Number</a></th>";
        echo "<th><a href=\"?page=$currentPage&sort=getOperatorId&order=" . ($sortColumn === 'getOperatorId' && $sortOrder === 'asc' ? 'desc' : 'asc') . "\">Operator ID</a></th>";
        echo "</tr>";
        echo "</thead>";

        // Skipping the header row from the data, and setting other variables
        $sortedData = $this->sortData(array_slice($this->trainData, 1), $sortColumn, $sortOrder);
        $startIndex = ($currentPage - 1) * $this->perPage;
        $endIndex = $startIndex + $this->perPage - 1;
        $endIndex = min($endIndex, count($sortedData) - 1);
        
        // Removing any duplicates
        $uniqueData = $this->removeDuplicates($sortedData);

        // Iterating over the data and displaying row by row
        $this->displayRow($startIndex, $endIndex, $uniqueData);

        echo "</table>";
    }
    
    // Helper function to remove duplicates, replicating SQL DISTINCT function
    public function removeDuplicates($array)
    {
        $uniqueData = [];
        foreach ($array as $train) {
            $key = $train->getTrainLine() . $train->getRouteName() . $train->getRunNumber() . $train->getOperatorId();
            if (!isset($uniqueData[$key])) {
                $uniqueData[$key] = $train;
            }
        }
        return array_values($uniqueData);
    }

    //Sorting data by any column, replicating SQL query 'ORDER BY ____'
    public function sortData($data, $column, $order)
    {
        usort($data, function ($a, $b) use ($column, $order) {
            $aValue = strtolower($a->{$column}());
            $bValue = strtolower($b->{$column}());

            if ($aValue === $bValue) return 0;
            return ($order === 'asc') ? ($aValue <=> $bValue) : ($bValue <=> $aValue);
        });

        // storing the sort and order data in the session
        $_SESSION['sortColumn'] = $column;
        $_SESSION['sortOrder'] = $order;

        return $data;
    }

    //Displaying data row by row
    public function displayRow($start, $end, $data)
    {
        for ($i = $start; $i <= $end; $i++) {
            if (isset($data[$i])) {
                $train = $data[$i];
                echo "<tr>";
                echo "<td>" . $train->getTrainLine() . "</td>";
                echo "<td>" . $train->getRouteName() . "</td>";
                echo "<td>" . $train->getRunNumber() . "</td>";
                echo "<td>" . $train->getOperatorId() . "</td>";
                echo "</tr>";
            }
        }
    }

    //Enabling pagination, 5 entries per page
    public function displayPagination()
    {
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
        $totalPages = ceil(count($this->trainData) / $this->perPage);
        $column = $_SESSION['sortColumn'];
        $order = $_SESSION['sortOrder'];
        
        echo "<div class='pagination'>";
        echo "<ul>";
        for ($i = 1; $i <= $totalPages; $i++) {
            $isActive = $i == $currentPage ? "active" : "";
            echo "<li class='$isActive'><a href='?page=$i&sort=$column&order=$order'>$i</a></li>";
        }
        echo "</ul>";
        echo "</div>";
    }
}
?>