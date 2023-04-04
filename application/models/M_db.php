<?php
use CodeIgniter\Database\Query;
class M_db extends CI_Model {
	
	public $_table;
	
	public function instance()
	{
		$obj = new stdClass();
		
		$fields = $this->db->field_data($this->_table);
		foreach ($fields as $field) {
			if (in_array(strtolower($field->type), array("bigint", "tinyint", "double", "float", "int", "integer"))) {
				if (!empty($field->default)) {
					$obj->{$field->name} = $field->default;
				} else {
					$obj->{$field->name} = 0;
				}
			}
			else if (in_array(strtolower($field->type), array("varchar", "text"))) {
				if (!empty($field->default)) {
					$obj->{$field->name} = $field->default;
				} else {
					$obj->{$field->name} = "";
				}
			}
		}
		
		return $obj;
	}
	
	public function load($id, $active=null)
	{
		$sql = "SELECT * FROM {$this->_table} WHERE 1 = 1";
		if (is_numeric($id)) {
			$sql .= " AND id = ?";
		} else if ($this->db->field_exists("alias", $this->_table)) {
			$sql .= " AND alias = ?";
		} else if ($this->db->field_exists("alias_en", $this->_table)) {
			$sql .= " AND alias_en = ?";
		} else if ($this->db->field_exists("keyword", $this->_table)) {
			$sql .= " AND keyword = ?";
		} else if ($this->db->field_exists("code", $this->_table)) {
			$sql .= " AND code = ?";
		}
		if (!is_null($active) && $this->db->field_exists("active", $this->_table)) {
			$sql .= " AND active = '{$active}'";
		}
		$stmt = $this->db->conn_id->prepare($sql);
		$stmt->bind_param('s', $id);
		$stmt->execute();
		$result = $stmt->get_result();
		if ($result->num_rows > 0) {
			return (object)$result->fetch_assoc();
		}
		return null;
	}
	public function load_vi($id, $active=null)
	{
		$sql = "SELECT * FROM {$this->_table} WHERE 1 = 1";
		if ($this->db->field_exists("alias", $this->_table)) {
			$sql .= " AND alias = '{$id}'";
		}
		if (!is_null($active) && $this->db->field_exists("active", $this->_table)) {
			$sql .= " AND active = '{$active}'";
		}
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		return null;
	}
	public function load_en($id, $active=null)
	{
		$sql = "SELECT * FROM {$this->_table} WHERE 1 = 1";
		if ($this->db->field_exists("alias_en", $this->_table)) {
			$sql .= " AND alias_en = '{$id}'";
		}
		if (!is_null($active) && $this->db->field_exists("active", $this->_table)) {
			$sql .= " AND active = '{$active}'";
		}
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		return null;
	}
	public function load_translate($translate_id, $lang='vi', $active=null)
	{
		$sql = "SELECT * FROM {$this->_table} WHERE 1 = 1";
		if ($this->db->field_exists("translate_id", $this->_table)) {
			$sql .= " AND translate_id = {$translate_id}";
		} 
		$sql .= " AND lang = '{$lang}'";
		if (!is_null($active) && $this->db->field_exists("active", $this->_table)) {
			$sql .= " AND active = '{$active}'";
		}
		$stmt = $this->db->conn_id->prepare($sql);
		$stmt->execute();
		$result = $stmt->get_result();
		if ($result->num_rows > 0) {
			return (object)$result->fetch_assoc();
		}
		return null;
	}

	public function count_product_child($category_name,$arr)
	{
		$str_where_in = '';
		$c = count($arr);
		for ($i=0; $i < $c; $i++) {
			if ($i != ($c-1)) {
				$str_where_in .= "'{$arr[$i]}',";
			} else {
				$str_where_in .= "'{$arr[$i]}'";
			}
		}
		if (!empty($arr)) {
			$sql = "SELECT P.*, COUNT(C.{$category_name}_id) AS qty FROM {$this->_table} AS P LEFT JOIN m_product_{$category_name} AS C ON P.id = C.{$category_name}_id WHERE P.id IN ({$str_where_in}) GROUP BY P.id ORDER BY name ASC";

			$query = $this->db->query($sql);
			return $query->result();
		} else {
			return array();
		}
	}
	
