<?
class M_product_category extends M_db
{
	public function __construct()
	{
		parent::__construct();
		
		$this->_table = "m_product_category";
	}
	
	public function items($info=null, $active=null, $limit=null, $offset=null, $order_by=null, $sort_by='DESC')
	{
		$sql = "SELECT * FROM {$this->_table} WHERE 1 = 1";
		if (!is_null($info)) {
			if (isset($info->parent_id)) {
				$sql .= " AND parent_id = '{$info->parent_id}'";	
			}
			if (!empty($info->id)) {
				$sql .= " AND id = '{$info->id}'";
			}
			if (isset($info->order_category)) {
				$sql .= " AND order_category = '{$info->order_category}'";	
			}
			if (!empty($info->show_cate)) {
				$sql .= " AND show_cate = '{$info->show_cate}'";
			}
		}
		if (!is_null($active)) {
			$sql .= " AND {$this->_table}.active = '{$active}'";
		}
		$sql .= " AND {$this->_table}.deleted = '0'";
		if (!empty($order_by)) {
			$sql .= " ORDER BY {$this->_table}.{$order_by} {$sort_by}";
		} else {
			$sql .= " ORDER BY {$this->_table}.id, {$this->_table}.order_num ASC";
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