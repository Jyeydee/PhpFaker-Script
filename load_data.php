<?php
require 'vendor/autoload.php';

$faker = Faker\Factory::create('en_PH');
$host = 'localhost';  // Update your database host
$username = 'root';
$password = '';
$database = 'appdev';

try {
    // Create a PDO database connection
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Insert 50 rows of fake data to Office
    for ($i = 1; $i <= 50; $i++) {
        $country = 'Philippines';
        $insertQuery = $pdo->prepare("INSERT INTO office (name, contactnum, email_address, city, country, postal) 
        VALUES (?, ?, ?, ?, ?, ?)");
        $insertQuery->execute([$faker->company, $faker->phoneNumber, $faker->email, $faker->city, $country, $faker->postcode]);
    }

    // Fetch valid office_id values from the 'office' table
    $officeId = $pdo->query("SELECT id FROM office")->fetchAll(PDO::FETCH_COLUMN);

    // Insert 200 rows of fake data to Employee
    for ($i = 1; $i <= 200; $i++) {
        $insertQuery = $pdo->prepare("INSERT INTO employee (lastname, firstname, office_id, address) 
        VALUES (?, ?, ?, ?)");
        $insertQuery->execute([$faker->lastName, $faker->firstName, $faker->randomElement($officeId), $faker->address]);
    }

    $employeeId = $pdo->query("SELECT id FROM employee")->fetchAll(PDO::FETCH_COLUMN);

    // Define possible actions and document codes
    $actions = ['Action1', 'Action2', 'Action3'];
    $documentCodes = ['Code1', 'Code2', 'Code3'];

    // Insert 500 rows of fake data to Transactions
    for ($i = 1; $i <= 500; $i++) {
        $insertQuery = $pdo->prepare("INSERT INTO transaction (employee_id, office_id, datelog, action, remarks, documentcode) 
        VALUES (?, ?, ?, ?, ?, ?)");
        $insertQuery->execute([$faker->randomElement($employeeId), $faker->randomElement($officeId), $faker->dateTimeThisDecade->format('Y-m-d H:i:s'), $faker->randomElement($actions), $faker->sentence, $faker->ean8]);
    }

    echo "Data has been successfully inserted into the database.";

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
