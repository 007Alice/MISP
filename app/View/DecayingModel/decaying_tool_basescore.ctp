<div class="row" style="padding: 15px;">
    <div class="span8" style="height: calc(90vh); overflow-y: scroll; border: 1px solid #ddd;">
        <input id="table_taxonomy_search" class="input" style="width: 250px; margin: 0px;" type="text" placeholder="<?php echo _('Search Taxonomy'); ?>"></input>
        <it class="fa fa-times useCursorPointer" title="<?php echo __('Clear search field'); ?>" onclick="$('#table_taxonomy_search').val('').trigger('input');"></it>
        <span style="float: right; margin-top: 6px;" class="badge badge-info"><b><?php echo h($taxonomies_not_having_numerical_value); ?></b><?php echo __(' not having numerical value'); ?></span>
        <table id="tableTaxonomy" class="table table-striped table-bordered table-condensed">
            <thead>
                <tr>
                    <th><?php echo __('Taxonomies') ?></th>
                    <th><?php echo __('Weight') ?></th>
                </tr>
            </thead>
            <tbody id="body_taxonomies">
                <?php foreach ($taxonomies as $name => $taxonomy): ?>
                    <tr>
                        <td>
                            <div class="btn-group">
                                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                      <?php echo h($name) ?>
                                      <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <?php foreach ($taxonomy['TaxonomyPredicate'] as $p => $predicate): ?>
                                        <?php foreach ($predicate['TaxonomyEntry'] as $e => $entry): ?>
                                            <li>
                                                <a style="position: relative; padding: 3px 5px;">
                                                    <span class="tag"
                                                    style="margin-right: 35px;background-color: <?php echo $entry['Tag']['colour']; ?>;color:<?php echo $this->TextColour->getTextColour($entry['Tag']['colour']);?>"
                                                    title="<?php echo sprintf('%s: %s', h($entry['expanded']), h($entry['description'])) ?>"><?php echo $entry['Tag']['name']; ?>
                                                    </span>
                                                    <span class="label label-inverse" style="position: absolute; right: 5px; top: 50%; margin-top: -9px;
"><?php echo h($entry['numerical_value']) ?></span>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </td>
                        <td>
                            <input id="slider_<?php echo h($name) ?>" data-taxonomyname="<?php echo h($name) ?>" type="range" min=0 max=100 step=1 value="<?php echo isset($taxonomy['value']) ? h($taxonomy['value']) : 0 ?>" onchange="sliderChanged(this);" oninput="sliderChanged(this);"></input>
                            <input type="number" min=0 max=100 step=1 value="<?php echo isset($taxonomy['value']) ? h($taxonomy['value']) : 0 ?>" style="display: inline-block; margin-left: 5px; margin: 0px; width: 40px;" data-taxonomyname="<?php echo h($name) ?>" onchange="inputChanged(this);" oninput="inputChanged(this);"></input>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="span8">
        <div style="margin-bottom: 5px;">
            <div id="treemapGraphTax" style="border: 1px solid #dddddd; border-radius: 4px; text-align: center;"></div>
        </div>
        <div style="margin-bottom: 5px; border: 1px solid #dddddd; border-radius: 4px; text-align: center; background-color: white;">
            <?php echo __('Placeholder for `Organisation source confidence`') ?>
        </div>
        <div>
            <h3><?php echo __('Example') ?><it class="fa fa-sync useCursorPointer" style="margin-left: 5px; font-size: small;" onclick="refreshExamples()"></it></h3>
            <table id="tableTaxonomy" class="table table-striped table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>Attribute</th>
                        <th>Tags</th>
                        <th>Base score</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            Tag your attribute
                        </td>
                        <td>
                            <div style="width:100%;display:inline-block;" data-original-title="" title="">
                                <div id="basescore-example-customtag-container" style="float:left" data-original-title="" title="">
                                    <button id="basescore-example-score-addTagButton" class="btn btn-inverse noPrint" style="line-height:10px; padding: 4px 4px;" title="Add tag" onclick="addTagWithValue(this);">+</button>
                                </div>
                            </div>
                        </td>
                        <td id="basescore-example-score-custom" style="position: relative; text-align: center; vertical-align: middle;">
                        </td>
                    </tr>
                    <tr onclick="genHelpBaseScoreComputation(1)">
                        <td>Attribute 1</td>
                        <td id="basescore-example-tag-1"><?php echo __('Pick a Taxonomy'); ?></td>
                        <td id="basescore-example-score-1" style="position: relative; text-align: center; vertical-align: middle;"></td>
                    </tr>
                    <tr onclick="genHelpBaseScoreComputation(2)">
                        <td>Attribute 2</td>
                        <td id="basescore-example-tag-2"><?php echo __('Pick a Taxonomy'); ?></td>
                        <td id="basescore-example-score-2" style="position: relative; text-align: center; vertical-align: middle;"></td>
                    </tr>
                    <tr onclick="genHelpBaseScoreComputation(3)">
                        <td>Attribute 3</td>
                        <td id="basescore-example-tag-3"><?php echo __('Pick a Taxonomy'); ?></td>
                        <td id="basescore-example-score-3" style="position: relative; text-align: center; vertical-align: middle;"></td>
                    </tr>
                </tbody>
            </table>

            <h3><?php echo __('Computation steps') ?></h3>
            <div id="computation_help_container" style="margin-bottom: 5px; border: 1px solid #dddddd; border-radius: 4px; text-align: center; background-color: white;">
                <table class="table histogram-legendH4">
                    <thead>
                        <tr>
                            <th rowspan="2" style="vertical-align: middle;"><?php echo __('Tag') ?></th>
                            <th colspan="3"><?php echo __('Computation') ?></th>
                            <th rowspan="2" style="vertical-align: middle;"><?php echo __('Result') ?></th>
                        </tr>
                        <tr>
                            <th style="padding: 0px; width: 90px;"><?php echo __('Taxonomy ratio') ?></th>
                            <th></th>
                            <th style="padding: 0px; width: 90px;"><?php echo __('Tag value') ?></th>
                        </tr>
                    </thead>
                    <tbody id="computation_help_container_body">
                        <tr><td></td><td></td><td></td><td></td><td></td></tr>
                    </tbody>
                </table>
                <b id="pick_notice"><?php echo __('Pick an Attribute') ?></b>
            </div>
        </div>
        <span class="btn btn-primary" onclick="applyBaseScoreConfig();"><i class="fas fa-wrench"> Apply base score</i></span>
    </div>
