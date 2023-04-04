<?php
class M_post extends M_db
{
	public function __construct()
	{
		parent::__construct();
		
		$this->_table = "m_post";
	}
	
	public function items($info=null, $active=null, $limit=null, $offset=null, $order_by=null, $sort_by='DESC')
	{
		$sql = "SELECT * FROM m_post WHERE 1 = 1";
		if (!is_null($info)) {
			
		}
		if (!is_null($active)) {
			$sql .= " AND active = '{$active}'";
		}
		$sql .= " AND deleted = '0'";
		if (!empty($order_by)) {
			$sql .= " ORDER BY {$order_by} {$sort_by}";
		} else {
			$sql .= " ORDER BY id ASC";
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
	
	public function count_items($info=null, $active=null)
	{
		$sql = "SELECT COUNT(*) FROM m_post WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->type)) {
				$sql .= " AND type = '{$info->type}'";
			}
		}
		if (!is_null($active)) {
			$sql .= " AND active = '{$active}'";
		}
		$sql .= " AND deleted = '0'";
		$query = $this->db->query($sql);
		$result = $query->row_array();
		return (int)$result['COUNT(*)'];
	}
}
?>