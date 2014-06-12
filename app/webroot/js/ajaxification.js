function deleteObject2(type, id, event) {
	var typeMessage, name, action;
	if (type == 'attributes') {
		action = 'delete';
		typeMessage = 'Attribute';
		name = '#Attribute' + '_' + id + '_delete';
	}
	if (type == 'shadow_attributes') {
		action = 'discard';
		typeMessage = 'Proposal';
		name = '#ShadowAttribute' + '_' + id + '_delete';
	}
	if (confirm("Are you sure you want to delete " + typeMessage + " #" + id + "?")) {
		var formData = $(name).serialize();
		$.ajax({
			data: formData, 
			success:function (data, textStatus) {
				updateIndex(event);
				handleGenericAjaxResponse(data);
			}, 
			type:"post", 
			cache: false,
			url:"/" + type + "/" + action + "/" + id,
		});
	}	
}

function deleteObject(type, action, id, event) {
	var destination = 'attributes';
	if (type == 'shadow_attributes') destination = 'shadow_attributes';
	$.get( "/" + destination + "/" + action + "/" + id, function(data) {
		$("#confirmation_box").fadeIn();
		$("#gray_out").fadeIn();
		$("#confirmation_box").html(data);
		$(window).bind('keypress', function(e) {
			var code = e.keyCode || e.which;
			if (code == 13) {
				submitDeletion(event, action, type, id);
			}
		});
	});
}

function cancelPrompt() {
	$("#confirmation_box").fadeIn();
	$("#gray_out").fadeOut();
	$("#confirmation_box").empty();
}

function submitDeletion(event, action, type, id) {
	var formData = $('#PromptForm').serialize();
	$.ajax({
		beforeSend: function (XMLHttpRequest) {
			$(".loading").show();
		}, 
		data: formData, 
		success:function (data, textStatus) {
			updateIndex(event);
			handleGenericAjaxResponse(data);
		}, 
		complete:function() {
			$(".loading").hide();
			$("#confirmation_box").fadeOut();
			$("#gray_out").fadeOut();
		},
		type:"post", 
		cache: false,
		url:"/" + type + "/" + action + "/" + id,
	});
}

function acceptObject(type, id, event) {
	name = '#ShadowAttribute_' + id + '_accept';
	var formData = $(name).serialize();
	$.ajax({
		data: formData, 
		success:function (data, textStatus) {
			updateIndex(event);
			handleGenericAjaxResponse(data);
		}, 
		type:"post", 
		cache: false,
		url:"/shadow_attributes/accept/" + id,
	});
}	

function updateIndex(id, context) {
	var url, div;
	if (context == 'event') {
		url = "/events/view/" + id + "/attributesPage:1";
		div = "#attributes_div";
	}
	if (context == 'template') {
		url = "/template_elements/index/" + id;
		div = "#templateElements";
	}
	$.ajax({
		beforeSend: function (XMLHttpRequest) {
			$(".loading").show();
		}, 
		dataType:"html", 
		cache: false,
		success:function (data, textStatus) {
			$(".loading").hide();
			$(div).html(data);
		}, 
		url: url,
	});
}

function updateAttributeFieldOnSuccess(name, type, id, field, event) {
	$.ajax({
		beforeSend: function (XMLHttpRequest) {
			if (field != 'timestamp') {
				$(".loading").show();
			}
		}, 
		dataType:"html", 
		cache: false,
		success:function (data, textStatus) {
			if (field != 'timestamp') {
				$(".loading").hide();
				$(name + '_solid').html(data);
				$(name + '_placeholder').empty();
				$(name + '_solid').show();
			} else {
				$('#' + type + '_' + id + '_' + 'timestamp_solid').html(data);
			}
		}, 
		url:"/attributes/fetchViewValue/" + id + "/" + field,
	});
}

function activateField(type, id, field, event) {
	resetForms();
	if (type == 'denyForm') return;
	var objectType = 'attributes';
	if (type == 'ShadowAttribute') {
		objectType = 'shadow_attributes';
	}
	var name = '#' + type + '_' + id + '_' + field;
	$.ajax({
		beforeSend: function (XMLHttpRequest) {
			$(".loading").show();
		}, 
		dataType:"html", 
		cache: false,
		success:function (data, textStatus) {
			$(".loading").hide();
			$(name + '_placeholder').html(data);
			postActivationScripts(name, type, id, field, event);
		}, 
		url:"/" + objectType + "/fetchEditForm/" + id + "/" + field,
	});
}