</div>

<?php
echo $this->element('genericElements/assetLoader', array(
    'css' => array(
        'treemap',
    )
));
?>

<script>
var taxonomies_with_num_value = <?php echo json_encode($taxonomies); ?>;
var mapping_tag_name_to_tag = {};
var base_score_computation = [];

function getRandomInt(max) {
    return Math.floor(Math.random() * Math.floor(max));
}

function addTagWithValue(clicked) {
    var $select = regenerateValidTags();
    var html = '<div>' + $select[0].outerHTML + '<button class="btn btn-primary" style="margin-left: 5px;" onclick="addPickedTags(this)"><?php echo __('Tag'); ?></button> </div>';
    openPopover(clicked, html, false, 'right', function($popover) {
        $popover.find('select').chosen({
            width: '300px',
        });
    });
}

function addPickedTags(clicked) {
    $select = $('#basescore-example-tag-picker');
    $select.val().forEach(function(tag_id) {
        var tag = mapping_tag_name_to_tag[tag_id];
        var tag_html = '<div style="display: inline-block;"><span class="tagFirstHalf" style="background-color: ' + tag.colour + '; color: ' + getTextColour(tag.colour) + ';">' + tag.name + '</span>'
            + '<span class="tagSecondHalf useCursorPointer" onclick="$(this).add($(this).prev()).remove()">×</span></div>&nbsp;'
        $('#basescore-example-customtag-container').append(tag_html);
    });
    $('#basescore-example-score-addTagButton').popover('destroy');
}

function regenerateValidTags() {
    var $select = $('<select id="basescore-example-tag-picker" multiple="multiple"/>');
    Object.keys(taxonomies_with_num_value).forEach(function(taxonomy_name) {
        var taxonomy = taxonomies_with_num_value[taxonomy_name];
        var $optgroup = $('<optgroup label="' + taxonomy_name + '"></optgroup>');
            taxonomy['TaxonomyPredicate'].forEach(function(predicate) {
                predicate['TaxonomyEntry'].forEach(function(entry) {
                    mapping_tag_name_to_tag[entry['Tag']['id']] = entry['Tag'];
                    var $option = $('<option value="' + entry['Tag']['id'] + '">' + entry['Tag']['name'] + ' [' + entry['Tag']['numerical_value'] + ']</option>');
                    $optgroup.append($option);
                });
            });
        $select.append($optgroup);
    });
    return $select;
}

function applyBaseScoreConfig() {
    decayingTool.apply_base_score(getRatioScore());
    $('#popover_form_large').fadeOut();
    $('#gray_out').fadeOut();
}

function fetchTaxonomyConfig() {
    var matching_inputs = $('#body_taxonomies > tr')
    .find('input[type="number"]')
    .filter(function() {
        return parseInt($(this).val()) > 0;
    });
    var taxonomy_config = {};
    matching_inputs.each(function() {
        taxonomy_config[$(this).data('taxonomyname')] = parseInt($(this).val());
    });
    return taxonomy_config;
}

