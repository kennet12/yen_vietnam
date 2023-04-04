<?
class M_payment_online extends M_db
{
	public function __construct()
	{
		parent::__construct();
		
		$this->_table = "m_payment_online";
	}
	
	public function items($info=null, $active=null, $limit=null, $offset=null, $order_by=null, $sort_by='DESC')
	{
		$sql = "SELECT * FROM {$this->_table} WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->booking_id)) {
				$sql .= " AND {$this->_table}.booking_id = '{$info->booking_id}'";
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
	public function booking($key)
	{
		$sql = "SELECT I.* FROM {$this->_table} AS I WHERE 1 = 1";
		if (!empty($key)) {
				$sql .= " AND I.payment_key = '{$key}'";
			}
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		return null;
	}
}
?>