//if someone clicks an inactive field, replace it with the hidden form field. Also, focus it and bind a focusout event, so that it gets saved if the user clicks away.
//If a user presses enter, submit the form
function postActivationScripts(name, type, id, field, event) {
	$(name + '_field').focus();
	inputFieldButtonActive(name + '_field');
	if (field == 'value' || field == 'comment') {
		autoresize($(name + '_field')[0]);
		$(name + '_field').on('keyup', function () {
		    autoresize(this);
		});
	}
	$(name + '_form').submit(function(e){ 
		e.preventDefault();
		submitForm(type, id, field, event);
		return false;
	});
	
	$(name + '_form').bind("focusout", function() {
		inputFieldButtonPassive(name + '_field');
	});

	$(name + '_form').bind("focusin", function(){
		inputFieldButtonActive(name + '_field');
	});
	
	$(name + '_form').bind("keydown", function(e) {
		if (e.ctrlKey && (e.keyCode == 13 || e.keyCode == 10)) {
			submitForm(type, id, field, event);
		}
	});
	$(name + '_field').closest('.inline-input-container').children('.inline-input-accept').bind('click', function() {
		submitForm(type, id, field, event);
	});
	
	$(name + '_field').closest('.inline-input-container').children('.inline-input-decline').bind('click', function() {
		resetForms();
	});

	$(name + '_solid').hide();
}

function resetForms() {
	$('.inline-field-solid').show();
	$('.inline-field-placeholder').empty();
}

function inputFieldButtonActive(selector) {
	$(selector).closest('.inline-input-container').children('.inline-input-accept').removeClass('inline-input-passive').addClass('inline-input-active');
	$(selector).closest('.inline-input-container').children('.inline-input-decline').removeClass('inline-input-passive').addClass('inline-input-active');
}

function inputFieldButtonPassive(selector) {
	$(selector).closest('.inline-input-container').children('.inline-input-accept').addClass('inline-input-passive').removeClass('inline-input-active');
	$(selector).closest('.inline-input-container').children('.inline-input-daecline').addClass('inline-input-passive').removeClass('inline-input-active');
}

function autoresize(textarea) {
    textarea.style.height = '20px';
    textarea.style.height = (textarea.scrollHeight) + 'px';
}

// submit the form - this can be triggered by unfocusing the activated form field or by submitting the form (hitting enter)
// after the form is submitted, intercept the response and act on it 
function submitForm(type, id, field, event) {
	var object_type = 'attributes';
	if (type == 'ShadowAttribute') object_type = 'shadow_attributes';
	var name = '#' + type + '_' + id + '_' + field;
	$.ajax({
		data: $(name + '_field').closest("form").serialize(),
		cache: false,
		success:function (data, textStatus) {
			handleAjaxEditResponse(data, name, type, id, field, event);
		}, 
		error:function() {
			showMessage('fail', 'Request failed for an unknown reason.');
			updateIndex(event);
		},
		type:"post", 
		url:"/" + object_type + "/editField/" + id
	});
	$(name + '_field').unbind("keyup");
	$(name + '_form').unbind("focusout");
	return false;
};

function submitTagForm(id) {
	$.ajax({
		data: $('#EventTag').closest("form").serialize(), 
		beforeSend: function (XMLHttpRequest) {
			$(".loading").show();
		}, 
		success:function (data, textStatus) {
			loadEventTags(id);
			handleGenericAjaxResponse(data);
		}, 
		error:function() {
			showMessage('fail', 'Could not add tag.');
			loadEventTags(id);
		},
		complete:function() {
			$(".loading").hide();
		},
		type:"post", 
		url:"/events/addTag/" + id
	});
	return false;
}

function handleAjaxEditResponse(data, name, type, id, field, event) {
	responseArray = JSON.parse(data);
	if (type == 'Attribute') {
		if (responseArray.saved) {
			showMessage('success', responseArray.success);
			updateAttributeFieldOnSuccess(name, type, id, field, event);
			updateAttributeFieldOnSuccess(name, type, id, 'timestamp', event);
		} else {
			showMessage('fail', 'Validation failed: ' + responseArray.errors.value);
			updateAttributeFieldOnSuccess(name, type, id, field, event);
		}
	}
	if (type == 'ShadowAttribute') {
		updateIndex(event);
	}
}

