<?php

namespace ov\ui;

/*
 * Copyright (c) 2006-2008 Byrne Reese. All rights reserved.
 * 
 * This library is free software; you can redistribute it and/or modify it 
 * under the terms of the BSD License.
 *
 * This library is distributed in the hope that it will be useful, but 
 * WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
 *
 * @author Byrne Reese <byrne@majordojo.com>
 * @version 1.02
 */


/*
 * Paginator - A PHP pagination control
By: Byrne Reese

OVERVIEW

How many times have you had to implement some kind of pagination control? 
You know the whole, next, previous, page 1, page 2, page 3 links you place 
at the bottom of a list? Ten times? a hundred times? Whatever the number, 
it is probably a lot. So why are there not libraries out there that make 
generation of these links a breeze?

Now there is.

HOW IT WORKS

The pagination model is based upon three key values that you pass into the
main pagination routine:

* the current offset in the list (how many items from the beginning of the
  list are you?
* the number of items on each page, or "limit"
* the total number of items in the result set

From these three key values one can generate a complete pagination control.

FUNCTIONS

There is one static function in this class called paginate. It takes four
parameters:

  paginate($offset,$limit,$total,$base_url);

  PARAMETERS

  offset   - This indicates to the control what is the current page
             you are on. The value is the number of items you are
             from the beginning of the list. 

  limit    - The number of items per page.

  total    - The total number of items in the list. This is used to
             compute the last page in the list.

  base_url - This is the URL that is used for generating all the other
             URLs and links in the pagination control. Paginator will
             append to the end of this URL a number corresponding to
             the offset for that particular page.

EXAMPLE CODE

<?php
include("Paginator.php");
Paginator::paginate(0,100,10,"foo.php?bar=2&baz=3&offset=");
Paginator::paginate(11,100,10,"foo.php?bar=2&baz=3&offset=");
Paginator::paginate(21,100,10,"foo.php?bar=2&baz=3&offset=");
Paginator::paginate(31,100,10,"foo.php?bar=2&baz=3&offset=");
Paginator::paginate(41,100,10,"foo.php?bar=2&baz=3&offset=");
Paginator::paginate(51,100,10,"foo.php?bar=2&baz=3&offset=");
Paginator::paginate(61,100,10,"foo.php?bar=2&baz=3&offset=");
Paginator::paginate(71,100,10,"foo.php?bar=2&baz=3&offset=");
Paginator::paginate(81,100,10,"foo.php?bar=2&baz=3&offset=");
Paginator::paginate(91,100,10,"foo.php?bar=2&baz=3&offset=");
?>

LICENSING

See LICENSE.txt
 * 
 */

class Paginator {
	
  public static function paginate($offset,$total,$limit,$base = '',$echo=true){
  	$out ='';
    $lastp = ceil($total / $limit);
    $thisp = ceil(($offset == 0 ? 1 : ($lastp / ($total / $offset))));
    $out = $out. "    <div class=\"paginator\">\n";
    if ($thisp==1) { $out = $out. "      <SPAN CLASS=\"atstart\">&lt Prev</SPAN>\n"; }
    else { $out = $out. "      <a href=\"".$base.((($thisp - 2) * $limit) + 1)."\" class=\"prev\">&lt; Prev</a> \n"; }
    $page1 = $base . "1";
    $page2 = $base . ($limit + 1);
    if ($thisp <= 5) {
      for ($p = 1;$p <= min( ($thisp<=3) ? 5 : $thisp+2,$lastp); $p++) {
	if ($p == $thisp) {
	  $out = $out. "      <span class=\"this-page\">$p</span>\n ";
	} else {
	  $url = $base . (($limit * ($p - 1)) + 1);
	  $out = $out. "      <a href=\"$url\">$p</a>\n ";
	}
      }
      if ($lastp > $p) {
		$out = $out. "      <span class=\"break\">...</span>\n";
		$out = $out. "      <a href=\"".$base.(($lastp - 2) * $limit)."\">".($lastp-1)."</a>\n";
		$out = $out. "      <a href=\"".$base.(($lastp - 1) * $limit)."\">".$lastp."</a>\n";
      }
    }
    else if ($thisp > 5) {
      $out = $out. "      <a href=\"".$page1."\">1</a> <a href=\"".$page2."\">2</a>";
      if ($thisp != 6) { $out = $out. " <span class=\"break\">...</span>\n "; }
      for ($p = ($thisp == 6) ? 3 : min($thisp - 2,$lastp-4);$p <= (($lastp-$thisp<=5) ? $lastp:$thisp+2); $p++) {
	if ($p == $thisp) {
	  $out = $out. "      <span class=\"this-page\">$p</span>\n ";
	} else if ($p <=$lastp) {
	  $url = $base . (($limit * ($p - 1)) + 1);
	  $out = $out. "      <a href=\"$url\">$p</a>\n ";
	}
      }
      if ($lastp > $p+1) {
		$out = $out. "      <span class=\"break\">...</span>\n";
		$out = $out. "      <a href=\"".$base.(($lastp - 2) * $limit)."\">".($lastp-1)."</a>\n";
		$out = $out. "      <a href=\"".$base.(($lastp - 1) * $limit)."\">".$lastp."</a>\n";
      }
    }
    if ($thisp == $lastp) { $out = $out. "      <SPAN CLASS=\"atend\"> Next &gt</SPAN>\n"; }
    else { $out = $out. "      <a href=\"".$base.((($thisp + 0) * $limit) + 1)."\" class=\"next\">Next &gt;</a>\n"; }
    $out = $out. "    </div>\n";
    
    if($echo){
    	echo $out;
    }else{
    	return $out;
    }
  }
}
?>
