/**
 * @author Sergey Burkov, http://www.wppizzatime.com
 * @copyright 2016
 */

function PizzaTime () {
	_this = this;
	this.selectLayer = function(obj) {
		var id = jQuery(obj).data('id');
		var type = jQuery(obj).data('type');
		var val = jQuery(obj).val();
		var single = jQuery(obj).data('single') || 0;
		var is_ingredient = jQuery(obj).data('ingredient') || 0;
		var pizza_layer = jQuery('#pizzatime-'+type+'-'+id);
		var extra_button = jQuery(obj).closest('div.pizzatime-ingredient').find('fieldset.pizzatime-fieldset');

		if (!isNaN(parseInt(pizzatime.maximum_ingredients)) && val>0 && is_ingredient) {
			var ingredients_count = _this.getIngredientsNumber();
			if (ingredients_count > pizzatime.maximum_ingredients) {
				jQuery( "<div>"+pizzatime.text_maximum+"</div>" ).dialog({
					modal: true,
					buttons: {
						Ok: function() {
							jQuery( this ).dialog( "close" );
							}
						},
					open: function(){
						//jQuery(this).closest(".ui-dialog").css('z-index', 12000)
					        jQuery(this).closest(".ui-dialog")
					        .find(".ui-dialog-titlebar-close")
					        .removeClass("ui-dialog-titlebar-close")
					        .html("<span class='ui-button-icon-primary ui-icon ui-icon-closethick'></span>");

						}
					});

					jQuery(obj).closest('div.pizzatime-ingredient').find('input.pizzatime-input[value=0]').prop('checked', true);
					return false;
			}
		}

		if (jQuery(obj).closest('div.pizzatime-ingredient').find('input.pizzatime-extra').is(':checked')) {
			var layer_image = jQuery('#pizzatime-'+type+'-'+id).find('img.pizzatime-image-extra');
			layer_image.show();
			jQuery('#pizzatime-'+type+'-'+id).find('img.pizzatime-image').hide();
		}
		else {
			var layer_image = jQuery('#pizzatime-'+type+'-'+id).find('img.pizzatime-image');
			layer_image.show();
			jQuery('#pizzatime-'+type+'-'+id).find('img.pizzatime-image-extra').hide();
		}

		if (single==1) {
			jQuery('.pizzatime-'+type).hide();
			jQuery(obj).closest('div.pizzatime-ingredient-list').find('.pizzatime-input:checked').each(function(i, sel_obj){
				var sel_obj_id = jQuery(sel_obj).data('id');

				if (sel_obj_id!=id) {
					jQuery(sel_obj).closest('div.pizzatime-ingredient').find('input.pizzatime-input[value=0]').prop('checked', true);

					jQuery(sel_obj).closest('div.pizzatime-ingredient').find('.pizzatime-fieldset').hide();
				}
				else {
					jQuery(sel_obj).closest('div.pizzatime-ingredient').find('.pizzatime-fieldset').show();
				}

			})
		}

		if (jQuery(obj).prop('checked') && val>0) {
			pizza_layer.show();

			if (val==1) { //show left half
				layer_image.css('clip', 'rect(0px, '+(layer_image.width()/2)+'px, '+(layer_image.height())+'px, 0px)');
				extra_button.css('visibility', 'visible');
			}
			else if (val==2) { //show right half
				layer_image.css('clip', 'rect(0px, '+(layer_image.width())+'px, '+(layer_image.height())+'px, '+(layer_image.width()/2)+'px)');
				extra_button.css('visibility', 'visible');
			}
			else if (val==3) { //show whole
				layer_image.css('clip', '');
				extra_button.css('visibility', 'visible');
			}
		}
		else { //hide layer
			pizza_layer.hide();
			layer_image.css('clip', '');
			extra_button.css('visibility', 'hidden');
		}

		_this.calculatePrice();

		_this.updateIngredients();
	}
	this.getIngredientsNumber = function() {
		var ingredients_number = 0;
		jQuery('.pizzatime-input:checked, select.pizzatime-select option:selected').each(function(i, obj){
			var type = jQuery(obj).data('type');
			var val = jQuery(obj).val();
			var is_ingredient = jQuery(obj).data('ingredient') || 0;
			if (is_ingredient) {
				if (val > 0) ingredients_number++;
			}
		})
		return ingredients_number;
		
	}
	this.calculatePrice = function() {
		var total = 0;
		var price_multiplier = 1;
		jQuery('input[name^=pizzatime-input-sizes]').each(function(i, obj){
			if (jQuery(obj).val()=='3' && jQuery(obj).prop('checked')) price_multiplier = jQuery(obj).data('price-multiplier');
		})
		jQuery('.pizzatime-ingredient-list').each(function(i, ingredient_list){
			var free_ingredients = parseInt(jQuery(ingredient_list).data('free-ingredients'));
			var use_multiplier = parseInt(jQuery(ingredient_list).data('use-multiplier'));
			var c=0;

			jQuery(ingredient_list).find('.pizzatime-input:checked, select.pizzatime-select option:selected').each(function(i, obj){
			if (jQuery(obj).val() > 0) {
				c++;
				if (free_ingredients>=c) return true;
			}
			if (jQuery(obj).closest('div.pizzatime-ingredient').find('input.pizzatime-extra').is(':checked')) {
				var obj_price = jQuery(obj).data('price-extra');

			}
			else {
				var obj_price = jQuery(obj).data('price');
			}

			if (jQuery(obj).hasClass('pizzatime-half')) obj_price = obj_price / 2;
	
			if (use_multiplier == 1 && jQuery(obj).data('type')!='sizes') obj_price*=price_multiplier;
			total+=obj_price;
      
			})
		})

		if (pizzatime.currency_position=='left')
			accounting.settings.currency.format = "%s%v";
		else if (pizzatime.currency_position=='left_space')
			accounting.settings.currency.format = "%s %v";
		else if (pizzatime.currency_position=='right')
			accounting.settings.currency.format = "%v%s";
		else if (pizzatime.currency_position=='right_space')
			accounting.settings.currency.format = "%v %s";

		var price = accounting.formatMoney(total, pizzatime.currency_symbol, pizzatime.price_num_decimals, pizzatime.thousand_sep, pizzatime.decimal_sep);

//		jQuery('#pizzatime-price').html(price);
		jQuery('p.price').find('span.amount').html(price);
	}
	this.updateIngredients = function() {
		var ingredients = [];
		jQuery('.pizzatime-input:checked, select.pizzatime-select option:selected').each(function(i, obj){
			var name = jQuery(obj).data('name');
			var id = jQuery(obj).data('id');
			var layer = jQuery(obj).data('layer');
			var part = jQuery(obj).data('part');
			var type = jQuery(obj).data('type');
			var name_extra = [];


			if (typeof(name)=='undefined')  return;

			if (typeof(part)!=='undefined') {
				var val = jQuery(obj).val();
				if (val==1) {
					name_extra.push(pizzatime.text_left);
				}
				if (val==2) {
					name_extra.push(pizzatime.text_right);
				}
			}

			if (jQuery(obj).closest('div.pizzatime-ingredient').find('input.pizzatime-extra').is(':checked')) {
				name_extra.push(pizzatime.text_extra);
			}
			if (name_extra.length>0) {
				name += ' ('+name_extra.join(', ')+')';
			}
			ingredients.push(name);




			
		})
		//jQuery('#pa_pizzatime_ingredients option:selected').val(ingredients);
		jQuery('#pizzatime-ingredients').html(ingredients.join(', '));
	}
	this.submitForm = function () {	
		var qty = parseInt(jQuery('#pizzatime-qty').val());
		var qty_m = parseInt(jQuery('#pizzatime-qty-m').val());
		if (qty_m>qty) qty = qty_m;

		jQuery('form.variations_form.cart input.qty').val(qty);
		jQuery('form.variations_form.cart').submit();
	}
	this.resetPizza = function () {
		jQuery('.pizzatime-input').each(function(){
			if (jQuery(this).data('single')==1) return;
			if (jQuery(this).val()==0) jQuery(this).click();
			if (jQuery(this).val()==3) jQuery(this).prop('checked', false)
		})
	}
}

