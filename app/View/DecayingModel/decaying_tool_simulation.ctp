<div id="simulationContainer">
    <div style="padding: 15px; height: 90vh; display: flex; flex-direction: column;">
        <div style="height: 40%; display: flex">
            <div style="width: 30%; display: flex; flex-direction: column;">
                <div class="panel-container" style="display: flex; flex-direction: column; flex-grow: 1">
                    <div style="display: flex;">
                        <select id="select_model_to_simulate" onchange="$('#select_model_to_simulate_infobox').popover('show'); refreshSimulation()" style="flex-grow: 1;">
                            <?php foreach ($all_models as $model): ?>
                                <option value="<?php echo h($model['DecayingModel']['id']) ?>" <?php echo $decaying_model['DecayingModel']['id'] == $model['DecayingModel']['id'] ? 'selected' : '' ?>><?php echo h($model['DecayingModel']['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span id="select_model_to_simulate_infobox" class="btn" style="padding: 4px; height: fit-content; margin-left: 5px;"><span class="fa fa-question-circle"></span></span>
                    </div>

                    <ul class="nav nav-tabs" style="margin-right: -5px; margin-bottom: 0px;" id="simulation-tabs">
                        <li class="<?php echo isset($attribute_id) ? '' : 'active'; ?>"><a href="#restsearch" data-toggle="tab">RestSearch</a></li>
                        <li class="<?php echo !isset($attribute_id) ? '' : 'active'; ?>"><a href="#specificid" data-toggle="tab">Specific ID</a></li>
                    </ul>

                    <div class="tab-content" style="padding: 5px; height: 100%;">
                        <div class="tab-pane <?php echo isset($attribute_id) ? '' : 'active'; ?>" id="restsearch" style="height: 100%;">
                            <div style="display: flex; flex-direction: column; height: 100%;">
                                <h3 style="">Attribute RestSearch<span style="vertical-align: top; font-size: x-small;" class="fa fa-question-circle" title="Enforced fields: returnFormat"></span></h3>
<?php
    $registered_taxonomies = array_keys($decaying_model['DecayingModel']['parameters']['base_score_config']);
    foreach ($registered_taxonomies as $i => &$taxonomy_name) {
        $taxonomy_name = $taxonomy_name . ':%' ;
    }
?>
                                <textarea style="margin-bottom: 0px; margin-left: 4px; flex-grow: 3; width: auto;">
{
    "decayingModel": <?php echo h($decaying_model['DecayingModel']['id']); ?>,
    "to_ids": 1,
    "org": <?php echo h($user['Organisation']['id']);?>,
    "tags": <?php echo json_encode($registered_taxonomies); ?>

}</textarea>
                                </br>
                                <span class="btn btn-primary" style="width: fit-content;" role="button" onclick="doRestSearch(this)"><?php echo __('Search'); ?></span>
                            </div>
                        </div>
                        <div class="tab-pane <?php echo !isset($attribute_id) ? '' : 'active'; ?>" id="specificid">
                            <h3 style="">Unique Attribute</h3>
                            <div style="display: flex;">
                                <div style="margin-left: 4px; margin-bottom: 0px;" class="input-prepend">
                                    <span class="add-on">ID</span>
                                    <input type="text" value="<?php echo isset($attribute_id) ? h($attribute_id) : ''; ?>" placeholder="<?php echo __('Attribute ID or UUID') ?>" onkeypress="handle_input_key(event)" style="width: auto;">
                                </div>
                                <span id="performRestSearchButton" class="btn btn-primary" style="width: fit-content; margin-left: 4px;" role="button" onclick="doSpecificSearch(this)"><?php echo __('Simulate'); ?></span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div style="width: 70%; display: flex;">
                <div class="panel-container" style="flex-grow: 1;">
                    <div id="chart-decay-simulation-container" style="width: 100%; height: 100%; position: relative">
                        <div id="simulation_chart" style="height: 100%; overflow: hidden;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div style="height: 60%; overflow-y: auto; background-color: #ffffff;" class="panel-container">
            <div style="height: 100%;" id="attributeTableContainer"></div>
        </div>
    </div>
</div>
<?php echo $this->Html->script('d3'); ?>
<?php echo $this->Html->script('decayingModelSimulation'); ?>
<script>
var model_list = <?php echo json_encode($all_models); ?>;
var models = {};
$(document).ready(function() {
    model_list.forEach(function(m) {
        models[m.DecayingModel.id] = m.DecayingModel;
    });
    $('#select_model_to_simulate_infobox').popover({
        title: function() {
            return $('#select_model_to_simulate option:selected').text();
        },
        content: function() {
            return '<div>' + syntaxHighlightJson(models[$('#select_model_to_simulate').val()]) + '</div>';
        },
        html: true,
        placement: 'bottom'
    });
    <?php echo isset($attribute_id) ? '$("#performRestSearchButton").click();' : ''; ?>
});


function doRestSearch(clicked, query) {
    var data = query === undefined ? $(clicked).parent().find('textarea').val() : query;
    fetchFormDataAjax('/decayingModel/decayingToolRestSearch/', function(formData) {
        var $formData = $(formData);
        url = $formData.find('form').attr('action');
        $('#simulationContainer').append($formData);
        $formData.find('#decayingToolRestSearchFilters').val(data);
        $.ajax({
            data: $formData.find('form').serialize(),
            beforeSend:function() {
                $('#attributeTableContainer').html('<div style="height:100%; display:flex; align-items:center; justify-content:center;"><span class="fa fa-spinner fa-spin" style="font-size: xx-large;"></span></div>');
            },
            success:function (data, textStatus) {
                $('#attributeTableContainer').html(data);
                var $trs = $('#attributeTableContainer tbody > tr');
                if ($trs.length == 1) {
                    $trs.click();
                }
            },
            error:function() {
                showMessage('fail', '<?php echo __('Failed to perform RestSearch') ?>');
            },
            type:'post',
            cache: false,
            url: url,
        });
    });
}

function doSpecificSearch(clicked) {
    doRestSearch(clicked, '{ "id": "' + $(clicked).parent().find('input').val() + '" }');
}

function handle_input_key(e) {
    if(e.keyCode === 13){
        e.preventDefault();
        $('#performRestSearchButton').click();
    }
}

function doSimulation(clicked, attribute_id) {
    $('#attribute_div tr').removeClass('success');
    $(clicked).addClass('success');
    var model_id = $('#select_model_to_simulate').val();
    var simulation_chart = $('#simulation_chart').data('DecayingSimulation');
    if (simulation_chart === undefined) {
        simulation_chart = $('#simulation_chart').decayingSimulation({});
    }
    $.ajax({
        beforeSend:function() {
            simulation_chart.toggleLoading(true);
        },
        success:function (data, textStatus) {
            simulation_chart.update(data, models[model_id]);
        },
        error:function() {
            showMessage('fail', '<?php echo __('Failed to perform the simulation') ?>');
        },
        complete:function() {
            simulation_chart.toggleLoading(false);
        },
        type:'get',
        cache: false,
        dataType: 'json',
        url: '/decayingModel/decayingToolComputeSimulation/' + model_id + '/' + attribute_id,
    });
}

function refreshSimulation() {
    var $row = $('#attribute_div tr.success');
    var attribute_id = $row.find('td:first').text();
    if (attribute_id !== '') {
        doSimulation($row, attribute_id);
    }
}
</script>
