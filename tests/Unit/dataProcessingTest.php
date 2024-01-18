<?php

namespace Tests\Unit;

use App\Console\Commands\DataProcessing;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class dataProcessingTest extends TestCase
{
    use RefreshDatabase; //don't use it with real database it is used for making memory database
    private $xmlFilePath;
    private $jsonFilePath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->xmlFilePath = dirname(__DIR__).'\Datafiles\xmlData.xml';
        $this->jsonFilePath = dirname(__DIR__).'\Datafiles\jsonData.json';

    }
    /*
     * this test is written to check readAndNormalizeData function return type. if a data is returned not in array type the this test will get fail
     * */
    public function testProcessXmlType()
    {
        $dataProcessing = new DataProcessing();
        $data = $dataProcessing->readAndNormalizeData($this->xmlFilePath);

        $this->assertIsArray($data);
    }

    /*
     * This below two tests are written to check that if Xml and json data provided is properly inserted in DB
     * and also it will check the value of each column of first record.
     * */
    public function testDBInsertXMLData(){
        $dataProcessing = new DataProcessing();
        $data = $dataProcessing->readAndNormalizeData($this->xmlFilePath);

        $dataProcessing->insert($data);

        $result_count = DB::table('products')->count();
        $result_data = DB::table('products')->first();

        $this->assertEquals(2, $result_count);
        $this->assertEquals(340, $result_data->entity_id);
        $this->assertEquals('Green Mountain Ground Coffee', $result_data->CategoryName);
        $this->assertEquals('20', $result_data->sku);
        $this->assertEquals('Green Mountain Coffee French Roast Ground Coffee 24 2.2oz Bag', $result_data->name);
        $this->assertEquals('abc', $result_data->description);
        $this->assertEquals('Green Mountain Coffee French Roast Ground Coffee 24 2.2oz Bag steeps cup after cup of smoky-sweet, '.
        'complex dark roast coffee from Green Mountain Ground Coffee.', $result_data->shortdesc);
        $this->assertEquals(41.6, $result_data->price);
        $this->assertEquals('http://www.coffeeforless.com/green-mountain-coffee-french-roast-ground-coffee-24-2-2oz-bag.html', $result_data->link);
        $this->assertEquals('http://mcdn.coffeeforless.com/media/catalog/product/images/uploads/intro/frac_box.jpg', $result_data->image);
        $this->assertEquals('Green Mountain Coffee', $result_data->Brand);
        $this->assertEquals(0, $result_data->Rating);
        $this->assertEquals('Caffeinated', $result_data->CaffeineType);
        $this->assertEquals(24, $result_data->Count);
        $this->assertEquals('No', $result_data->Flavored);
        $this->assertEquals('No', $result_data->Seasonal);
        $this->assertEquals('Yes', $result_data->Instock);
        $this->assertEquals(1, $result_data->Facebook);
        $this->assertEquals(1, $result_data->IsKCup);
    }

    public function testDBInsertJsonData(){
        $dataProcessing = new DataProcessing();
        $data = $dataProcessing->readAndNormalizeData($this->jsonFilePath);

        $dataProcessing->insert($data);

        $result_count = DB::table('products')->count();
        $result_data = DB::table('products')->first();

        $this->assertEquals(2, $result_count);
        $this->assertEquals(340, $result_data->entity_id);
        $this->assertEquals('Green Mountain Ground Coffee', $result_data->CategoryName);
        $this->assertEquals('20', $result_data->sku);
        $this->assertEquals('Green Mountain Coffee French Roast Ground Coffee 24 2.2oz Bag', $result_data->name);
        $this->assertEquals('abc', $result_data->description);
        $this->assertEquals('Green Mountain Coffee French Roast Ground Coffee 24 2.2oz Bag steeps cup after cup of smoky-sweet, '.
            'complex dark roast coffee from Green Mountain Ground Coffee.', $result_data->shortdesc);
        $this->assertEquals(41.6, $result_data->price);
        $this->assertEquals('http://www.coffeeforless.com/green-mountain-coffee-french-roast-ground-coffee-24-2-2oz-bag.html', $result_data->link);
        $this->assertEquals('http://mcdn.coffeeforless.com/media/catalog/product/images/uploads/intro/frac_box.jpg', $result_data->image);
        $this->assertEquals('Green Mountain Coffee', $result_data->Brand);
        $this->assertEquals(0, $result_data->Rating);
        $this->assertEquals('Caffeinated', $result_data->CaffeineType);
        $this->assertEquals(24, $result_data->Count);
        $this->assertEquals('No', $result_data->Flavored);
        $this->assertEquals('No', $result_data->Seasonal);
        $this->assertEquals('Yes', $result_data->Instock);
        $this->assertEquals(1, $result_data->Facebook);
        $this->assertEquals(1, $result_data->IsKCup);
    }
}
