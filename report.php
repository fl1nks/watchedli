<?php
require_once 'db.php'; // Подключение к базе данных
require_once 'vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Запрос данных из базы данных
$stmt = $pdo->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Создание отчета в Word
$phpWord = new PhpWord();
$section = $phpWord->addSection();
$section->addText('Отчет по пользователям', array('size' => 24));

$table = $section->addTable();
$table->addRow();
$table->addCell(2000)->addText('ID');
$table->addCell(2000)->addText('Имя');
$table->addCell(2000)->addText('Email');

foreach ($users as $user) {
    $table->addRow();
    $table->addCell(2000)->addText($user['id']);
    $table->addCell(2000)->addText($user['surname']);
    $table->addCell(2000)->addText($user['email']);
}

$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('export/users_report.docx');

// Создание отчета в Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Имя');
$sheet->setCellValue('C1', 'Email');

$row = 2;
foreach ($users as $user) {
    $sheet->setCellValue('A' . $row, $user['id']);
    $sheet->setCellValue('B' . $row, $user['surname']);
    $sheet->setCellValue('C' . $row, $user['email']);
    $row++;
}

$writer = new Xlsx($spreadsheet);
$writer->save('export/users_report.xlsx');

echo "Отчеты созданы успешно!";
