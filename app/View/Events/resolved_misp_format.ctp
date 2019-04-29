<div class="index">
    <h2><?php echo h($title); ?></h2>
    <?php
        $event_id = $event['Event']['id'];
        $url = '/events/handleModuleResults/' . $event_id;
        echo $this->Form->create('Event', array('url' => $url, 'class' => 'mainForm'));
        $formSettings = array(
            'type' => 'hidden',
            'value' => json_encode($event, true)
        );
        echo $this->Form->input('data', $formSettings);
        echo $this->Form->input('JsonObject', array(
                'label' => false,
                'type' => 'text',
                'style' => 'display:none;',
                'value' => ''
        ));
        if (!isset($importComment)) {
            $importComment = $attributeValue . ': Enriched via the ' . $module . ' module';
        }
        echo $this->Form->input('default_comment', array(
                'label' => false,
                'type' => 'text',
                'style' => 'display:none;',
                'value' => $importComment
        ));
        echo $this->Form->end();
        $objects_array = array();
        foreach (array('Attribute', 'Object') as $field) {
            if (!empty($event[$field])) {
                $objects_array[] = strtolower($field) . 's';
            }
        }
        if (empty($objects_array)) {
            echo '<p>Results from the enrichment module for this attribute are empty.</p>';
        } else {
            $scope = join(' and ', $objects_array);
            echo '<p>Below you can see the ' . $scope . 'that are to be created from the results of the enrichment module.</p>';
        }
        $attributeFields = array('category', 'type', 'value', 'uuid');
        $header_present = false;
        if (!empty($event['Object'])) {
    ?>
    <table class='table table-striped table-condensed'>
      <tbody>
        <tr>
          <th><?php echo __('Category');?></th>
          <th><?php echo __('Type');?></th>
          <th><?php echo __('Value');?></th>
          <th><?php echo __('UUID');?></th>
          <th><?php echo __('IDS');?></th>
          <th><?php echo __('Disable Correlation');?></th>
          <th><?php echo __('Comment');?></th>
          <th><?php echo __('Distribution');?></th>
        </tr>
        <?php
            $header_present = true;
            foreach ($event['Object'] as $o => $object) {
        ?>
        <tbody class='MISPObject'>
          <tr class='tableHighlightBorderTop borderBlue blueRow' tabindex='0'>
            <td colspan="6">
              <?php if(!empty($object['id'])) { ?>
              <span class="bold"><?php echo __('ID: ');?></span><span class="ObjectID"><?php echo h($object['id']); ?></span><br />
              <?php } ?>
              <span class="bold"><?php echo __('Name: ');?></span><span class="ObjectName"><?php echo h($object['name']); ?></span>
              <span class="fa fa-expand useCursorPointer" title="<?php echo __('Expand or Collapse');?>" role="button" tabindex="0" aria-label="<?php echo __('Expand or Collapse');?>" data-toggle="collapse" data-target="#Object_<?php echo $o; ?>_collapsible"></span><br />
              <div id="Object_<?php echo $o; ?>_collapsible" class="collapse">
                <span class="bold"><?php echo __('UUID: ');?></span><span class="ObjectUUID"><?php echo h($object['uuid']); ?></span><br />
                <span class="bold"><?php echo __('Meta Category: ');?></span><span class="ObjectMetaCategory"><?php echo h($object['meta-category']); ?></span>
              </div>
              <span class="bold"><?php echo __('References: ')?></span>
              <?php
                if (!empty($object['ObjectReference'])) {
                    echo sizeof($object['ObjectReference']);
              ?>
              <span class="fa fa-expand useCursorPointer" title="<?php echo __('Expand or Collapse');?>" role="button" tabindex="0" aria-label="<?php echo __('Expand or Collapse');?>" data-toggle="collapse" data-target="#Object_<?php echo $o; ?>_references_collapsible"></span>
              <div id="Object_<?php echo $o; ?>_references_collapsible" class="collapse">
              <?php
                    foreach ($object['ObjectReference'] as $reference) {
                        echo '&nbsp;&nbsp;<span class="ObjectReference">';
                        echo '<span class="Relationship">' . h($reference['relationship_type']) . ' </span>';
                        $referenced_uuid = $reference['referenced_uuid'];
                        foreach ($event['Object'] as $object_reference) {
                            if ($referenced_uuid === $object_reference['uuid']) {
                                $name = $object_reference['name'];
                                $category = $object_reference['meta-category'];
                                $objectType = 'Object';
                                break;
                            }
                        }
                        if (!isset($name)) {
                            foreach ($event['Attribute'] as $attribute_reference) {
                                if ($referenced_uuid === $attribute_reference['uuid']) {
                                    $name = $attribute_reference['type'];
                                    $category = $attribute_reference['category'];
                                    $objectType = 'Attribute';
                                    break;
                                }
                            }
                            if (!isset($name)) {
                                $name = '';
                                $category = '';
                                $objectType = '';
                            }
                        }
                        echo $objectType . ' <span class="ReferencedUUID">' . $referenced_uuid . '</span> (' . $name . ': ' . $category . ')</span><br />';
                        unset($name);
                    }
                    echo '</div>';
                } else {
                    echo 0;
                }
              ?>
            </td>
            <td class="ObjectComment shortish"><?php echo (!empty($object['comment']) ? h($object['comment']) : ''); ?></td>
            <td style="width:60px;text-align:center;">
              <select class="ObjectDistribution" style="padding:0px;height:20px;margin-bottom:0px;">
                <?php
                foreach ($distributions as $distKey => $distValue) {
                    echo '<option value="' . h($distKey) . '" ' . ($distKey == $object['distribution'] ? 'selected="selected"' : '') . '>' . h($distValue) . '</option>';
                }
                ?>
              </select>
              <div style="display:none;">
                <select class='ObjectSharingGroup' style='padding:0px;height:20px;margin-top:3px;margin-bottom:0px;'>
                  <?php
                    foreach ($sgs as $sgKey => $sgValue) {
                        echo '<option value="' . h($sgKey) . '" ' . ($sgKey == $object['sharing_group_id'] ? 'selected="selected"' : '') . '>' . h($sgValue) . '</option>';
                    }
                  ?>
                </select>
              </div>
            </td>
          </tr>
          <?php
                if (!empty($object['Attribute'])) {
                    $last_attribute = end($object['Attribute']);
                    foreach ($object['Attribute'] as $a => $attribute) {
                        $border_position = ($attribute == $last_attribute ? 'Bottom' : 'Center');
          ?>
          <tr class="ObjectAttribute tableHighlightBorder<?php echo $border_position; ?> borderBlue">
            <td class="ObjectCategory"><?php echo (isset($attribute['category']) ? h($attribute['category']) : ''); ?></td>
            <td class="short">
              <span class="ObjectRelation bold"><?php echo h($attribute['object_relation']); ?></span>:
              <span class="AttributeType"><?php echo h($attribute['type']); ?></span>
            </td>
            <?php
                        foreach (array('value', 'uuid') as $field) {
                            echo '<td class="Attribute' . ucfirst($field) . '">' . h($attribute[$field]) . '</td>';
                        }
            ?>
            <td class="short" style="width:40px;text-align:center;">
              <input type="checkbox" class="AttributeToIds" <?php if (!empty($attribute['to_ids'])) echo 'checked'; ?>/>
            </td>
            <td class="short" style="width:40px;text-align:center;">
              <input type="checkbox" class="AttributeDisableCorrelation" <?php if (!empty($attribute['disable_correlation'])) echo 'checked'; ?>/>
            </td>
            <td class="short">
              <input type="text" class="AttributeComment" style="padding:0px;height:20px;margin-bottom:0px;" placeholder="<?php echo h($importComment); ?>" <?php if (!empty($attribute['comment'])) echo 'value="' . h($attribute['comment']) . '"';?>/>
            </td>
            <td class="short" style="width:40px;text-align:center;">
              <select class='AttributeDistribution' style='padding:0px;height:20px;margin-bottom:0px;'>
                <?php
                        foreach ($distributions as $distKey => $distValue) {
                            echo '<option value="' . h($distKey) . '" ' . ($distKey == $attribute['distribution'] ? 'selected="selected"' : '') . '>' . h($distValue) . '</option>';
                        }
                ?>
              </select>
              <div style="display:none;">
                <select class='AttributeSharingGroup' style='padding:0px;height:20px;margin-top:3px;margin-bottom:0px;'>
                  <?php
                        foreach ($sgs as $sgKey => $sgValue) {
                            echo '<option value="' . h($sgKey) . '" ' . ($sgKey == $attribute['sharing_group_id'] ? 'selected="selected"' : '') . '>' . h($sgValue) . '</option>';
                        }
                  ?>
                </select>
              </div>
            </td>
            <?php
                        echo '</tr>';
                    }
                }
                echo '<tr><td colspan="8" /></tr>';
            ?>
        </tbody>
        <?php
            }
        }
        if (!empty($event['Attribute'])) {
            foreach ($event['Attribute'] as $a => $attribute) {
                echo '<tr class="MISPAttribute">';
                foreach (array('category', 'type') as $field) {
                    $field_header = 'class="Attribute' . ucfirst($field);
                    if (isset($attribute[$field])) {
                        if (is_array($attribute[$field])) {
                            echo '<td class="short" style="width:40px;text-align:center;"><select ' . $field_header . 'Select"  style="padding:0px;height:20px;margin-bottom:0px;">';
                            foreach ($attribute[$field] as $v => $value) {
                                echo '<option value="' . h($value) . '" ' . ($v ? '' : 'selected="selected"') . '>' . h($value) . '</option>';
                            }
                            echo '</select></td>';
                        } else {
                            echo '<td ' . $field_header . '">' . h($attribute[$field]) . '</td>';
                        }
                    } else {
                        echo '<td ' . $field_header . '"></td>';
                    }
                }
                foreach (array('value', 'uuid') as $field) {
                    echo '<td class="Attribute' . ucfirst($field) . '">' . h($attribute[$field]) . '</td>';
                }
        ?>
          <td class="short" style="width:40px;text-align:center;">
            <input type="checkbox" class="AttributeToIds" <?php if (isset($attribute['to_ids']) && $attribute['to_ids']) echo 'checked'; ?>/>
          </td>
          <td class="short" style="width:40px;text-align:center;">
            <input type="checkbox" class="AttributeDisableCorrelation" <?php if (isset($attribute['disable_correlation']) && $attribute['disable_correlation']) echo 'checked'; ?>/>
          </td>
          <td class="short">
            <input type="text" class="AttributeComment" style="padding:0px;height:20px;margin-bottom:0px;" placeholder="<?php echo h($importComment); ?>" <?php if (!empty($attribute['comment'])) echo 'value="' . h($attribute['comment']) . '"';?>/>
          </td>
          <td class="short" style="width:40px;text-align:center;">
            <select class='AttributeDistribution' style='padding:0px;height:20px;margin-bottom:0px;'>
            <?php
                foreach ($distributions as $distKey => $distValue) {
                    echo '<option value="' . h($distKey) . '" ' . ($distKey == $attribute['distribution'] ? 'selected="selected"' : '') . '>' . h($distValue) . '</option>';
                }
            ?>
            </select>
            <div style="display:none;">
              <select class='AttributeSharingGroup' style='padding:0px;height:20px;margin-top:3px;margin-bottom:0px;'>
                <?php
                foreach ($sgs as $sgKey => $sgValue) {
                    echo '<option value="' . h($sgKey) . '" ' . ($sgKey == $attribute['sharing_group_id'] ? 'selected="selected"' : '') . '>' . h($sgValue) . '</option>';
                }
                ?>
              </select>
            </div>
          </td>
          <?php
                echo '</tr>';
            }
          ?>
      </tbody>
    </table>
    <?php } ?>
    <span>
      <button class="btn btn-primary" style="float:left;" onClick="moduleResultsSubmit('<?php echo h($event_id); ?>');"><?php echo __('Submit'); ?></button>
    </span>
</div>
<script type="text/javascript">
    $(document).ready(function() {
      $('.AttributeDistribution').change(function() {
          if ($(this).val() == 4) {
              $(this).next().show();
          } else {
              $(this).next().hide();
          }
      });
      $('.ObjectDistribution').change(function() {
          if ($(this).val() == 4) {
              $(this).next().show();
          } else {
              $(this).next().hide();
          }
      });
    });
</script>
<?php
    if (!isset($menuItem)) {
        $menuItem = 'freetextResults';
    }
    echo $this->element('/genericElements/SideMenu/side_menu', array('menuList' => 'event', 'menuItem' => $menuItem));
?>
