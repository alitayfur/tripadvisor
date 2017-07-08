<?php
require('tripadvisor.php');


$ta = new tripadvisor();
//$data =$ta->getQuery('map','36.544493,31.987939');

//$data = $ta->getQuery('location','89575','','hotels');
//$data = $ta->getQuery('map','36.544493,31.987939');
//$data = $ta->getQuery('location_mapper','37.857257,27.247885',['q'=>'otel Carina']);
$data = $ta->getHotels('37.857257,27.247885','otel Carina');


setlocale(LC_TIME, "tr_TR.utf8",'turkish');
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta http-equiv="imagetoolbar" content="no">
<meta name="robots" content="noindex, follow">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no"/>
<title>TripAdvisor</title>

<link rel="stylesheet" type="text/css" media="screen" href="http://static.tacdn.com/css2/widget/cdswidget-property-v23716473622b.css">
</head>
<body id="WIDGET" style="margin:0;padding:0; background-color:#fff;">
<div id="CDSPOP" class="cdsPropDetail2014 cdsBx">
<div class="cdsData" style="">
<div class="cdsBranding">
<dl>
<dt>Seyhatsever Yorumlarını size sağlayan:</dt> <dd>
<img width="132px" src="http://www.tripadvisor.com.tr/img/cdsi/img2/branding/tripadvisor_logo_transp_340x80-20634-2.png" alt="TripAdvisor"/>
</dd>
</dl>
</div>
<div class="cdsComponent">
<div class="cdsSummary">
<dl>
<dt class="propertyName"><?=$data['name']?></dt>
<dd>
<address>
<span class="format_address"><span class="street-address" property="v:street-address"><?=$data['address_obj']['address_string']?></span></span>
</address>
</dd>
</dl>

<div class="cdsWrap">
<div class="cdsInformation">
<div class="cdsWrap">
<div class="taRating">
<img src="<?=$data['rating_image_url']?>" alt="<?=$data['rating']?>/5 yıldız" class="rsImg"/>
</div>
<div class="numReviews"> <?=$data['num_reviews']?> yorum </div>
<div class="cdsClear"></div>
    <div class="popIndex">
        <span class="ranking"><?=$data['ranking_data']['ranking_out_of']?> içerisinde <?=$data['ranking_data']['ranking']?>. sırada </span>
        <span>
        <?=$data['ranking_data']['geo_location_name']?> içindeki oteller
        </span>
    </div>
<div class="cdsBadges">
<? if (isset($data['awards'])):
	foreach($data['awards'] as $cdsBadge):
?>
 <div class="cdsBadge">
     <div class="cdsBadgeImage"><img src="<?=$cdsBadge['images']['small']?>"></div>
     <div class="cdsBadgeContents cdsBadgeContentsSingleLine">
        <div class="cdsBadgeTitle"><?=$cdsBadge['display_name']?></div>
     </div> 
 </div>
<?
   endforeach;
 endif;?>
</div>
</div>
</div>
</div><!--/ cdsInformation-->
</div><!--/ cdsWrap-->
<div class="cdsClear"></div>
</div><!--/ cdsSummary-->
<div class="cdsTrvlRtng" style="padding:0px 15px;">
<div class="cb">
<div class="cdsH18">TripAdvisor Seyahat Eden Puanı:</div> </div>
<div class="expect">
<? foreach ($data['subratings'] as $key => $expect):?>
    <div class="expectLine">
    <div class="expectText"><?=$expect['localized_name']?></div>
    <div class="expectScore">
    <img src="<?=str_replace('ss','s',$expect['rating_image_url'])?>" alt="<?=$data['num_reviews']?> yoruma göre <?=$expect['value']?>" class="rsImg"/>
    </div>
    <div class="cdsClear"></div>
    </div>
<? endforeach;?>
</div>
<div class="barRatings rightSide ">
<?
	$barRatings = ['Berbat','Kötü','Ortalama','Çok İyi','Mükemmel'];
	 for ($i=5;$i>0;$i--):
	 $barRating = $data['review_rating_count'][$i];
	 ?>
    <div class="barRatingLine">
        <div class="barRatingText"><?=$barRatings[$i-1]?></div>
        <div class="barRatingBox">
            <div style="width: 100px" class="barRatingBkg">
                <div style="width: <?=round(($data['num_reviews']/100)*$barRating)?>px;" class="barRatingImg"></div>
            </div>
        </div>
        <div class="barRatingTextGreen"><?=$barRating?></div>
    </div>
    <? endfor;?>

</div>


</div><!--/ cdsTrvlRtng-->

<dl id="CDSREVIEWS" style="padding:0px 15px;">
<dt class="cdsH18">Son Yorumlar:</dt>
<dd id="reviewsContainer">
<div id="PAGE_1" name="review_page">
<? foreach($data['reviews'] as $review):?>
<dl name="sortableReviewPair" id="rr5-20150112-3">
<dd name="sortableReview" id="r5-20150112-3" class="hReview ">
<div class="reviewBoxLeft">
<div class="attribution">
<div class="username"><?=$review['user']['username']?></div>
<div class="location">
<?=$review['user']['user_location']['name']?>
</div>
<div class="tripType">
<div class="tripTypeHeader">
Gezi türü: </div>
<?=$review['trip_type']?>
</div>
<div class="cdsClear"></div>
</div>
</div>
<div class="reviewBoxRight">
<div>
<div class="reviewTitle">
<?=$review['title']?>
</div>
<div class="reviewInfo">
<span class="rating">
<img src="<?=$review['rating_image_url']?>" alt="<?=$review['rating']?>/5 yıldız" class="rsImg"/>
</span>
<span class="date"><?=strftime("%d %b %y",strtotime($review['published_date']))?></span>
</div>
</div>
<div class="reviewBody">
<dl>
<dd style="display:block;" name="reviewText" id="rshort5"><?=$review['text']?>
</dd> 
 </dl>
</div>
</div>
<div class="cdsClear"></div>
</dd>
<? endforeach;?>
<div class="clear"></div>
<div id="PAGING_INFO_1" class="pagingInfo">
<div class="pagingLoc">1-<?=count($data['reviews'])?>/<?=$data['num_reviews']?> Yorum</div> <div class="cdsClear"></div>
</div>
</div> </dd>
</dl><!--/ CDSREVIEWS-->
<div class="cdsDisclaimer" style="padding:0px 15px;">
<p>Bu yorum bağımsız bir gezginin öznel görüşü olup TripAdvisor LLC şirketine veya ortaklarına ait değildir.</p> </div><!--/ cdsDisclaimer-->
<div class="cdsLegal" style="padding:0px 15px;">© 2016 TripAdvisor LLC</div><!--/ legal-->
</div><!--/ cdsComponent-->
</div><!--/ cdsData-->
</div><!--/ CDSPOP.cdsBx-->
</body><!--/ WIDGET-->
<!-- st: 23 dc: 0 sc: 5 -->
<!-- uid: VuP-kAoQH3IAAQ@ra8YAAAAx -->
</html>