<div class="view">

<h2>Decaying Of Indicator Fine Tuning Tool</h2>

<div class="row">
    <div class="span7" style="border: 1px solid #ddd; border-radius: 4px;">
        <div style="height: calc(100vh - 180px); overflow-y: scroll;">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th><input id="checkAll" type="checkbox" title="<?php echo __('Check all'); ?>"></input></th>
                        <th>Attribute Type</th>
                        <th>Category</th>
                        <th>Model Name</th>
                        <!-- <th>Action</th> -->
                    </tr>
                </thead>
                <tbody id="attributeTypeTableBody">
                    <?php foreach ($types as $type => $info): ?>
                        <?php if ($info['to_ids'] == 1): ?>
                            <tr>
                                <td><input type="checkbox"></input></td>
                                <td class="useCursorPointer"><?php echo h($type); ?></td>
                                <td class="useCursorPointer"><?php echo h($info['default_category']); ?></td>
                                <td></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="span10">
        <div style="border: 1px solid #ddd; border-radius: 4px; margin-bottom: 20px;">
            <canvas id="decayGraph" style="width: 100%;"></canvas>
        </div>
        <div class="row">
            <div class="span6" style="margin-bottom: 20px;">
                <?php foreach ($parameters as $param => $config): ?>
                    <div class="input-prepend input-append">
                        <span class="add-on" data-toggle="tooltip" data-placement="left" style="min-width: 70px;" title="<?php echo isset($config['info']) ? h($config['info']) : ''?>">
                            <?php echo h($param) . (isset($config['greek']) ? ' <strong>'.h($config['greek']).'</strong>' : ''); ?>
                        </span>
                        <input id="input_<?php echo h($param); ?>" class="input-mini" type="number" min=0 step=<?php echo h($config['step']); ?> value=<?php echo h($config['value']); ?> oninput="refreshGraph(this);" ></input>
                        <span class="add-on"><input id="input_<?php echo h($param); ?>_range" type="range" min=0 <?php echo isset($config['max']) ? 'max=' . $config['max'] : '' ?> step=<?php echo h($config['step']); ?> value=<?php echo h($config['value']); ?> oninput="$('#input_<?php echo h($param); ?>').val(this.value).trigger('input');"></input></span>
                        <?php if (isset($config['unit'])): ?>
                            <span class="add-on"><?php echo h($config['unit']); ?></span>
                        <?php endif; ?>

                    </div>
                <?php endforeach; ?>
            </div>
            <div class="span4">
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <td>Expire after (lifetime)</td>
                            <td id="infoCellExpired"></td>
                        </tr>
                        <tr>
                            <td>Score halved after (Half-life)</td>
                            <td id="infoCellHalved"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="span10">
                <form id="saveForm" class="form-inline">
                    <input type="text" name="name" class="input" placeholder="Model name" required>
                    <textarea  rows="1" name="description" class="input" placeholder="Description"></textarea>
                    <span class="btn btn-success" data-save-type="add" onclick="saveModel(this)"><i class="fa fa-save"> Save</i></span>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="span10">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="2">Model Name</th>
                            <th rowspan="2">Org id</th>
                            <th rowspan="2">Description</th>
                            <th colspan="3">Parameters</th>
                            <th rowspan="2">Action</th>
                        </tr>
                        <tr>
                            <th>Tau</th>
                            <th>Delta</th>
                            <th>Threshold</th>
                        </tr>
                    </thead>
                    <tbody id="modelTableBody">
                        <?php foreach ($savedModels as $k => $model): ?>
                            <tr id="modelId_<?php echo h($model['DecayingModel']['id']); ?>">
                                <td class="DMName"><?php echo h($model['DecayingModel']['name']); ?></td>
                                <td class="DMOrg"><?php echo $this->OrgImg->getOrgImg(array('name' => $model['DecayingModel']['org_id'], 'size' => 24)); ?> </td>
                                <td class="DMDescription"><?php echo h($model['DecayingModel']['description']); ?></td>
                                <td class="DMParameterTau"><?php echo h($model['DecayingModel']['parameters']['tau']); ?></td>
                                <td class="DMParameterDelta"><?php echo h($model['DecayingModel']['parameters']['delta']); ?></td>
                                <td class="DMParameterThreshold"><?php echo h($model['DecayingModel']['parameters']['threshold']); ?></td>
                                <td>
                                    <button class="btn btn-success btn-small" onclick="loadModel(this);"><span class="fa fa-line-chart"><?php echo __(' Load model') ?></span></button>
                                    <button class="btn btn-danger btn-small" data-save-type="edit" data-model-id="<?php echo h($model['DecayingModel']['id']); ?>" onclick="saveModel(this);"><span class="fa fa-paste"><?php echo __(' Overwrite model') ?></span></button>
                                    <button class="btn btn-info btn-small" onclick="applyModel(this);" title="<?php echo __(' Apply model to selected attribute type') ?>"><span class="fa fa-upload"><?php echo __(' Apply model') ?></span></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

</div>

<?php echo $this->element('/genericElements/SideMenu/side_menu', array('menuList' => 'decayingModel', 'menuItem' => 'decayingTool')); ?>
<?php echo $this->Html->script('Chart.min'); ?>
<?php echo $this->Html->script('DecayingTool'); ?>

<script>
</script>
