<?
class M_block extends M_db
{
	public function __construct()
	{
		parent::__construct();
		
		$this->_table = "m_block";
	}
	
	public function users($info=null, $limit=null, $offset=null)
	{
		$sql = "SELECT *, '0' AS 'child_num' FROM {$this->_table} WHERE 1 = 1";
		
		if (!is_null($info)) {
			if (!empty($info->phone)) {
				$sql .= " AND {$this->_table}.phone = '{$info->phone}'";
			}
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
