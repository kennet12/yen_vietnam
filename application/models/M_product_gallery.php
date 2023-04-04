<?php
class M_product_gallery extends M_db
{
	public function __construct()
	{
		parent::__construct();

		$this->_table = "m_product_gallery";
	}

	public function items($info=null, $limit=null, $offset=null)
	{
		$sql = "SELECT * FROM {$this->_table} WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->product_id)) {
				$sql .= " AND product_id = '{$info->product_id}'";
			}
		}
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
	public function get_one_thumb($info=null)
	{
		$sql = "SELECT * FROM {$this->_table} WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->product_id)) {
				$sql .= " AND product_id = '{$info->product_id}'";
			}
		}
		$sql .= " ORDER BY id ASC";
		$sql .= " LIMIT 1";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		return null;
	}
}
?>
