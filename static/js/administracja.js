jQuery(function(){
	jQuery('.can-delete').on('click', function(e){
		e.preventDefault();
		var href = jQuery(this).attr('href');
		if(confirm('Czy na pewno chcesz usunąć element?')) {
			window.location.href = href;
		}
	});
	
	jQuery('.can-cancel-reservation').on('click', function(e){
		e.preventDefault();
		var href = jQuery(this).attr('href');
		if(confirm('Czy na pewno chcesz anulować rezerwację?')) {
			window.location.href = href;
		}
	});
	
	jQuery('.can-cancel-order').on('click', function(e){
		e.preventDefault();
		var href = jQuery(this).attr('href');
		if(confirm('Czy na pewno chcesz anulować zamówienie?')) {
			window.location.href = href;
		}
	});
	
	var setupRoleCode = function(value) {
		var result = value;
		var clearArray = [];
		var changePolish = ['ą','ę','ż','ź','ś','ć','ó','ł','ń','ć','Ą','Ę','Ż','Ź','Ś','Ć','Ó','Ł','Ń','Ć'];
		var changeNeutral = ['a','e','z','z','s','c','o','l','n','c','A','E','Z','Z','S','C','O','L','N','C'];
		
		var changeLetters = {
			'ą':'a',
			'ę': 'e',
			'ż': 'z',
			'ź': 'z',
			'ś': 's',
			'ć': 'c',
			'ó': 'o',
			'ł': 'l',
			'ń': 'n',
			'Ą': 'A',
			'Ę': 'E',
			'Ż': 'Z',
			'Ź': 'Z',
			'Ś': 'S',
			'Ć': 'C',
			'Ó': 'O',
			'Ł': 'L',
			'Ń': 'N',
			' ': '_'
		};
		
		function replaceAll(str,mapObj){
		    var re = new RegExp(Object.keys(mapObj).join("|"),"gi");

		    return str.replace(re, function(matched){
		        return mapObj[matched.toLowerCase()];
		    });
		}
		
		result = result.replace(/[;:\"\.,-?&=]/g,'');
		
		result = replaceAll(result, changeLetters).toUpperCase();
		
		jQuery('#rolaKod').val(result);
	};
	 
	jQuery('#rolaNazwa').on('keyup', function() {
		var value = jQuery(this).val();
		setupRoleCode(value);
	});
	
	jQuery('#rolaNazwa').on('blur', function() {
		var value = jQuery(this).val();
		setupRoleCode(value);
	});
	
	
	jQuery('#goscZagraniczny').on('click', function() {
		if(jQuery(this).is(':checked')) {
			jQuery('#goscPesel').attr('disabled','disabled');
		}
		else {
			jQuery('#goscPesel').removeAttr('disabled');
		}
	});
	
	if(jQuery('#goscZagraniczny').is(':checked')) {
		jQuery('#goscPesel').attr('disabled','disabled');
	}
	else {
		jQuery('#goscPesel').removeAttr('disabled');
	}
	
	var convertDate = function(date) {
		if(date) {
			var dateTimeElements = date.split(' ');
			var dateElement = dateTimeElements[0];
			
			var onlyDateElements = dateElement.split('-');
			
			var properDate = onlyDateElements[1] + '/' + onlyDateElements[2] + '/' + onlyDateElements[0];
		
			return properDate;
		}
		
		return null;
	};
	
	var preparePrice = function(group, from, to) {
		if(from && to) {
		
			var properDateFrom = convertDate(from);
			var properDateTo = convertDate(to);
			
			var fromDate = new Date(properDateFrom);
			var toDate = new Date(properDateTo);
			
			
			if(toDate.getTime() > fromDate.getTime()) {
				var difference = Math.abs(toDate.getTime() - fromDate.getTime());
				var days = Math.ceil(difference / (1000 * 3600 * 24)); 
				
				jQuery.ajax({
					  method: "GET",
					  url: "index.php?controller=Ajax&action=rezerwacje_cena_grupy",
					  data: {group_id : group },
					  dataType: 'json'
				})
				.done(function( data ) {
					jQuery('#rpCenaNetto').val(days * data);
					var tax = jQuery('#rpPodatek').val();
					
					var withTax = parseFloat(days * (parseFloat(data) + (parseFloat(data) * parseInt(tax) / 100))).toFixed(2);
					jQuery('#rpCenaBrutto').val(withTax);
				});
			}
		}
	};
	
	var prepareRooms = function(group, from, to) {
		jQuery('#rpPokoj').select2().val(null).trigger('change');
		jQuery.ajax({
		  method: "GET",
		  url: "index.php?controller=Ajax&action=rezerwacje_pokoje",
		  data: {group_id : group, from: from, to: to },
		  dataType: 'json'
		})
		.done(function( data ) {
			jQuery('#rpPokoj').select2({data: data}).val(jQuery('#rpPokoj_restore').val()).trigger('change');
			jQuery('#rpPokoj').removeAttr('disabled');
		})
		.fail(function(jqXHR, textStatus, errorThrown) {
			alert(textStatus);
		});
	};
	
	var getGroupInformation = function(groupId) {
		jQuery.ajax({
			  method: "GET",
			  url: "index.php?controller=Ajax&action=rezerwacje_informacje_o_grupie",
			  data: {group_id : groupId },
			  dataType: 'json'
		})
		.done(function(data) {
			jQuery('#groupIcon').attr('src', data.ikona);
			jQuery('#groupName').text(data.nazwa);
			jQuery('#groupDescription').html(data.opis);
			jQuery('#groupPriceWithoutTax').text(data.cena.netto);
			jQuery('#groupPriceWithTax').text(data.cena.brutto);
			jQuery('#groupInformation').show();
		})
		.fail(function(xhr, status) {
			clearGroupInformation();
		});
	};
	
	var refreshRoomsAvailability = function() {
		var from = jQuery('#rpOd').val();
		var to = jQuery('#rpDo').val();
		var group = jQuery('#rpGrupaPokoi').val();
		
		if(from && to && group) {
			jQuery.ajax({
				  method: "GET",
				  url: "index.php?controller=Ajax&action=rezerwacje_dostepnosc_pokoi",
				  data: {group_id : group, from: from, to: to},
				  dataType: 'text'
			})
			.done(function(data) {
				jQuery('#groupRoomsCount').text(data);
				if(data) {
					jQuery('#rpRezerwacjaStart').show();
				}
				else {
					jQuery('#rpRezerwacjaStart').hide();
				}
			})
			.fail(function(xhr, status) {
				clearGroupInformation();
			});
		}
	};
	
	var clearGroupInformation = function() {
		jQuery('#groupIcon').removeAttr('src');
		jQuery('#groupInformation').hide();
		jQuery('#groupName').text('');
		jQuery('#groupDescription').html('');
		jQuery('#groupPriceWithoutTax').text('');
		jQuery('#groupPriceWithTax').text('');
		
		jQuery('#groupRoomsCount').text('0');
		jQuery('#rpRezerwacjaStart').hide();
	};
	
	jQuery('#rpGrupaPokoi').on('change', function() {
		var value = jQuery(this).val();
		if(value) {
			clearGroupInformation();
			refreshRoomsAvailability();
			getGroupInformation(value);
			
		}
		else {
			clearGroupInformation();
		}
	});
	
	jQuery('#rpOd').on('changeDate', function() {
		refreshRoomsAvailability();
	});
	
	jQuery('#rpDo').on('changeDate', function() {
		refreshRoomsAvailability();		
	});
	
	var group_value = jQuery('#rpGrupaPokoi').val();
	if(group_value) {
		clearGroupInformation();
		refreshRoomsAvailability();
		getGroupInformation(group_value);
	}
	
	var getSalaInformation = function(salaId) {
		jQuery.ajax({
			  method: "GET",
			  url: "index.php?controller=Ajax&action=rezerwacje_informacje_o_sali",
			  data: {sala_id : salaId },
			  dataType: 'json'
		})
		.done(function(data) {
			jQuery('#salaIcon').attr('src', data.ikona);
			jQuery('#salaName').text(data.nazwa);
			jQuery('#salaDescription').html(data.opis);
			jQuery('#salaInformation').show();
		})
		.fail(function(xhr, status) {
			clearSalaInformation();
		});
	};
	
	var refreshTablesAvailability = function() {
		var date = jQuery('#rsData').val();
		var sala = jQuery('#rsSala').val();
		
		if(date && sala) {
			jQuery.ajax({
				  method: "GET",
				  url: "index.php?controller=Ajax&action=rezerwacje_dostepnosc_stolikow",
				  data: {sala_id : sala, data: date},
				  dataType: 'text'
			})
			.done(function(data) {
				jQuery('#salaTablesCount').text(data);
				if(data) {
					jQuery('#rsStolikiStart').show();
				}
				else {
					jQuery('#rsStolikiStart').hide();
				}
			})
			.fail(function(xhr, status) {
				clearSalaInformation();
			});
		}
	};
	
	var clearSalaInformation = function() {
		jQuery('#salaIcon').removeAttr('src');
		jQuery('#salaInformation').hide();
		jQuery('#salaName').text('');
		jQuery('#salaDescription').html('');
		
		jQuery('#salaTablesCount').text('0');
		jQuery('#rsStolikiStart').hide();
	};
	
	jQuery('#rsSala').on('change', function() {
		var value = jQuery(this).val();
		if(value) {
			clearSalaInformation();
			refreshTablesAvailability();
			getSalaInformation(value);
			
		}
		else {
			clearSalaInformation();
		}
	});
	
	jQuery('#rsData').on('changeDate', function() {
		refreshTablesAvailability();
	});
	
	var sala_value = jQuery('#rsSala').val();
	if(sala_value) {
		clearSalaInformation();
		refreshTablesAvailability();
		getSalaInformation(sala_value);
	}
	
	
});
