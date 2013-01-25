<?php
$xml = simplexml_load_file('xml/copydeck-hana.xml');
if ($_GET['pageId']){
	$pageId = $_GET['pageId'];
} else {
	$pageId = 0;
}

if ($_GET['sectionId']){
	$sectionId = $_GET['sectionId'];
} else {
	$sectionId = 0;
}
if ($_GET['page']){
	$page_slug = $_GET['page'];
} else {
	$page_slug = "";
}
$primarySection = 0;

//if the default page in this section is not the first in the XML use this :  
/*
if ($_GET['page']){
	$page_slug = $_GET['page'];
	if ($page_slug == "undefined"){
		$page_slug = "travaasa-hana-overview";
	}
} else {
	$page_slug = "travaasa-hana-overview";
}
*/

?>
<?php
$count = 0;
$totalCount = 0;
$html = '<ul class="secondary_nav primarySection0">';
$rootCounter = 0;
foreach ($xml->hana->secondaryPages->page as $section) {
	
	//if the section hasnt been determined by the param, we need to see which section is for which page.
	//and also which Primary Section this page belongs to.
	if (!$_GET['sectionId']){
		if($section->meta->slug == $page_slug){
			$sectionId = $section['section'];
			$primarySection = $section['primarySection'];
		} 
	}
}
foreach ($xml->hana->secondaryPages->page as $section) {
  
	if ($section[secondaryNav] == "true"){
	 // if($section[primarySection] == $primarySection){
		$title = $section->title;
	
		
		//******  This currently only allows there to be children of section #1
		if ($section[section] == 4){
			$parentNav = "parentOfOne";
		} else {
			$parentNav = "";
		}
		
		if ($section[primarySection] == 4){
			$parentNav = "";
		}
		
		if ($section[parentId] == 4){ 
			$childNav = "childOfOne";
			
		} else {
			$childNav = "";
		}

		
		if ($count == $sectionId){
			$selected = 'selected';
			//If the page hasn't been determined by the param, we need to see which page is the landing page for the particular section.
			if (!$_GET['pageId']){
				$pageId = $section[id];
				
			}
		} else {
			$selected = '';
		}
	
		
		if ($section[root] != "true"){
			$html .= '<li class="'.$parentNav.$childNav.'"><a href="/hana/'.$section->meta->slug.'" id="'.$section[id].'" class="' .$selected .'" rel="/'.$section->meta->slug.'">'.$title.'<img src="/images/nav/icon_selected-arrow.png" /></a></li>';
		} else {
			if ($rootCounter > 0){
//				if ($rootCounter == 1){
//					$html .= '<li class=""><a href="/hana/hana-experiences">Sea Ranch Accommodations</a></li>';
//					$html .= '<li class=""><a href="/hana/hana-garden-view-suites-overview">Garden View Accommodations</a></li>';
//				}
				$html .= '</ul><ul class="secondary_nav primarySection'.$rootCounter.'">';
			} 
			$html .= '<li class="top '.$parentNav.'"><a href="/hana/'.$section->meta->slug.'" id="'.$section[id].'" class="'.$selected.'" rel="/'.$section->meta->slug.'">'.$title.'<img src="/images/nav/icon_selected-arrow.png" /></a></li>';
			
			$rootCounter++;
		}
		$count++;
	  // }//end primary section = the same primary section of the loading page.
	} // end secondaryNav = true
	$totalCount++;
 	

}
?>
<?php
$countx = 0;
$imageArray = "";
$countPfd = 0;
//$pdfDownload = array();
foreach ($xml->hana->secondaryPages->page as $page) {

	if (($_GET['page'] && $page->meta->slug ==  $page_slug) || (!$_GET['page'] && $page[id] ==  $pageId)){
		$imgSrc = $page->mainImg;
		$imageCaption = $page->imageCaption;
		$header = $page->h1;
		$copy = $page->copy;
		$content = '<h1>'.$header.'</h1>'.$copy;
		$pdfDownloads = $page->pdf_downloads->pdf->title;
		$pageTitle = $page->meta->pageTitle;
		$metaKeywords = $page->meta->keywords;
		$metaDescription = $page->meta->description;
		if($pdfDownloads != ""){
			$pdf_downloads_html = '<div id="download_pdf_frame">';

			foreach($page->pdf_downloads->pdf as $pdf_download){
				$pdf_downloads_html .= '<div class="download_pdf">';
				$pdf_downloads_html .= '<p><a href="'.$pdf_download->url.'" target="_blank" class="pdf_download" rel="'.$pdf_download->title.'">'.$pdf_download->title.'<img src="/images/icon_pdf_download_arrow.png" /></a></p>';
				$pdf_downloads_html .= '</div><!-- .download_pdf -->';	
			}
			$pdf_downloads_html .= '</div><!-- #download_pdf_frame -->';
		}
	}
	
	$imageArray .= "'/".$page->mainImg."'";
	if ($countx < $totalCount){
		$imageArray .= ",";
	}

}

