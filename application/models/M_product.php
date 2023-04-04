<?php
class M_product extends M_db
{
	public function __construct()
	{
		parent::__construct();
		
		$this->_table = "m_product";
	}
	
	public function items($info=null, $active=null, $limit=null, $offset=null, $order_by=null, $sort_by='DESC')
	{
		$sql = "SELECT * FROM {$this->_table} WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->bold)) {
				$sql .= " AND bold = '{$info->bold}'";
			}
			if (!empty($info->category_id)) {
				$sql .= " AND (";
				foreach ($info->category_id as $category_id) {
					$sql .= " category_id LIKE '%\"{$category_id}\"%'";

					if (end($info->category_id)!=$category_id)
					$sql .= " OR";
				}
				$sql .= " )";
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
		if (!is_null($limit)) {
			$sql .= " LIMIT {$limit}";
		}
		if (!is_null($offset)) {
			$sql .= " OFFSET {$offset}";
		}
		$query = $this->db->query($sql);
		return $query->result();
	}

	public function relative_items ($select='*',$info=null, $id, $active=null, $limit=null, $offset=null, $order_by=null, $sort_by=null) {
		$sql = "SELECT {$select} FROM {$this->_table} p 
		JOIN m_product_type t ON p.id=t.product_id WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->bold)) {
				$sql .= " AND bold = '{$info->bold}'";
			}
			if (!empty($info->category_id)) {
				$sql .= " AND category_id LIKE '%\"{$info->category_id}\"%'";
			}
			if (!empty($info->quantityEmpty)) {
				$sql .= " AND ".OPEN_CONVERT_CAST."JSON_EXTRACT(t.quantity, '$[0]')".CLOSE_CONVERT_CAST." <> 0";
			}
			if (!empty($info->flashDeal)) {
				$sql .= " AND ".OPEN_CONVERT_CAST."JSON_EXTRACT(t.sale, '$[0]')".CLOSE_CONVERT_CAST." <> 0";
			}
			if (!empty($info->price)) {
				$sql .= " AND (";
					$i = 0;
				foreach ($info->price as $price) {
					$price = explode('-', $price); $min = $price[0]; $max = $price[1];
					if ($i > 0) $sql .= " OR";
					if ($min != '2000000') $sql .= " ( ".OPEN_CONVERT_CAST."JSON_EXTRACT(t.price, '$[0]')".CLOSE_CONVERT_CAST." >= {$min} AND ".OPEN_CONVERT_CAST."JSON_EXTRACT(t.price, '$[0]')".CLOSE_CONVERT_CAST." <= {$max} )";
					else $sql .= " ( ".OPEN_CONVERT_CAST."JSON_EXTRACT(t.price, '$[0]')".CLOSE_CONVERT_CAST." >= {$min} )";
					$i++;
				}
				$sql .= " )";
			}
		}
		$sql .= " AND p.id <> '{$id}'";
		if (!is_null($active)) {
			$sql .= " AND p.active = '{$active}'";
		}
		$sql .= " AND p.deleted = '0'";
		// $sql .= " GROUP BY p.id";
		if (!empty($order_by)) {
			$sql .= " ORDER BY {$order_by} {$sort_by}";
		} else {
			$sql .= " ORDER BY p.created_date DESC";
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
	public function get_items ($select='*',$info=null, $active=null, $limit=null, $offset=null, $order_by=null, $sort_by=null) {
		$sql = "SELECT {$select} FROM {$this->_table} p 
		JOIN m_product_type t ON p.id=t.product_id 
        JOIN m_product_gallery g ON p.id=g.product_id WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->bold)) {
				$sql .= " AND bold = '{$info->bold}'";
			}
			if (!empty($info->category_id)) {
				$sql .= " AND category_id IN ({$info->category_id})";
			}
			if (!empty($info->search_text)) {
				$sql .= ' AND (p.title LIKE "%'.$info->search_text.'%" OR p.meta_key LIKE "%'.$info->search_text.'%" OR p.alias LIKE "%'.$this->util->slug($info->search_text).'%")';
			}
			if (!empty($info->quantityEmpty)) {
				$sql .= " AND ".OPEN_CONVERT_CAST."JSON_EXTRACT(t.quantity, '$[0]')".CLOSE_CONVERT_CAST." <> 0";
			}
			if (!empty($info->flashDeal)) {
				$sql .= " AND ".OPEN_CONVERT_CAST."JSON_EXTRACT(t.sale, '$[0]')".CLOSE_CONVERT_CAST." <> 0";
			}
			if (!empty($info->price)) {
				$sql .= " AND (";
					$i = 0;
				foreach ($info->price as $price) {
					$price = explode('-', $price); $min = $price[0]; $max = $price[1];
					if ($i > 0) $sql .= " OR";
					if ($min != '2000000') $sql .= " ( ".OPEN_CONVERT_CAST."JSON_EXTRACT(t.price, '$[0]')".CLOSE_CONVERT_CAST." >= {$min} AND ".OPEN_CONVERT_CAST."JSON_EXTRACT(t.price, '$[0]')".CLOSE_CONVERT_CAST." <= {$max} )";
					else $sql .= " ( ".OPEN_CONVERT_CAST."JSON_EXTRACT(t.price, '$[0]')".CLOSE_CONVERT_CAST." >= {$min} )";
					$i++;
				}
				$sql .= " )";
			}
		}
		if (!is_null($active)) {
			$sql .= " AND p.active = '{$active}'";
		}
		$sql .= " AND p.deleted = '0'";
		// $sql .= " GROUP BY p.id";
		if (!empty($order_by)) {
			$sql .= " ORDER BY {$order_by} {$sort_by}";
		} else {
			$sql .= " ORDER BY p.created_date DESC";
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
	public function get_list_category_items ($select='*',$info=null, $active=null, $limit=null, $offset=null, $order_by=null, $sort_by=null) {
		$sql = "SELECT {$select} FROM {$this->_table} p 
		JOIN m_product_type t ON p.id=t.product_id WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->bold)) {
				$sql .= " AND bold = '{$info->bold}'";
			}
			if (!empty($info->order_product)) {
				$sql .= " AND order_product = '{$info->order_product}'";
			}
			if (!empty($info->category_id)) {
				$sql .= " AND category_id IN ({$info->category_id})";
			}
			if (!empty($info->list_category)) {
				$sql .= " AND (";
				foreach ($info->list_category as $list_category) {
					$sql .= " list_category LIKE '%\"{$list_category}\"%'";

					if (end($info->list_category)!=$list_category)
					$sql .= " OR";
				}
				$sql .= " )";
			}
			if (!empty($info->constraint)) {
				$sql .= " AND (";
				foreach ($info->constraint as $const) {
					$sql .= " brand LIKE '%{$const}%'";

					if (end($info->constraint)!=$const)
					$sql .= " OR";
				}
				$sql .= " )";
			}
			if (!empty($info->like_item)) {
				$sql .= " AND p.id IN ({$info->like_item})";
			}
			if (!empty($info->search_text)) {
				$sql .= ' AND (p.title LIKE "%'.$info->search_text.'%" OR p.title_en LIKE "%'.$info->search_text.'%" OR p.meta_key LIKE "%'.$info->search_text.'%" OR p.code_product LIKE "%'.$info->search_text.'%")';
			}
			if (!empty($info->quantityEmpty)) {
				$sql .= " AND ".OPEN_CONVERT_CAST."JSON_EXTRACT(t.quantity, '$[0]')".CLOSE_CONVERT_CAST." <> 0";
			}
			if (!empty($info->flashDeal)) {
				$sql .= " AND ".OPEN_CONVERT_CAST."JSON_EXTRACT(t.sale, '$[0]')".CLOSE_CONVERT_CAST." <> 0";
			}
			if (!empty($info->price)) {
				$sql .= " AND (";
					$i = 0;
				foreach ($info->price as $price) {
					$price = explode('-', $price); $min = $price[0]; $max = $price[1];
					if ($i > 0) $sql .= " OR";
					if ($min != '2000000') $sql .= " ( ".OPEN_CONVERT_CAST."JSON_EXTRACT(t.price, '$[0]')".CLOSE_CONVERT_CAST." >= {$min} AND ".OPEN_CONVERT_CAST."JSON_EXTRACT(t.price, '$[0]')".CLOSE_CONVERT_CAST." <= {$max} )";
					else $sql .= " ( ".OPEN_CONVERT_CAST."JSON_EXTRACT(t.price, '$[0]')".CLOSE_CONVERT_CAST." >= {$min} )";
					$i++;
				}
				$sql .= " )";
			}
		}
		if (!is_null($active)) {
			$sql .= " AND p.active = '{$active}'";
		}
		$sql .= " AND p.deleted = '0'";
		// $sql .= " GROUP BY p.id";
		if (!empty($order_by)) {
			$sql .= " ORDER BY {$order_by} {$sort_by}";
		} else {
			$sql .= " ORDER BY p.created_date DESC";
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
	public function get_search_list_items ($select='*',$info=null, $active=null, $limit=null, $offset=null, $order_by=null, $sort_by=null) {
		$sql = "SELECT {$select} FROM {$this->_table} p 
		JOIN m_product_type t ON p.id=t.product_id WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->bold)) {
				$sql .= " AND bold = '{$info->bold}'";
			}
			if (!empty($info->category_id)) {
				$sql .= " AND category_id IN ({$info->category_id})";
			}
			if (!empty($info->list_category)) {
				$sql .= " AND (";
				foreach ($info->list_category as $list_category) {
					$sql .= " list_category LIKE '%\"{$list_category}\"%'";

					if (end($info->list_category)!=$list_category)
					$sql .= " OR";
				}
				$sql .= " )";
			}
			if (!empty($info->like_item)) {
				$sql .= " AND p.id IN ({$info->like_item})";
			}
			if (!empty($info->search_text)) {
				$sql .= ' AND (p.title LIKE "%'.$info->search_text.'%" OR p.meta_key LIKE "%'.$info->search_text.'%" OR p.code_product LIKE "%'.$info->search_text.'%")';
			}
			if (!empty($info->quantityEmpty)) {
				$sql .= " AND JSON_EXTRACT(t.quantity, '$[0]') <> 0";
			}
			if (!empty($info->price)) {
				$sql .= " AND (";
					$i = 0;
				foreach ($info->price as $price) {
					$price = explode('-', $price); $min = $price[0]; $max = $price[1];
					if ($i > 0) $sql .= " OR";
					if ($min != '2000000') $sql .= " ( JSON_EXTRACT(t.price, '$[0]') >= {$min} AND JSON_EXTRACT(t.price, '$[0]') <= {$max} )";
					else $sql .= " ( JSON_EXTRACT(t.price, '$[0]') >= {$min} )";
					$i++;
				}
				$sql .= " )";
			}
		}
		if (!is_null($active)) {
			$sql .= " AND p.active = '{$active}'";
		}
		$sql .= " AND p.deleted = '0'";
		// $sql .= " GROUP BY p.id";
		if (!empty($order_by)) {
			$sql .= " ORDER BY {$order_by} {$sort_by}";
		} else {
			$sql .= " ORDER BY p.created_date DESC";
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
	public function get_list_category_admin ($select='*',$info=null, $active=null, $limit=null, $offset=null, $order_by=null, $sort_by=null) {
		$sql = "SELECT {$select} FROM {$this->_table} p 
		JOIN m_product_type t ON p.id=t.product_id WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->bold)) {
				$sql .= " AND bold = '{$info->bold}'";
			}
			if (!empty($info->category_id)) {
				$sql .= " AND category_id IN ({$info->category_id})";
			}
			if (!empty($info->list_category)) {
				$sql .= " AND (";
				foreach ($info->list_category as $list_category) {
					$sql .= " category_id LIKE '%\"{$list_category}\"%'";

					if (end($info->list_category)!=$list_category)
					$sql .= " OR";
				}
				$sql .= " )";
			}
			if (!empty($info->search_text)) {
				$sql .= ' AND (p.title LIKE "%'.$info->search_text.'%" OR p.meta_key LIKE "%'.$info->search_text.'%" OR p.code_product LIKE "%'.$info->search_text.'%")';
			}
			if (!empty($info->quantityEmpty)) {
				$sql .= " AND ".OPEN_CONVERT_CAST."JSON_EXTRACT(t.quantity, '$[0]')".CLOSE_CONVERT_CAST." <> 0";
			}
			if (!empty($info->flashDeal)) {
				$sql .= " AND ".OPEN_CONVERT_CAST."JSON_EXTRACT(t.sale, '$[0]')".CLOSE_CONVERT_CAST." <> 0";
			}
			if (!empty($info->price)) {
				$sql .= " AND (";
					$i = 0;
				foreach ($info->price as $price) {
					$price = explode('-', $price); $min = $price[0]; $max = $price[1];
					if ($i > 0) $sql .= " OR";
					if ($min != '2000000') $sql .= " ( ".OPEN_CONVERT_CAST."JSON_EXTRACT(t.price, '$[0]')".CLOSE_CONVERT_CAST." >= {$min} AND ".OPEN_CONVERT_CAST."JSON_EXTRACT(t.price, '$[0]')".CLOSE_CONVERT_CAST." <= {$max} )";
					else $sql .= " ( ".OPEN_CONVERT_CAST."JSON_EXTRACT(t.price, '$[0]')".CLOSE_CONVERT_CAST." >= {$min} )";
					$i++;
				}
				$sql .= " )";
			}
		}
		if (!is_null($active)) {
			$sql .= " AND p.active = '{$active}'";
		}
		$sql .= " AND p.deleted = '0'";
		// $sql .= " GROUP BY p.id";
		if (!empty($order_by)) {
			$sql .= " ORDER BY {$order_by} {$sort_by}";
		} else {
			$sql .= " ORDER BY p.created_date DESC";
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