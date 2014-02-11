<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *	
 *
 *	@category Controller
 *  @package micron.manassas.Controllers
 *	@copyright Micron
 *  @version 1.0
 *	@since 2012-08-27
 *	@author Erin Cook <ecook@micron.com>
 *  @location ./application/controllers/equip.php
 */
class Equip extends CI_Controller 
{
	
	/**
	 *  Constructs the account controller.
	 */
	public function __construct() 
	{
		parent::__construct();
		$this->load->model('config_model');
		$this->load->model('sitestats_model');
		
  }
	
	/**
	 *	
	 */
	public function index($deptNum = null)
	{
		if(!is_null($deptNum))
		{
			$this->load->model('tool_model');		
			//go get all the data to set and change to other department.
			$department = $this->tool_model->getDeptArray($deptNum);
			
			// then set that array to this data.
			
			$this->session->set_userdata('department_name', $department['name']);
			$this->session->set_userdata('department_id', $department['id']);
			$this->session->set_userdata('department_mfg_OID', $department['mfg_area_oid']);
			$this->session->set_userdata('department_OID', $department['area_oid']);
			
		}
		
		$this->notes();
	}	
	
	//   ________ ________ ______        __    __           __                       
	//  |        |        |      \      |  \  |  \         |  \                      
	//  | $$$$$$$$\$$$$$$$$\$$$$$$      | $$\ | $$ ______ _| $$_    ______   _______ 
	//  | $$__      | $$    | $$        | $$$\| $$/      |   $$ \  /      \ /       \
	//  | $$  \     | $$    | $$        | $$$$\ $|  $$$$$$\$$$$$$ |  $$$$$$|  $$$$$$$
	//  | $$$$$     | $$    | $$        | $$\$$ $| $$  | $$| $$ __| $$    $$\$$    \ 
	//  | $$_____   | $$   _| $$_       | $$ \$$$| $$__/ $$| $$|  | $$$$$$$$_\$$$$$$\
	//  | $$     \  | $$  |   $$ \      | $$  \$$$\$$    $$ \$$  $$\$$     |       $$
	//   \$$$$$$$$   \$$   \$$$$$$       \$$   \$$ \$$$$$$   \$$$$  \$$$$$$$\$$$$$$$ 
	//                                                                               
	//                                                                               
	//                                                                               

	/**
	 *
	 */
	 
	 
public function exportCheckList($titleName = null)
{
	if(!is_null($titleName))
	{
		$titleName = urldecode($titleName);
		
		$this->load->model('tool_model');
		$xml = $this->tool_model->getXMLCheckList($titleName);
		
		//var_dump($xml);
		$filename = "Checklist_" . $titleName . ".xls";
		header("Content-Disposition: attachment; filename=\"$filename\"");
		header("Content-Type: application/vnd.ms-excel");
		echo $xml;
		/*
		echo "\tDate \tComment \tState \tUsername\r\n";
		foreach ($parsedNotes as $item)
		{
			if(($hideAutomated and $item['author'] != 'AUTOMATED TOOL') and ($hideEmpty and $item['note'] != ''))
			{
				if($item['date']) echo "\t" . $this->cleanData($item['date']);
				if($item['note']) echo "\t" . $this->cleanData($item['note']);
				if($item['equip_state_id']) echo "\t" . $this->cleanData($item['equip_state_id']);
				if($item['author']) echo "\t" . $this->cleanData($item['author']);
				echo "\r\n";
			}
			
		}
		*/
		exit;
	}
	exit;
}	 
public function exportToExcel($toolName = null, $startDate = null, $endDate = null, $sortOrder = 'ASC')
{
	if($toolName)
	{
		$this->load->model('tool_model');
		$startDate = str_replace('-', '/', $startDate);
		$startDate = date('m/d/Y 00:00:00', strtotime($startDate));
		$endDate = str_replace('-', '/', $endDate);
		$endDate = date('m/d/Y 23:59:59', strtotime($endDate)); 
		$notes = $this->tool_model->getEquipNotes($toolName, $startDate, $endDate);
		
		$includeCurrentStatusInHistory = false;
		$toolArray = array('equip_id' => $toolName);
		$equipStatusHistory = $this->tool_model->getEquipStatusHistory(
								$toolArray, 
								$startDate, 
								$endDate, 
								$includeCurrentStatusInHistory
						);
		//parse the notes.
		
		$hideAutomated = false;
		$parsedNotes = $this->tool_model->parseEquipNotes(
						$notes, 
						$startDate, 
						$endDate, 
						$hideAutomated, 
						$equipStatusHistory,
						$sortOrder
					);
	
		$filename = "ETI_notes_" . $toolName . '_' . date('Ymd') . ".xls";
		header("Content-Disposition: attachment; filename=\"$filename\"");
		header("Content-Type: application/vnd.ms-excel");
		echo "\tDate \tComment \tState \tUsername\r\n";
		foreach ($parsedNotes as $item)
		{
			
				if($item['date'])
				{	
					echo "\t" . $this->cleanData($item['date']);
				}
				else 
				{
					echo "\t";
				}
				if($item['note'])
				{
					echo "\t" . $this->cleanData($item['note']);
				}
				else
				{
					echo "\t";
				}
				if($item['equip_state_id'])
				{
					echo "\t" . $this->cleanData($item['equip_state_id']);
				}
				else
				{
					echo "\t";
				}
				if($item['author'])
				{	
					echo "\t" . $this->cleanData($item['author']);
				}
				else
				{
					echo "\t";
				}
				echo "\r\n";
			
			
		}
		
	exit;
	}
	exit;
}
public function cleanData(&$str)
{
	$str = preg_replace("/\t/","\\t", $str);
	$str = preg_replace("/\r?\n/","\\n", $str);
	if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';	
	return $str;
}



