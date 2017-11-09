/**
 * @author Sergey Burkov, http://www.wppizzatime.com
 * @copyright 2016
 */
jQuery(document).ready(function() {
	jQuery('.pizzatime-expand').accordion({collapsible:true, active:false});
	jQuery( "#pizzatime_tabs" ).tabs();
	jQuery('.sumoselect').SumoSelect({ okCancelInMulti: true, selectAll: true });

});

function pizzatimeAddsize() {
        var max_index = 0;

	jQuery('input[name^=pizzatime_size]').each(function(){
		var start = jQuery(this).attr('name').indexOf('[')+1;
		var end = jQuery(this).attr('name').indexOf(']');
		var index = parseInt(jQuery(this).attr('name').substring(start, end));
		if (index > max_index) max_index = index;
	});

	jQuery('table.size').first().clone().insertBefore('#add_size_button').find('input').val('');
	jQuery('table.size').last().find('.remove_size').remove();
	jQuery('table.size').last().find('.item_id').remove();
	jQuery('table.size').last().find('tr.size_crusts').remove();
	jQuery('table.size').last().find('.pizzatime-preview').remove();
	jQuery('table.size').last().find('.tooltip').tooltipster({ contentAsHTML: true, multiple: true });

	jQuery('table.size').last().find('input[name^=pizzatime_size], select[name^=pizzatime_size], textarea[name^=pizzatime_size]').each(function(){
		var start = jQuery(this).attr('name').indexOf('[')+1;
		var end = jQuery(this).attr('name').indexOf(']');
		var index = parseInt(jQuery(this).attr('name').substring(start, end));
		var new_name = jQuery(this).attr('name').replace('['+index+']', '['+(max_index+1)+']');
		jQuery(this).attr('name', new_name);
	});

}

function pizzatimeAddCrust() {
        var max_index = 0;

	jQuery('input[name^=pizzatime_crust]').each(function(){
		var start = jQuery(this).attr('name').indexOf('[')+1;
		var end = jQuery(this).attr('name').indexOf(']');
		var index = parseInt(jQuery(this).attr('name').substring(start, end));
		if (index > max_index) max_index = index;
	});

	jQuery('table.crust').first().clone().insertBefore('#add_crust_button').find('input').val('');
	jQuery('table.crust').last().find('.pizzatime-preview').remove();
	jQuery('table.crust').last().find('.remove_crust').remove();
	jQuery('table.crust').last().find('.item_id').remove();
	jQuery('table.crust').last().find('.tooltip').tooltipster({ contentAsHTML: true, multiple: true });

	jQuery('table.crust').last().find('input[name^=pizzatime_crust], select[name^=pizzatime_crust], textarea[name^=pizzatime_crust]').each(function(){
		var start = jQuery(this).attr('name').indexOf('[')+1;
		var end = jQuery(this).attr('name').indexOf(']');
		var index = parseInt(jQuery(this).attr('name').substring(start, end));
		var new_name = jQuery(this).attr('name').replace('['+index+']', '['+(max_index+1)+']');
		jQuery(this).attr('name', new_name);
	});
}

function pizzatimeAddSauce() {
        var max_index = 0;

	jQuery('input[name^=pizzatime_sauce]').each(function(){
		var start = jQuery(this).attr('name').indexOf('[')+1;
		var end = jQuery(this).attr('name').indexOf(']');
		var index = parseInt(jQuery(this).attr('name').substring(start, end));
		if (index > max_index) max_index = index;
	});

	jQuery('table.sauce').first().clone().insertBefore('#add_sauce_button').find('input').val('');
	jQuery('table.sauce').last().find('.pizzatime-preview').remove();
	jQuery('table.sauce').last().find('.remove_sauce').remove();
	jQuery('table.sauce').last().find('.item_id').remove();
	jQuery('table.sauce').last().find('.tooltip').tooltipster({ contentAsHTML: true, multiple: true });

	jQuery('table.sauce').last().find('input[name^=pizzatime_sauce], select[name^=pizzatime_sauce], textarea[name^=pizzatime_sauce]').each(function(){
		var start = jQuery(this).attr('name').indexOf('[')+1;
		var end = jQuery(this).attr('name').indexOf(']');
		var index = parseInt(jQuery(this).attr('name').substring(start, end));
		var new_name = jQuery(this).attr('name').replace('['+index+']', '['+(max_index+1)+']');
		jQuery(this).attr('name', new_name);
	});
}