function handleGenericAjaxResponse(data) {
	if (typeof data == 'string') {
		responseArray = JSON.parse(data);
	} else {
		responseArray = data;
	}
	if (responseArray.saved) {
		showMessage('success', responseArray.success);
	} else {
		showMessage('fail', responseArray.errors);
	}
}

function toggleAllAttributeCheckboxes() {
	if ($(".select_all").is(":checked")) {
		$(".select_attribute").prop("checked", true);
	} else {
		$(".select_attribute").prop("checked", false);
	}
}

function attributeListAnyCheckBoxesChecked() {
	if ($('input[type="checkbox"]:checked').length > 0) $('.mass-select').show();
	else $('.mass-select').hide();
}


function deleteSelectedAttributes(event) {
	var answer = confirm("Are you sure you want to delete all selected attributes?");
	if (answer) {
		var selected = [];
		$(".select_attribute").each(function() {
			if ($(this).is(":checked")) {
				var test = $(this).data("id");
				selected.push(test);
			}
		});
		$('#AttributeIds').attr('value', JSON.stringify(selected));
		var formData = $('#delete_selected').serialize();
		$.ajax({
			data: formData, 
			cache: false,
			type:"POST", 
			url:"/attributes/deleteSelected/" + event,
			success:function (data, textStatus) {
				updateIndex(event);
				handleGenericAjaxResponse(data);
			}, 
		});
	}
	return false;
}

function editSelectedAttributes(event) {
	$.get("/attributes/editSelected/"+event, function(data) {
		$("#popover_form").fadeIn();
		$("#gray_out").fadeIn();
		$("#popover_form").html(data);
	});
}

function getSelected() {
	var selected = [];
	$(".select_attribute").each(function() {
		if ($(this).is(":checked")) {
			var test = $(this).data("id");
			selected.push(test);
		}
	});
	return JSON.stringify(selected);
}

function loadEventTags(id) {
	$.ajax({
		dataType:"html", 
		cache: false,
		success:function (data, textStatus) {
			$(".eventTagContainer").html(data);
		}, 
		url:"/tags/showEventTag/" + id,
	});
}

function removeEventTag(event, tag) {
	var answer = confirm("Are you sure you want to remove this tag from the event?");
	if (answer) {
		var formData = $('#removeTag_' + tag).serialize();
		$.ajax({
			beforeSend: function (XMLHttpRequest) {
				$(".loading").show();
			}, 
			data: formData, 
			type:"POST", 
			cache: false,
			url:"/events/removeTag/" + event + '/' + tag,
			success:function (data, textStatus) {
				loadEventTags(event);
				handleGenericAjaxResponse(data);
			}, 
			complete:function() {
				$(".loading").hide();
			}
		});
	}
	return false;
}

function clickCreateButton(event, type) {
	var destination = 'attributes';
	if (type == 'Proposal') destination = 'shadow_attributes';
	$.get( "/" + destination + "/add/" + event, function(data) {
		$("#popover_form").fadeIn();
		$("#gray_out").fadeIn();
		$("#popover_form").html(data);
	});
}

function submitPopoverForm(context_id, referer) {
	var url = null;
	var context = 'event';
	var contextNamingConvention = 'Attribute';
	if (referer == 'add') url = "/attributes/add/" + context_id;
	if (referer == 'propose') url = "/shadow_attributes/add/" + context_id;
	if (referer == 'massEdit') url = "/attributes/editSelected/" + context_id;
	if (referer == 'addTextElement') {
		url = "/templateElements/templateElementAdd/text/" + context_id;
		context = 'template';
		contextNamingConvention = 'TemplateElementText';
	}
	if (referer == 'addAttributeElement') {
		url = "/templateElements/templateElementAdd/attribute/" + context_id;
		context = 'template';
		referer = 'TemplateElementAttribute';
		contextNamingConvention = 'TemplateElementAttribute';
	}
	if (url !== null) {
		$.ajax({
			beforeSend: function (XMLHttpRequest) {
				$(".loading").show();
				$("#gray_out").fadeOut();
				$("#popover_form").fadeOut();
			}, 
			data: $("#submitButton").closest("form").serialize(), 
			success:function (data, textStatus) {
				handleAjaxPopoverResponse(data, context_id, url, referer, context, contextNamingConvention);
				$(".loading").show();
			}, 
			type:"post", 
			url:url
		});
	}
};