	public function ajaxGetNoteSearchForm()
	{
		$this->sitestats_model->insert(__CLASS__ . '/' . __FUNCTION__);
		/*
		'equip_name' : $('#equip_name').val(),
		'start_date' : $('#start_date').val(),
		'end_date' : $('#end_date').val(),
		'hide_automated' : $('#hide_automated').val()
		*/
		$equipName = $this->input->post('equip_name');
		$startDate = $this->input->post('start_date');
		$endDate = $this->input->post('end_date');
		$hideAutomated = $this->input->post('hide_automated');
		$selectOnLoadWS = $this->input->post('workstation_name');
		$sortOrder = $this->input->post('sortOrder');
		$hideEmpty = $this->input->post('hideEmpty');
		
		$this->load->model('tool_model');
		$workstations = $this->tool_model->getWorkstations();
		$vendors = array();
		if (count ($workstations) > 0)
		{
			$vendors = $this->tool_model->organizeByVendor($workstations);
		}
		$this->loadNoteSearch($workstations, $vendors, false, $selectOnLoadWS, $equipName, $hideAutomated, $startDate, $endDate, $sortOrder, $hideEmpty);
	}
	
	/**
	 *
	 */
	private function loadNoteSearch(
		&$workstations, 
		&$vendors, 
		$showButtons = true, 
		$selectOnLoadWS = NULL,
		$selectOnLoadEquip = NULL,
		$hideAutomatedSelected = true,
		$startDate = NULL,
		$endDate = NULL,
		$sortOrder = 'DESC',
		$hideEmpty = true
	) {
		$this->load->helper('form_builder_helper');
		
		if (count($workstations) > 0)
		{
			if (is_null($selectOnLoadWS))
			{
				$firstWS = array_first_entry($workstations);
				$selectOnLoadWS = $firstWS['WS_name'];
			}
			
			$workstationSelect = $this->load->view(
				'workstation_selector', 
				array(
					'workstations' => $workstations, 
					'vendors' => $vendors,
					'selectedWorkstation' => $selectOnLoadWS,
					'multipleWS' => true
				), 
				TRUE
			);
			
			
			$filter = array();
			if (is_null($selectOnLoadWS))
			{
				$firstWS = array_first_entry($workstations);
				$filter = array(
					'where' => array(
						'ws.WS_name' => $firstWS['WS_name']
					)
				);
			}
			else
			{
				//figure out what ws it belongs to.
				$filter = array(
					'where' => array(
						'ws.WS_name' => $selectOnLoadWS,
						'equip_tracking.equip_status' => 'ACTIVE'
					)
				);
			}
			$equipmentForWs = $this->tool_model->getTools($filter);
			
			$equipData = array();
			
			foreach ($equipmentForWs as $equip)
			{
				$equipData[$equip['equip_id']] = array(
					'value' => $equip['equip_id'],
					'label' => $equip['equip_id']
				);
				//check to see if the parent is in the list
				if (!is_null($equip['parent_equip_id']) && $equip['parent_equip_id'] != '')
				if (!isset($equipData[$equip['parent_equip_id']]))
				{
					$equipData[$equip['parent_equip_id']] = array(
						'value' => $equip['parent_equip_id'],
						'label' => $equip['parent_equip_id']
					);
				}
			}
			
			
			$dropdownData = array(
				'field' => 'equip_select',
				'options' => $equipData,
				'value' => $selectOnLoadEquip
			);
			$equipDropdown = dropdown($dropdownData);
			
			//the equipment might not belong to a workstation!
			if (!is_null($selectOnLoadEquip) && strpos($equipDropdown, 'selected') === FALSE)
			{
				$workstationSelect = '';
				$equipDropdown = '';
				//that was easy.
			}
			
			$equipNoteSearchData = array(
				'workstationSelect' => $workstationSelect,
				'equip_dropdown'    => $equipDropdown,
				'equip_name'        => $selectOnLoadEquip,
				'showButtons'       => $showButtons,
				'startDate'         => $startDate,
				'endDate'           => $endDate,
				'hideAutomated'     => $hideAutomatedSelected,
				'sortOrder'         => $sortOrder,
				'hideEmpty'         => $hideEmpty
			);
			$this->load->view('equip_note_search', $equipNoteSearchData);
			
		}
		else
		{
			//they dont have workstations, only display the search box.
			$equipNoteSearchData = array(
				'workstationSelect' => '',
				'equip_dropdown'    => '',
				'equip_name'        => $selectOnLoadEquip,
				'showButtons'       => $showButtons,
				'startDate'         => $startDate,
				'endDate'           => $endDate,
				'hideAutomated'     => $hideAutomatedSelected,
				'sortOrder'         => $sortOrder, 
				'hideEmpty'         => $hideEmpty
			);
			$this->load->view('equip_note_search', $equipNoteSearchData);
		}
	}



