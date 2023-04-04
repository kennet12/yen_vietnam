<?php
class M_subscribe extends M_db
{
	public function __construct()
	{
		parent::__construct();
		
		$this->_table = "m_subscribe";
	}
	
	function items($info=NULL, $active=NULL, $limit=NULL, $offset=NULL)
	{
		$sql = "SELECT * FROM {$this->_table} WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->email)) {
				$sql .= " AND email = '{$info->email}'";
			}
			if (!empty($info->fromdate)) {
				$sql .= " AND DATE(created_date) >= '{$info->fromdate}'";
			} 
			if (!empty($info->today)) {
				$sql .= " AND DATE(created_date) <= '{$info->today}'";
			}
		}
		if (!is_null($active)) {
			$sql .= " AND active = '{$active}'";
		}
		if (!is_null($limit)) {
			$sql .= " LIMIT {$limit}";
		}
		if (!is_null($offset)) {
			$sql .= " OFFSET {$offset}";
		}
		$query = $this->db->query($sql);
		return $query->result();
	}
	function count($info=NULL, $active=NULL)
	{
		$sql   = "SELECT COUNT(*) AS 'total' FROM {$this->_table} WHERE 1=1";
		if (!is_null($info)) {
			if (!empty($info->search_text)) {
				$sql .= " AND email LIKE '%{$info->search_text}%'";
			}
		}
		if (!is_null($active)) {
			$sql .= " AND active = '{$active}'";
		}
		
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			return $query->row()->total;
		}
		
		return 0;
	}
}
?>