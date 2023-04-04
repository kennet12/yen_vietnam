<?
class M_brand extends M_db
{
	public function __construct()
	{
		parent::__construct();
		
		$this->_table = "m_brand";
	}
	
	public function items($info=null, $active=null, $limit=null, $offset=null)
	{
		$sql = "SELECT * FROM {$this->_table} WHERE 1 = 1";
		
		if (!is_null($info)) {
			if (!empty($info->product_category_id)) {
				$sql .= " AND {$this->_table}.product_category_id LIKE '%\"{$info->product_category_id}\"%'";
			}
		}
		if (!is_null($active)) {
			$sql .= " AND {$this->_table}.active = '{$active}'";
		}
		$sql .= " ORDER BY {$this->_table}.created_date DESC";
		
		if (!is_null($limit)) {
			$sql .= " LIMIT {$limit}";
		}
		if (!is_null($limit)) {
			$sql .= " OFFSET {$offset}";
		}
		
		$query = $this->db->query($sql);
		return $query->result();
	}
}
?>