?>
<?php include("php/header_agentTest.include.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
	<head profile="http://www.w3.org/2005/10/profile">
	<link rel="icon" 
	      type="image/png" 
	      href="/images/favicon.png" />
	<title><?php echo $pageTitle; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="<?php echo $metaKeywords; ?>" />
	<meta name="description" content="<?php echo $metaDescription; ?>" />
	
	<link rel="stylesheet" href="/css/global.css"/>
	<link rel="stylesheet" href="/css/anytimec.css"/>
	<link rel="stylesheet" href="/css/prettyGallery.css" type="text/css" media="screen" charset="utf-8" />
	<link rel="stylesheet" href="/css/jquery-ui-1.8.8.custom.css" type="text/css" />
    <link rel="stylesheet" href="/js/fancybox/source/jquery.fancybox.css?v=2.0.4" type="text/css" media="screen" />
    <link rel="stylesheet" href="/js/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=2.0.4" type="text/css" media="screen" />
    <link rel="stylesheet" href="/fancybox/source/helpers/jquery.fancybox-buttons.css?v=2.0.4" type="text/css" media="screen" />
	<!--[if IE]><link rel="stylesheet" href="/css/ie.css" type="text/css" /> <![endif]-->
	<?php echo $ipadStyle; ?>
	<!-- begin fonts.com code -->
    <script type="text/javascript" src="http://fast.fonts.com/jsapi/855e233c-3953-43b0-abfe-6551b0031c94.js"></script>
    <!-- end fonts.com code -->
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.7/jquery-ui.min.js"></script>
	<script type="text/javascript" src="/js/jquery.livequery.js"></script>
	
	<script type="text/javascript" src="/js/jquery.address-1.3.1.min.js?autoUpdate=1&crawling=1&history=1&tracker=trackFunction&strict=0&wrap=0"></script>
	
	<script type="text/javascript" src="/js/anytimec.js"></script>
	
	<script src="/js/jquery.cookie.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="/js/jquery.jcarousel.min.js"></script>
	<script type="text/javascript" src="/js/jquery.bgfix.js"></script>
	<script type="text/javascript" src="/js/thumbgallery.js"></script>
	<script type="text/javascript" src="/js/application.js"></script>
    <script type="text/javascript" src="/js/fancybox/source/jquery.fancybox.js?v=2.0.4"></script>
    <script type="text/javascript" src="/js/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=2.0.4"></script>
    <script type="text/javascript" src="/js/fancybox/source/helpers/jquery.fancybox-buttons.js?v=2.0.4"></script>
    <script type="text/javascript" src="/js/jquery.easing.compatibility.js"></script>
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function () {
		$('a.fancy').fancybox();
		$('a.fancyajax').fancybox({type: 'ajax'}); 
			$('#background').bgfix();
		});
		var split_path;
	
		if (window.location.pathname != "/hana/"){
			if (window.location.pathname != "/hana"){
					if(!window.location.hash){
							split_path = window.location.pathname.split('/');
						//	if (split_path[2] != "austin-experiences"){
					  			window.location.href = 'http://'+window.location.host+'/hana/#/'+split_path[2];
						//	}
						}
			}
		}
	
	</script>
    <!-- start Typekit -->
    <script type="text/javascript" src="http://use.typekit.com/ypk4oxw.js"></script>
    <script type="text/javascript">try{Typekit.load();}catch(e){}</script>
    <!-- end Typekit -->
    <!-- start Hana Conversion --><script type='text/javascript'>
	// Conversion Name: Travaasa Hana Home Page
	var ebRand = Math.random()+'';
	ebRand = ebRand * 1000000;
	//<![CDATA[ 
	document.write('<scr'+'ipt src="HTTP://bs.serving-sys.com/BurstingPipe/ActivityServer.bs?cn=as&amp;ActivityID=213438&amp;rnd=' + ebRand + '"></scr' + 'ipt>');
	//]]>
	</script>
	<noscript>
	<img width="1" height="1" style="border:0" src="HTTP://bs.serving-sys.com/BurstingPipe/ActivityServer.bs?cn=as&amp;ActivityID=213438&amp;ns=1"/>
	</noscript>
	<!-- end Hana Conversion -->
	</head>
	<body class="hana">
	<!-- start IMI -->
	<script type="text/javascript">
	document.write(unescape('%3Cscript src="' + document.location.protocol + '//d1ivexoxmp59q7.cloudfront.net/imi/live.js" type="text/javascript"%3E%3C/script%3E'));
	</script>
	<!-- end IMI -->
	<!-- start Hana Retargeting -->
	<script type="text/javascript">
	// Retargeting Tag Name: Travaasa Hana  Home Page Retargeting
	// The retargeting Tags should be placed at the top of the <BODY> section of the HTML page.
	var ebRand = Math.random()+ ' ';
	ebRand = ebRand * 1000000;
	//<![CDATA[
	document.write('<scr'+'ipt src="HTTP://bs.serving-sys.com/BurstingPipe/ActivityServer.bs?CN=TT&amp;TID=4096&amp;AdvertiserID=52437&amp;TKV1=z&amp;rnd=' + ebRand + '"></scr' + 'ipt>');
	//]]>
	</script>
	<noscript>
	<img width="1" height="1" style="border:0" src="HTTP://bs.serving-sys.com/BurstingPipe/ActivityServer.bs?CN=TT&amp;TID=4096&amp;AdvertiserID=52437&amp;TKV1=z&amp;ns=1"/>
	</noscript>
	<!-- end Hana Retargeting -->
		<div id="page_content">
			<div class="content_wrapper">
				<div id="header_frame">
					<div id="logo">
						<a href="/home/"><img src="/images/global/logo_maui.png" height="157" /></a>
					</div><!-- #logo -->
					<div id="reservation_cta">
						<p class="reservation_title">1-855-TO-TRAVAASA  (1-855-868-7282)</p>
                                                <div id="book-buttons">
                                                <div id="book-hana"><a href="https://www.phgsecure.com/IBE/bookingRedirect.ashx?propertyCode=HNMHM&numberOfAdults=2" target="_blank" onclick="_gaq.push(['_link', 'https://www.phgsecure.com/IBE/bookingRedirect.ashx?propertyCode=HNMHM&numberOfAdults=2']); return false;">Book Hana</a></div>
						<div id="book-austin"><a href="https://www.phgsecure.com/IBE/bookingRedirect.ashx?propertyCode=AUSTC" target="_blank" onclick="_gaq.push(['_link', 'https://www.phgsecure.com/IBE/bookingRedirect.ashx?propertyCode=AUSTC']); return false;">Book Austin</a></div>
                                                <div class="clearbox"></div>
                                                <div id="getonthelist"><a href="http://www.data2gold.com/gallery/travaasa/eClub/eClub.html">Get on our email list</a></div>
                                                </div>
					</div><!-- #reservation_cta -->
				</div><!-- #header_frame -->
				<div class="clear"></div>
				<div id="main_nav">
					<ul>
