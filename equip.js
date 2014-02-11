


var bindNoteSearchActions = function()
{
	$('#searchByWorkstationFilter').bind('click', function()
	{
		$('#workstationFilterContainer').show();
		$('#equipmentFilterContainer').hide();
	});
	
	$('#searchByEquipmentName').bind('click', function()
	{
		$('#equipmentFilterContainer').show();
		$('#workstationFilterContainer').hide();
	});
	
	if ($("#searchByEquipmentName").is(":checked"))
	{
		$('#searchByEquipmentName').click();
	}
	else if($("#searchByWorkstationFilter").is(":checked"))
	{
		$('#searchByWorkstationFilter').click();
	}
}


var drawEquipStatusGraph = function()
{
	var graphSettings = 
	{
		xaxis:
		{
			mode : "time",
			twelveHourClock : true
		},
		'yaxis':
		{
			'tickFormatter': function(v, axisObj) 
			{ 
				if (v == 0) return 'Down';
				return 'Up';
			},
			'tickSize' : 1,
			'ticks' : [0, 1]
		},
		series: 
		{
			lines : 
			{ 
				show: true, 
				steps: true
			},
			points: 
			{
				show: true
			}
		},
		shadowSize: 5,
		grid: 
		{
			hoverable: false,
			clickable: false
		}
	};
	
	$.plot($('#equipStatusGraph'), [graphData], graphSettings);
}

