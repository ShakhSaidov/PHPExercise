<!DOCTYPE html>
<html>
<head>
    <title>Wellspring Exercise</title>
    <link rel="stylesheet" type="text/css" href="./styles.css">
    <link rel="icon" href="./wellspring-favicon.webp" type="image/x-icon">
</head>
<body>
    <form action="index.php" method="post" enctype="multipart/form-data">
        <h3>Upload your own CSV file!</h3>
        <input type="file" name="csv_file" accept=".csv">
        <input type="submit" value="Upload">
    </form>
    <?php
    include_once(__DIR__ . "/src/Train.php");
    include_once(__DIR__ . "/src/TrainData.php");

    session_start(); 
    $trainData = new TrainData();

    // Allowing user to upload their own CSV file
    // Checking whether to read from the local data or the user-uploaded CSV, which is stored in the session.
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
        $file = $_FILES['csv_file']['tmp_name'];

        // Processing the CSV file
        if (($handle = fopen($file, 'r')) !== false) {
            $data = [];
            while (($row = fgetcsv($handle)) !== false) {
                $train = new Train($row[0], $row[1], $row[2], $row[3]);
                $data[] = $train;
            }
            fclose($handle);

            // Setting up the data from the csv and storing in the session
            $trainData->setData($data);
            $_SESSION['uploaded_data'] = $data;
        } else {
            echo 'Error reading the CSV file.';
        }
    } else {
        // Checking if there is user-uploaded data
        if (isset($_SESSION['uploaded_data'])) {
            $trainData->setData($_SESSION['uploaded_data']);
        } else {
            //Otherwise, by default reading the local data
            $trainData->readDataFromFile(__DIR__ . "/src/data/trains_6.csv");
        }
    }

    //Display of Train Data in a table
    $trainData->displayTrainData();

    //Pagination functionality
    $trainData->displayPagination();
    ?>
</body>
</html>