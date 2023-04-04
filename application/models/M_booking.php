<?php
class M_booking extends M_db
{
	public function __construct()
	{
		parent::__construct();

		$this->_table = "m_booking";
	}

	public function items( $info=null, $limit=null, $offset=null, $order_by=null, $sort_by='DESC')
	{
		$sql = "SELECT * FROM {$this->_table}  WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->user_id)) {
				$sql .= " AND user_id = '{$info->user_id}'";
			}
			if (!empty($info->phone)) {
				$sql .= " AND phone LIKE '%{$info->phone}'";
			}
			if (!empty($info->code)) {
				$sql .= " AND id = '{$info->code}'";
			}
			if (!empty($info->payment_status)) {
				$sql .= " AND payment_status = '{$info->payment_status}'";
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
			if (!empty($info->from_paid_date)) {
				$sql .= " AND DATE(paid_date) >= '{$info->from_paid_date}'";
			}
			if (!empty($info->to_paid_date)) {
				$sql .= " AND DATE(paid_date) <= '{$info->to_paid_date}'";
			}
		}
		if (!empty($order_by)) {
			$sql .= " ORDER BY {$order_by} {$sort_by}";
		} else {
			$sql .= " ORDER BY created_date DESC";
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
	public function booking($key,$active=null)
	{
		$sql = "SELECT I.* FROM {$this->_table} AS I WHERE 1 = 1";
		if (!empty($key)) {
				$sql .= " AND I.booking_key = '{$key}'";
			}
		if (!is_null($active)) {
			$sql .= " AND I.active = '{$active}'";
		}
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		return null;
	}
	public function view_booking( $info=null, $limit=null, $offset=null, $order_by=null, $sort_by='DESC')
	{
		$sql = "SELECT COUNT(id) as total_book, SUM(total) as total, SUM(cost) as cost FROM {$this->_table}  WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->user_id)) {
				$sql .= " AND user_id = '{$info->user_id}'";
			}
			if (!empty($info->payment_status)) {
				$sql .= " AND payment_status = '{$info->payment_status}'";
			}
			if (!empty($info->fromdate)) {
				$sql .= " AND DATE(created_date) >= '{$info->fromdate}'";
			}
			if (!empty($info->todate)) {
				$sql .= " AND DATE(created_date) <= '{$info->todate}'";
			}
		}
		if (!empty($order_by)) {
			$sql .= " ORDER BY {$order_by} {$sort_by}";
		} else {
			$sql .= " ORDER BY created_date DESC";
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
