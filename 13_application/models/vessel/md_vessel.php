<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_vessel extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}



	public function get_area_delivery()
	{

		/*$sql = "SELECT * FROM area_draft_survey ORDER BY draft_date ASC;";*/

		$sql = "
			SELECT
		      *,
		      (SELECT COUNT(*) AS cnt FROM area_draft_survey WHERE draft_date <= p.draft_date AND voyage_no = p.voyage_no and vessel_name = p.vessel_name) as 'Day' 
		FROM  area_draft_survey p     
		ORDER BY draft_date ASC;
		";
		
		$result = $this->db->query($sql);		
		return $result->result_array();		

	}

	public function get_area_delivery_group()
	{

		$sql ="
			SELECT 
			p1.draft_id,
			(SELECT draft_date FROM area_draft_survey WHERE draft_id = p2.minpostid)'start_date',
			p1.draft_date'end_date',
			DATEDIFF(p1.draft_date,(SELECT draft_date FROM area_draft_survey WHERE draft_id = p2.minpostid))'date_diff',
			p1.vessel_name,
			p1.status
			FROM area_draft_survey p1
			INNER JOIN (SELECT pi.voyage_no, MAX(pi.draft_id) AS maxpostid, MIN(pi.draft_id) AS minpostid,pi.vessel_name
			            FROM area_draft_survey PI WHERE pi.status = 'COMPLETE' GROUP BY pi.vessel_id,voyage_no) p2
			ON (p1.draft_id = p2.maxpostid)
			ORDER BY p1.draft_date ASC;
		";

		$result = $this->db->query($sql);
		return $result->result_array();

	}



}