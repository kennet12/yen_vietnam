<?php
class M_affiliate extends M_db
{
	public function __construct()
	{
		parent::__construct();

		$this->_table = "m_affiliate";
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
			if (!empty($info->affiliate_code)) {
				$sql .= " AND affiliate_code = '{$info->affiliate_code}'";
			}
			if (!empty($info->payment_status)) {
				$sql .= " AND payment_status = '{$info->payment_status}'";
			}
		}
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
	public function jion_booking_items($info=null, $limit=null, $offset=null, $order_by=null, $sort_by='DESC')
	{
		$sql = "SELECT B.*, A.id as a_id, A.affiliate_code as a_affiliate_code, A.amount as a_amount, A.payment_status as a_payment_status FROM {$this->_table} AS A INNER JOIN m_booking AS B ON (A.booking_id = B.id) WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->affiliate_code)) {
				$sql .= " AND A.affiliate_code = '{$info->affiliate_code}'";
			}
			if (!empty($info->phone)) {
				$sql .= " AND B.phone LIKE '%{$info->phone}'";
			}
			if (!empty($info->a_payment_status)) {
				$sql .= " AND A.payment_status = '{$info->a_payment_status}'";
			}
			if (!empty($info->payment_status)) {
				$sql .= " AND B.payment_status = '{$info->payment_status}'";
			}
			if (!empty($info->search_text)) {
				$sql .= " AND (B.id LIKE '%{$info->search_text}%' OR B.fullname LIKE '%{$info->search_text}%' OR B.email LIKE '%{$info->search_text}%' OR B.phone LIKE '%{$info->search_text}%')";
			}
			if (!empty($info->fromdate)) {
				$sql .= " AND DATE(B.created_date) >= '{$info->fromdate}'";
			}
			if (!empty($info->todate)) {
				$sql .= " AND DATE(B.created_date) <= '{$info->todate}'";
			}
			if (!empty($info->from_paid_date)) {
				$sql .= " AND DATE(B.paid_date) >= '{$info->from_paid_date}'";
			}
			if (!empty($info->to_paid_date)) {
				$sql .= " AND DATE(B.paid_date) <= '{$info->to_paid_date}'";
			}
		}
		if (!empty($order_by)) {
			$sql .= " ORDER BY B.{$order_by} {$sort_by}";
		} else {
			$sql .= " ORDER BY B.created_date DESC";
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
	public function jion_count_booking_items($info=null, $limit=null, $offset=null, $order_by=null, $sort_by='DESC')
	{
		$sql = "SELECT B.*, sum(A.amount) as amount , A.id as a_id, A.affiliate_code as a_affiliate_code, A.payment_status as a_payment_status FROM {$this->_table} AS A INNER JOIN m_booking AS B ON (A.booking_id = B.id) WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->affiliate_code)) {
				$sql .= " AND A.affiliate_code = '{$info->affiliate_code}'";
			}
			if (!empty($info->phone)) {
				$sql .= " AND B.phone LIKE '%{$info->phone}'";
			}
			if (!empty($info->a_payment_status)) {
				$sql .= " AND A.payment_status = '{$info->a_payment_status}'";
			}
			if (!empty($info->payment_status)) {
				$sql .= " AND B.payment_status = '{$info->payment_status}'";
			}
			if (!empty($info->search_text)) {
				$sql .= " AND (B.id LIKE '%{$info->search_text}%' OR B.fullname LIKE '%{$info->search_text}%' OR B.email LIKE '%{$info->search_text}%' OR B.phone LIKE '%{$info->search_text}%')";
			}
			if (!empty($info->fromdate)) {
				$sql .= " AND DATE(B.created_date) >= '{$info->fromdate}'";
			}
			if (!empty($info->todate)) {
				$sql .= " AND DATE(B.created_date) <= '{$info->todate}'";
			}
			if (!empty($info->from_paid_date)) {
				$sql .= " AND DATE(B.paid_date) >= '{$info->from_paid_date}'";
			}
			if (!empty($info->to_paid_date)) {
				$sql .= " AND DATE(B.paid_date) <= '{$info->to_paid_date}'";
			}
		}
		if (!empty($order_by)) {
			$sql .= " ORDER BY B.{$order_by} {$sort_by}";
		} else {
			$sql .= " ORDER BY B.created_date DESC";
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
