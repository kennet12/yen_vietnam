<?
class M_order extends M_db
{
	public function __construct()
	{
		parent::__construct();
		
		$this->_table = "m_order";
	}
	
	public function items($info=null,$limit=null, $offset=null)
	{
		$sql = "SELECT * FROM {$this->_table} WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->email)) {
				$sql .= " AND email = '{$info->email}'";
			}
            if (!empty($info->status)) {
				$sql .= " AND status = '{$info->status}'";
			}
			if (!empty($info->search_text)) {
				$sql .= " AND (id LIKE '%{$info->search_text}%' OR fullname LIKE '%{$info->search_text}%' OR email LIKE '%{$info->search_text}%' OR phone LIKE '%{$info->search_text}%')";
			}
			if (!empty($info->fromdate)) {
				$sql .= " AND DATE(created_date) >= '{$info->fromdate}'";
			}
			if (!empty($info->todate)) {
				$sql .= " AND DATE(created_date) <= '{$info->todate}'";
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
	public function load_key($key)
	{
		$sql = "SELECT * FROM {$this->_table} WHERE 1 = 1";
		if ($this->db->field_exists("order_key", $this->_table)) {
			$sql .= " AND order_key = '{$key}'";
		}
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		return null;
	}
}