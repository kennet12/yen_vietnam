<?php
class M_rating extends M_db
{
	public function __construct()
	{
		parent::__construct();

		$this->_table = "m_rating";
	}

	public function items($select='*', $info=null, $active=null, $limit=null, $offset=null, $order_by=null, $sort_by='DESC')
	{
		$sql = "SELECT {$select} FROM {$this->_table}  WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->user_id)) {
				$sql .= " AND user_id = '{$info->user_id}'";
			}
			if (isset($info->parent_id)) {
				$sql .= " AND parent_id = '{$info->parent_id}'";	
			}
			if (isset($info->product_id)) {
				$sql .= " AND product_id = '{$info->product_id}'";	
			}
			if (!empty($info->fromdate)) {
				$sql .= " AND DATE(created_date) >= '{$info->fromdate}'";
			}
			if (!empty($info->todate)) {
				$sql .= " AND DATE(created_date) <= '{$info->todate}'";
			}
			if (!empty($info->point)) {
				$sql .= " AND point = '{$info->point}'";	
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
	public function product_items($select='*', $info=null, $active=null, $limit=null, $offset=null, $order_by=null, $sort_by='DESC')
	{
		$sql = "SELECT {$select} FROM {$this->_table} as r JOIN m_product p ON p.id=r.product_id WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->user_id)) {
				$sql .= " AND r.user_id = '{$info->user_id}'";
			}
			if (isset($info->parent_id)) {
				$sql .= " AND r.parent_id = '{$info->parent_id}'";	
			}
			if (isset($info->product_id)) {
				$sql .= " AND r.product_id = '{$info->product_id}'";	
			}
			if (!empty($info->fromdate)) {
				$sql .= " AND DATE(r.created_date) >= '{$info->fromdate}'";
			}
			if (!empty($info->todate)) {
				$sql .= " AND DATE(r.created_date) <= '{$info->todate}'";
			}
			if (!empty($info->point)) {
				$sql .= " AND r.point = '{$info->point}'";	
			}
		}
		if (!is_null($active)) {
			$sql .= " AND r.active = '{$active}'";
		}
		if (!empty($order_by)) {
			$sql .= " ORDER BY r.{$order_by} {$sort_by}";
		} else {
			$sql .= " ORDER BY r.created_date DESC";
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
