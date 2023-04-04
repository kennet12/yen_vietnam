<?php
class M_promotion extends M_db
{
	public function __construct()
	{
		parent::__construct();

		$this->_table = "m_promotion";
	}

	public function items( $info=null,$active=null, $limit=null, $offset=null, $order_by=null, $sort_by='DESC')
	{
		$sql = "SELECT * FROM {$this->_table}  WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->code)) {
				$sql .= " AND code = '{$info->code}'";
			}
            if (!empty($info->date)) {
				$sql .= " AND DATE(fromdate) <= '{$info->date}' AND DATE(todate) >= '{$info->date}'";
			}
		}
		if (!is_null($active)) {
			$sql .= " AND active = '{$active}'";
		}
		if (!empty($order_by)) {
			$sql .= " ORDER BY {$this->_table}.{$order_by} {$sort_by}";
		} else {
			$sql .= " ORDER BY {$this->_table}.created_date DESC";
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
}
?>