function handleAjaxPopoverResponse(response, context_id, url, referer, context, contextNamingConvention) {
	responseArray = JSON.parse(response);
	var message = null;
	if (responseArray.saved) {
		updateIndex(context_id, context);
		if (responseArray.success) {
			showMessage("success", responseArray.success);
		}
		if (responseArray.errors) {
			showMessage("fail", responseArray.errors);
		}
	} else {
		var savedArray = saveValuesForPersistance();
		$.ajax({
			async:true, 
			dataType:"html", 
			success:function (data, textStatus) {
				$("#gray_out").fadeIn();
				$("#popover_form").fadeIn();
				$("#popover_form").html(data);
				var error_context = context.charAt(0).toUpperCase() + context.slice(1);
				handleValidationErrors(responseArray.errors, context, contextNamingConvention);
				if (!isEmpty(responseArray)) {
					$("#formWarning").show();
					$("#formWarning").html('The object(s) could not be saved. Please, try again.');
				}
				recoverValuesFromPersistance(savedArray);
				$(".loading").hide();
			},
			url:url
		});	
	}
}

function isEmpty(obj) {
	var name;
	for (name in obj) {
		return false;
	}
	return true;
}

//before we update the form (in case the action failed), we want to retrieve the data from every field, so that we can set the fields in the new form that we fetch 
function saveValuesForPersistance() {
	var formPersistanceArray = new Array();
	for (i = 0; i < fieldsArray.length; i++) {
		formPersistanceArray[fieldsArray[i]] = document.getElementById(fieldsArray[i]).value;
	}
	return formPersistanceArray;
}

function recoverValuesFromPersistance(formPersistanceArray) {
	for (i = 0; i < fieldsArray.length; i++) {
		 document.getElementById(fieldsArray[i]).value = formPersistanceArray[fieldsArray[i]];
	}
}

function handleValidationErrors(responseArray, context, contextNamingConvention) {
	for (var k in responseArray) {
		var elementName = k.charAt(0).toUpperCase() + k.slice(1);
		console.log("#" + contextNamingConvention + elementName);
		$("#" + contextNamingConvention + elementName).parent().addClass("error");
		$("#" + contextNamingConvention + elementName).parent().append("<div class=\"error-message\">" + responseArray[k] + "</div>");
	}
}

function toggleHistogramType(type, old) {
	var done = false;
	old.forEach(function(entry) {
		if (type == entry) {
			done = true;
			old.splice(old.indexOf(entry), 1);
		}
	});
	if (done == false) old.push(type);
	updateHistogram(JSON.stringify(old));
}

function updateHistogram(selected) {
	$.ajax({
		beforeSend: function (XMLHttpRequest) {
			$(".loading").show();
		}, 
		dataType:"html", 
		cache: false,
		success:function (data, textStatus) {
			$(".loading").hide();
			$("#histogram").html(data);
		}, 
		url:"/users/histogram/" + selected,
	});
}

function showMessage(success, message) {
	$("#ajax_" + success).html(message);
	var duration = 1000 + (message.length * 40);
	$("#ajax_" + success + "_container").fadeIn("slow");
	$("#ajax_" + success + "_container").delay(duration).fadeOut("slow");
}

function cancelPopoverForm() {
	$("#popover_form").empty();
	$('#gray_out').fadeOut();
	$('#popover_form').fadeOut();
}

function activateTagField() {
	$("#addTagButton").hide();
	$("#addTagField").show();
}

function tagFieldChange() {
	if ($("#addTagField :selected").val() > 0) {
		var selected = $("#addTagField :selected").text();
		if ($.inArray(selected, selectedTags)==-1) {
			selectedTags.push(selected);
			appendTemplateTag(selected);
		}
	}
	$("#addTagButton").show();
	$("#addTagField").hide();
}