function getRatioScore(taxonomies_name) {
    if (taxonomies_name === undefined) {
        taxonomies_name = Object.keys(fetchTaxonomyConfig());
    }
    var config = fetchTaxonomyConfig();
    var ratioScore = {};
    total_score = taxonomies_name.reduce(function(acc, name) {
        return acc + config[name];
    }, 0)
    taxonomies_name.forEach(function(name) {
        ratioScore[name] = config[name] / total_score;
    });
    return ratioScore;
}

function pickRandomTags() {
    var taxonomies_name = Object.keys(fetchTaxonomyConfig());
    var tags = [];

    if (taxonomies_name.length == 0) {
        return [];
    }

    var temp_taxonomies_name = taxonomies_name.slice();
    var max_tag_num = taxonomies_name.length > 3 ? 3 : taxonomies_name.length;
    var picked_tag_number = getRandomInt(max_tag_num) + 1;
    for (var i = 0; i < picked_tag_number; i++) { // number of tags
        // pick a taxonomy
        var picked_number_taxonomy = getRandomInt(temp_taxonomies_name.length);
        var picked_taxonomy_name = temp_taxonomies_name[picked_number_taxonomy];
        var picked_taxonomy = taxonomies_with_num_value[picked_taxonomy_name];
        // pick a random predicate
        var picked_number_predicate = getRandomInt(picked_taxonomy['TaxonomyPredicate'].length);
        var picked_predicate = picked_taxonomy['TaxonomyPredicate'][picked_number_predicate];
        // pick a random entry -> tag
        var picked_number_entry = getRandomInt(picked_predicate['TaxonomyEntry'].length);
        var picked_entry = picked_predicate['TaxonomyEntry'][picked_number_entry];
        picked_entry['Tag']['numerical_value'] = parseInt(picked_entry['Tag']['numerical_value']);
        tags.push(picked_entry['Tag']);
        // delete temp_taxonomies_name[picked_number_taxonomy];
        temp_taxonomies_name.splice(picked_number_taxonomy, 1);
    }
    return tags;
}

function html_computation_step(steps, i) {
    var step = steps.steps.computation[i];
    if (step === undefined) {
        return ['', '', ''];
    }
    var html1 = step.ratio.toFixed(2);
    var html2 = '*';
    var html3 = step.tag_value.toFixed(2);
    return [html1, html2, html3];
}

function computation_step(steps, i) {
    var step = steps.steps.computation[i];
    if (step === undefined) { // last row, just sum everything up
        return  (steps.score).toFixed(2);
    }
    return  (step.ratio * step.tag_value).toFixed(2);
}

function genHelpBaseScoreComputation(index) {
    $('#tableTaxonomy > tbody > tr').removeClass('success').css('font-weight', 'inherit');
    $('#tableTaxonomy > tbody > tr:nth-child(' + (index+1) + ')').addClass('success').css('font-weight', 'bold');
    var steps = base_score_computation[index];
    var $tags = $('#basescore-example-tag-'+index + ' > span');
    var last_tag_index = $tags.length;
    $tags.push($(''));
    $('#computation_help_container_body').empty();
    var	tbody = d3.select('#computation_help_container_body');

    // create a row for each object in the data
    var rows = tbody.selectAll('tr')
        .data($tags)
        .enter()
        .append('tr')
        .attr('class', function(e, row_i) {
            if (last_tag_index == row_i) {
                return 'cellHeavyTopBorder bold';
            }
        });

    // create a cell in each row for each column
    var cells = rows.selectAll('td')
        .data(function (tag, row_i) {
            var html_computation = html_computation_step(steps, row_i);
            return [
                tag.outerHTML,
                html_computation[0], html_computation[1], html_computation[2],
                computation_step(steps, row_i),
            ]
        });
    cells.enter()
        .append('td')
        .html(function (e) { return e; })
        .style('opacity', 0.0)
        .transition().duration(500)
        .style('opacity', 1.0);

    $('#pick_notice').remove();

}

function refreshExamples() {
    for (var i = 1; i <= 3; i++) {
        var numerical_values = [];
        tags = pickRandomTags();
        tags_html = '';
        tags.forEach(function(tag) {
            numerical_values.push({name: tag.name.split(':')[0], value: tag['numerical_value']})
            var text_color = getTextColour(tag.colour);
            tags_html += '<span class="tag decayingExampleTags" style="background-color: ' + tag.colour + ';color:' + text_color + '" '
                + 'title="numerical_value=' + tag['numerical_value'] + '" '
                + 'data-placement="right">' + tag.name + '</span>&nbsp;';
        });

        base_score_computation[i] = compute_base_score(numerical_values);
        var base_score = base_score_computation[i].score.toFixed(1);
        var base_score_computation_steps = base_score_computation[i].steps;
        $('#basescore-example-tag-'+i).empty().append(tags_html);
        $('#basescore-example-score-'+i).empty()
            .text(base_score)
            .append('<i class="fas fa-question-circle helptext-in-cell useCursorPointer" onclick="genHelpBaseScoreComputation(' + i + ')"></i>');
        $('span.decayingExampleTags').tooltip();
    }
}

