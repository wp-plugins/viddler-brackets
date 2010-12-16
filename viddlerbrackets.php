<?php
/*
Plugin Name: Viddler Brackets
Plugin URI: http://silentblue.net/
Description: Insert Viddler videos in post using bracket method. Enables Viddler blogging to standalone wordpress setups.
Author: Gregory Lam
Version: 1.1.1
Author URI: http://gregorylam.ca
*/

/*
Mimics WordPress.com's functionality with YouTube shortcodes. Adjust $width and $height variables below to taste. 
Based on the Quicktime Posting plugin by Shawn Van Every and YouTube Brackets by Robert Buzink ( http://www.robertbuzink.nl/journal/2006/11/23/youtube-brackets-wordpress-plugin/ )

Very no frills. No UI settings to configure. To embed Viddler videos, enclose the video URL in square brackets and Bob's your uncle.

License is GPLv3.
*/ 

$stag = "[viddler id=";
$etag = "]";

function quicktime_post($the_content)
{
    GLOBAL $stag, $etag;

    $spos = strpos($the_content, $stag);
    if ($spos !== false)
    {
        $epos = strpos($the_content, $etag, $spos);
        $spose = $spos + strlen($stag);
        $slen = $epos - $spose;
        $tagargs = substr($the_content, $spose, $slen);
        
        $the_args = explode(" ", $tagargs);
        
        if (sizeof($the_args) == 1)
        {
            $file = $tagargs;
			/* DEFINE YOUR VIDEO DIMENSIONS HERE.  */
			$width = 545;														 /* Default width is 545px for widscreen SDTV content */
			$height = 349;														 /* Default height is 349px for widescreen SDTV content */
            $tags = generate_tags($file,$width,$height);
            $new_content = substr($the_content,0,$spos);
            $new_content .= $tags;
            $new_content .= substr($the_content,($epos+1));
        }
		
		else if (sizeof($the_args) == 3)
        {
            list($file,$width,$height) = explode(" ", $tagargs);
            $tags = generate_tags($file,$width,$height);
            $new_content = substr($the_content,0,$spos);
            $new_content .= $tags;
            $new_content .= substr($the_content,($epos+1));
        }
       /* else if (sizeof($the_args) == 4)
        {
            list($file,$poster,$width,$height) = explode(" ", $tagargs);
            $tags = generate_tags($file,$width,$height,$poster);
            $new_content = substr($the_content,0,$spos);
            $new_content .= $tags;
            $new_content .= substr($the_content,($epos+1));
        }
        else if (sizeof($the_args) == 5)
        {
            list($file,$width,$height,$autoplay,$controller) = explode(" ",$tagargs);
            $poster = "";
            $tags = generate_tags($file,$width,$height,$poster,$autoplay,$controller);
            $new_content = substr($the_content,0,$spos);
            $new_content .= $tags;
            $new_content .= substr($the_content,($epos+1));
        }
        else if (sizeof($the_args) == 6)
        {
            list($file,$poster,$width,$height,$autoplay,$controller) = explode(" ",$tagargs);
            $tags = generate_tags($file,$width,$height,$poster,$autoplay,$controller);
            $new_content = substr($the_content,0,$spos);
            $new_content .= $tags;
            $new_content .= substr($the_content,($epos+1));
        } */
                
        if ($epos+1 < strlen($the_content))
        {
            $new_content = quicktime_post($new_content);
        }
        return $new_content;
    }
    else
    {
        return $the_content;
    }
}

function generate_tags($file, $width, $height, $poster = "", $autoplay = "false", $controller = "")
{
    $tag_line = "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" id=\"viddler\" width=\"";
	$tag_line .= $width;
	$tag_line .= "\" height=\"";
	$tag_line .= $height;
	$tag_line .= "\"><param name=\"movie\" value=\"http://www.viddler.com/player/";
	$tag_line .= $file;
	$tag_line .= "\"></param><param name=\"allowScriptAccess\" value=\"always\"><param name=\"wmode\" value=\"opaque\"><embed src=\"http://www.viddler.com/player/";
	$tag_line .= $file;
	$tag_line .= "\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" name=\"viddler\" allowfullscreen=\"true\" wmode=\"opaque\" width=\"";
	$tag_line .= $width;
	$tag_line .= "\" height=\"";
	$tag_line .= $height;
	$tag_line .= "\"></embed></object>";

	$script_tags = $tag_line;
        
    return $script_tags;
}

add_filter('the_content', 'quicktime_post');
add_filter('the_excerpt','quicktime_post');
?>