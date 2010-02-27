<?php
/**
 * Feedback Forms js file.
 *
 * Handles javascript stuff related to feedback function.
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Ushahidi Team <team@ushahidi.com> 
 * @package    Ushahidi - http://source.ushahididev.com
 * @module     API Controller
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */
?>
function submit_tags(id)
{  
	  var tag = document.getElementById('tag_'+id);
	  var theurl	= '/main/Ajax_tagging/'+id+'/'+tag.value; 
		tag.value = "";
			   $.ajax( //ajax request starting
				 	{
		       url: theurl, //send the ajax request to student/delete/$id
           type:"POST",//request is a POSt request
		       dataType: "json",//expect json as 
		       success: function(data) //trigger this on success
			   	 {
				   		document.getElementById('lbltags_'+id).innerHTML = data['tags'];
				   }			   
		    });		    
	}

function submitfeed_to_ushahidi(id,cat)
{  
	   var theurl	= '/main/submit_report_via_API/'+id+'/'+cat; 		 	  
			    $.ajax( //ajax request starting
				 	{
		       url: theurl, //send the ajax request to student/delete/$id
           type:"POST",//request is a POSt request
		       dataType: "json",//expect json as 
		       success: function(data) //trigger this on success
			   	 {  //in the future this is suposed to make the this feed this disappear.
				   		document.getElementById('lblreport_'+id).innerHTML = data['message'];
						 	document.getElementById('weight_'+id).innerHTML = data['weight']+'%';
						 	disable_feed_links(id);
				   }			   
		    });			
}

function disable_feed_links(id)
{
		document.getElementById('feed_link_'+id).setAttribute('href','#');
		document.getElementById('irrelevant_link_'+id).setAttribute('href','#');
		document.getElementById('increase_ratting_link_'+id).setAttribute('href','#');
		document.getElementById('reduce_ratting_link_'+id).setAttribute('href','#');
}
function change_feed_rating(id,cat,increment)
{  
	   var theurl	= '/main/change_source_rating/'+id+'/'+cat+'/'+increment; 	  
			   $.ajax( //ajax request starting
				 	{
		       url: theurl, //send the ajax request to student/delete/$id
           type:"POST",//request is a POSt request
		       dataType: "json",//expect json as 
		       success: function(data) //trigger this on success
			   	 {
				   		document.getElementById('weight_'+id).innerHTML = data['weight']+'%';
				   }			   
		    });		    
	}

function mark_irrelevant(id,cat)
{  
	   var theurl	= '/main/mark_irrelevant/'+id+'/'+cat+'/'; 	  
			   $.ajax( //ajax request starting
				 	{
		       url: theurl, //send the ajax request to student/delete/$id
           type:"POST",//request is a POSt request
		       dataType: "json",//expect json as 
		       success: function(data) //trigger this on success
			   	 {
				   		document.getElementById('lblreport_'+id).innerHTML = data['message']; 	
				   		disable_feed_links(id);
				   }			   
		    });		    
	}
	
	