	public function get_max_value($column="id")
	{
		$sql   = "SELECT MAX({$column}) AS val FROM {$this->_table}";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			$row = $query->row();
			return $row->val;
		}
		return 0;
	}
	public function get_auto_incre($column="id")
	{
		$database = DATABASE;
		$sql = "SELECT AUTO_INCREMENT as val FROM information_schema.TABLES WHERE TABLE_SCHEMA = '{$database}' AND TABLE_NAME = '{$this->_table}'";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			$row = $query->row();
			return $row->val;
		}
		return 0;
	}
	public function get_next_value($column="id")
	{
		return $this->get_max_value($column) + 1;
	}
	
	public function log($action=null, $data=null, $where=null)
	{
		if (strtolower($this->router->fetch_class()) == "syslog") {
			$user = $this->session->userdata("admin");
			$this->save_log($user, $action, $data, $where);
		} else {
			$user = $this->session->userdata("user");
		}
		if (!is_null($data)) {
			if (!empty($user)) {
				if ($this->db->field_exists("updated_by", $this->_table)) {
					$data["updated_by"] = $user->id;
				}
			}
			if ($this->db->field_exists("updated_date", $this->_table)) {
				$data["updated_date"] = date($this->config->item("log_date_format"));
			}
		}
		return $data;
	}
	
	public function save_log($user, $action, $data, $where)
	{
		if (!in_array($this->_table, array("m_history", "m_user_online"))) {
			$log_data = array(
				"user_id"		=> $user->id,
				"user_name"		=> $user->fullname,
				"action"		=> $action,
				"table_name"	=> $this->_table,
				"created_date"	=> date($this->config->item("log_date_format"))
			);
			if ($action == "add" && !is_null($data)) {
				$log_data["new_data"] = serialize($data);
			}
			else if ($action == "update" && !is_null($data) && !is_null($where)) {
				if (!empty($where["id"])) {
					$item = $this->load($where["id"]);
					if (!empty($item)) {
						$log_data["item_id"] = $item->id;
						$log_data["old_data"] = serialize($item);
					}
				}
				$log_data["new_data"] = serialize($data);
			}
			else if ($action == "delete" && !is_null($where)) {
				if (!empty($where["id"])) {
					$item = $this->load($where["id"]);
					if (!empty($item)) {
						$log_data["item_id"] = $item->id;
						$log_data["old_data"] = serialize($item);
					}
				}
			}
			$this->db->insert("m_history", $log_data);
		}
	}
	
	public function order_up($id)
	{
		$item = $this->load($id);
		
		$sql = "SELECT * FROM {$this->_table} WHERE 1 = 1";
		if ($this->db->field_exists("parent_id", $this->_table)) {
			$sql .= " AND parent_id = '{$item->parent_id}'";
		}
		if ($this->db->field_exists("category_id", $this->_table)) {
			$sql .= " AND category_id = '{$item->category_id}'";
		}
		$sql .= " ORDER BY order_num ASC";
		$query = $this->db->query($sql);
		$items = $query->result();
		
		$idx = sizeof($items);
		for ($i=0; $i<sizeof($items); $i++) {
			if ($items[$i]->id == $id) {
				$idx = $i;
			}
		}
		
		for ($i=0; $i<=($idx-2); $i++) {
			$data = array("order_num" => $i);
			$where = array("id" => $items[$i]->id);
			$this->db->update($this->_table, $data, $where);
		}
		
		for ($i=($idx-1); $i<=$idx; $i++) {
			$data = array("order_num" => ($i+1));
			$where = array("id" => $items[$i]->id);
			$this->db->update($this->_table, $data, $where);
		}
		
		for ($i=($idx+1); $i<sizeof($items); $i++) {
			$data = array("order_num" => $i);
			$where = array("id" => $items[$i]->id);
			$this->db->update($this->_table, $data, $where);
		}
		
		$data = array("order_num" => ($idx-1));
		$where = array("id" => $id);
		$this->update($data, $where);
	}
	
	public function order_down($id)
	{
		$item = $this->load($id);
		
		$sql = "SELECT * FROM {$this->_table} WHERE 1 = 1";
		if ($this->db->field_exists("parent_id", $this->_table)) {
			$sql .= " AND parent_id = '{$item->parent_id}'";
		}
		if ($this->db->field_exists("category_id", $this->_table)) {
			$sql .= " AND category_id = '{$item->category_id}'";
		}
		$sql .= " ORDER BY order_num ASC";
		$query = $this->db->query($sql);
		$items = $query->result();
		
		$idx = sizeof($items);
		for ($i=0; $i<sizeof($items); $i++) {
			if ($items[$i]->id == $id) {
				$idx = $i;
			}
		}
		
		for ($i=0; $i<$idx; $i++) {
			$data = array("order_num" => $i);
			$where = array("id" => $items[$i]->id);
			$this->db->update($this->_table, $data, $where);
		}
		
		for ($i=$idx; $i<=($idx+1); $i++) {
			$data = array("order_num" => ($i-1));
			$where = array("id" => $items[$i]->id);
			$this->db->update($this->_table, $data, $where);
		}
		
		for ($i=($idx+2); $i<sizeof($items); $i++) {
			$data = array("order_num" => $i);
			$where = array("id" => $items[$i]->id);
			$this->db->update($this->_table, $data, $where);
		}
		
		$data = array("order_num" => ($idx+1));
		$where = array("id" => $id);
		$this->update($data, $where);
	}
	
	public function view($id)
	{
		if ($this->db->field_exists("view_num", $this->_table)) {
			$item = $this->load($id);
			$data = array("view_num" => ($item->view_num + 1));
			$where = array("id" => $item->id);
			$this->db->update($this->_table, $data, $where);
		}
	}
	
	public function add($data)
	{
		if ($this->db->field_exists("created_date", $this->_table)) {
			$data["created_date"] = date($this->config->item("log_date_format"));
		}
		if ($this->db->field_exists("created_by", $this->_table)) {
			if (strtolower($this->router->fetch_class()) == "syslog") {
				$user = $this->session->userdata("admin");
			} else {
				$user = $this->session->userdata("user");
			}
			if (!empty($user)) {
				$data["created_by"] = $user->id;
			}
		}
		return $this->db->insert($this->_table, $this->log("add", $data));
	}
	
	public function update($data, $where)
	{
		return $this->db->update($this->_table, $this->log("update", $data, $where), $where);
	}
	
	public function delete($where)
	{
		$this->log("delete", null, $where);
		return $this->db->delete($this->_table, $where);
	}
	public function export_csv($filename,$arr_select,$info=null){
		$this->load->dbutil();
		$this->load->helper('file');
		$this->load->helper('download');
		$delimiter = "\t";
		$newline = "\r\n";
		$filename .= '.csv';
		$sql = "SELECT {$arr_select} FROM {$this->_table} WHERE 1 = 1";
		if (!is_null($info)) {
			foreach ($info as $key => $value) {
				if (!empty($value)) {
					$sql .= " AND {$key} = '{$value}'";
				}
			}
		}
		$result = $this->db->query($sql);
		$data = $this->dbutil->csv_from_result($result,$delimiter,$newline);
		force_download($filename, $data);
	}
}
?>