	/**
	 *
	 */
	public function notes(
		$toolName = NULL,
		$startDate = null, 
		$endDate = null, 
		$hideAutomated = true,
		$sortOrder = 'ASC',
		$hideEmpty = true
	)	{

		//the insert into the site stats is lower in the function!
		if (!databaseIsConnectable('msdb', true)) return;
		$includeEquipStatusWithNotes = true; 
		//make sure the sort order is a valid thing.
		$sortOrder = strtoupper($sortOrder);
		if ($sortOrder != 'DESC' && $sortOrder != 'ASC') $sortOrder = 'DESC';

		if (
			strtolower($hideAutomated) === 'false'
			|| $hideAutomated === '0'
		) {
			$hideAutomated = false;
		}
		elseif (
			strtolower($hideAutomated) === 'true'
			|| $hideAutomated === '1'
		) {
			$hideAutomated = true;
		}
		else
		{
			$hideAutomated = true;
		}
		
		//clean up hide empty input
		if (
			strtolower($hideEmpty) === 'false'
			|| $hideEmpty === '0'
		) {
			$hideEmpty = false;
		}
		elseif (
			strtolower($hideEmpty) === 'true'
			|| $hideEmpty === '1'
		) {
			$hideEmpty = true;
		}
		else
		{
			$hideEmpty = true;
		}
		
		if (is_null($startDate))
		{		
			$startDate = date('m/d/Y 00:00:00', strtotime('-3 day'));
		}
		else
		{
			$startDate = str_replace('-', '/', $startDate);
			$startDate = date('m/d/Y 00:00:00', strtotime($startDate));
		}
		
		if (is_null($endDate))
		{ 
			$endDate = date('m/d/Y 23:59:59');
		}
		else
		{
			$endDate = str_replace('-', '/', $endDate);
			$endDate = date('m/d/Y 23:59:59', strtotime($endDate)); 
		}
		
		//logic to figure out if the current status of the tool needs to be added.
		$includeCurrentStatusInHistory = false;
		if (
			$includeEquipStatusWithNotes 
			&& (strtotime($endDate) > strtotime('now'))
		) {
			$includeCurrentStatusInHistory = true;
		}
		
		if ($this->input->post('searchBy'))
		{
			$equipName = '';
			$searchBy = $this->input->post('searchBy');
			
			if ($this->input->post('equip_select') && $searchBy == 'dropdown')
			{
				$equipName = $this->input->post('equip_select');
			}
			elseif ($this->input->post('equipment') && $searchBy == 'text')
			{
				$equipName = $this->input->post('equipment');
			}
			$startDate = str_replace('/', '-', $this->input->post('startDateForm'));
			$endDate = str_replace('/', '-', $this->input->post('endDateForm'));
			
			$sortOrder = $this->input->post('sortOrder');
			
			$hideEmpty = $this->input->post('hide_empty_check') ? 'true' : 'false';
			$hideAutomated = $this->input->post('hide_automated_check') ? 'true' : 'false';
			
			//set the URL!
			$this->output->set_header(
				"Location: " . 
				site_url('equip/notes/' . $equipName . '/' . $startDate . '/' . $endDate . '/' . $hideAutomated . '/' . $sortOrder . '/' . $hideEmpty)
			);
			return;
		}
		
		
		$this->sitestats_model->insert(__CLASS__ . '/' . __FUNCTION__);
		$this->load->model('tool_model');
		$raw_workstations = $this->tool_model->getWorkstationsWithTools();
		//set the index of the stuff to the workstation name!
		$workstations = array();
		foreach ($raw_workstations as $workstation)
		{
			##start code for adding frames to the workstation
			//lets go through all the equipfor the workstation and add the paretnt to the tool
			if (count($workstation['tools']) > 0)
			{
				$equips = $workstation['tools'];
				
				foreach ($equips as $equip)
				{
					$workstation['tools'][$equip['equip_id']] = $equip;
					//check to see if there is a value.
					if (!is_null($equip['parent_equip_id']) && $equip['parent_equip_id'] != '')
					{
						//if there is a value, check to see if its already in the list
						if (!isset($workstation['tools'][$equip['parent_equip_id']]))
						{
							//add it to the list
							$workstation['tools'][$equip['parent_equip_id']] = array(
								'equip_id' => $equip['parent_equip_id'],
								'parent_equip_id' => null
							);
						}
					}
				}
				##end code for adding frames to the workstation
				$workstations[$workstation['WS_name']] = $workstation;
			}
		}
		unset($raw_workstations); //Free up some ram! -- this makes absolutely no difference in load time.
		
		$js = 'var workstations = ' . json_encode($workstations) . ';';
		$vendors = $this->tool_model->organizeByVendor($workstations);
		$js .= 'var vendors = ' . json_encode($vendors) . ';';
		if (is_null($toolName))
		{
			$includeData = array(
				'additionalJSFiles' => array(
					'ajaxHelper',
					'equip',
					'pmPlanner',
					'jQuery/jquery-impromptu',
					'popupHelper'
				)
			);
			$viewData = array(
				'includes'     => $this->load->view('default_includes', $includeData, TRUE),
				'jsOnload'     => 'onLoad();equipOnLoad();workstationSearchOnLoad();',
				'title'        => 'ETI Note Search - Micron Overview Suite', 
				'userdata'     => $this->session->userdata,
				'currentPage'  => 'equip',
				'js'           => $js,
				'errorMsg'     => $this->session->flashdata('errorMsg')
			);
			
			$this->load->view('default_header', $viewData);
			
			
			$this->loadNoteSearch(
					$workstations, $vendors, 
					true, NULL, NULL, true, 
					date("m/d/Y", strtotime($startDate)), 
					date('m/d/Y', strtotime($endDate)),
					$sortOrder
			);
			
			$this->load->view('default_footer', $viewData);
		}
		else
		{
			//try to find the equipment that the user meant.
			
			$toolName = trim(strtoupper(urldecode($toolName))); //just incase.
			$this->benchmark->mark('checking_for_tool_in_db_start');
			
			//where the name is exact.
			$toolFilter = array(
				'where' => array(
					'equip_tracking.equip_id' => $toolName
				)
			);
			$tools = $this->tool_model->getTools($toolFilter);

			//if no results, try it where name is similar to what they typed
			if (count($tools) == 0)
			{
				//try again but with like.
				$toolFilter = array(
					'like' => array(
						array(
							'field' => 'equip_tracking.equip_id',
							'value' => $toolName
						)
					),
				);
				
				$tools = $this->tool_model->getTools($toolFilter);
			}
			
			//we found stuff, just more then one!
			//lets try it with only active stuff.
			if (count($tools) > 1)
			{
				//try again but with like.
				$toolFilter = array(
					'like' => array(
						array(
							'field' => 'equip_tracking.equip_id',
							'value' => $toolName
						)
					),
					'where' => array(
						'equip_tracking.equip_status' => 'ACTIVE'
					)
				);
				
				$tools = $this->tool_model->getTools($toolFilter);
			}

			//ok, still more then 1 with active. lets try again
			//but only in their department
			if (count($tools) > 1)
			{
				
				//lets try again but only for the users selected department.
				
				$toolFilter = array(
					'like' => array(
						array(
							'field' => 'equip_tracking.equip_id',
							'value' => $toolName
						)
					),
					'where' => array(
						'mfg_area_id' => $this->session->userdata('department_name')
					)
				);
				
				$toolsForArea = $this->tool_model->getTools($toolFilter);
				if (count($toolsForArea) == 1)
				{
					$tools = $toolsForArea;
				}
				else
				{
					$viewData = array(
						'includes'     => $this->load->view('default_includes', array(), TRUE),
						'jsOnload'     => 'onLoad();',
						'title'        => 'ETI Notes Equip Search Results - Micron Overview Suite',
						'userdata'     => $this->session->userdata,
						'currentPage'  => 'equip',
						'js'           => $js
					);
					
					$equipViewData = array(
						'equipFound' => $tools,
						'hideAutomated' => $hideAutomated,
						'startDate' => date('m-d-Y', strtotime($startDate)),
						'endDate' => date('m-d-Y', strtotime($endDate)),
						'sortOrder' => $sortOrder,
						'hideEmpty' => $hideEmpty
					);
					$this->load->view('default_header', $viewData);
					$this->load->view('equip_note_select_list', $equipViewData);
					$this->load->view('default_footer');
				}
			}
			
			//yay! we have only one tool.
			if (count($tools) == 1)
			{
				$tool = array_first_entry($tools);
				$this->benchmark->mark('checking_for_tool_in_db_end');
				if (count($tool) > 0)
				{
					//get the notes
					$notes = $this->tool_model->getEquipNotes($tool['equip_id'], $startDate, $endDate);
					$equipStatusHistory = null;
					if ($includeEquipStatusWithNotes)
					{
						$equipStatusHistory = $this->tool_model->getEquipStatusHistory(
								$tool, 
								$startDate, 
								$endDate, 
								$includeCurrentStatusInHistory
						);
					}
					
				
					//parse the notes.
					$parsedNotes = $this->tool_model->parseEquipNotes(
						$notes, 
						$startDate, 
						$endDate, 
						$hideAutomated, 
						$equipStatusHistory,
						$sortOrder
					);
					
					$includeData = array(
						'additionalJSFiles' => array(
							'equip',
							'pmPlanner' 
						)
					);
					$viewData = array(
						'includes'     => $this->load->view('default_includes', $includeData, TRUE),
						'jsOnload'     => 'onLoad();equipOnLoad();',
						'title'        => 'ETI Notes for ' . $tool['equip_id'] . ' - Micron Overview Suite',
						'userdata'     => $this->session->userdata,
						'currentPage'  => 'equip',
						'js'           => $js
					);
					
					$equipData = array(
						'notes' => $parsedNotes,
						'tool' => $tool,
						'hideAutomated' => $hideAutomated,
						'startDate' => $startDate,
						'endDate' => $endDate,
						'sortOrder' => $sortOrder,
						'hideEmpty' => $hideEmpty
					);
					
					$this->load->helper('htmlpurifier_helper');

					$this->load->view('default_header', $viewData);
					$this->load->view('equip_notes', $equipData);
					$this->load->view('default_footer', $viewData);
				}
			}
			//we couldn't find anything. sorry man/woman/thing/alien/person.
			elseif (count($tools) == 0)
			{
				//show an error screen!
				$includeData = array(
					'additionalJSFiles' => array(
						'equip',
						'pmPlanner' 
					)
				);
				
				$viewData = array(
					'includes'     => $this->load->view('default_includes', $includeData, TRUE),
					'jsOnload'     => 'onLoad();equipOnLoad();workstationSearchOnLoad();',
					'title'        => 'ETI Note Search - Micron Overview Suite', 
					'userdata'     => $this->session->userdata,
					'currentPage'  => 'equip',
					'js' => $js,
					'errorMsg' => 'We were unable to find any tools with a name similar to "' . $toolName . '". Please check' . 
					' the name of the equipment and try again.'
				);
				
				$this->load->view('default_header', $viewData);
				
				$this->loadNoteSearch(
						$workstations, $vendors, 
						true, NULL, $toolName, $hideAutomated, 
						date("m/d/Y", strtotime($startDate)), 
						date('m/d/Y', strtotime($endDate)),
						$sortOrder
				);
				
				$this->load->view('default_footer', $viewData);
			}
		} //end getting equipment to display the note for!
	}

		
	//    ______ ________  ______ ________ __    __  ______         __    __ ______  ______ ________ 
	//   /      |        \/      |        |  \  |  \/      \       |  \  |  |      \/      |        \
	//  |  $$$$$$\$$$$$$$|  $$$$$$\$$$$$$$| $$  | $|  $$$$$$\      | $$  | $$\$$$$$|  $$$$$$\$$$$$$$$
	//  | $$___\$$ | $$  | $$__| $$ | $$  | $$  | $| $$___\$$      | $$__| $$ | $$ | $$___\$$ | $$   
	//   \$$    \  | $$  | $$    $$ | $$  | $$  | $$\$$    \       | $$    $$ | $$  \$$    \  | $$   
	//   _\$$$$$$\ | $$  | $$$$$$$$ | $$  | $$  | $$_\$$$$$$\      | $$$$$$$$ | $$  _\$$$$$$\ | $$   
	//  |  \__| $$ | $$  | $$  | $$ | $$  | $$__/ $|  \__| $$      | $$  | $$_| $$_|  \__| $$ | $$   
	//   \$$    $$ | $$  | $$  | $$ | $$   \$$    $$\$$    $$      | $$  | $|   $$ \\$$    $$ | $$   
	//    \$$$$$$   \$$   \$$   \$$  \$$    \$$$$$$  \$$$$$$        \$$   \$$\$$$$$$ \$$$$$$   \$$   
	//                                                                                               
	//                                                                                               
	//                                                                                               
	
