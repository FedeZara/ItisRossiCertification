<?php

require 'php/phpspreadsheet/vendor/autoload.php';
require "coursesDB.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$coursesDB = new CoursesDB();

$courses = $coursesDB->getCourses();

$spreadsheet = new Spreadsheet();
$spreadsheet->getProperties()
    ->setCreator("Federico Zarantonello")
    ->setLastModifiedBy("Federico Zarantonello")
    ->setTitle("Risultati iscrizioni corsi")
    ->setSubject("Risultati iscrizioni corsi");

$i = 0;

foreach ($courses as $c) {
    $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, str_replace(['*', ':', '/', '\\', '?', '[', ']'], ' ', $c["name"]));
    $spreadsheet->addSheet($sheet, $i);
    $sheet->setCellValue('B10', 'Classe     ')
        ->setCellValue('C10', 'Cognome     ')
        ->setCellValue('D10', 'Nome     ')
        ->setCellValue('B4', 'Nome corso: ')
        ->setCellValue('B5', 'Prof.ssa: ')
        ->setCellValue('B6', 'Posti: ')
        ->setCellValue('B2', 'Infomazioni')
        ->setCellValue('C4', $c["name"])
        ->setCellValue('C5', $c["teacher_name"])
        ->setCellValue('B8', 'Iscritti');
    $sheet->getCell('C6')
        ->setValueExplicit(
            $c["num_students"] . "/" . $c["max_students"],
            \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
        );
    $sheet->getStyle('B2')->getFont()->setSize(16)->setBold(true);
    $sheet->getStyle('B8')->getFont()->setSize(16)->setBold(true);
    $students = $coursesDB->getStudentsFromCourseId($courses[$i]["course_id"]);
    $j = 11;
    foreach ($students as $s) {
        $sheet->setCellValue('B' . $j, $s["class"] . " ")
            ->setCellValue('C' . $j, $s["surname"] . " ")
            ->setCellValue('D' . $j, $s["name"] . " ");
        $j += 1;
    }
    $sheet->setAutoFilter('B10:D' . ($j - 1));
    $sheet->getStyle('B10:D10')->getFont()->setBold(true);
    $sheet->getColumnDimension('B')->setAutoSize(true);
    $sheet->getColumnDimension('C')->setAutoSize(true);
    $sheet->getColumnDimension('D')->setAutoSize(true);

    $i += 1;
}

$spreadsheet->removeSheetByIndex($i);

$fileName = 'results/Risultati ' . date('d-m') . ".xlsx";
$writer = new Xlsx($spreadsheet);
$writer->save($fileName);

echo $fileName;
