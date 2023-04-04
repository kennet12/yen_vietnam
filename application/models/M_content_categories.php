<?
class M_content_categories extends M_db
{
	public function __construct()
	{
		parent::__construct();
		
		$this->_table = "m_content_categories";
	}
	
	public function items($info=null, $active=null, $limit=null, $offset=null, $order_by=null, $sort_by='DESC')
	{
		$sql = "SELECT * FROM {$this->_table} WHERE 1 = 1";
		if (!is_null($info)) {
			if (isset($info->parent_id)) {
				$sql .= " AND parent_id = '{$info->parent_id}'";	
			}
		}
		if (!is_null($active)) {
			$sql .= " AND {$this->_table}.active = '{$active}'";
		}
		$sql .= " AND {$this->_table}.deleted = '0'";
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
	public function load_content_category($id, $parent_id=null)
	{
		$sql = "SELECT * FROM {$this->_table} WHERE 1 = 1";
		if (is_numeric($id)) {
			$sql .= " AND id = ?";
		} else if ($this->db->field_exists("alias", $this->_table)) {
			$sql .= " AND alias = ?";
		}
		if (!is_null($parent_id)) {
			$sql .= " AND {$this->_table}.parent_id = '{$parent_id}'";
		}
		$stmt = $this->db->conn_id->prepare($sql);
		$stmt->bind_param('s', $id);
		$stmt->execute();
		$result = $stmt->get_result();
		if ($result->num_rows > 0) {
			return (object)$result->fetch_assoc();
		}
		return null;
	}
}
?>