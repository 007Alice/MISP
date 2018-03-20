// Util
function getRandomColor() {
	var letters = '0123456789ABCDEF';
	var color = '#';
	for (var i = 0; i < 6; i++) {
		color += letters[Math.floor(Math.random() * 16)];
	}
	return color;
}

function getTextColour(hex) {
	hex = hex.slice(1);
	var r = parseInt(hex.substring(0,2), 16);
	var g = parseInt(hex.substring(2,4), 16);
	var b = parseInt(hex.substring(4,6), 16);
	var avg = ((2 * r) + b + (3 * g))/6;
	if (avg < 128) {
		return 'white';
	} else {
		return 'black';
	}
}

function get_node_color(uuid) {
	return nodes.get(uuid).icon.color;
}


// Global var
var shortcut_text = "<b>V:</b> Center camera"
		+ "\n<b>X:</b> Expaned node"
		+ "\n<b>C:</b> Collapse node"
		+ "\n<b>E:</b> Edit node"
		+ "\n<b>SHIFT:</b> Hold to add a reference"
		+ "\n<b>DEL:</b> Delete selected item";

var scope_id = $('#references_network').data('event-id');
var container = document.getElementById('references_network');
var nodes = new vis.DataSet();
var edges = new vis.DataSet();
var map_id_to_uuid = new Map();
var map_fromto_to_rel_id = new Map();
var all_obj_relation = new Map();
var user_manipulation = $('#references_network').data('user-manipulation');
var data = {
	nodes: nodes,
	edges: edges
};


// Options
var network_options = {
	interaction: {
		hover: true
	},
	manipulation: {
		enabled: user_manipulation,
		initiallyActive: false,
		addEdge: add_reference,
		editEdge: false,
		addNode: add_item,
		deleteNode: delete_item,
		deleteEdge: remove_reference
	},
	physics: {
		enabled: true,
		barnesHut: {
			gravitationalConstant: -10000,
			centralGravity: 5,
			springLength: 150,
			springConstant: 0.24,
			damping: 1.4,

		}
	},
	edges: {
		width: 3,
		arrows: 'to'
	},
	nodes: {
		chosen: {
			node: function(values, id, selected, hovering) {
				values.shadow = true;
				values.shadowSize = 5;
				values.shadowX = 2;
				values.shadowY = 2;
				values.shadowColor = "rgba(0,0,0,0.1)";
			}
		}
	},
	groups: {
		object: {
			shape: 'icon',
			icon: {
				face: 'FontAwesome',
				code: '\uf00a',
				size: 50
			},
			font: {
				size: 18, // px
				background: 'rgba(255, 255, 255, 0.7)'
			}
		},
		obj_relation: {
			size: 10,
			color: { 
				border:'black'
			}
		},
		attribute: {
			shape: 'box',
			color: { 
				background:'orange', 
				border:'black'
			},
			size: 15
		},
	},
	locales: {
		en: {
			edit: 'Edit',
			del: 'Delete selected',
			back: 'Back',
			addNode: 'Add Object or Attribute',
			addDescription: 'Click in an empty space to place a new node.',
			addEdge: 'Add Reference',
			edgeDescription: 'Click on an Object and drag the edge to another Object (or Attribute) to connect them.'
		}
	}
};


// Graph interaction
function collapse_node(parent_id) {
	if (parent_id === undefined) { //  No node selected
		return
	}
	var connected_nodes = network.getConnectedNodes(parent_id);
	var connected_edges = network.getConnectedEdges(parent_id);
	// Remove nodes
	for (var nodeID of connected_nodes) {
 		// Object's attribute are in UUID format (while other object or in simple integer)
		if (nodeID.length > 10) {
			nodes.remove(nodeID);
		}
	}

	// Remove edges
	for (var edgeID of connected_edges) {
 		// Object's attribute (edge) are in UUID format (while other object or in simple integer)
		if (edgeID.length > 10) {
			edges.remove(edgeID);
		}
	}
}