function pizzatimeAddCheese() {
        var max_index = 0;

	jQuery('input[name^=pizzatime_cheese]').each(function(){
		var start = jQuery(this).attr('name').indexOf('[')+1;
		var end = jQuery(this).attr('name').indexOf(']');
		var index = parseInt(jQuery(this).attr('name').substring(start, end));
		if (index > max_index) max_index = index;
	});

	jQuery('table.cheese').first().clone().insertBefore('#add_cheese_button').find('input').val('');
	jQuery('table.cheese').last().find('.pizzatime-preview').remove();
	jQuery('table.cheese').last().find('.remove_cheese').remove();
	jQuery('table.cheese').last().find('.item_id').remove();
	jQuery('table.cheese').last().find('.tooltip').tooltipster({ contentAsHTML: true, multiple: true });
	jQuery('table.cheese').last().find('img.pizzatime-preview').remove();

	jQuery('table.cheese').last().find('input[name^=pizzatime_cheese], select[name^=pizzatime_cheese], textarea[name^=pizzatime_cheese]').each(function(){
		var start = jQuery(this).attr('name').indexOf('[')+1;
		var end = jQuery(this).attr('name').indexOf(']');
		var index = parseInt(jQuery(this).attr('name').substring(start, end));
		var new_name = jQuery(this).attr('name').replace('['+index+']', '['+(max_index+1)+']');
		jQuery(this).attr('name', new_name);
	});


}

function pizzatimeAddMeat() {
        var max_index = 0;

	jQuery('input[name^=pizzatime_meat]').each(function(){
		var start = jQuery(this).attr('name').indexOf('[')+1;
		var end = jQuery(this).attr('name').indexOf(']');
		var index = parseInt(jQuery(this).attr('name').substring(start, end));
		if (index > max_index) max_index = index;
	});

	jQuery('table.meat').first().clone().insertBefore('#add_meat_button').find('input').val('');
	jQuery('table.meat').last().find('.pizzatime-preview').remove();
	jQuery('table.meat').last().find('.remove_meat').remove();
	jQuery('table.meat').last().find('.item_id').remove();
	jQuery('table.meat').last().find('.tooltip').tooltipster({ contentAsHTML: true, multiple: true });

	jQuery('table.meat').last().find('input[name^=pizzatime_meat], select[name^=pizzatime_meat], textarea[name^=pizzatime_meat]').each(function(){
		var start = jQuery(this).attr('name').indexOf('[')+1;
		var end = jQuery(this).attr('name').indexOf(']');
		var index = parseInt(jQuery(this).attr('name').substring(start, end));
		var new_name = jQuery(this).attr('name').replace('['+index+']', '['+(max_index+1)+']');
		jQuery(this).attr('name', new_name);
	});

}

function pizzatimeAddTopping() {
        var max_index = 0;

	jQuery('input[name^=pizzatime_topping]').each(function(){
		var start = jQuery(this).attr('name').indexOf('[')+1;
		var end = jQuery(this).attr('name').indexOf(']');
		var index = parseInt(jQuery(this).attr('name').substring(start, end));
		if (index > max_index) max_index = index;
	});

	jQuery('table.topping').first().clone().insertBefore('#add_topping_button').find('input').val('');
	jQuery('table.topping').last().find('.pizzatime-preview').remove();
	jQuery('table.topping').last().find('.remove_topping').remove();
	jQuery('table.topping').last().find('.item_id').remove();
	jQuery('table.topping').last().find('.tooltip').tooltipster({ contentAsHTML: true, multiple: true });

	jQuery('table.topping').last().find('input[name^=pizzatime_topping], select[name^=pizzatime_topping], textarea[name^=pizzatime_topping]').each(function(){
		var start = jQuery(this).attr('name').indexOf('[')+1;
		var end = jQuery(this).attr('name').indexOf(']');
		var index = parseInt(jQuery(this).attr('name').substring(start, end));
		var new_name = jQuery(this).attr('name').replace('['+index+']', '['+(max_index+1)+']');
		jQuery(this).attr('name', new_name);
	});

}

