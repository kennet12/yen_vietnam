<?php
class M_contents extends M_db
{
	public function __construct()
	{
		parent::__construct();
		
		$this->_table = "m_contents";
	}
	
	public function items($info=null, $active=null, $limit=null, $offset=null, $order_by=null, $sort_by='DESC')
	{
		$sql = "SELECT I.*, C.alias AS 'category_alias', C.alias_en AS 'category_alias_en', '0' AS 'child_num' FROM m_contents AS I INNER JOIN m_content_categories AS C ON (I.category_id = C.id) WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->category_id)) {
				$sql .= " AND I.category_id = '{$info->category_id}'";
			}
			if (!empty($info->search_text)) {
				$sql .= ' AND (I.title LIKE "%'.$info->search_text.'%" OR I.meta_key LIKE "%'.$info->search_text.'%" OR I.alias LIKE "%'.$this->util->slug($info->search_text).'%")';
			}
		}
		if (!is_null($active)) {
			$sql .= " AND I.active = '{$active}'";
		}
		$sql .= " AND I.deleted = '0'";
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

	public function relative_items ($info=null, $ids, $active=null, $limit=null, $offset=null, $order_by=null, $sort_by='DESC') {
		$sql = "SELECT I.*, C.alias AS 'category_alias', C.alias_en AS 'category_alias_en', '0' AS 'child_num' FROM m_contents AS I INNER JOIN m_content_categories AS C ON (I.category_id = C.id) WHERE 1 = 1";
		foreach ($ids as $id) {
			$sql .= " AND I.id <> '{$id}'";
		}
		if (!is_null($info)) {
			if (!empty($info->category_id)) {
				$sql .= " AND I.category_id = '{$info->category_id}'";
			}
		}
		if (!is_null($active)) {
			$sql .= " AND I.active = '{$active}'";
		}
		$sql .= " AND I.deleted = '0'";
		if (!empty($order_by)) {
			$sql .= " ORDER BY {$order_by} {$sort_by}";
		} else {
			$sql .= " ORDER BY I.created_date DESC";
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

	public function count_relative_items($info=null, $ids, $active=null, $order_by=null, $sort_by='DESC') {
		$sql   = "SELECT COUNT(*) FROM m_contents  WHERE 1 = 1";
		foreach ($ids as $id) {
			$sql .= " AND id <> '{$id}'";
		}
		if (!is_null($info)) {
			if (!empty($info->category_id)) {
				$sql .= " AND category_id = '{$info->category_id}'";
			}
		}
		if (!is_null($active)) {
			$sql .= " AND active = '{$active}'";
		}
		$sql .= " AND deleted = '0'";
		if (!empty($order_by)) {
			$sql .= " ORDER BY {$order_by} {$sort_by}";
		} else {
			$sql .= " ORDER BY created_date DESC";
		}
		$query = $this->db->query($sql);
		$result = $query->row_array();
		return (int)$result['COUNT(*)'];
	}

	public function count_items($info=null, $active=null)
	{
		$sql = "SELECT COUNT(*) FROM m_contents WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->category_id)) {
				$sql .= " AND category_id = '{$info->category_id}'";
			}
		}
		if (!is_null($active)) {
			$sql .= " AND active = '{$active}'";
		}
		$sql .= " AND deleted = '0'";
		$query = $this->db->query($sql);
		$result = $query->row_array();
		return (int)$result['COUNT(*)'];
	}
}
?>