function expand_node(parent_id) {
	if (parent_id === undefined) { //  Node node selected
		return;
	} else if (nodes.get(parent_id).group == "attribute") { //  Cannot expand attribute
		return;
	}

	newNodes = [];
	newRelations = [];

	for(var attr of all_obj_relation.get(parent_id)) {
		var parent_color = get_node_color(parent_id);
				
		// Ensure unicity of nodes
		if (nodes.get(attr.uuid) !== null) {
			continue;
		}
				
		var node = { 
			id: attr.uuid,
			label: attr.type + ': ' + attr.value,
			group: 'obj_relation',
			color: { 
				background: parent_color
			},
			font: {
				color: getTextColour(parent_color)
			}
		};
		newNodes.push(node);
				
		var rel = {
			from: parent_id,
			to: attr.uuid,
			arrows: '',
			color: {
				opacity: 0.5,
				color: parent_color
			},
			length: 40
		};
		newRelations.push(rel);
	}
		
	nodes.add(newNodes);
	edges.add(newRelations);
}
			
function remove_reference(edgeData, callback) {
	edge_id = edgeData.edges[0];
	var fromto = edge_id;
	var relation_id = map_fromto_to_rel_id.get(fromto);
	deleteObject('object_references', 'delete', relation_id, scope_id);
}

function add_reference(edgeData, callback) {
	var uuid = map_id_to_uuid.get(edgeData.to);
	genericPopup('/objectReferences/add/'+edgeData.from, '#popover_form', function() {
		$('#targetSelect').val(uuid);
		$('option[value='+uuid+']').click()
	});
}

function add_item(nodeData, callback) {
	choicePopup("Add an element", [
		{
			text: "Add an Object",
			onclick: "getPopup('"+scope_id+"', 'objectTemplates', 'objectChoice');"
		},
		{
			text: "Add an Attribute",
			onclick: "simplePopup('/attributes/add/"+scope_id+"');"
		},
	]);
}

function delete_item(nodeData, callback) {
	var selected_nodes = nodeData.nodes;
	for (nodeID of selected_nodes) {
		node = nodes.get(nodeID)
		if (node.group == "attribute") {
			deleteObject('attributes', 'delete', nodeID, scope_id);
		} else if (node.group == "object") {
			deleteObject('objects', 'delete', nodeID, scope_id);
		}
	}
	
}

function genericPopupCallback(result) {
	if (result == "success") {
		fetch_data_and_update();
		reset_view_on_stabilized();
	}
}

function reset_graphs() {
	nodes.clear();
	edges.clear();
}

function update_graph(data) {
	var total = data.items.length + data.relations.length;
	network_loading(0, total);
	
	// New nodes will be automatically added
	// removed references will be deleted
	newNodes = [];
	newNodeIDs = [];
	for(var node of data.items) {
		var group, label;
		if ( node.node_type == 'object' ) {
			group =  'object';
			label = '('+node.val+') ' + node.type;
			label = node.type;
		} else {
			group =  'attribute';
			label = node.type + ': ' + node.val;
		}
		var node = { 
			id: node.id,
			label: label,
			group: group,
			mass: 5,
			icon: {
				color: getRandomColor()
			}
		};
		newNodes.push(node);
		newNodeIDs.push(node.id);
	}
	// check if nodes got deleted
	var old_node_ids = nodes.getIds();
	for (var old_id of old_node_ids) {
		// This old node got removed
		if (newNodeIDs.indexOf(old_id) == -1) {
			nodes.remove(old_id);
		}
	}

	nodes.update(newNodes);
	network_loading(data.items.length, total);
	
	// New relations will be automatically added
	// removed references will be deleted
	newRelations = [];
	newRelationIDs = [];
	for(var rel of data.relations) {
		var fromto = rel.from + '-' + rel.to;
		var rel = {
			id: fromto,
			from: rel.from,
			to: rel.to,
			label: rel.type,
			title: rel.comment,
			color: {
				opacity: 1.0
			}
		};
		newRelations.push(rel);
		newRelationIDs.push(fromto);
	}
	// check if nodes got deleted
	var old_rel_ids = edges.getIds();
	for (var old_id of old_rel_ids) {
		// This old node got removed
		if (newRelationIDs.indexOf(old_id) == -1) {
			edges.remove(old_id);
		}
	}

	edges.update(newRelations);
	network_loading(total, total);
}

function reset_view() {
	network.fit({animation: true });
}

function reset_view_on_stabilized() {
	network.on("stabilized", function(params) {
		network.fit({ animation: true });
		network.off("stabilized"); //  Removed listener
	});
}

