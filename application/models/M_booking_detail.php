<?php
class M_booking_detail extends M_db
{
	public function __construct()
	{
		parent::__construct();

		$this->_table = "m_booking_detail";
	}

	public function items( $info=null, $limit=null, $offset=null, $order_by=null, $sort_by='DESC')
	{
		$sql = "SELECT * FROM {$this->_table}  WHERE 1 = 1";
		if (!is_null($info)) {

			if (!empty($info->user_id)) {
				$sql .= " AND user_id = '{$info->user_id}'";
			}
			if (!empty($info->booking_id)) {
				$sql .= " AND booking_id = '{$info->booking_id}'";
			}
			if (!empty($info->list_booking_id)) {
				$sql .= " AND booking_id IN ({$info->list_booking_id})";
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
	public function jion_product_items( $info=null, $limit=null, $offset=null, $order_by=null, $sort_by='DESC')
	{
		$sql = "SELECT BD.*, P.affiliate as p_affiliate FROM {$this->_table} AS BD INNER JOIN m_product AS P ON (BD.product_id = P.id) WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->booking_id)) {
				$sql .= " AND BD.booking_id = '{$info->booking_id}'";
			}
			if (!empty($info->list_booking_id)) {
				$sql .= " AND BD.booking_id IN ({$info->list_booking_id})";
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
	public function report_items($select="*", $info=null, $limit=null, $offset=null, $order_by=null, $sort_by='DESC')
	{
		$sql = "SELECT {$select} FROM {$this->_table} as s INNER JOIN m_booking as b ON (s.booking_id = b.id)  WHERE 1 = 1";
		if (!is_null($info)) {

			if (!empty($info->user_id)) {
				$sql .= " AND user_id = '{$info->user_id}'";
			}
			if (!empty($info->booking_id)) {
				$sql .= " AND booking_id = '{$info->booking_id}'";
			}
			if (!empty($info->list_booking_id)) {
				$sql .= " AND booking_id IN ({$info->list_booking_id})";
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
