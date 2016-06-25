<?php
/**
 * @version		$Id: qnresults.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class SurveyQuestionResults {
	
	private $_wysiwyg = false;
	private $_bbcode = false;
	private $_content = false;
	private $_score = 0;
	private $_count = 0;
	
	function __construct($wysiwyg, $bbcode, $content){
		
		$this->_wysiwyg = $wysiwyg;
		$this->_bbcode = $bbcode;
		$this->_content = $content;
	}
	
	public function get_score(){
		
		return $this->_score;
	}
	
	public function get_count(){
		
		return $this->_count;
	}
	
	public function get_percentage(){
		
		return $this->_count > 0 ? round($this->_score * 100 / $this->_count, 2) : 0;
	}
	
	public function get_page_header_question($item, $class){
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well qn-page-header">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
		$html .= '</div>';
		
		return $html;
	}
	
	public function get_choice_question($item, $class){

		$free_text = '';
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
		
		$html .= '<div class="answers margin-bottom-20 margin-top-20">';
		$correct_answer = true;
		$icon = $item->question_type == 3 ? 'icon-check-empty' : 'icon-circle-blank';
		
		foreach ($item->answers as $answer){
			
			$selected = false;
			
			foreach ($item->responses as $response){

				if($response->answer_id == $answer->id){
					
					$selected = true;
				} else if(!empty($response->free_text)){
					
					$free_text = $response->free_text;
				}
			}
			
			$html .= '<div class="form-inline report-answers">';
				
				if($selected){
					
					$html .= '<i class="icon-check tooltip-hover" title="'.JText::_('LBL_SELECTED_ANSWER').'"></i>';
				} else {
					
					$html .= '<i class="'.$icon.' icon-muted"></i>';
				}
				
				$html .= CJFunctions::escape($answer->answer_label);
			
			$html .= '</div>';
		}
		
		$html .= '<p class="margin-top-10">'.CJFunctions::escape($free_text).'</p>';
		$html .= '</div>';
		$html .= '</div>';
		$this->_count++;
		
		return $html;
	}
	
	public function get_grid_question($item, $class){

		$free_text = '';
		$item->columns = array();
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
		
		$html .= '<div class="answers margin-bottom-20 margin-top-20">';
		$correct_answer = true;
		$icon = $item->question_type == 6 ? 'icon-check-empty' : 'icon-circle-blank';
		
		$html .= '<table class="table table-bordered table-hover table-striped margin-top-20">';
		$html .= '<thead><tr><td></td>';
		
		foreach($item->answers as $answer){

			if($answer->answer_type == 'y'){
				
				$item->columns[] = $answer;
				$html .= '<td class="center">'.CJFunctions::escape($answer->answer_label).'</td>';
			}
		}
		
		$html .= '</tr><thead>';
		$html .= '<tbody>';
		
		foreach($item->answers as $answer){

			if($answer->answer_type == 'x'){
				
				$html .= '<tr>';
					$html .= '<td>'.CJFunctions::escape($answer->answer_label).'</td>';
					
					foreach($item->columns as $column){

						$selected = false;
						
						foreach ($item->responses as $response){

							if($response->answer_id == $answer->id && $response->column_id == $column->id){
									
								$selected = true;
							} else if(!empty($response->free_text)){
									
								$free_text = $response->free_text;
							}
						}
						
						if($selected){
							
							$html .= '<td class="center"><i class="icon-ok tooltip-hover" title="'.JText::_('LBL_SELECTED_ANSWER').'"></i></td>';
						} else {
							
							$html .= '<td class="center"><i class="'.$icon.' icon-muted"></i></td>';
						}
					}

				$html .= '</tr>';
			}
		}
		
		$html .= '</tbody>';
		$html .= '</table>';
		
		$html .= '<p class="margin-top-10">'.CJFunctions::escape($free_text).'</p>';
		
		$html .= '</div>';
		$html .= '</div>';
		
		$this->_count++;
		
		return $html;
	}

	public function get_text_question($item, $class, $escape = true){
	
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant qn-page-header">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
		
		foreach ($item->responses as $response){
			
			if(!empty($response->free_text)){
				
				$html .= '<h4>'.JText::_('LBL_ANSWER').'</h4>';
				$html .= '<div>'.($escape ? CJFunctions::escape($response->free_text) : $response->free_text).'</div>';
			}
		}
		
		$html .= '</div>';
	
		return $html;
	}
	
	public function get_image_question($item, $class, $base_uri){

		$free_text = '';
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'"><i class="icon icon-hand-right"></i> '.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
		
		$html .= '<div class="answers margin-bottom-20 margin-top-20">';
		$correct_answer = true;
		$icon = $item->question_type == 12 ? 'icon-check-empty' : 'icon-circle-blank';
		
		foreach ($item->answers as $i=>$answer){
			
			$selected = false;
			
			foreach ($item->responses as $response){

				if($response->answer_id == $answer->id){
					
					$selected = true;
				} else if(!empty($response->free_text)){
					
					$free_text = $response->free_text;
				}
			}
			
			if($i % 6 == 0) $html .= '<div class="row-fluid">';
			
			$html .= '<div class="thumbnail span2 margin-bottom-20">';
				$html .= '<div class="center">';
					$html .= '<img src="'.$base_uri.$answer->image.'" alt="'.CJFunctions::escape($answer->answer_label).'">';
					$html .= '<div class="form-inline">';
						$html .= '<label class="checkbox margin-top-10">';
						
							if($selected){
								
								$html .= '<i class="icon-check tooltip-hover" title="'.JText::_('LBL_SELECTED_ANSWER').'"></i> ';
							} else {
								
								$html .= '<i class="'.$icon.' icon-muted"></i> ';
							}
							
							$html .= CJFunctions::escape($answer->answer_label);
							
						$html .= '</label>';
					$html .= '</div>';
				$html .= '</div>';
			$html .= '</div>';
			
			if($i % 6 == 5 || count($item->answers) == ($i + 1)) $html .= '</div>';
		}
		
		$html .= '<p class="margin-top-10">'.CJFunctions::escape($free_text).'</p>';
		$html .= '</div>';
		$html .= '</div>';
		$this->_count++;
		
		return $html;
	}
}