	/**
	 * Displays the recent history of the equipment status.
	 * @param  string $equipName the name of the equipment.
	 * @return null
	 */
	public function statusHistory($equipName = null)
	{
		$this->load->model('tool_model');
		$afterDateTime = date('Y/m/d h:i A', strtotime('2 weeks ago'));
		$beforeDateTime = date('Y/m/d h:i A', strtotime('now'));
		$equip = array('equip_id' => $equipName);
		$equipHistory = $this->tool_model->getEquipStatusHistory($equip, $afterDateTime, $beforeDateTime, false);
		
		$graphData = array();
		foreach ($equipHistory as $history)
		{
			//index 0 is the js timestamp, index 1 is the 0 for down or 1 for up.
			$graphData[] = array(
				strtotime($history['equip_state_in_datetime']) * 1000, 
				(($history['production_state'] == 'DOWN') ? 0 : 1)
			);
		}

		$js = 'var graphData = ' . json_encode($graphData) . ';';
		
		$includeData = array(
		'additionalJSFiles' => array(
				'jQuery/flot/jquery.flot',
				'jQuery/flot/jquery.flot.axislabel',
				'equip',
			)
		);
		$viewData = array(
			'includes'     => $this->load->view('default_includes', $includeData, TRUE),
			'jsOnload'     => 'onLoad();drawEquipStatusGraph();',
			'title'        => 'Upcoming PM Graph - Micron Overview Suite',
			'userdata'     => $this->session->userdata,
			'currentPage'  => 'equip',
			'js' => $js
		);
		$pageContentView = array(
			'pageDescription' => '',
			'header' => 'Equip Status for ' . anchor('equip/show/' . $equip['equip_id'], $equip['equip_id']),
			'display' => '<div class="graphPlaceholder" id="equipStatusGraph" style="width:1018;height:550px;"></div>',
		);
		
		

		$this->load->view('default_header', $viewData);
		$this->load->view('generic_page_content', $pageContentView);
		$this->load->view('default_footer');
	}


