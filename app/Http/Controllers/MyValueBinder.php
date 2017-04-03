<?php 
namespace App\Http\Controllers;

use Maatwebsite\Excel\Classes\PHPExcel;

use PHPExcel_Cell;
use PHPExcel_Cell_DataType;
use PHPExcel_Cell_IValueBinder;
use PHPExcel_Cell_DefaultValueBinder;

class MyValueBinder extends PHPExcel_Cell_DefaultValueBinder implements PHPExcel_Cell_IValueBinder
{
    public function bindValue(PHPExcel_Cell $cell, $value = null)
    {
        $cell->setValueExplicit( $value, PHPExcel_Cell_DataType::TYPE_STRING );
        return true;

        // if (is_numeric($value))
        // {
        //     $cell->setValueExplicit($value, PHPExcel_Cell_DataType::TYPE_STRING);

        //     return true;
        // }

        // // else return default behavior
        // return parent::bindValue($cell, $value);
    }
}