jQuery(document).ready(function() {
	if (jQuery('#pizzatime-accordion').length==0) return;
	window.myPizza = new PizzaTime();
	jQuery('.pizzatime-input.pizzatime-for-layer').on('change', function(e){
		myPizza.selectLayer(this);
	})

	jQuery('.pizzatime-extra, .pizzatime-regular').on('change', function(e){
		myPizza.selectLayer(jQuery(this).closest('div.pizzatime-ingredient').find('input.pizzatime-for-layer:checked'));
	})

//	jQuery('select.pizzatime-select option:selected').each(function(i, obj){
//		myPizza.selectLayer(this);
//	})
	jQuery('.pizzatime-input:checked').each(function(i, obj){
		myPizza.selectLayer(this);
	})
	myPizza.calculatePrice();
	myPizza.updateIngredients();
	jQuery('body').addClass('pizzatime'); //:(


	jQuery( "input.pizzatime-checkbox" ).checkboxradio();
	jQuery( ".pizzatime-regular" ).prop('checked', true)
	jQuery( ".pizzatime-extra" ).prop('checked', false)
	jQuery( ".pizzatime-fieldset" ).controlgroup();
	jQuery('div.images').sticky('div.product');

	jQuery('.pizzatime-select').selectmenu(
		{
			change: function() {
                		myPizza.selectLayer(jQuery(this).find('option:selected'));
			}
		});

	jQuery( "#pizzatime-accordion" ).accordion({
		collapsible: true,
		active: false,
		heightStyle: "content",
		beforeActivate: function (event, ui) {
			// Make all tabs expandable
			if (ui.newHeader[0]) {
				var currHeader = ui.newHeader;
				var currContent = currHeader.next('.ui-accordion-content');
			} else {
				var currHeader = ui.oldHeader;
				var currContent = currHeader.next('.ui-accordion-content');
			}

			var isPanelSelected = currHeader.attr('aria-selected') == 'true';
			currHeader.toggleClass('ui-corner-all', isPanelSelected).toggleClass('accordion-header-active ui-state-active ui-corner-top', !isPanelSelected).attr('aria-selected', ((!isPanelSelected).toString()));
			currHeader.children('.ui-icon').toggleClass('ui-icon-triangle-1-e', isPanelSelected).toggleClass('ui-icon-triangle-1-s', !isPanelSelected);
			currContent.toggleClass('accordion-content-active', !isPanelSelected)
			if (isPanelSelected) {
				currContent.slideUp();
			} else {
				currContent.slideDown();
			}

			return false; 
		}
	});

	jQuery('.pizzatime-desc-image').bind('click', function(e) {
		var image_src = jQuery(this).prop('src');
		var desc = jQuery(this).closest('.pizzatime-ingredient').find('p.pizzatime-ingredient-description').html();
		var image_desc = jQuery('<div class="pizzatime-popup"><img class="pizzatime-popup-image" src="'+image_src+'"><p class="pizzatime-popup-ingredient-description">'+desc+'</p></div>')
		.dialog({
			//resizable: false,
			modal: true,
			width: 'auto',
			zIndex: 12000,
			open: function(){
				jQuery('.ui-widget-overlay').bind('click',function(){
					image_desc.dialog('close');
				})

				//Bugfix for the close button when bootstrap.js is used by the theme
			        jQuery(this).closest(".ui-dialog")
			        .find(".ui-dialog-titlebar-close")
			        .removeClass("ui-dialog-titlebar-close")
			        .html("<span class='ui-button-icon-primary ui-icon ui-icon-closethick'></span>");

			}
		})
	})

	jQuery('#pizzatime-accordion h3:visible:first').click();

	jQuery('#pa_pizzatime_ingredients').val('all').change();




});