function appendTemplateTag(selected) {
	var selectedTag;
	allTags.forEach(function(tag) {
		if (tag.name == selected) {
			$.ajax({
				beforeSend: function (XMLHttpRequest) {
					$(".loading").show();
				}, 
				dataType:"html", 
				cache: false,
				success:function (data, textStatus) {
					$(".loading").hide();
					$("#tags").append(data);
				}, 
				url:"/tags/viewTag/" + tag.id,
			});
			updateSelectedTags();
		}
	});
}

function addAllTags(tagArray) {
	parsedTagArray = JSON.parse(tagArray);
	parsedTagArray.forEach(function(tag) {
		appendTemplateTag(tag);
	});
}

function removeTemplateTag(id, name) {
	selectedTags.forEach(function(tag) {
		if (tag == name) {
			var index = selectedTags.indexOf(name);
			if (index > -1) {
				selectedTags.splice(index, 1);
				updateSelectedTags();
			}
		}
	});
	$('#tag_bubble_' + id).remove();
}

function updateSelectedTags() {
	$('#hiddenTags').attr("value", JSON.stringify(selectedTags));
}

function saveElementSorting(order) {
	$.ajax({
		data: order, 
		dataType:"json",
		contentType: "application/json",
		cache: false,
		success:function (data, textStatus) {
			handleGenericAjaxResponse(data);
		}, 
		type:"post", 
		cache: false,
		url:"/templates/saveElementSorting/",
	});
}

function templateAddElementClicked(id) {
	$("#gray_out").fadeIn();
	$.ajax({
		beforeSend: function (XMLHttpRequest) {
			$(".loading").show();
		}, 
		dataType:"html", 
		cache: false,
		success:function (data, textStatus) {
			$(".loading").hide();
			$("#popover_form").html(data);
			$("#popover_form").fadeIn();
		}, 
		url:"/template_elements/templateElementAddChoices/" + id,
	});
}

function templateAddElement(type, id) {
	$.ajax({
		dataType:"html", 
		cache: false,
		success:function (data, textStatus) {
			$("#popover_form").html(data);
		}, 
		url:"/template_elements/templateElementAdd/" + type + "/" + id,
	});
}

function templateUpdateAvailableTypes() {
	$("#innerTypes").empty();
	var type = $("#TemplateElementAttributeType option:selected").text();
	var complex = $('#TemplateElementAttributeComplex:checked').val();
	if (complex && type != 'Select Type') {
		currentTypes.forEach(function(entry) {
			$("#innerTypes").append("<div class=\"templateTypeBox\" id=\"" + entry + "TypeBox\">" + entry + "</div>");
		});
		$('#outerTypes').show();
	}
	else $('#outerTypes').hide();
}

function populateTemplateTypeDropdown() {
	var cat = $("#TemplateElementAttributeCategory option:selected").text();
	currentTypes = [];
	if (cat == 'Select Category') {
		$('#TemplateElementAttributeType').html("<option>Select Type</option>");
	} else {
		var complex = $('#TemplateElementAttributeComplex:checked').val();
		if (cat in typeGroupCategoryMapping) {
			$('#TemplateElementAttributeType').html("<option>Select Type</option>");
			typeGroupCategoryMapping[cat].forEach(function(entry) {
				$('#TemplateElementAttributeType').append("<option>" + entry + "</option>");
			});
		} else {
			complex = false;
		}
		if (!complex) {
			$('#TemplateElementAttributeType').html("<option>Select Type</option>");
			categoryTypes[cat].forEach(function(entry) {
				$('#TemplateElementAttributeType').append("<option>" + entry + "</option>");
			});
		}
	}
}

function templateElementAttributeTypeChange() {
	var complex = $('#TemplateElementAttributeComplex:checked').val();
	var type = $("#TemplateElementAttributeType option:selected").text();
	currentTypes = [];
	if (type != 'Select Type') {
		if (complex) {
			complexTypes[type]["types"].forEach(function(entry) {
				currentTypes.push(entry);
			});
		} else {
			currentTypes.push(type);
		}
	} else {
		currentTypes = [];
	}
	$("#typeJSON").html(JSON.stringify(currentTypes));
	templateUpdateAvailableTypes();
}

function templateElementAttributeCategoryChange(category) {
	if (category in typeGroupCategoryMapping) {
		$('#complexToggle').show();
	} else {
		$('#complexToggle').hide();
	}
	if (category != 'Select Type') {
		populateTemplateTypeDropdown();
	}
	templateUpdateAvailableTypes();
}