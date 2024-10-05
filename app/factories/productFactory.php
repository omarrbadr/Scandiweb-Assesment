<?php
//require_once($_SERVER['DOCUMENT_ROOT'] . '/Scandiweb/app/Models/dvd.php');
//require_once($_SERVER['DOCUMENT_ROOT'] . '/Scandiweb/app/Models/book.php');
//require_once($_SERVER['DOCUMENT_ROOT'] . '/Scandiweb/app/Models/furniture.php');
namespace App\Factories;

use App\Models\DVD;
use App\Models\Book;
use App\Models\Furniture;
class ProductFactory{
    public static function create($type, $data){
        switch ($type) {
            case 'D':
                return new DVD($data['sku'], $data['name'], $data['price'], $data['size']);
            case 'B':
                return new Book($data['sku'], $data['name'], $data['price'], $data['weight']);
            case 'F':
                return new Furniture($data['sku'], $data['name'], $data['price'], $data['length'], $data['width'], $data['height']);
            default:
                throw new \Exception("Invalid product type.");
        }
    }
}
