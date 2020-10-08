<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
class ScrapController extends Controller
{
	private $homedetail = [];
	private $images = [];
	private $text = [];
	private $address = [];
	private $homedetailprop = [];
	private $agentsdetails = [];
	private $product_details = [];
	private $desc = [];
	private $essential = [];
	private $footer = [];
    public function get_data(){

    	$client = new Client();

    	$homepage = $client->request('GET','https://www.viprealestate.com/idx/sabor/');

    	$page = $client->request('GET','https://www.viprealestate.com/listing-sabor/1473622-7196-e-loop-1604-san-antonio-tx-78233/');
    	
    	$homepage->filter('.mediaBody')->each(function($item){
    		array_push($this->homedetail, $item->filter('h3')->text());
    		array_push($this->homedetailprop, $item->filter('.property-type')->text());	
    		});

		$page->filter('.top-menu')->each(function($item){
    		array_push($this->address, $item->filter('h4')->text());
    		});

		$page->filter('.wrap-inner')->each(function($item){
    		array_push($this->agentsdetails, $item->text());
	    	});

        $page->filter('.mediaBodyStats > li')->each(function($item){
    		array_push($this->product_details, $item->text());
 			});
					
    	$page->filter('.slideset')->each(function($item){
    		array_push($this->text, $item->text());
    		array_push($this->images, $item->filter('img')->attr('src'));
	    	});

    	$page->filter('.remarks')->each(function($item){
    		array_push($this->desc, $item->filter('p')->text());
 			});

		$page->filter('.dataset > ul > li')->each(function($item){
    		array_push($this->essential, $item->filter('span')->text());
 			});

		$page->filter('.disclaimer')->each(function($item){
    		array_push($this->footer, $item->filter('p')->text());
 			});

    		$result = $this->returnResults();
    		return response($result , 200);
    }

    private function returnResults(){
    	$output = [];
    	$output['title'] = $this->homedetail[0];
    	$output['property_type'] = $this->homedetailprop[0];
    	$output['images'] = $this->images[0];
    	$output['text'] = $this->text[0];
    	$output['agent_details'] = $this->agentsdetails[0];
    	$output['price'] = $this->product_details[0];
    	$output['sqft'] = $this->product_details[1]; 
    	$output['Acres'] = $this->product_details[2]; 
    	$output['DOM'] = $this->product_details[3]; 
    	$output['product_details'] = $this->desc[0];
    	$output['MLS'] = $this->essential[0]; 
    	$output['Price'] = $this->essential[1]; 
    	$output['Square Footage'] = $this->essential[2]; 
    	$output['Acres105.31'] = $this->essential[3]; 
    	$output['TypeCommercial'] = $this->essential[4]; 
    	$output['Sub-Type'] = $this->essential[5]; 
    	$output['Status'] = $this->essential[6]; 
    	$output['Address'] = $this->essential[7]; 
    	$output['Area'] = $this->essential[8]; 
    	$output['City'] = $this->essential[9]; 
    	$output['Country'] = $this->essential[10]; 
    	$output['State'] = $this->essential[11]; 
    	$output['Zip Code'] = $this->essential[12]; 
    	$output['Utilities'] = $this->essential[13]; 
    	$output['Data Listed'] = $this->essential[14]; 
    	$output['Day on Markets'] = $this->essential[15]; 
    	$output['Zoning'] = $this->essential[16]; 
    	$output['Office'] = $this->essential[17];
    	$output['mis-Disclaimer'] = $this->footer[0]; 
    	$output['Disclaimer'] = $this->footer[1]; 
    	return $output;
    }
}