// Data
function extract_references(data) {
	var items = [];
	var relations = [];

	if (data.Attribute !== undefined) {
		for (var attr of data.Attribute) {
			map_id_to_uuid.set(attr.id, attr.uuid);
			items.push({
				'id': attr.id,
				'type': attr.type,
				'val': attr.value,
				'node_type': 'attribute'
			});
		}
	}

	if (data.Object !== undefined) {
		for (var obj of data.Object) {
			map_id_to_uuid.set(obj.id, obj.uuid);
			all_obj_relation.set(obj.id, obj.Attribute);
			items.push({
				'id': obj.id,
				'type': obj.name,
				'val': obj.value,
				'node_type': 'object'
			});
			
			for (var rel of obj.ObjectReference) {
				var fromto = obj.id + '-' + rel.referenced_id;
				map_fromto_to_rel_id.set(fromto, rel.id);
				relations.push({
					'from': obj.id,
					'to': rel.referenced_id,
					'type': rel.relationship_type,
					'comment': rel.comment != "" ? rel.comment : "[Comment not set]"
				});
			}
		}
	}

	return {
		items: items,
		relations: relations
	}
}

function fetch_data_and_update() {
	network_loading(-1, 0);
	$.getJSON( "/events/getReferences/"+scope_id+"/event.json", function( data ) {
		extracted = extract_references(data);
		network_loading(1, 0);
		update_graph(extracted, reset_view);
	});
}

// -1: Undefined state
// 0<=iterations<total: state known
// iterations>=total: finished
function network_loading(iterations, total) {
	var progressbar_length = 3; // divided by 100
	if(iterations == -1) {
		var loadingText = 'Fetching data';
		$('.loading-network-div').show();
		$('.spinner-network').show();
		$('.loadingText-network').text(loadingText);
		$('.loadingText-network').show();
	} else if (iterations >= 0 && iterations < total) {
		var loadingText = 'Constructing network';
		$('.loading-network-div').show();
		$('.loadingText-network').text(loadingText);
		$('.loadingText-network').show();
		$('.spinner-network').hide();
		// pb
		var percentage = parseInt(iterations*100/total);
		$('.progressbar-network-div').show();
		$('#progressbar-network').show();
		$('#progressbar-network').width(percentage*progressbar_length);
		$('#progressbar-network').text(percentage+' %');

	} else if (iterations >= total) {
		$('#progressbar-network').width(100*progressbar_length);
		$('#progressbar-network').text(100+' %');
		setTimeout(function() {
			$('.loading-network-div').hide();
			$('.spinner-network').hide();
			$('.loadingText-network').hide();
			$('.progressbar-network-div').hide();
			$('#progressbar-network').hide();
		}, 1000)
	}
}

//$( document ).ready(function() {
function enable_interactive_graph() {
	// unregister onclick
	$('#references_toggle').removeAttr('onclick');

	// Defer the loading of the network to let some time for the DIV to appear
	setTimeout(function() {
		$('.shortcut-help').popover({
			container: 'body',
			title: 'Shortcuts',
			content: shortcut_text,
			placement: 'left',
			trigger: 'hover',
			html: true,
		});

		network = new vis.Network(container, data, network_options);
		network.on("selectNode", function (params) {
			network.moveTo({
				position: {
					x: params.pointer.canvas.x,
					y: params.pointer.canvas.y
				},
				animation: true,
			});
		});
		// Fit view only when page is loading for the first time
		reset_view_on_stabilized();

		$(document).on("keydown", function(evt) {
			switch(evt.keyCode) {
				case 88: // x
					var selected_id = network.getSelectedNodes()[0]; 
					expand_node(selected_id);
					break;

				case 67: // c
					var selected_id = network.getSelectedNodes()[0]; 
					collapse_node(selected_id);
					break;
				case 86: // v
					reset_view();
					break;

				case 16: // <SHIFT>
					if (!user_manipulation) { // user can't modify references
						break;
					}
					network.addEdgeMode(); // toggle edit mode
					break;

				case 46: // <Delete>
					if (!user_manipulation) { // user can't modify references
						break;
					}
					//  References
					var selected_ids = network.getSelectedEdges(); 
					for (var selected_id of selected_ids) {
						var edge = { edges: [selected_id] }; // trick to use the same function
						remove_reference(edge);
					}

					//  Objects or Attributes
					selected_ids = network.getSelectedNodes();
					data = { nodes: selected_ids };
					delete_item(data);
					break;

				default:
					break;
			}
		});

		$(document).on("keyup", function(evt) {
			switch(evt.keyCode) {
				case 16: // <SHIFT>
					if (!user_manipulation) { // user can't modify references
						break;
					}
					network.disableEditMode(); // un-toggle edit mode
					break;
				default:
					break;
			}

			
		});

		fetch_data_and_update();
	}, 1);
}