function pizzatimeAddDressing() {
        var max_index = 0;

	jQuery('input[name^=pizzatime_dressing]').each(function(){
		var start = jQuery(this).attr('name').indexOf('[')+1;
		var end = jQuery(this).attr('name').indexOf(']');
		var index = parseInt(jQuery(this).attr('name').substring(start, end));
		if (index > max_index) max_index = index;
	});

	jQuery('table.dressing').first().clone().insertBefore('#add_dressing_button').find('input').val('');
	jQuery('table.dressing').last().find('.pizzatime-preview').remove();
	jQuery('table.dressing').last().find('.remove_dressing').remove();
	jQuery('table.dressing').last().find('.item_id').remove();
	jQuery('table.dressing').last().find('.tooltip').tooltipster({ contentAsHTML: true, multiple: true });

	jQuery('table.dressing').last().find('input[name^=pizzatime_dressing], select[name^=pizzatime_dressing], textarea[name^=pizzatime_dressing]').each(function(){
		var start = jQuery(this).attr('name').indexOf('[')+1;
		var end = jQuery(this).attr('name').indexOf(']');
		var index = parseInt(jQuery(this).attr('name').substring(start, end));
		var new_name = jQuery(this).attr('name').replace('['+index+']', '['+(max_index+1)+']');
		jQuery(this).attr('name', new_name);
	});

}

function pizzatimeAddPreset() {
        var max_index = 0;

	jQuery('input[name^=pizzatime_preset]').each(function(){
		var start = jQuery(this).attr('name').indexOf('[')+1;
		var end = jQuery(this).attr('name').indexOf(']');
		var index = parseInt(jQuery(this).attr('name').substring(start, end));
		if (index > max_index) max_index = index;
	});

	jQuery('table.preset').first().clone().insertBefore('#add_preset_button').find('input').val('');
	jQuery('table.preset').last().find('.pizzatime-preview').remove();
	jQuery('table.preset').last().find('.remove_preset').remove();
	jQuery('table.preset').last().find('.item_id').remove();
	jQuery('table.preset').last().find('.tooltip').tooltipster({ contentAsHTML: true, multiple: true });
	jQuery('table.preset').last().find('.CaptionCont, .optWrapper').remove();
	jQuery('table.preset').last().find('.sumoselect').unwrap().show();
	jQuery('table.preset').last().find('.sumoselect option:selected').prop('selected', false);
	jQuery('table.preset').last().find('.sumoselect').SumoSelect({ okCancelInMulti: true, selectAll: true });



	jQuery('table.preset').last().find('input[name^=pizzatime_preset], select[name^=pizzatime_preset]').each(function(){
		var start = jQuery(this).attr('name').indexOf('[')+1;
		var end = jQuery(this).attr('name').indexOf(']');
		var index = parseInt(jQuery(this).attr('name').substring(start, end));
		var new_name = jQuery(this).attr('name').replace('['+index+']', '['+(max_index+1)+']');
		jQuery(this).attr('name', new_name);
	});

}

function pizzatimeAddCustomIngredient() {
        var max_index = 0;

	jQuery('input[name^=pizzatime_custom_ingredient]').each(function(){
		var start = jQuery(this).attr('name').indexOf('[')+1;
		var end = jQuery(this).attr('name').indexOf(']');
		var index = parseInt(jQuery(this).attr('name').substring(start, end));
		if (index > max_index) max_index = index;
	});

	jQuery('table.custom_ingredient').first().clone().insertBefore('#add_custom_ingredient_button').find('input').val('');
	jQuery('table.custom_ingredient').last().find('.pizzatime-preview').remove();
	jQuery('table.custom_ingredient').last().find('.remove_custom_ingredient').remove();
	jQuery('table.custom_ingredient').last().find('.item_id').remove();
	jQuery('table.custom_ingredient').last().find('.tooltip').tooltipster({ contentAsHTML: true, multiple: true });

	jQuery('table.custom_ingredient').last().find('input[name^=pizzatime_custom_ingredient], select[name^=pizzatime_custom_ingredient], textarea[name^=pizzatime_custom_ingredient] ').each(function(){
		var start = jQuery(this).attr('name').indexOf('[')+1;
		var end = jQuery(this).attr('name').indexOf(']');
		var index = parseInt(jQuery(this).attr('name').substring(start, end));
		var new_name = jQuery(this).attr('name').replace('['+index+']', '['+(max_index+1)+']');
		jQuery(this).attr('name', new_name);
	});

}