	//   ________  ______  __    __ ______ _______         _______  ________ ________  ______  ______ __       ______  
	//  |        \/      \|  \  |  |      |       \       |       \|        |        \/      \|      |  \     /      \ 
	//  | $$$$$$$|  $$$$$$| $$  | $$\$$$$$| $$$$$$$\      | $$$$$$$| $$$$$$$$\$$$$$$$|  $$$$$$\\$$$$$| $$    |  $$$$$$\
	//  | $$__   | $$  | $| $$  | $$ | $$ | $$__/ $$      | $$  | $| $$__      | $$  | $$__| $$ | $$ | $$    | $$___\$$
	//  | $$  \  | $$  | $| $$  | $$ | $$ | $$    $$      | $$  | $| $$  \     | $$  | $$    $$ | $$ | $$     \$$    \ 
	//  | $$$$$  | $$ _| $| $$  | $$ | $$ | $$$$$$$       | $$  | $| $$$$$     | $$  | $$$$$$$$ | $$ | $$     _\$$$$$$\
	//  | $$_____| $$/ \ $| $$__/ $$_| $$_| $$            | $$__/ $| $$_____   | $$  | $$  | $$_| $$_| $$____|  \__| $$
	//  | $$     \\$$ $$ $$\$$    $|   $$ | $$            | $$    $| $$     \  | $$  | $$  | $|   $$ | $$     \$$    $$
	//   \$$$$$$$$ \$$$$$$\ \$$$$$$ \$$$$$$\$$             \$$$$$$$ \$$$$$$$$   \$$   \$$   \$$\$$$$$$\$$$$$$$$\$$$$$$ 
	//                 \$$$                                                                                            
	//                                                                                                                 
	//                                                                                                                 


