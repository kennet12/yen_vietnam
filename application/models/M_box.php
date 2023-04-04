<?
class M_box extends M_db
{
	public function __construct()
	{
		parent::__construct();
		
		$this->_table = "m_box";
	}
	
	public function items($info=null,$limit=null, $offset=null)
	{
		$sql = "SELECT * FROM {$this->_table} WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->product_id)) {
				$sql .= " AND product_id = '{$info->product_id}'";
			}
			if (!empty($info->fromdate)) {
				$sql .= " AND DATE(created_date) >= '{$info->fromdate}'";
			}
			if (!empty($info->todate)) {
				$sql .= " AND DATE(created_date) <= '{$info->todate}'";
			}
			if (!empty($info->search_box)) {
				$sql .= " AND title LIKE '%{$info->search_box}%'";
			}
			if (!empty($info->category_id)) {
				$sql .= " AND category_id LIKE '%\"{$info->category_id}\"%'";
			}
			if (!empty($info->list_category)) {
				$sql .= " AND (";
				foreach ($info->list_category as $list_category) {
					$sql .= " list_category LIKE '%\"{$list_category}\"%'";

					if (end($info->list_category)!=$list_category)
					$sql .= " OR";
				}
				$sql .= " )";
			}
		}
		$sql .= " ORDER BY created_date DESC";
		if (!is_null($limit)) {
			$sql .= " LIMIT {$limit}";
		}
		if (!is_null($offset)) {
			$sql .= " OFFSET {$offset}";
		}
		$query = $this->db->query($sql);
		return $query->result();
	}
	public function join_items($info=null,$limit=null, $offset=null)
	{
		$sql = "SELECT b.*, bd.id as box_detail_id, bd.quantity, bd.cost FROM {$this->_table} as b JOIN m_box_detail bd ON b.id=bd.box_id  WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->product_id)) {
				$sql .= " AND b.product_id = '{$info->product_id}'";
			}
			if (!empty($info->typename)) {
				$sql .= " AND bd.typename = '{$info->typename}'";
			}
			if (!empty($info->fromdate)) {
				$sql .= " AND DATE(b.created_date) >= '{$info->fromdate}'";
			}
			if (!empty($info->todate)) {
				$sql .= " AND DATE(b.created_date) <= '{$info->todate}'";
			}
		}
		$sql .= " ORDER BY b.created_date ASC";
		if (!is_null($limit)) {
			$sql .= " LIMIT {$limit}";
		}
		if (!is_null($offset)) {
			$sql .= " OFFSET {$offset}";
		}
		$query = $this->db->query($sql);
		return $query->result();
	}
	public function count_item($info=null,$limit=null, $offset=null)
	{
		$sql = "SELECT COUNT(b.id) as total FROM {$this->_table} as b  WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->product_id)) {
				$sql .= " AND b.product_id = '{$info->product_id}'";
			}
			if (!empty($info->fromdate)) {
				$sql .= " AND DATE(b.created_date) >= '{$info->fromdate}'";
			}
			if (!empty($info->todate)) {
				$sql .= " AND DATE(b.created_date) <= '{$info->todate}'";
			}
			if (!empty($info->list_category)) {
				$sql .= " AND (";
				foreach ($info->list_category as $list_category) {
					$sql .= " b.list_category LIKE '%\"{$list_category}\"%'";

					if (end($info->list_category)!=$list_category)
					$sql .= " OR";
				}
				$sql .= " )";
			}
		}
		$sql .= " ORDER BY b.created_date ASC";
		if (!is_null($limit)) {
			$sql .= " LIMIT {$limit}";
		}
		if (!is_null($offset)) {
			$sql .= " OFFSET {$offset}";
		}
		$query = $this->db->query($sql);
		return $query->result();
	}
	public function count_item_int($info=null,$limit=null, $offset=null)
	{
		$sql = "SELECT DISTINCT b.product_id FROM {$this->_table} as b JOIN m_box_detail bd ON b.id=bd.box_id  WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->product_id)) {
				$sql .= " AND b.product_id = '{$info->product_id}'";
			}
			if (!empty($info->fromdate)) {
				$sql .= " AND DATE(b.created_date) >= '{$info->fromdate}'";
			}
			if (!empty($info->todate)) {
				$sql .= " AND DATE(b.created_date) <= '{$info->todate}'";
			}
			if (!empty($info->list_category)) {
				$sql .= " AND (";
				foreach ($info->list_category as $list_category) {
					$sql .= " b.list_category LIKE '%\"{$list_category}\"%'";

					if (end($info->list_category)!=$list_category)
					$sql .= " OR";
				}
				$sql .= " )";
			}
		}
		$sql .= " AND (JSON_EXTRACT(bd.quantity, '$[0]') <> 0 OR JSON_EXTRACT(bd.quantity, '$[1]') <> 0 OR JSON_EXTRACT(bd.quantity, '$[2]') <> 0 OR JSON_EXTRACT(bd.quantity, '$[3]') <> 0)";
		$sql .= " ORDER BY b.created_date ASC";
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