function pizzatimeRemoveSize(id) {
	jQuery( '<form action="admin.php?page=pizzatime#pizzatime_tabs-1" method="post">'+jQuery('#pizzatime_nonce').html()+'<input type="hidden" name="action" value="remove_size"><input type="hidden" name="size_id" value="'+id+'"></form>' ).appendTo('body').submit()
}
function pizzatimeRemoveCrust(id) {
	jQuery( '<form action="admin.php?page=pizzatime#pizzatime_tabs-2" method="post">'+jQuery('#pizzatime_nonce').html()+'<input type="hidden" name="action" value="remove_crust"><input type="hidden" name="crust_id" value="'+id+'"></form>' ).appendTo('body').submit()
}
function pizzatimeRemoveSauce(id) {
	jQuery( '<form action="admin.php?page=pizzatime#pizzatime_tabs-3" method="post">'+jQuery('#pizzatime_nonce').html()+'<input type="hidden" name="action" value="remove_sauce"><input type="hidden" name="sauce_id" value="'+id+'"></form>' ).appendTo('body').submit()
}
function pizzatimeRemoveCheese(id) {
	jQuery( '<form action="admin.php?page=pizzatime#pizzatime_tabs-4" method="post">'+jQuery('#pizzatime_nonce').html()+'<input type="hidden" name="action" value="remove_cheese"><input type="hidden" name="cheese_id" value="'+id+'"></form>' ).appendTo('body').submit()
}
function pizzatimeRemoveMeat(id) {
	jQuery( '<form action="admin.php?page=pizzatime#pizzatime_tabs-5" method="post">'+jQuery('#pizzatime_nonce').html()+'<input type="hidden" name="action" value="remove_meat"><input type="hidden" name="meat_id" value="'+id+'"></form>' ).appendTo('body').submit()
}
function pizzatimeRemoveTopping(id) {
	jQuery( '<form action="admin.php?page=pizzatime#pizzatime_tabs-6" method="post">'+jQuery('#pizzatime_nonce').html()+'<input type="hidden" name="action" value="remove_topping"><input type="hidden" name="topping_id" value="'+id+'"></form>' ).appendTo('body').submit()
}
function pizzatimeRemoveDressing(id) {
	jQuery( '<form action="admin.php?page=pizzatime#pizzatime_tabs-7" method="post">'+jQuery('#pizzatime_nonce').html()+'<input type="hidden" name="action" value="remove_dressing"><input type="hidden" name="dressing_id" value="'+id+'"></form>' ).appendTo('body').submit()
}

function pizzatimeRemovePreset(id) {
	jQuery( '<form action="admin.php?page=pizzatime#pizzatime_tabs-9" method="post">'+jQuery('#pizzatime_nonce').html()+'<input type="hidden" name="action" value="remove_preset"><input type="hidden" name="preset_id" value="'+id+'"></form>' ).appendTo('body').submit()
}
function pizzatimeRemoveCustom(id) {
	jQuery( '<form action="admin.php?page=pizzatime#pizzatime_tabs-10" method="post">'+jQuery('#pizzatime_nonce').html()+'<input type="hidden" name="action" value="remove_custom"><input type="hidden" name="custom_id" value="'+id+'"></form>' ).appendTo('body').submit()
}
function pizzatimeRemoveCustomIngredient(id) {
	jQuery( '<form action="admin.php?page=pizzatime#pizzatime_tabs-20" method="post">'+jQuery('#pizzatime_nonce').html()+'<input type="hidden" name="action" value="remove_custom_ingredient"><input type="hidden" name="custom_ingredient_id" value="'+id+'"></form>' ).appendTo('body').submit()
}


function pizzatimeSetCrustType(obj)  {
        var crust_type = obj.value;
	jQuery(obj).closest('table.form-table.crust').find('tr, a').each(function(i, el){
		var className = jQuery(el).attr('class');
		if (typeof(className)!=='undefined') {
			if (className.indexOf('crust')==0) {

				if (className=='crust_'+crust_type) jQuery(el).show();
				else jQuery(el).hide();
			}
		}
	});
}

jQuery(document).ready(function(){
	jQuery('.tooltip').tooltipster({ contentAsHTML: true });
	jQuery('.pizzatime-default').on('change', function(){
	        var obj = this;
		jQuery('.pizzatime-default').each(function(){
			if (this != obj)
				jQuery(this).prop('checked', false)
		})

	})
});