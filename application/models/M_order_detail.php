<?
class M_order_detail extends M_db
{
	public function __construct()
	{
		parent::__construct();
		
		$this->_table = "m_order_detail";
	}
	
	public function items($info=null)
	{
		$sql = "SELECT * FROM {$this->_table} WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->order_id)) {
				$sql .= " AND order_id = '{$info->order_id}'";
			}
		}
		$query = $this->db->query($sql);
		return $query->result();
	}
}