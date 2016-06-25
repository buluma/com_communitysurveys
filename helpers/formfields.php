<?php
/**
 * @version		$Id: questions.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class SurveyFormFields {
	
	private $_wysiwyg = false;
	private $_bbcode = false;
	private $_content = false;
	
	function __construct($wysiwyg, $bbcode, $content){
		
		$this->_wysiwyg = $wysiwyg;
		$this->_bbcode = $bbcode;
		$this->_content = $content;
	}
	
	public function get_page_header_question($item, $class){
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well qn-page-header '.$class.'">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
		$html .= '</div>';
		
		return $html;
	}
	
	public function get_radio_question($item, $class){
		
		$selected = false;
		$free_text = '';
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant '.$class.'">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
		
		$html .= '<div class="answers'.($item->orientation == 'H' ? '' : ' form-inline').' margin-bottom-20">';

			foreach ($item->answers as $i=>$answer){
				
				$selected = false;
				
				if(!empty($item->responses)){
					
					foreach ($item->responses as $response){
						
						if($answer->id == $response->answer_id){
							
							$selected = true;
						} else if(!empty($response->free_text)){
							
							$free_text = CJFunctions::escape($response->free_text);
						}
					}
				}
								
				$html .= '<label class="radio margin-right-20">';
					$html .= '<input type="radio" name="answer-'.$item->id.'" value="'.$answer->id.'" class="margin-right-5'.($item->mandatory ? ' required' : '').'"'.($selected ? ' checked="checked"' : '').'>';
					$html .= CJFunctions::escape($answer->answer_label);
				$html .= '</label>';
			}
		
		$html .= '</div>';

		if($item->custom_choice == 1){
			
			$html .= '<input type="text" name="free-text-'.$item->id.'" class="input-xlarge valid" value="'.$free_text.'" placeholder="'.JText::_('LBL_ENTER_YOUR_ANSWER').'" aria-required="false">';
		}
		
		$html .= '</div>';
		
		return $html;
	}
	
	public function get_checkbox_question($item, $class){

		$selected = false;
		$free_text = '';
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant '.$class.'">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
		
		$html .= '<div class="answers'.($item->orientation == 'H' ? '' : ' form-inline').' margin-bottom-20">';
			
			foreach ($item->answers as $i=>$answer){

				$selected = false;
				
				if(!empty($item->responses)){
					
					foreach ($item->responses as $response){
							
						if($answer->id == $response->answer_id){
					
							$selected = true;
						} else if(!empty($response->free_text)){
							
							$free_text = CJFunctions::escape($response->free_text);
						}
					}
				}
								
				$html .= '<label class="checkbox margin-right-20">';
					$html .= '<input type="checkbox" name="answer-'.$item->id.'[]" value="'.$answer->id.'" class="margin-right-5'.($item->mandatory ? ' required' : '').'"'.($selected ? ' checked="checked"' : '').'>';
					$html .= CJFunctions::escape($answer->answer_label);
				$html .= '</label>';
			}
		
		$html .= '</div>';

		if($item->custom_choice == 1){
		
			$html .= '<input type="text" name="free-text-'.$item->id.'" class="input-xlarge" value="'.$free_text.'" placeholder="'.JText::_('LBL_ENTER_YOUR_ANSWER').'">';
		}
		
		$html .= '</div>';
		
		return $html;
	}
	
	public function get_select_question($item, $class){

		$selected = false;
		$free_text = '';
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant '.$class.'">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
		
		$html .= '<div class="answers">';
			$html .= '<select name="answer-'.$item->id.'"'.($item->mandatory ? ' class="required"' : '').'>';
				$html .= '<option value="">'.JText::_('LBL_SELECT_OPTION').'</option>';

				foreach ($item->answers as $i=>$answer){

					$selected = false;
					
					if(!empty($item->responses)){
						
						foreach ($item->responses as $response){
								
							if($answer->id == $response->answer_id){
						
								$selected = true;
							} else if(!empty($response->free_text)){
								
								$free_text = CJFunctions::escape($response->free_text);
							}
						}
					}
					
					$html .= '<option value="'.$answer->id.'"'.($selected ? ' selected="selected"' : '').'>'.CJFunctions::escape($answer->answer_label).'</option>';
				}
				
			$html .= '</select>';
		$html .= '</div>';

		if($item->custom_choice == 1){
		
			$html .= '<input type="text" name="free-text-'.$item->id.'" class="input-xlarge" value="'.$free_text.'" placeholder="'.JText::_('LBL_ENTER_YOUR_ANSWER').'">';
		}
		
		$html .= '</div>';
		
		return $html;
	}
	
	public function get_grid_radio_question($item, $class){

		$selected = false;
		$free_text = '';
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant '.$class.'">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
		
		$html .= '<table class="table table-hover table-bordered table-striped margin-bottom-20">';
		$html .= '<thead><tr><th></th>';
		
		foreach ($item->answers as $answer){
			
			if($answer->answer_type == 'y'){
				
				$html .= '<th style="text-align: center;">'.CJFunctions::escape($answer->answer_label).'</th>';
			}
		}
		
		$html .= '</tr></thead>';
		$html .= '<tbody>';
		
		foreach($item->answers as $i=>$answer){
			
			if($answer->answer_type == 'x'){
				
				$html .= '<tr><td>'.CJFunctions::escape($answer->answer_label).'</td>';
				
				foreach ($item->answers as $j=>$column){
					
					if($column->answer_type == 'y'){

						$selected = false;
						
						if(!empty($item->responses)){
	
							foreach ($item->responses as $response){
									
								if(($response->answer_id == $answer->id) && ($response->column_id == $column->id)){
							
									$selected = true;
								} else if(!empty($response->free_text)){
										
									$free_text = CJFunctions::escape($response->free_text);
								}
							}
						}
												
						$html .= '<td style="text-align: center;">';
						$html .= '<input type="radio" name="answer-'.$item->id.'-'.$answer->id.'" value="'.$column->id.'"'.($item->mandatory ? ' class="required"' : '').($selected ? ' checked="checked"' : '').'/>';
						$html .= '</td>';
					}
				}
				
				$html .= '</tr>';
			}
		}
		
		$html .= '</tbody></table>';
		
		if($item->custom_choice == 1){
		
			$html .= '<input type="text" name="free-text-'.$item->id.'" class="input-xlarge" value="'.$free_text.'" placeholder="'.JText::_('LBL_ENTER_YOUR_ANSWER').'">';
		}
		
		$html .= '</div>';
		
		return $html;
	}
	
	public function get_grid_checkbox_question($item, $class){

		$selected = false;
		$free_text = '';
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant '.$class.'">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
		
		$html .= '<table class="table table-hover table-bordered table-striped margin-bottom-20">';
		$html .= '<thead><tr><th></th>';
		
		foreach ($item->answers as $answer){
			
			if($answer->answer_type == 'y'){
				
				$html .= '<th class="center">'.CJFunctions::escape($answer->answer_label).'</th>';
			}
		}
		
		$html .= '</tr></thead>';
		$html .= '<tbody>';
		
		foreach($item->answers as $i=>$answer){
			
			if($answer->answer_type == 'x'){
				
				$html .= '<tr><td>'.CJFunctions::escape($answer->answer_label).'</td>';
				
				foreach ($item->answers as $j=>$column){

					if($column->answer_type == 'y'){

						$selected = false;
						
						if(!empty($item->responses)){
	
							foreach ($item->responses as $response){
									
								if(($response->answer_id == $answer->id) && ($response->column_id == $column->id)){
							
									$selected = true;
								} else if(!empty($response->free_text)){
										
									$free_text = CJFunctions::escape($response->free_text);
								}
							}
						}
												
						$html .= '<td class="center">';
						$html .= '<input type="checkbox" name="answer-'.$item->id.'-'.$answer->id.'[]" value="'.$column->id.'"'.($item->mandatory ? ' class="required"' : '').($selected ? ' checked="checked"' : '').'/>';
						$html .= '</td>';
					}
				}
				
				$html .= '</tr>';
			}
		}
		
		$html .= '</tbody></table>';
		
		if($item->custom_choice == 1){
		
			$html .= '<input type="text" name="free-text-'.$item->id.'" class="input-xlarge" value="'.$free_text.'" placeholder="'.JText::_('LBL_ENTER_YOUR_ANSWER').'">';
		}
		
		$html .= '</div>';
		
		return $html;
	}
	
	public function get_single_line_textbox_question($item, $class){

		$free_text = '';
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant '.$class.'">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
		
		if(!empty($item->responses)){
			
			foreach ($item->responses as $response){
				
				if(!empty($response->free_text)){
					
					$free_text = CJFunctions::escape($response->free_text);
					break;
				}
			}
		}
				
		$html .= '<input type="text" name="free-text-'.$item->id.'" class="input-xlarge'.($item->mandatory ? ' required' : '').'" value="'.$free_text.'" placeholder="'.JText::_('LBL_ENTER_YOUR_ANSWER').'">';
		$html .= '</div>';
		
		return $html;
	}
	
	public function get_multiline_textarea_question($item, $class){

		$free_text = '';
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant '.$class.'">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
		
		if(!empty($item->responses)){
			
			foreach ($item->responses as $response){
				
				if(!empty($response->free_text)){
					
					$free_text = CJFunctions::escape($response->free_text);
					break;
				}
			}
		}
				
		$html .= '<textarea name="free-text-'.$item->id.'" cols="25" rows="3" class="input-xxlarge'.($item->mandatory ? ' required' : '').'" placeholder="'.JText::_('LBL_ENTER_YOUR_ANSWER').'">'.$free_text.'</textarea>';
		
		$html .= '</div>';
		
		return $html;
	}
	
	public function get_password_textbox_question($item, $class){

		$free_text = '';
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant '.$class.'">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
		
		if(!empty($item->responses)){
			
			foreach ($item->responses as $response){
				
				if(!empty($response->free_text)){
					
					$free_text = CJFunctions::escape($response->free_text);
					break;
				}
			}
		}
				
		$html .= '<input type="password" name="free-text-'.$item->id.'" class="input-xlarge'.($item->mandatory ? ' class="required"' : '').'" value="'.$free_text.'" placeholder="'.JText::_('LBL_ENTER_YOUR_ANSWER').'">';
		$html .= '</div>';
		
		return $html;
	}
	
	public function get_rich_textbox_question($item, $class){

		$free_text = '';
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant '.$class.'">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
		
		if(!empty($item->responses)){
			
			foreach ($item->responses as $response){
				
				if(!empty($response->free_text)){
					
					$free_text = $response->free_text;
					break;
				}
			}
		}
				
		$editor = $this->_wysiwyg ? ($this->_bbcode ? 'bbcode' : 'wysiwyg') : 'none';
		$html .= CJFunctions::load_editor($editor, 'free-text-'.$item->id, 'free-text-'.$item->id, $free_text, '5', '23', '100%', '200px', ($item->mandatory ? 'required' : ''), 'width: 99%;');
		
		$html .= '</div>';
		
		return $html;
	}

	public function get_image_radio_question($item, $class, $base_uri){

		$free_text = '';
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant '.$class.'">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';

		$html .= '<div class="answers clearfix"><div class="row-fluid">';

		foreach ($item->answers as $i=>$answer){
		
			$selected = false;
		
			if(!empty($item->responses)){
				
				foreach ($item->responses as $response){
						
					if($answer->id == $response->answer_id){
			
						$selected = true;
					} else if(!empty($response->free_text)){
			
						$free_text = CJFunctions::escape($response->free_text);
					}
				}
			}
			
			if($item->orientation == 'H'){
			
				$html .= '<div class="margin-bottom-20">';
					$html .= '<div class="form-inline margin-bottom-10">';
						$html .= '<label class="radio margin-top-10">';
							$html .= '<input type="radio" name="answer-'.$item->id.'" value="'.$answer->id.'" class="margin-right-10'.($item->mandatory ? ' required' : '').'"'.($selected ? ' checked="checked"' : '').'>';
							$html .= CJFunctions::escape($answer->answer_label);
						$html .= '</label>';
					$html .= '</div>';
					$html .= '<img src="'.$base_uri.$answer->image.'" alt="'.CJFunctions::escape($answer->answer_label).'">';
				$html .= '</div>';
			} else {
			
				$html .= '<div class="thumbnail span2 margin-bottom-20">';
					$html .= '<div class="center">';
						$html .= '<img src="'.$base_uri.$answer->image.'" alt="'.CJFunctions::escape($answer->answer_label).'">';
						$html .= '<div class="form-inline">';
							$html .= '<label class="radio margin-top-10">';
								$html .= '<input type="radio" name="answer-'.$item->id.'" value="'.$answer->id.'" class="margin-right-10'.($item->mandatory ? ' required' : '').'"'.($selected ? ' checked="checked"' : '').'>';
								$html .= CJFunctions::escape($answer->answer_label);
							$html .= '</label>';
						$html .= '</div>';
					$html .= '</div>';
				$html .= '</div>';
			}
		}
		
		$html .= '</div>';
		
		if($item->custom_choice == 1){
		
			$html .= '<input type="text" name="free-text-'.$item->id.'" class="input-xlarge" value="'.$free_text.'" placeholder="'.JText::_('LBL_ENTER_YOUR_ANSWER').'">';
		}
		
		$html .= '</div></div>';
		
		return $html;
	}

	public function get_image_checkbox_question($item, $class, $base_uri){

		$free_text = '';
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant '.$class.'">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';

		$html .= '<div class="answers clearfix"><div class="row-fluid">';

		foreach ($item->answers as $i=>$answer){
		
			$selected = false;
		
			if(!empty($item->responses)){
				
				foreach ($item->responses as $response){
						
					if($answer->id == $response->answer_id){
			
						$selected = true;
					} else if(!empty($response->free_text)){
			
						$free_text = CJFunctions::escape($response->free_text);
					}
				}
			}
						
			if($item->orientation == 'H'){

				$html .= '<div class="margin-bottom-20">';
					$html .= '<div class="form-inline margin-bottom-10">';
						$html .= '<label class="checkbox margin-top-10">';
							$html .= '<input type="checkbox" name="answer-'.$item->id.'[]" value="'.$answer->id.'" class="margin-right-10'.($item->mandatory ? ' required' : '').'"'.($selected ? ' checked="checked"' : '').'>';
							$html .= CJFunctions::escape($answer->answer_label);
						$html .= '</label>';
					$html .= '</div>';
					$html .= '<img src="'.$base_uri.$answer->image.'" alt="'.CJFunctions::escape($answer->answer_label).'">';
				$html .= '</div>';
			} else {

				$html .= '<div class="thumbnail span2 margin-bottom-20">';
					$html .= '<div class="center">';
						$html .= '<img src="'.$base_uri.$answer->image.'" alt="'.CJFunctions::escape($answer->answer_label).'">';
						$html .= '<div class="form-inline">';
							$html .= '<label class="checkbox margin-top-10">';
								$html .= '<input type="checkbox" name="answer-'.$item->id.'[]" value="'.$answer->id.'" class="margin-right-10'.($item->mandatory ? ' required' : '').'"'.($selected ? ' checked="checked"' : '').'>';
								$html .= CJFunctions::escape($answer->answer_label);
							$html .= '</label>';
						$html .= '</div>';
					$html .= '</div>';
				$html .= '</div>';
			}
		}
		
		$html .= '</div></div>';
		
		if($item->custom_choice == 1){
		
			$html .= '<input type="text" name="free-text-'.$item->id.'" class="input-xlarge" value="'.$free_text.'" placeholder="'.JText::_('LBL_ENTER_YOUR_ANSWER').'">';
		}
		
		$html .= '</div>';
		
		return $html;
	}
}