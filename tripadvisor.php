<?php

class tripadvisor {
	private $config = [
		'root'       => 'data',
		'cache_time' => (60*24),
		'key'        => 'df87a091ea2941e6a7f6c850c574659d',
		'v'          => '2.0',
		'url'        => 'http://api.tripadvisor.com/api/partner/',
		'comment'    => true
	];
	
	public  $cache = '';
	public  $debug = '';
	public  $last  = '';
	
	
	public function check($url){
		 $url = $this->config['root'].'/'.md5($url).'.json';
		 if (file_exists($url)) {
		  $this->debug =@file_get_contents($url);
		  return false; } 
		return true;
		
		}
		
	public function getCurl($url){
		    $this->last=$url;
			if ($this->debug=='' or $this->check($url)):
		
				$ch = curl_init ();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);				
			      $this->cache['json'][$this->last]   = curl_exec($ch);					
			      $this->cache['decode'][$this->last] = json_decode($this->cache['json'][$this->last],true);
				  
			      if (!isset($this->cache['decode'][$this->last]['error'])):
						$file = fopen($this->config['root'].'/'.md5($url).'.json','w');
						fwrite($file,$this->cache['json'][$this->last]);
						fclose($file);
					elseif (file_exists($this->config['root'].'/'.md5($url).'.json')):
						$this->debug =@file_get_contents($url);
						$this->cache['json'][$this->last] = $this->debug;						
				  endif;
				
				 $this->cache['decode'][$this->last] = json_decode($this->cache['json'][$this->last],true);
				curl_close($ch);	
			
			else:
			 $this->cache['json'][$this->last] = $this->debug;
			 $this->cache['decode'][$this->last] = json_decode($this->cache['json'][$this->last],true);
			endif;
			
			return $this->cache['decode'][$this->last];
			
		}
		
  /*
  	query some keywords 
		q			 => some string text about of searc
		distance	 => 0.1
  
  */	
		
	public function getQuery($template,$value,$query='',$type=''){	
		if ($query!='') $query = '&'.http_build_query($query);
		$this->config['type']     = ($type=='')?'':$type;
		$this->config['template'] = $template;	
		$this->config['value']    = $value;	
		if ($template=='location_mapper' and $this->config['type']=='') $this->config['type']='hotels';	
		foreach($this->config as $key => $val){
			 $arrKeys[] = '{'.$key.'}';
			 $arrVals[] = $val;
	     }
		 
		$pattern=[
		'location_mapper' => '{url}{v}/{template}/{value}?key={key}-mapper&category={type}'.$query,
		'default'         => '{url}{v}/{template}/{value}/{type}?key={key}'.$query];
	
	    $this->getCurl(str_replace($arrKeys,$arrVals,((isset($pattern[$template]))?$pattern[$template]:$pattern['default'])));
		if ($this->config['comment']):
	    return $this->{'get'.(($this->config['type']=='')?'':'Detail').'Formatter'}();	
		else:
		return $this->cache['decode'][$this->last];
		endif;
		}
	private function getFormatter()	{
		$json   = $this->cache['decode'][$this->last];
		$return = '';
		if ($this->config['template']=='map'):
		return $this->getDetailFormatter();
		else:
			$row = $json;
			$return = [
				'isim'         => $row['name'],
				'yorum'        => $row['num_reviews'],
				'yorum_detay'  => $row['trip_types'],
				'yorumlar'     => $row['reviews'],
				'puan'     	   => $row['rating'],
				'puanlama'     => $row['review_rating_count'],
				'puan_detay'   => $row['subratings'],
				'konum_id'     => $row['ranking_data']['geo_location_id'],
				'konum'	       => $row['ranking_data']['geo_location_name'],				
				'location_id'  => $row['location_id'],
				'sıralama'     => [
									'toplam' => $row['ranking_data']['ranking_out_of'],
									'sıra'   => $row['ranking_data']['ranking']
									],
				'harita_x'	   => $row['latitude'],
				'harita_y'	   => $row['longitude'],
				'resim'        => $row['rating_image_url'],
				'site_url'     => $row['write_review'],
				'resim_url'    => $row['see_all_photos'],
				
			];
			

		endif;
		return $return;
		}
		
	private function getDetailFormatter() {
		$json   = $this->cache['decode'][$this->last];	
		$return = [];
		if (!isset($json['data'])) return $return;
		if (($this->config['template']=='map') or ($this->config['template']=='location')):
			foreach($json['data'] as $row):
			$row['distance'] = (isset($row['distance']))?$row['distance']:'';
			
			$return[] = [
				'isim'         => $row['name'],
				'turu'         => $row['category']['name'],
				'distance'     => $row['distance'],
				'yorum'        => $row['num_reviews'],
				'puan'     	   => $row['rating'],
				'konum_id'     => $row['ranking_data']['geo_location_id'],
				'konum'	       => $row['ranking_data']['geo_location_name'],				
				'location_id'  => $row['location_id'],
				'sıralama'     => [
									'toplam' => $row['ranking_data']['ranking_out_of'],
									'sıra'   => $row['ranking_data']['ranking']
									],
				'harita_x'	   => $row['latitude'],
				'harita_y'	   => $row['longitude'],
				'resim'        => $row['rating_image_url'],
				'api_url'      => $row['api_detail_url'],
				'site_url'     => $row['write_review'],
				'resim_url'    => $row['see_all_photos'],
				
			];
			
			
			endforeach;
		else:
		  foreach ($json['data'] as $row):
		  	$return[] = [
				'isim'         => $row['name'],
				'distance'     => $row['distance'],
				'location_id'  => $row['location_id']
			];
		  endforeach;
		endif;
		return $return;
		}
  
  
  	public function getHotels($values,$q) {
			$data = $this->getQuery('location_mapper',$values,['q'=>$q,'lang'=>'tr']);
			if (!isset($data[0]['location_id'])) return false;
			$this->config['comment'] = false;
			return $this->getQuery('location',$data[0]['location_id'],['lang'=>'tr']);		
			
		}
  
}

?>
