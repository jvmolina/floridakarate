<?php
//$wp_session['city']="kkllii";
//echo "-->".$wp_session;die();
//echo "<pre>",$wp_session['city'],"</pre>";die();
global $wp_session;
$value = $wp_session['session_id'];
echo $wp_session['session_id'];
//echo "<pre>",print_r($wp_session),"</pre>";die();
?>
<div id="locationField"><input id="autocomplete" type="text" placeholder="Enter your address" /></div>
<table id="address">
<tbody>
<tr>
<td class="label">Street address</td>
<td class="slimField"><input id="street_number" class="field" disabled="disabled" type="text" /></td>
<td class="wideField" colspan="2"><input id="route" class="field" disabled="disabled" type="text" /></td>
</tr>
<tr>
<td class="label">City</td>

<td class="wideField" colspan="3"><input id="locality" class="field" disabled="disabled" type="text" /></td>
</tr>
<tr>
<td class="label">State</td>
<td class="slimField"><input id="administrative_area_level_1" class="field" disabled="disabled" type="text" /></td>
&nbsp;&nbsp;
<td class="label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Zip code</td>
<td class="wideField"><input id="postal_code" class="field" disabled="disabled" type="text" /></td>
</tr>
<tr>
<td class="label">Country</td>
<td class="wideField" colspan="3"><input id="country" class="field" disabled="disabled" type="text" /></td>
</tr>
</tbody>
<input type="hidden" id="myId" class="field" value="<?php echo $value; ?>" type="text" />
</table>
<div id="floating-panel">

<input id="submit" type="button" value="Check" />

</div>
<div id="map"></div>
<script src="/wordpress/wp-includes/js/maps-hero.js" type="text/javascript"></script>


