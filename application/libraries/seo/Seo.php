<?php
class Seo
{
  private $metas = array(); // for meta tags
  
  function __construct()
  {
    // defaults
    $CI =& get_instance();    
      // main
    $this->metas['charset'] = config_item('charset');
    $this->metas['title'] = LIVE_DOMAIN_PATH;
    $this->metas['site'] = LIVE_DOMAIN_PATH;
    $this->metas['description'] = '';
    $this->metas['keywords'] = '';
    // mobile
    $this->metas['format-detection'] = 'telephone=no';
    $this->metas['apple-mobile-web-app-capable'] = 'yes';
    $this->metas['viewport'] = 'width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no, minimal-ui';
    // social
    $this->metas['og:type'] = 'website';
    $this->metas['fb:app_id'] = $CI->lang->line('global_fb_app_id');
      // verifications
    if(defined('GOOGLE_SITE_VERIFICATION')) $this->metas['google-site-verification'] = GOOGLE_SITE_VERIFICATION;
    if(defined('BING_SITE_VERIFICATION')) $this->metas['msvalidate.01'] = BING_SITE_VERIFICATION;

  }

  public function define($parameter = NULL, $value=NULL)
  {
    if($parameter === NULL OR $value === NULL) return FALSE;
    $value = strip_tags(preg_replace( "/\r|\n/", "",$value));
    // defino tags especiales
    switch ($parameter){
      case 'title':
        $this->metas['og:title'] = $value;
        $this->metas['twitter:title'] = $value;
        $this->metas['itemprop:name'] = $value;
        $this->metas[$parameter] = $value;
        break;
      case 'description':
        $this->metas['og:description'] = substr($value,0,200);
        $this->metas['twitter:description'] = substr($value,0,200);
        $this->metas[$parameter] = $value;
        break;
      case 'thumb':
        $dimensiones = @getimagesize($value);
        $this->metas['og:image'] = $value;
        $this->metas['og:image:width'] = $dimensiones[0];
        $this->metas['og:image:height'] = $dimensiones[1];
        $this->metas['twitter:image:width'] = $dimensiones[0];
        $this->metas['twitter:image:height'] = $dimensiones[1];
        $this->metas['twitter:image'] = $value;
        $this->metas['twitter:card'] = 'summary_large_image';
        break;
      case 'site':
        $this->metas['twitter:site'] = '@eDairyMarket_'.ucfirst(language_current('site'));
        $this->metas['twitter:creator'] = '@eDairyMarket_'.ucfirst(language_current('site'));
        break;
      case 'canonical':
      $this->metas['canonical'] = $value;
      $this->metas['og:url'] = $value;
        break;
    }
  }

  public function printSeo() 
  {
    foreach($this->metas as $meta => $value)
    {
      if      ($meta === 'title') echo '<title>'.$value.'</title>';
      elseif  ($meta === 'charset')  echo '<meta charset="'.$value.'">';
      elseif  ($meta === 'canonical')  echo '<link rel="canonical" href="'.$value.'">';
      elseif  (preg_match('/(^og:)|(^fb:)/',$meta)) echo '<meta property="'.$meta.'" content="'.$value.'" />';
      elseif  (preg_match('/(^itemprop:)/',$meta)) echo '<meta itemprop="'.$meta.'" content="'.preg_replace('/(^itemprop:)/','',$mvalueeta).'" />';
      else echo '<meta name="'.$meta.'" content="'.$value.'" />';
      echo "\r\n";
    }
  }
  
}

/*
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@nytimes">
<meta name="twitter:creator" content="@SarahMaslinNir">
<meta name="twitter:title" content="Parade of Fans for Houston’s Funeral">
<meta name="twitter:description" content="NEWARK - The guest list and parade of limousines with celebrities emerging from them seemed more suited to a red carpet event in Hollywood or New York than than a gritty stretch of Sussex Avenue near the former site of the James M. Baxter Terrace public housing project here.">
<meta name="twitter:image" content="http://graphics8.nytimes.com/images/2012/02/19/us/19whitney-span/19whitney-span-articleLarge.jpg">

<!-- Update your html tag to include the itemscope and itemtype attributes. -->
<html itemscope itemtype="http://schema.org/Article">

<!-- Place this data between the <head> tags of your website -->
<title>Page Title. Maximum length 60-70 characters</title>
<meta name="description" content="Page description. No longer than 155 characters." />

<!-- Google Authorship and Publisher Markup -->
<link rel="author" href=" https://plus.google.com/[Google+_Profile]/posts"/>
<link rel="publisher" href=” https://plus.google.com/[Google+_Page_Profile]"/>

<!-- Schema.org markup for Google+ -->
<meta itemprop="name" content="The Name or Title Here">
<meta itemprop="description" content="This is the page description">
<meta itemprop="image" content=" http://www.example.com/image.jpg">

<!-- Open Graph data -->
<meta property="og:title" content="Title Here" />
<meta property="og:type" content="article" />
<meta property="og:url" content=" http://www.example.com/" />
<meta property="og:image" content=" http://example.com/image.jpg" />
<meta property="og:description" content="Description Here" />
<meta property="og:site_name" content="Site Name, i.e. Moz" />
<meta property="article:published_time" content="2013-09-17T05:59:00+01:00" />
<meta property="article:modified_time" content="2013-09-16T19:08:47+01:00" />
<meta property="article:section" content="Article Section" />
<meta property="article:tag" content="Article Tag" />
<meta property="fb:admins" content="Facebook numberic ID" />





<!-- Update your html tag to include the itemscope and itemtype attributes. -->
<html itemscope itemtype="http://schema.org/Product">

<!-- Place this data between the <head> tags of your website -->
<title>Page Title. Maximum length 60-70 characters</title>
<meta name="description" content="Page description. No longer than 155 characters." />

<!-- Schema.org markup for Google+ --> 
<meta itemprop="name" content="The Name or Title Here">
<meta itemprop="description" content="This is the page description">
<meta itemprop="image" content="http://www.example.com/image.jpg">

<!-- Twitter Card data -->
<meta name="twitter:card" content="product">
<meta name="twitter:site" content="@publisher_handle">
<meta name="twitter:title" content="Page Title">
<meta name="twitter:description" content="Page description less than 200 characters">
<meta name="twitter:creator" content="@author_handle">
<meta name="twitter:image" content=" http://www.example.com/image.html">
<meta name="twitter:data1" content="$3">
<meta name="twitter:label1" content="Price>
<meta name="twitter:data2" content="Black">
<meta name="twitter:label2" content="Color">

<!-- Open Graph data -->
<meta property="og:title" content="Title Here" />
<meta property="og:type" content="article" />
<meta property="og:url" content=" http://www.example.com/" />
<meta property="og:image" content=" http://example.com/image.jpg" />
<meta property="og:description" content="Description Here" />
<meta property="og:site_name" content="Site Name, i.e. Moz" />
<meta property="og:price:amount" content="15.00" />
<meta property="og:price:currency" content="USD" />


https://dev.twitter.com/docs/cards/validation/validator
https://developers.facebook.com/tools/debug
http://www.google.com/webmasters/tools/richsnippets
http://developers.pinterest.com/rich_pins/validator/
*/
