<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('vendor/fpdf/fpdf.php'); // Путь к файлу fpdf.php
require('vendor/fpdi/src/autoload.php'); // Путь к файлу autoload.php
require('vendor/fpdi/src/Fpdi.php'); 

$servername = "localhost"; // Имя сервера базы данных
$username = "warsawexpo_badge"; // Имя пользователя базы данных
$password = "AMZYE(8jHz-7_-w9"; // Пароль пользователя базы данных
$dbname = "warsawexpo_badge"; // Имя базы данных


// Создаем подключение к базе данных
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверяем соединение
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Выбираем все строки с Status = 'new'
$sql = "SELECT * FROM `give_me_badge` WHERE `Status` = 'new'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Обновляем статус на 'ok' для всех найденных строк
    $updateSql = "UPDATE `give_me_badge` SET `Status` = 'ok' WHERE `Status` = 'new'";
    if ($conn->query($updateSql) === TRUE) {
        echo "Статус успешно обновлен на 'ok' для всех строк с Status = 'new'";
        
        // Получаем все строки в виде массива
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        foreach ($data as $row) {
            $id = $row['id'];
            $status = $row['Status'];
            $currentDate = $row['data_curent'];
            $category_fair = $row['www'];
            $category_pdftype = $row['typ_badge'];
            $name = $row['name'];
            $firm = $row['firm'];
            $email = $row['email'];
            $street = $row['ulica'];
            $city = $row['miasto'];
            $postalCode = $row['kod_pocztowy'];
            $country = $row['country'];
            $qrUrl = $row['qr_url'];


            $pdf_url = "https://warsawexpo.eu/assets/badge/local/files/" . $category_fair . "/" . $category_fair . "_" . $category_pdftype . ".pdf";
            if (filter_var($pdf_url, FILTER_VALIDATE_URL)) {
                echo $pdf_url;
            } else {
                echo "Error: Нет ссылки на PDF.\n";

            }


            $fontPath = 'https://warsawexpo.eu/Ubuntu-R.ttf'; 

            $pdf = new \setasign\Fpdi\Fpdi();
            // $pdf = new Fpdi();
            $tplId = $pdf->importPage(1); // Import the first page of the PDF template
            $pdf->AddPage();
            $pdf->useTemplate($tplId);

            $pdf->AddFont('Ubuntu', '', $fontPath);
            $pdf->SetFont('Ubuntu', '', 12);
            $pdf->SetTextColor(0, 0, 0);
            
            // Add the name
            $pdf->SetXY($pdf->GetPageWidth() / 2 - $pdf->GetStringWidth($name) / 2, 175);
            $pdf->Cell(0, 0, $name, 0, 1, 'C');
            
            // Add the firm
            $pdf->SetXY($pdf->GetPageWidth() / 2 - $pdf->GetStringWidth($firm) / 2, 160);
            $pdf->Cell(0, 0, $firm, 0, 1, 'C');

            $qrCodeImage = file_get_contents($qrUrl); // Get the QR code image content
            $pdf->Image('@' . $qrCodeImage, 175, 10, 120, 120); // Add QR code image to the PDF


            // Save the PDF with a specific filename format in the 'files' directory
            $pdfFilename = 'files/badge_' . $category_fair . '_' . $category_pdftype . '.pdf';
            $pdf->Output($pdfFilename, 'F');
            

            //проверка есть ли файл
            
            // Записываем данные в файл data.json
            $filename = 'data.json';
            file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
            echo "Данные успешно записаны в файл data.json\n";
            echo "\n";
        }
    } else {
        echo "Ошибка при обновлении статуса: \n" . $conn->error;
        echo "\n";
    }
} else {
    echo "Нет строк с Status = 'new'\n";
    echo "\n";
}

// Закрываем соединение с базой данных
$conn->close();
?>