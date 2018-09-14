<?php 

// vars
$field = fieldmaster_extract_var( $args, 'field');
$groups = fieldmaster_extract_var( $field, 'conditional_logic');
$disabled = empty($groups) ? 1 : 0;


// UI needs at least 1 conditional logic rule
if( empty($groups) ) {
	
	$groups = array(
		
		// group 0
		array(
			
			// rule 0
			array()
		
		)
		
	);
	
}

?>
<tr class="fm-field fm-field-true-false fm-field-setting-conditional_logic" data_type="true_false" data-name="conditional_logic">
	<td class="fieldmaster-label">
		<label><?php _e("Conditional Logic",'fieldmaster'); ?></label>
	</td>
	<td class="fieldmaster-input">
		<?php 
		
		fieldmaster_render_field(array(
			'type'			=> 'true_false',
			'name'			=> 'conditional_logic',
			'prefix'		=> $field['prefix'],
			'value'			=> $disabled ? 0 : 1,
			'ui'			=> 1,
			'class'			=> 'conditional-toggle',
		));
		
		?>
		<div class="rule-groups" <?php if($disabled): ?>style="display:none;"<?php endif; ?>>
			
			<?php foreach( $groups as $group_id => $group ): 
				
				// validate
				if( empty($group) ) continue;
				
				
				// vars
				// $group_id must be completely different to $rule_id to avoid JS issues
				$group_id = "group_{$group_id}";
				$h4 = ($group_id == "group_0") ? __("Show this field if",'fieldmaster') : __("or",'fieldmaster');
				
				?>
				<div class="rule-group" data-id="<?php echo $group_id; ?>">
				
					<h4><?php echo $h4; ?></h4>
					
					<table class="fieldmaster-table -clear">
						<tbody>
						<?php foreach( $group as $rule_id => $rule ): 
							
							// valid rule
							$rule = wp_parse_args( $rule, array(
								'field'		=>	'',
								'operator'	=>	'==',
								'value'		=>	'',
							));
							
							
							// vars		
							// $group_id must be completely different to $rule_id to avoid JS issues
							$rule_id = "rule_{$rule_id}";
							$prefix = "{$field['prefix']}[conditional_logic][{$group_id}][{$rule_id}]";
							
							?>
							<tr class="rule" data-id="<?php echo $rule_id; ?>">
								<td class="param">
									<?php 
									
									$choices = array();
									$choices[ $rule['field'] ] = $rule['field'];
									
									// create field
									fieldmaster_render_field(array(
										'type'		=> 'select',
										'prefix'	=> $prefix,
										'name'		=> 'field',
										'value'		=> $rule['field'],
										'choices'	=> $choices,
										'class'		=> 'conditional-rule-param',
										'disabled'	=> $disabled,
									));										
		
									?>
								</td>
								<td class="operator">
									<?php 	
									
									$choices = array(
										'=='	=>	__("is equal to",'fieldmaster'),
										'!='	=>	__("is not equal to",'fieldmaster'),
									);
									
									
									// create field
									fieldmaster_render_field(array(
										'type'		=> 'select',
										'prefix'	=> $prefix,
										'name'		=> 'operator',
										'value'		=> $rule['operator'],
										'choices' 	=> $choices,
										'class'		=> 'conditional-rule-operator',
										'disabled'	=> $disabled,
									)); 	
									
									?>
								</td>
								<td class="value">
									<?php 
									
									$choices = array();
									$choices[ $rule['value'] ] = $rule['value'];
									
									// create field
									fieldmaster_render_field(array(
										'type'		=> 'select',
										'prefix'	=> $prefix,
										'name'		=> 'value',
										'value'		=> $rule['value'],
										'choices'	=> $choices,
										'class'		=> 'conditional-rule-value',
										'disabled'	=> $disabled,
									));
									
									?>
								</td>
								<td class="add">
									<a href="#" class="button add-conditional-rule"><?php _e("and",'fieldmaster'); ?></a>
								</td>
								<td class="remove">
									<a href="#" class="fieldmaster-icon -minus remove-conditional-rule"></a>
								</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					
				</div>
			<?php endforeach; ?>
			
			<h4><?php _e("or",'fieldmaster'); ?></h4>
			
			<a href="#" class="button add-conditional-group"><?php _e("Add rule group",'fieldmaster'); ?></a>
			
		</div>
		
	</td>
</tr>