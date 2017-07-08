
# TRIPADVISOR API SAMPLE CLASS WITH PHP 

![tripadvisor](https://static.tacdn.com/img2/langs/tr/branding/rebrand/TA_logo_primary_v2.svg)

# Config
  
    $ private $config = [
		'root'       => 'data',  // query cache folder
		'cache_time' => (60*24), // query cache time
		'key'        => '',      // partner key
		'v'          => '2.0',   // version
		'url'        => 'http://api.tripadvisor.com/api/partner/',
		'comment'    => true
	];

# Using

    $ $ta = new tripadvisor();
    
    $ $data = $ta->getQuery('map','36.544493,31.987939');
    
    $ $data = $ta->getQuery('location','89575','','hotels');
    
    $ $data = $ta->getQuery('location_mapper','37.857257,27.247885',['q'=>'otel Carina']);
    
    $ data  = $ta->getHotels('37.857257,27.247885','otel Carina');