	/**
	 *
	 */

	
	
	
	public function show($toolName = NULL)
	{
		

		if (!databaseIsConnectable('msdb', true)) return;
		if (!databaseIsConnectable('pgdb', true)) return;
		$this->sitestats_model->insert(__CLASS__ . '/' . __FUNCTION__);

	
		if (is_null($toolName))
		{
			redirect('equip/notes');
			return;
		}
		

		$toolName = urldecode($toolName);
		$this->load->model('tool_model');
		$toolFilter = array(
			'where' => array(
				'equip_tracking.equip_id' => $toolName
			)
		);
		$this->tool_model->appendDataToTools = true;
		$tool = $this->tool_model->getTool($toolFilter);
		
		
		
		if (count($tool) > 0)
		{
			$startDate = date('m/d/Y H:i', strtotime('-24 hours'));
			$endDate = date('m/d/Y 00:00:00', strtotime('tomorrow'));
			$hideAutomated = false;
			$includeCurrentStatusInHistory= true;
			$equipStatusHistory = $this->tool_model->getEquipStatusHistory(
					$tool, 
					$startDate, 
					$endDate, 
					$includeCurrentStatusInHistory
			);
			
			//get the children equipment.
			$childEquip = $this->tool_model->getChildrenEquipment($tool['equip_id']);
			
			$childEquipWidgets = array();
			foreach ($childEquip as $child)
			{
				$childEquipWidgets[] = $this->load->view('equip_widget', array('tool' => $child, 'includeNotesButton' => true), true);
			}
			
			$tool['has_children'] = count($childEquip) > 0;
			$toolData = array(
				'tool' => $tool,
				'equipWidget' => $this->load->view('equip_widget', array('tool' => $tool, 'includeNotesButton' => false), true),
				'childEquipWidgets' => $childEquipWidgets
			);
			
			
		//lets do a redirect for the post
		
		if ($this->input->post())
		{
			redirect('/equip/show/' . $toolName);
			return;	
		}
		
		
			
			
			$includeData = array(
				'additionalJSFiles' => array(
					'equip'
				)
			);
			$viewData = array(
				'includes'     => $this->load->view('default_includes', $includeData, TRUE),
				'jsOnload'     => 'onLoad();equipOnLoad();',
				'title'        => 'Equip Details for ' . $tool['equip_id'] . ' - Micron Overview Suite',
				'userdata'     => $this->session->userdata,
				'currentPage'  => 'equip'
			);
			
			
			$this->load->view('default_header', $viewData);
			$this->load->view('equipment_details', $toolData);
			$this->load->view('default_footer', $viewData);
		}
		else
		{
			$viewData = array(
				'includes'     => $this->load->view('default_includes', array(), TRUE),
				'jsOnload'     => 'onLoad();',
				'title'        => 'Equipment Details - Micron Overview Suite',
				'userdata'     => $this->session->userdata,
				'currentPage'  => 'equip',
			);
			
			$messageData = array(
				'message' => '<div class="error">We were unable to find the equipment in the database. ' . 
						'Please double check the equipment ID.  This could be a bug - if you think is it ' . 
						'please use the '. anchor('contact', 'contact') . ' form.</div>'
			);
			
			$this->load->view('default_header', $viewData);
			$this->load->view('display_message', $messageData);
			$this->load->view('default_footer', $viewData);
		}
	}
	