function compute_base_score(numerical_values) {
    var base_score = 0;
    var config = getRatioScore(numerical_values.map(x => x.name));
    var steps = {
        init: {
            config
        },
        computation: []
    };
    numerical_values.forEach(function(tag) {
        base_score += config[tag.name] * tag.value;
        steps.computation.push({ ratio: config[tag.name], tag_value: tag.value });
    });
    return { score: base_score, steps: steps };
}

function filterTableTaxonomy(searchString) {
    var $table = $('#tableTaxonomy');
    var $body = $table.find('tbody');
    if (searchString === '') {
        $body.find('tr').forceClass('hidden', false);
    } else {
        $body.find('tr').forceClass('hidden', true);
        // show only matching elements
        var $cells = $body.find('tr > td:nth-child(1)');
        $cells.each(function() {
            if ($(this).text().trim().toUpperCase().indexOf(searchString.toUpperCase()) != -1) {
                $(this).parent().forceClass('hidden', false);
            }
        });
    }
}

$('#table_taxonomy_search').on('input', function() {
    filterTableTaxonomy(this.value);
});

var refreshExamplesTimeout = null;
function refreshExamplesThrottle() {
    if (refreshExamplesTimeout !== null) {
        clearTimeout(refreshExamplesTimeout);
    }
    refreshExamplesTimeout = setTimeout(function() { refreshExamples(); }, 200);
}

function sliderChanged(changed) {
    $(changed).parent().find('input[type="number"]').val(changed.value);
    var new_data = genTreeData();
    updateTree(new_data);
    refreshExamplesThrottle();
}
function inputChanged(changed) {
    $(changed).parent().find('input[type="range"]').val(changed.value);
    var new_data = genTreeData();
    updateTree(new_data);
    refreshExamplesThrottle();
}

function updateTree(new_data) {
    var treemap = d3.layout.treemap()
        .size([width, height])
        .sticky(true)
        .value(function(d) { return d.size; });
    var nodes = div.datum(new_data).selectAll(".node")
        .data(treemap.nodes);

    nodes.enter()
        .append("div")
        .attr("class", "node useCursorPointer")
        .style("background", function(d) {
            if (d.depth == 0) {
                return 'white';
            } else if (!d.children) {
                return color(d.name);
            } else {
                return null;
            }
        })
        .attr("id", function(d) { return d.name + '-node'})
        .on('click', function() { $('#table_taxonomy_search').val(d3.select(this).data()[0].name).trigger('input');})
    nodes.transition().duration(100)
        .call(position)
        .attr("title", function(d) { return d.name + ': ' + d.size})
        .text(function(d) {
            if (d.children) {
                return '';
            } else if (d.name !== '' && !isNaN(d.ratio) ) {
                return d.name + ' ('+parseInt(d.ratio*100)+'%)';
            } else {
                return '';
            }
        });

    nodes.exit()
        .remove();
}

function genTreeData() {
    var root = {
        name: '',
        children: []
    };
    var sum = 0;
    var $sliders = $('#body_taxonomies').find('input[type="range"]');
    $sliders.each(function(){
        sum += parseInt($(this).val());
    });
    $sliders.each(function(){
        var val = parseInt($(this).val());
        if (val > 0) {
            var tmp = {
                name: $(this).data('taxonomyname'),
                size: val,
                ratio: val/sum
            };
            root.children.push(tmp);
        }
    });
    return root;
}

    var root = genTreeData();
    var margin = {top: 0, right: 0, bottom: 0, left: 0},
    	width = 620 - margin.left - margin.right,
    	height = 500 - margin.top - margin.bottom;

    var color = d3.scale.category20c();

    var treemap = d3.layout.treemap()
        .size([width, height])
        .sticky(true)
        .value(function(d) { return d.size; });

    var div = d3.select("#treemapGraphTax").append("div").text('No taxonomy')
    	.style("position", "relative")
    	.style("width", (width + margin.left + margin.right) + "px")
    	.style("height", (height + margin.top + margin.bottom) + "px")
    	.style("left", margin.left + "px")
    	.style("top", margin.top + "px");

    updateTree(root);
    refreshExamples();

    function position() {
      this.style("left", function(d) { return d.x + "px"; })
          .style("top", function(d) { return d.y + "px"; })
          .style("width", function(d) { return Math.max(0, d.dx - 1) + "px"; })
          .style("height", function(d) { return Math.max(0, d.dy - 1) + "px"; });
    }
</script>
