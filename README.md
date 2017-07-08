
# TRIPADVISOR API SAMPLE CLASS WITH PHP 

![tripadvisor](https://static.tacdn.com/img2/langs/tr/branding/rebrand/TA_logo_primary_v2.svg)

# API Description
Approved users of the TripAdvisor Content API can access the following business details for accommodations, restaurants, and attractions:

Location ID, name, address, latitude & longitude
Read reviews link, write-a-review link
Overall rating, ranking, subratings, awards, the number of reviews the rating is based on, rating bubbles image
Price level symbol, accommodation category/subcategory, attraction type, restaurant cuisine(s)

# Config
  
     private $config = [
		'root'       => 'data',  // query cache folder
		'cache_time' => (60*24), // query cache time
		'key'        => '',      // partner key
		'v'          => '2.0',   // version
		'url'        => 'http://api.tripadvisor.com/api/partner/',
		'comment'    => true
	];

# Using
getQuery($template,$value,$query='',$type='')

$template = (map|location|location_mapper)   [Documentation](https://developer-tripadvisor.com/content-api/documentation/)

$type     = (hotels|restaurants|Attractions) [Content API](https://developer-tripadvisor.com/content-api/business-content/categories-subcategories-and-types/)

     $ta   = new tripadvisor();                  
     $data = $ta->getQuery('map','36.544493,31.987939');
     $data = $ta->getQuery('location','89575','','hotels');
     $data = $ta->getQuery('location_mapper','37.857257,27.247885',['q'=>'otel Carina']);
     
     $data = $ta->getHotels('37.857257,27.247885','otel Carina');