	//   ________ ________  ______ ________        ________ __    __ __    __  ______   ______  
	//  |        |        \/      |        \      |        |  \  |  |  \  |  \/      \ /      \ 
	//   \$$$$$$$| $$$$$$$|  $$$$$$\$$$$$$$$      | $$$$$$$| $$  | $| $$\ | $|  $$$$$$|  $$$$$$\
	//     | $$  | $$__   | $$___\$$ | $$         | $$__   | $$  | $| $$$\| $| $$   \$| $$___\$$
	//     | $$  | $$  \   \$$    \  | $$         | $$  \  | $$  | $| $$$$\ $| $$      \$$    \ 
	//     | $$  | $$$$$   _\$$$$$$\ | $$         | $$$$$  | $$  | $| $$\$$ $| $$   __ _\$$$$$$\
	//     | $$  | $$_____|  \__| $$ | $$         | $$     | $$__/ $| $$ \$$$| $$__/  |  \__| $$
	//     | $$  | $$     \\$$    $$ | $$         | $$      \$$    $| $$  \$$$\$$    $$\$$    $$
	//      \$$   \$$$$$$$$ \$$$$$$   \$$          \$$       \$$$$$$ \$$   \$$ \$$$$$$  \$$$$$$ 
	//                                                                                          
	//                                                                                          
	//                                                                                          