<!--						<li class="nav0"><a href="/home/">Home</a></li>-->
						<li class="nav1"><a href="/austin">Travaasa Austin</a></li>
						<li class="nav2"><a class="selected" href="/hana">Travaasa H&#257;na</a></li>
						<li class="nav3"><a href="/experiences">Experiences</a></li>
						<li class="nav4"><a href="/about-us">About Travaasa</a></li>
						<li class="nav5"><a href="/contact-us">Contact Us</a></li>
						
					</ul>
				</div><!-- #main_nav -->
				<div class="hide_content_button_container">
					<a href="#" class="hide_content"><img src="/images/btn_hide_content.png" alt="X" /></a>
				</div><!--hide_content_button_container-->
				<div id="top_960"></div><!-- #top_960 -->
				<div id="content_960">
						<script type="text/javascript">
						var pageId = <?=$pageId?>;
						var sectionId = <?=$sectionId?>;
						var page_slug = "<?=$page_slug?>";
						</script>
					<div id="secondary_nav_frame">
							<?php echo $html; ?>
							</ul>
				
							<div class="pdf_downloads_html_wrapper">
							<?php echo $pdf_downloads_html; ?>
							</div><!-- pdf_downloads_html_wrapper-->
					</div><!-- #secondary_nav -->
					<div id="main_content_frame">
						<div id="main_image">
						
						
					
							<img src="/<?php echo $imgSrc; ?>" class="mainimg" width="729" />
							<?php if($imageCaption != ""):?>
							<div id="main_image_caption">
								<p><?php echo $imageCaption; ?></p>
							</div>
							<?php else: ?>
							<div id="main_image_caption" style="display:none">
								<p></p>
							</div>
							<?php endif; ?>
						</div><!-- #main_image -->
						<div class="clear"></div>
						<div id="slideshow_frame2">
							<div id="slideshow">
								<ul class="gallery">
							
								</ul>
							</div><!-- #slideshow -->
						</div><!-- #slideshow_frame2 -->
						
						<div class="clear"></div>
						<div class="main_content_top"></div>
						<div class="main_content_content">
							<?php echo $content; ?>
						</div><!-- .main_content_content -->
						<div class="main_content_bottom"></div>
					</div><!-- #main_content_frame -->
					<div class="clear"></div>
					
				</div><!-- #content_960 -->
				<div id="bottom_960"></div><!-- #bottom_960 -->
				<div id="page_level_footer">
					<p>Call 855-TO-TRAVAASA (855-868-7282) to book your stay.</p>
					<div class="clear"></div>
				</div><!-- #page_level_footer -->
				<div class="clear"></div>
			</div><!-- .content_wrapper -->
			<div class="clear"></div>
		</div><!-- #page_content -->
		<div class="clear"></div>
		<script>
		var bg_image = new Array();
		var bg_caption = new Array();
		<?php 
			$dex = 0;
			foreach($xml->backgroundImages->image as $bg_image):
		?>	
			bg_image[<?php echo $dex; ?>] ='<?php echo $bg_image->src; ?>';
			bg_caption[<?php echo $dex; ?>] ='<?php echo str_replace("\n", "", $bg_image->caption); ?>';
			<? $dex++; endforeach; ?>
		</script>
		<div id="background" style="background-image:url(/<?php echo $xml->backgroundImages->image->src; ?>)">
		</div><!-- #background -->

		<?php include("php/footer_social.include.php"); ?>
        
	<script type="text/javascript" src="/js/jquery.cycle.all.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				<?php if(!$ipad):?>
					$([<?php //echo $imageArray ?>]).preload();

					$([<?php //echo $thumbNailArray ?>]).preload();
				<?endif?>
				if (!$("ul.gallery li").size()){
					$("#slideshow").hide();
				}
				$("ul.secondary_nav").hide()//hide all to start
				show_correct_secondary_nav(<?php echo $primarySection ?>);
				
			});
		</script>
		<script type="text/javascript">
setTimeout(function(){var a=document.createElement("script");
var b=document.getElementsByTagName("script")[0];
a.src=document.location.protocol+"//dnn506yrbagrg.cloudfront.net/pages/scripts/0011/4602.js?"+Math.floor(new Date().getTime()/3600000);
a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b)}, 1);
		</script>
		</body>
	</html>