var equipOnLoad = function()
{
  $('#viewPMsAs').bind('click', function()
	{
		var show = $('#viewPMType').html();
		if (show == 'Widgets')
		{
			$('#viewPMType').html('a Table');
			$('#pm_table_container').hide();
			$('#pm_widget_container').show();
		}
		else if (show == 'a Table')
		{
			$('#viewPMType').html('Widgets');
			$('#pm_table_container').show();
			$('#pm_widget_container').hide();
		}
		return false;
	});
	
	$('.toggleNote').bind('click', function()
	{
		var noteContainer = $(this).parent().parent();
		var noteBody = noteContainer.find('.equip_note_body');
		if (noteBody.is(':visible'))
		{
			//hide and change the toggle note text to [+]
			noteBody.hide();
			$(this).html('[+]');
		}
		else
		{
			//show it and change toggle note text to [-]
			noteBody.show();
			$(this).html('[-]');
		}
	});
	

	
	
	bindNoteSearchActions();
	
	
	/*##################################################################*/
	/*##   Start logic for getting the form for modifying the search  ##*/
	/*##################################################################*/
	$('.modifyNoteSearch').bind('click', function()
	{
		//get a loading popup...
		var popupConfig = {
			'buttons' : {"Submit" : true, "Cancel" : false},
			'popupWidth' : 440,
			'submit' : function(v, modal, f)
			{
				if (handleCancel(v)) return true;
				var equip_name = '';
				if ($('#searchByEquipmentName').is(':checked'))
				{
					//look at the text field
					equip_name = $('#equipment').val();
				}
				else if ($('#searchByWorkstationFilter').is(':checked'))
				{
					equip_name = $('#equip_select').val();
				}
				else
				{
					//if neither is checked that means that we should use the textbox by default
					equip_name = $('#equipment').val();	
				}
				
				var sortOrder = 'DESC';
				if ($('#sortOrderASC').is(':checked')) sortOrder = 'ASC';
				
				
				window.location = '/index.php/equip/notes/' + equip_name + "/" +
						$('#startDateForm').val().replace(/\//g, "-") + "/" + $('#endDateForm').val().replace(/\//g, "-") +
						"/" + $('#hide_automated_check').is(':checked') + '/' + sortOrder + '/' + $('#hide_empty_check').is(':checked');
				$.prompt.destroyOverlayOnClose(false);
				$.prompt.close();
				$.prompt(getLoadingMessage(), {'showOverlay' : false});
				hidePopupButtons();
				hidePopupCloseButton();
				return true;
			}
		};
		$.prompt(getLoadingMessage(), popupConfig);
		hidePopupButtons();
		hidePopupCloseButton();
	
		var requestName = 'getEquipDropdown';
		var scriptUrl = '/index.php/equip/ajaxGetNoteSearchForm/';
		var postObject = {
			'equip_name' : $('#equip_name').val(),
			'start_date' : $('#start_date').val(),
			'end_date' : $('#end_date').val(),
			'hide_automated' : $('#hide_automated').val(),
			'workstation_name' : $('#workstation_name').val(),
			'sortOrder' : $('#sort_order').val(),
			'hideEmpty' : $('#hide_empty').val()
		};

		var returnFxn = function(data)
		{
			populatePopup(data);
			workstationSearchOnLoad();
			attachWSChangeListener();
			initializeDatePicker();
			bindNoteSearchActions();
		};
		ajaxPost(
			requestName, 
			scriptUrl, 
			postObject, 
			returnFxn
		);
	});

	/*#########################################*/
	/*### Start Graph Stuff for Equip Usage ###*/
	/*#########################################*/
	
	Array.prototype.count = function (){
		return this.length;
	}
	
	
	if (typeof usageHistory != 'undefined')
	{
		var usageSettings = 
		{
			xaxis:
			{
				mode : "time",
				twelveHourClock : true
			},
			series: 
			{
				lines : 
				{ 
					show: true, 
					lineWidth: 2
				},
				point: 
				{
					show: true
				}
			},
			shadowSize: 5,
			grid: 
			{
				hoverable: true,
				clickable: false,
				borderWidth: 1
			}
		};
		$.plot($('.equip_usage_chart'), [usageHistory], usageSettings);
	}
		
		
		

		
	if (typeof countHistory != 'undefined')
	{	
		
	$('.equip_rf_chart').bind("plothover", function (event, pos, item) {
		if (item) {
                if (previousPoint1 != item.dataIndex) {
                    previousPoint1 = item.dataIndex;
                    
                    $("#tooltip1").remove();
                    var x = item.datapoint[0].toFixed(0),
					
                        y = item.datapoint[1].toFixed(0);
					x = Number(x);
					var xx = new Date(x);
					xx = xx.toString();
					xx = xx.substr(0, 21);
                    showTooltip1(item.pageX, item.pageY,
                                xx + " Count:" + y);
                }
            }
            else {
                $("#tooltip1").remove();
                previousPoint1 = null;            
            }
        });
	
	
	
	
		var History = [];

		for(var item in countHistory)
		{
			myCount = countHistory[item]['PM_usage_current_count'];
			myDate = Number(countHistory [item]['hist_insert_datetime']);
			if(myCount > 0) History.push([myDate, countHistory [item]['PM_usage_current_count']]);
		}

		
			var usageSettings = 
			{
				series: {
					points: {show: true},
					lines: {show: true}
					},
					
				grid: {hoverable: true, clickable: true},
				xaxis: {
					mode: "time",
					minTickSize: [1, "day"]
				}
			
			};		
			$.plot($('.equip_rf_chart'), [History], usageSettings);
	}
	
	attachWSChangeListener();
}

function showTooltip1(x, y, contents) {
        $('<div id="tooltip1">' + contents + '</div>').css( {
            position: 'absolute',
            display: 'none',
            top: y + 5,
            left: x + 5,
            border: '1px solid #fdd',
            padding: '2px',
            'background-color': '#fee',
            opacity: 0.80
        }).appendTo("body").fadeIn(200);
}

var attachWSChangeListener = function()
{
	$('#workstationSelect').change(function()
	{
		
		buildEquipDropDown($(this).val());
	});
}

/**
 * @param wsNames array - the array of workstation names that are selected.
 */
var buildEquipDropDown = function(wsNames, equipSelectID)
{
	if (typeof equipSelectID == 'undefined') equipSelectID = 'equip_select';
	if (typeof workstations == 'undefined') 
	{
		alert('missing the workstations object!');
		return false;
	}
	var equip_select = $('#' + equipSelectID);
	equip_select.attr('disabled', 'disabled');
	$('input[type=submit]').addClass('disabled');
	$('input[type=submit]').attr('disabled', 'disabled');
	
	if (wsNames != null)
	{
		equip_select.html('<option>Loading...</option>');
		//look at the json!
		var equipFound = false;
		var html = '';
		$.each(wsNames, function(wIndex, wsName)
		{
			var tools = workstations[wsName]['tools'];
			$.each(tools, function(tIndex, tool) 
			{
				equipFound = true;
				html = html + '<option value="' + tool['equip_id'] + '">' + tool['equip_id'] + '</option>';
			});
		});
		
		if (equipFound)
		{
			equip_select.html(html);
		}
		else
		{
			html = '<option>No equip for WS found</option>';
			equip_select.html(html);
			return false;
		}
		
		equip_select.removeAttr('disabled');
		$('input[type=submit]').removeClass('disabled');
		$('input[type=submit]').removeAttr('disabled');
		return true;
	}
	else
	{
		equip_select.html('<option>Please select a workstation.</option>');
	}
}