	public function testETIColors($lightOrDark = 'dark')
	{
		//build some test data.
		//load the views
		
		$equipName = 'ASDFASDF';
		$equipTypeID = 'AMAT';
		$equipStateID = 'testing!';
		$equipSemiStates = array(
			'ENGINEERING',
			'PRODUCTIVE',
			'STANDBY',
			'UNSCHEDULED_DOWNTIME',
			'SCHEDULED_DOWNTIME',
			'NON-SCHEDULED'
		);
		$current_equip_state_datetime = date('m-d-Y h:i A');
		$equip = array();
		for ($i = 0; $i < 3; $i++)
		{
			foreach($equipSemiStates as $semi)
			{
				$equip[] = array(
					'equip_id' => $equipName,
					'equip_type_id' => $equipTypeID,
					'equip_state_id' => $equipStateID,
					'semi_state_id' => $semi,
					'current_equip_state_datetime' => $current_equip_state_datetime
				);
			}
		}

		$data = array(
			'equips' => $equip,
			'lightOrDark' => $lightOrDark,
			'forPassdown' => false
		);

		$includeData = array(
			'additionalCSSFiles' => array(
				'eti_colors', 'jquery.tablesorter'
			)
			
		);

		$viewData = array(
				'includes'     => $this->load->view('default_includes', $includeData, TRUE),
				'jsOnload'     => 'onLoad();',
				'title'        => 'Equipment Details - Micron Overview Suite',
				'userdata'     => $this->session->userdata,
				'currentPage'  => 'equip',
			);
		$this->load->view('default_header', $viewData );
		$this->load->view('eti_equip_table', $data);
		$this->load->view('default_footer');
	}
}