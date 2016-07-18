<?php

use ov\ui\Paginator;
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.paginatorl.php
 * Type:     function
 * Name:     paginator
 * Purpose:  outputs a paginator control
 * -------------------------------------------------------------
 */
function smarty_function_paginator($params, &$smarty)
	{
		
	  	if (empty($params['currentPage'])) {
	        $smarty->trigger_error("assign: missing 'currentPage' parameter");
	        return;
	    }
	    
		if (empty($params['totalPages'])) {
	        $smarty->trigger_error("assign: missing 'totalPages' parameter");
	        return;
	    }
	    
		if (empty($params['baseURL'])) {
	        $smarty->trigger_error("assign: missing 'baseURL' parameter");
	        return;
	    }
		
	    return Paginator::paginate($params['currentPage'],$params['totalPages'],1,$params['baseURL'],false);   
	}

?>