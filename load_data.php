<?php
require 'vendor/autoload.php';

$faker = Faker\Factory::create('en_PH');
$host = 'root';
$username = 'root'; 
$password = ''; 
$database = 'appdev'; 

try {
    // Create a PDO database connection
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);

    // Insert 50 rows of fake data to Office
    for ($i = 1; $i <= 50; $i++) {
        $country = 'Philippines';
        $insertQuery = $pdo->prepare("INSERT INTO office (name, contactnum, email_address, city, country, postal) 
        VALUES ('".$faker->company."','".$faker->phonenumber."', '".$faker->email."' , '".$faker->city."', '".$country."', '".$faker->postcode."')");

    }

    // Fetch valid office_id values from the 'office' table
    $officeId = $pdo->query("SELECT id FROM office")->fetchAll(PDO::FETCH_COLUMN);

    // Insert 200 rows of fake data to Employee
    for ($i = 1; $i <= 200; $i++) {

        $insertQuery = $pdo->prepare("INSERT INTO employee (lastname, firstname, office_id, address) 
        VALUES ('".$faker->lastName."','".$faker->firstName."', '".$faker->randomElement($officeID)."' , '".$faker->address."')");
    }

    $employeeId = $pdo->query("SELECT id FROM employee")->fetchAll(PDO::FETCH_COLUMN);
    // Insert 500 rows of fake data to Transactions
    for ($i = 1; $i <= 500; $i++) {

        $insertQuery = $pdo->prepare("INSERT INTO transaction (employee_id, office_id, datelog, action, remarks, documentcode) 
        VALUES ('".$faker->randomeElement($employeeId)."','".$faker->randomElement($officeId)."', '".$faker->dateTimeThisDecade."' , '".$faker->randomElement([])."', '".$faker->sentence."', '".$faker->ean8."')");
    }

    echo "Data has been successfully inserted into the database.";

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>