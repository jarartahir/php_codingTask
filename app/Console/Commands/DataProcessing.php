<?php

namespace App\Console\Commands;

use App\Models\Product;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DataProcessing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:data-processing {filePath : The path for your data file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to read data from files (e.g: XML, JSON) and Insert that data into Database';
    protected $connectionName;

    public function __construct()
    {
        parent::__construct();
        /*
         *this condition is used for testing purpose because the testing enviroment is only set for sqlite in-memory
         *so if test are running so it will set the connection for sqlite else it will set the connection
         * as it was set in .env->CONNECTION_NAME
         */
        if (app()->environment('testing')){
            $this->connectionName = 'sqlite';
        }else{
            $this->connectionName = env("DB_CONNECTION_NAME", 'default');
        }
    }
    /**
     * Execute the console command.
     */
    public function handle():void
    {
        try {

            $data = $this->readAndNormalizeData($this->argument('filePath'));
            $this->insert($data);
            $this->info('XML data processed and inserted into the database.');

        } catch (\Exception $e) {
            Log::channel('custom')->error($e->getMessage());
            $this->error('Error processing XML. Check the log for details.');
        }
    }

    /*
     * this function is used to insert data into DB it will take array data and loop through each array
     * and set each array into model and then save that model into DB
     *
     * @var array
     */
    public function insert($data):void
    {
        foreach ($data as $item) {
            $product_data = new Product();

            $product_data->entity_id = isset($item['entity_id']) ? (int)$item['entity_id']:null;
            $product_data->CategoryName = isset($item['CategoryName']) ? (string)$item['CategoryName'] : "";
            $product_data->sku = isset($item['sku']) ? (string)$item['sku'] : "";
            $product_data->name = isset($item['name']) ? (string)$item['name'] : "";
            $product_data->description = isset($item['description']) ? (string)$item['description'] : "";
            $product_data->shortdesc = isset($item['shortdesc']) ? (string)$item['shortdesc'] : "";
            $product_data->price = isset($item['price']) ? (float)$item['price'] : "";
            $product_data->link = isset($item['link']) ? (string)$item['link'] : "";
            $product_data->image = isset($item['image']) ? (string)$item['image'] : "";
            $product_data->brand = isset($item['Brand']) ? (string)$item['Brand'] : "";
            $product_data->Rating = isset($item['Rating']) ? (int)$item['Rating'] : 0;
            $product_data->CaffeineType = isset($item['CaffeineType']) ? (string)$item['CaffeineType'] : "";
            $product_data->Count = isset($item['Count']) ? (int)$item['Count'] : 0;
            $product_data->Flavored = isset($item['Flavored']) ? (string)$item['Flavored'] : "";
            $product_data->Seasonal = isset($item['Seasonal']) ? (string)$item['Seasonal'] : "";
            $product_data->Instock = isset($item['Instock']) ? (string)$item['Instock'] : "";
            $product_data->Facebook = isset($item['Facebook']) ? (int)$item['Facebook'] : 0;
            $product_data->IsKCup = isset($item['IsKCup']) ? (int)$item['IsKCup'] : 0;

            $product_data->setConnection($this->connectionName)->save();
        }

    }


    /*
     * this function will take the filePath and extract the extensiong from the string and by using extension
     * it will read the data and return the data array
     *
     * @var string
     * */
    public function readAndNormalizeData($filePath):array {

        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

        switch (strtolower($fileExtension)) {
            case 'json':
                $data = json_decode(file_get_contents($filePath), true)['item'];
                break;

            case 'xml':
                $data = $this->objectToArray(simplexml_load_file($filePath));
                break;


            // Add more cases for other file formats if needed

            default:
                throw new Exception("Unsupported file format: $fileExtension");
        }

        return $data;

    }

    /*
     * this function will take a object variable and convert that into array data
     *
     * @var objrct
     * */
    public function objectToArray($objectData): array {
        $data = [];

        foreach ($objectData->item as $item) {
            $productData = [
                'entity_id' => (string)$item->entity_id,
                'CategoryName' => (string)$item->CategoryName,
                'sku' => (string)$item->sku,
                'name' => (string)$item->name,
                'description' => (string)$item->description,
                'shortdesc' => (string)$item->shortdesc,
                'price' => (float)$item->price,
                'link' => (string)$item->link,
                'image' => (string)$item->image,
                'Brand' => (string)$item->Brand,
                'Rating' => (int)$item->Rating,
                'CaffeineType' => (string)$item->CaffeineType,
                'Count' => (int)$item->Count,
                'Flavored' => (string)$item->Flavored,
                'Seasonal' => (string)$item->Seasonal,
                'Instock' => (string)$item->Instock,
                'Facebook' => (int)$item->Facebook,
                'IsKCup' => (int)$item->IsKCup,
            ];

            $data[] = $productData;
        }

        return $data;
    }
}
