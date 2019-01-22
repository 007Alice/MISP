<div class="popover_choice">
        <?php echo $this->Form->create('ObjectReference', array('url' => '/objectReferences/add/' . $objectId));?>
        <fieldset>
            <legend><?php echo __('Add Object Reference'); ?></legend>
                <div class="overlay_spacing">
                    <div class="row-fluid">
                        <div class="span6">
                            <?php
                                echo $this->Form->input('relationship_type_select', array(
                                    'label' => __('Relationship type'),
                                    'options' => $relationships,
                                    'style' => 'width:334px;',
                                    'div' => false
                                ));
                        ?>
                            <div id="" class="hidden">
                                <label for="ObjectReferenceRelationshipTypeSelect"><?php echo __('Relationship type');?></label>
                                <?php
                                    echo $this->Form->input('relationship_type', array(
                                        'label' => false,
                                        'style' => 'width:320px;',
                                        'div' => false
                                    ));
                                ?>
                            </div>
                        </div>
                        <div class="span6">
                            <?php
                                echo $this->Form->input('comment', array(
                                    'label' => __('Comment'),
                                    'rows' => 1,
                                    'style' => 'width:320px;height:20px !important;'
                                ));
                            ?>
                        </div>
                    </div>
                    <div class="input clear"></div>
                    <div class="row-fluid">
                        <div class="span6">
                            <?php
                                echo $this->Form->input('referenced_uuid', array(
                                    'label' => __('Target UUID'),
                                    'div' => false,
                                    'style' => 'width:320px;'
                                ));
                            ?>
                            <br />

                            <?php
                                $items = array();
                                if (!empty($event['Object'])){
                                    $template = '<it class="fa fa-th-large"></it> ';
                                    $template .= '{{=it.name}}';
                                    $template .= '<it class="fa fa-info-circle" style="float:right;margin-top:5px;line-height:13px;" title="{{=it.attributes}}"></it>';
                                    $template .= '<div class="apply_css_arrow" style="padding-left: 5px; font-size: smaller;"><i>{{=it.metaCategory}}</i></div>';

                                    foreach ($event['Object'] as $object) {
                                        $combinedFields = __('Object');
                                        $combinedFields .= '/' . h($object['meta-category']);
                                        $combinedFields .= '/' . h($object['name']);

                                        $attributes = array();
                                        $attributesValues = array();
                                        foreach ($object['Attribute'] as $attribute) {
                                            $combinedFields .= '/' . h($attribute['value']);
                                            $attributesValues[] = h($attribute['value']);
                                            $attributes[] = h($attribute['value']);
                                            $combinedFields .= '/' . h($attribute['id']);
                                        }
                                        $attributesValues = implode(', ', $attributesValues);
                                        $items[] = array(
                                            'name' => $combinedFields,
                                            'value' => h($object['uuid']),
                                            'additionalData' => array(
                                                'type' => 'Object'
                                            ),
                                            'template' => $template,
                                            'templateData' => array(
                                                'type' => __('Object'),
                                                'name' => h($object['name']),
                                                'metaCategory' => h($object['meta-category']),
                                                'attributes' => h($attributesValues),
                                            )
                                        );
                                    }
                                }
                                if (!empty($event['Attribute'])) {
                                    $template = '{{=it.value}}';
                                    $template .= '<it style="float: right;border: 1px solid #999; background-color: #ddd;border-radius: 5px;padding: 1px;margin-top: 5px; color:#000;line-height:13px;" class="chosen-single-hiddenXX">ids: <it style="margin-right: 0px;line-height:13px;" class="fa fa-{{=it.ids}}"></it></it>';
                                    $template .= '<div class="apply_css_arrow" style="padding-left: 5px; font-size: smaller;"><i>{{=it.category}} :: {{=it.type}}</i></div>';
                                    foreach ($event['Attribute'] as $attribute) {
                                        $combinedFields = __('Attribute');
                                        $combinedFields .= '/' . h($attribute['category']);
                                        $combinedFields .= '/' . h($attribute['type']);
                                        $combinedFields .= '/' . h($attribute['value']);
                                        $combinedFields .= '/' . h($attribute['id']);
                                        $items[] = array(
                                            'name' => $combinedFields,
                                            'value' => h($attribute['uuid']),
                                            'additionalData' => array(
                                                'type' => 'Attribute'
                                            ),
                                            'template' => $template,
                                            'templateData' => array(
                                                'value' => h($attribute['value']),
                                                'category' => h($attribute['category']),
                                                'type' => h($attribute['type']),
                                                'ids' => $attribute['to_ids'] ? 'check' : 'times'
                                            )
                                        );
                                    }
                                }
                                $options = array(
                                    'functionName' => 'changeObjectReferenceSelectOption',
                                    'chosen_options' => array('width' => '334px'),
                                    'select_options' => array('data-targetselect' => 'targetSelect')
                                );
                                echo $this->element('generic_picker', array('items' => $items, 'options' => $options));
                            ?>

                            <!-- <select id="targetSelect" size="10" style="width:100%;height:200px;">
                                <?php
                                    if (!empty($event['Object'])):
                                        foreach ($event['Object'] as $object):
                                            $combinedFields = __('Object');
                                            $combinedFields .= '/' . h($object['meta-category']);
                                            $combinedFields .= '/' . h($object['name']);
                                            foreach ($object['Attribute'] as $attribute) {
                                                $combinedFields .= '/' . $attribute['value'];
                                                $combinedFields .= '/' . $attribute['id'];
                                            }
                                ?>
                                            <option value="<?php echo h($object['uuid']);?>" data-type="Object"><?php echo $combinedFields; ?></option>
                                <?php
                                        endforeach;
                                    endif;
                                    if (!empty($event['Attribute'])):
                                        foreach ($event['Attribute'] as $attribute):
                                            $combinedFields = __('Attribute');
                                            $combinedFields .= '/' . h($attribute['category']);
                                            $combinedFields .= '/' . h($attribute['type']);
                                            $combinedFields .= '/' . h($attribute['value']);
                                            $combinedFields .= '/' . h($attribute['id']);
                                ?>
                                            <option class="selectOption" value="<?php echo h($attribute['uuid']);?>" data-type="Attribute"><?php echo $combinedFields; ?></option>
                                <?php
                                        endforeach;
                                    endif;
                                ?>
                            </select> -->


                        </div>
                        <div class="span6">
                            <label for="selectedData"><?php echo __('Target Details');?></label>
                            <div class="redHighlightedBlock" id="targetData">
                                &nbsp;
                            </div>
                        </div>
                    </div>
                    <div>
                        <table style="margin-bottom:5px;">
                            <tr>
                                <td>
                                    <span id="submitButton" class="btn btn-primary" title="<?php echo __('Submit');?>" role="button" tabindex="0" aria-label="<?php echo __('Submit');?>" onClick="submitPopoverForm('<?php echo h($objectId); ?>', 'addObjectReference')"><?php echo __('Submit');?></span>
                                </td>
                                <td style="width:100%;">&nbsp;</td>
                                <td>
                                    <span class="btn btn-inverse" title="<?php echo __('Cancel');?>" role="button" tabindex="0" aria-label="<?php echo __('Cancel');?>" onClick="cancelPopoverForm();"><?php echo __('Cancel');?></span>
                                </td>
                            </tr>
                        </table>
                    </div>
                <?php
                    echo $this->Form->end();
                ?>
            </div>
        </fieldset>
    </div>
</div>
<script type="text/javascript">
    var targetEvent = <?php echo json_encode($event); ?>;
    $(document).ready(function() {
        $('#ObjectReferenceReferencedUuid').on('input', function() {
            objectReferenceInput();
        });
        $(".selectOption").click(function() {
            changeObjectReferenceSelectOption();
        });
        $("#ObjectReferenceRelationshipTypeSelect").change(function() {
            objectReferenceCheckForCustomRelationship();
        });

        $('#ObjectReferenceRelationshipTypeSelect').chosen({ width: "100%" });
    });
</script>
<?php echo $this->Js->writeBuffer(); // Write cached scripts
