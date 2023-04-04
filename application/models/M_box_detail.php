<?
class M_box_detail extends M_db
{
	public function __construct()
	{
		parent::__construct();
		
		$this->_table = "m_box_detail";
	}
	
	public function items($info=null)
	{
		$sql = "SELECT * FROM {$this->_table} WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->box_id)) {
				$sql .= " AND box_id = '{$info->box_id}'";
			}
			if (!empty($info->fromdate)) {
				$sql .= " AND DATE(created_date) >= '{$info->fromdate}'";
			}
			if (!empty($info->todate)) {
				$sql .= " AND DATE(created_date) <= '{$info->todate}'";
			}
			if (!empty($info->list_box_id)) {
				$sql .= " AND box_id IN ({$info->list_box_id})";
			}
		}
		$sql .= " ORDER BY id ASC";
		$query = $this->db->query($sql);
		return $query->result();
	}
	public function join_box($info=null,$i=0,$limit=null, $offset=null)
	{
		$sql = "SELECT bd.*, b.category_id, b.created_date as box_created_date FROM {$this->_table} as bd JOIN m_box as b ON bd.box_id=b.id WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->box_id)) {
				$sql .= " AND bd.box_id = '{$info->box_id}'";
			}
			if (!empty($info->fromdate)) {
				$sql .= " AND DATE(b.created_date) >= '{$info->fromdate}'";
			}
			if (!empty($info->todate)) {
				$sql .= " AND DATE(b.created_date) <= '{$info->todate}'";
			}
			if (!empty($info->list_box_id)) {
				$sql .= " AND bd.box_id IN ({$info->list_box_id})";
			}
			if (!empty($info->search_box)) {
				$sql .= " AND title LIKE '%{$info->search_box}%'";
			}
			if (!empty($info->category_id)) {
				$sql .= " AND b.category_id LIKE '%\"{$info->category_id}\"%'";
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
		$sql .= " ORDER BY bd.id ASC";
		$query = $this->db->query($sql);
		return $query->result();
	}
	public function join_items($info=null,$i=0,$limit=null, $offset=null)
	{
		$sql = "SELECT * FROM {$this->_table} WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->typename)) {
				$sql .= " AND typename = '{$info->typename}'";
			}
		}
		$sql .= " AND JSON_EXTRACT(quantity, '$[{$i}]') <> 0";
		$sql .= " ORDER BY id ASC";
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