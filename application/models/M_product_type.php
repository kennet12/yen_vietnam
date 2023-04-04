<?
class M_product_type extends M_db
{
	public function __construct()
	{
		parent::__construct();
		
		$this->_table = "m_product_type";
	}
	
	public function items($info=null)
	{
		$sql = "SELECT * FROM {$this->_table} WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->product_id)) {
				$sql .= " AND product_id = '{$info->product_id}'";
			}
			if (!empty($info->typename)) {
				$sql .= " AND typename = '{$info->typename}'";
			}
			if (!empty($info->list_product_id)) {
				$sql .= " AND product_id IN ({$info->list_product_id})";
			}
		}
		$sql .= " ORDER BY id ASC";
		$query = $this->db->query($sql);
		return $query->result();
	}
}
?>