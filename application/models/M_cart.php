<?php
class M_cart extends M_db
{
	public function __construct()
	{
		parent::__construct();

		$this->_table = "m_cart";
	}

	public function items( $info=null, $limit=null, $offset=null, $order_by=null, $sort_by='DESC')
	{
		$sql = "SELECT * FROM {$this->_table}  WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->user_id)) {
				$sql .= " AND user_id = '{$info->user_id}'";
			}
			if (!empty($info->product_id)) {
				$sql .= " AND product_id = '{$info->product_id}'";
			}
			if (!empty($info->product_id_temp)) {
				$sql .= " AND product_id_temp = '{$info->product_id_temp}'";
			}
		}
		if (!empty($order_by)) {
			$sql .= " ORDER BY {$order_by} {$sort_by}";
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
}
?>
