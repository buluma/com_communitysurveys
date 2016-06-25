<?php
/**
 * @version		$Id: reports.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Surveys
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class SurveyReports {
	
	private $_wysiwyg = false;
	private $_bbcode = false;
	private $_content = false;
	
	function __construct($wysiwyg, $bbcode, $content){
		
		$this->_wysiwyg = $wysiwyg;
		$this->_bbcode = $bbcode;
		$this->_content = $content;
	}
	
	public function get_page_header_question($item, $class){
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well qn-page-header">';
		$html .= '<div class="question-title qtype-'.$item->question_type.'">'.CJFunctions::escape($item->title).'</div>';
		$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
		$html .= '</div>';
		
		return $html;
	}
	
	public function get_choice_question($item, $class){

		$array_data = array(array('',''));
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant">';
			$html .= '<div class="question-title qtype-'.$item->question_type.'">'.CJFunctions::escape($item->title).'</div>';
			$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
			
			$html .= '<div>'.JText::_('LBL_TOTAL_RESPONSES').': <span class="label label-success">'.$item->total_votes.'</span></div>';
			
			$html .= '<div class="row-fluid">';
				$html .= '<div class="answers margin-top-20 span8">';

					foreach($item->answers as $answer){

						$num_votes = intval(!empty($answer->responses[0]->votes) ? $answer->responses[0]->votes : 0);
						$percentage = $num_votes ? round($num_votes * 100 / $item->total_votes, 2) : 0;
						array_push($array_data, array(CJFunctions::escape($answer->answer_label), $num_votes));
						
						$html .= '<p>'.JText::sprintf('TXT_REPORTS_ANSWER_TITLE', CJFunctions::escape($answer->answer_label), $percentage, $num_votes).'</p>';
						$html .= '<div>';
						$html .= '<div class="margin-left-5 pull-right label label-info">'.$percentage.'%</div>';
						$html .= '<div class="progress progress-info">';
							$html .= '<div class="bar" style="width: '.$percentage.'%;"></div>'; 
						$html .= '</div>';
						$html .= '</div>';
					}
					
				$html .= '</div>';
				$html .= '<div class="report-chart span4">';
					$html .= '<div style="display: none;" class="array_data">'.json_encode($array_data).'</div>';
					$html .= '<div id="chart-wrapper-'.$item->id.'" class="chart-wrapper"></div>';
				$html .= '</div>';
			$html .= '</div>';

		$html .= '</div>';
		
		return $html;
	}
	
	public function get_grid_question($item, $class){
		
		$html = '<div id="qn-'.$item->id.'" class="question-item well well-transperant">';
			$html .= '<div class="question-title qtype-'.$item->question_type.'">'.CJFunctions::escape($item->title).'</div>';
			$html .= '<div class="question-description">'.CJFunctions::process_html($item->description, $this->_bbcode, $this->_content).'</div>';
			
			$html .= '<div>'.JText::_('LBL_TOTAL_RESPONSES').': <span class="label label-success">'.$item->total_votes.'</span></div>';
			
			$html .= '<table class="table table-bordered table-hover table-striped margin-top-20">';
				$html .= '<thead><tr><td></td>';

				foreach($item->columns as $column){
					
					$html .= '<td class="center">'.CJFunctions::escape($column->answer_label).'</td>';
				}
				
				$html .= '</tr><thead>';
				$html .= '<tbody>';
				
				foreach($item->answers as $answer){
					
					$columns_html = '';
					$answer_total = 0;
					
					foreach($item->columns as $column){
						
						$found = false;
						
						foreach ($answer->responses as $response){

							if($response->answer_id == $answer->id && $response->column_id == $column->id){
							
								$columns_html .= '<td class="center">'.$response->votes.'</td>';
								$answer_total += $response->votes;
								$found = true;
							}
						}
						
						if(!$found) $columns_html .= '<td class="center">0</td>';
					}
					
					$percentage = $answer_total > 0 ? round($answer_total * 100 / $item->total_votes, 2) : 0;
					$html .= '<tr><td>'.JText::sprintf('TXT_REPORTS_ANSWER_TITLE', CJFunctions::escape($answer->answer_label), $percentage, $answer_total).'</td>'.$columns_html.'</tr>';
				}
				
				$html .= '</tbody>';
			$html .= '</table>';
					
		$html .= '</div>';
		
		return $html;
	}
}