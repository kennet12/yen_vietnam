<?
class M_user extends M_db
{
	public function __construct()
	{
		parent::__construct();
		
		$this->_table = "m_user";
	}
	
	public function user($info=null, $active=null)
	{
		if (!is_null($info)) {
			$info->email    = addslashes(trim($info->email));
			$info->password = addslashes(trim($info->password));
			
			if (empty($info->email) || empty($info->password)) {
				return null;
			}
			
			$info->email    = strtoupper($info->email);
			$info->password = md5($info->password);
			
			$sql  = " SELECT * FROM {$this->_table} WHERE 1 = 1";
			$sql .= " AND UPPER({$this->_table}.email) = '{$info->email}'";
			$sql .= " AND {$this->_table}.password = '{$info->password}'";
			
			if (!is_null($active)) {
				$sql .= " AND {$this->_table}.active = '{$active}'";
			}
			
			$query = $this->db->query($sql);
			if ($query->num_rows() > 0) {
				return $query->row();
			}
		}
		
		return null;
	}
	
	public function users($info=null, $active=null, $limit=null, $offset=null)
	{
		$sql = "SELECT *, '0' AS 'child_num' FROM {$this->_table} WHERE 1 = 1";
		
		if (!is_null($info)) {
			if (!empty($info->user_type)) {
				$sql .= " AND {$this->_table}.user_type = '{$info->user_type}'";
			}
			if (!empty($info->user_types)) {
				$sql .= " AND {$this->_table}.user_type IN (".implode(",", $info->user_types).")";
			}
			if (!empty($info->search_text)) {
				$info->search_text = strtoupper(trim($info->search_text));
				$sql .= " AND (UPPER({$this->_table}.email) = '{$info->search_text}' OR UPPER({$this->_table}.fullname) LIKE '%{$info->search_text}%')";
			}
		}
		if (!is_null($active)) {
			$sql .= " AND {$this->_table}.active = '{$active}'";
		}
		$sql .= " AND {$this->_table}.id <> '0'";
		$sql .= " ORDER BY {$this->_table}.created_date DESC";
		
		if (!is_null($limit)) {
			$sql .= " LIMIT {$limit}";
		}
		if (!is_null($limit)) {
			$sql .= " OFFSET {$offset}";
		}
		
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	public function count($info=null, $active=null)
	{
		$sql = "SELECT COUNT(*) AS 'total' FROM {$this->_table} WHERE 1 = 1";
		if (!is_null($info)) {
			if (!empty($info->user_type)) {
				$sql .= " AND {$this->_table}.user_type = '{$info->user_type}'";
			}
			if (!empty($info->user_types)) {
				$sql .= " AND {$this->_table}.user_type IN (".implode(",", $info->user_types).")";
			}
			if (!empty($info->search_text)) {
				$info->search_text = strtoupper(trim($info->search_text));
				$sql .= " AND (UPPER({$this->_table}.email) = '{$info->search_text}' OR UPPER({$this->_table}.fullname) LIKE '%{$info->search_text}%')";
			}
		}
		if (!is_null($active)) {
			$sql .= " AND {$this->_table}.active = '{$active}'";
		}
		
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			return $query->row()->total;
		}
		
		return 0;
	}
	
	public function is_user_exist($email, $active=null, $provider=null)
	{
		$email = addslashes(trim($email));
		
		$sql = "SELECT * FROM {$this->_table} WHERE 1 = 1";
		if (!is_null($email)) {
			$sql .= " AND LOWER({$this->_table}.email) = '".strtolower($email)."'";
		}
		if (!is_null($active)) {
			$sql .= " AND {$this->_table}.active = '{$active}'";
		}
		if (!empty($provider)) {
			$sql .= " AND {$this->_table}.provider = '{$provider}'";
		}
		
		$query = $this->db->query($sql);
		return ($query->num_rows() > 0);
	}
	
	public function get_user_by_email($email, $active=null, $provider=null)
	{
		$email = addslashes(trim($email));
		
		if (empty($email)) {
			return null;
		}
		
		$sql = "SELECT * FROM {$this->_table} WHERE LOWER({$this->_table}.email) = '".strtolower($email)."'";
		
		if (!is_null($active)) {
			$sql .= " AND {$this->_table}.active = '{$active}'";
		}
		if (!empty($provider)) {
			$sql .= " AND {$this->_table}.provider = '{$provider}'";
		}
		
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		
		return null;
	}
	public function get_user_by_phone($phone, $active=null, $provider=null)
	{
		$phone = addslashes(trim($phone));
		
		if (empty($phone)) {
			return null;
		}
		
		$sql = "SELECT * FROM {$this->_table} WHERE LOWER({$this->_table}.phone) = '".strtolower($phone)."'";
		
		if (!is_null($active)) {
			$sql .= " AND {$this->_table}.active = '{$active}'";
		}
		if (!empty($provider)) {
			$sql .= " AND {$this->_table}.provider = '{$provider}'";
		}
		
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		
		return null;
	}
	
	public function get_social_user($uid=null, $provider=null, $email=null, $active=null)
	{
		if (empty($provider)) {
			return null;
		}
		
		$email = addslashes(trim($email));
		
		if (empty($email)) {
			return null;
		}
		
		$sql = "SELECT * FROM {$this->_table} WHERE 1 = 1";
		if (!empty($uid)) {
			$sql .= " AND {$this->_table}.uid = '{$uid}'";
		}
		
		if (!is_null($email)) {
			$sql .= " AND LOWER({$this->_table}.email) = '".strtolower($email)."'";
		}
		
		$sql .= " AND {$this->_table}.provider = '{$provider}'";
		
		if (!is_null($active)) {
			$sql .= " AND {$this->_table}.active = '{$active}'";
		}
		
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		
		return null;
	}
	
	public function login($email, $password, $user_type="user")
	{
		$email    = addslashes(trim($email));
		$password = addslashes(trim($password));
		
		if (empty($email) || empty($password)) {
			return false;
		}
		
		$email    = strtoupper($email);
		$password = md5($password);
		$sql      = "SELECT * FROM {$this->_table} WHERE UPPER({$this->_table}.email)='{$email}' AND {$this->_table}.password='{$password}' AND {$this->_table}.active=1";
		$query    = $this->db->query($sql);
		
		if ($query->num_rows() > 0) {
			$user = $query->row();
			$this->session->set_userdata($user_type, $user);
			if ($user_type == "admin") {
				$this->session->set_userdata("agent_id", ADMIN_AGENT_ID);
			}
			return true;
		}
		
		return false;
	}
	
	function cp_login($id, $user_type="user")
	{
		if (empty($id)) {
			return false;
		}
		
		$sql = "SELECT * FROM {$this->_table} WHERE {$this->_table}.id='{$id}'";
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0) {
			$user = $query->row();
			$this->session->set_userdata($user_type, $user);
			if ($user_type == "admin") {
				$this->session->set_userdata("agent_id", ADMIN_AGENT_ID);
			}
			return true;
		}
		
		return false;
	}
	
	public function logout()
	{
		$this->session->sess_destroy();
	}
	
	public function last_activity($user_id)
	{
		$data = array("last_activity" => date($this->config->item("log_date_format")));
		$where = array("id" => $user_id);
		$this->db->update($this->_table, $data, $where);
	}
	public function get_user_email($email, $active=null)
	{
		if (empty($email)) {
			return null;
		}
		$sql = "SELECT * FROM m_user WHERE LOWER(email) = '".strtolower($email)."'";
		if (!is_null($active)) {
			$sql .= " AND active = '{$active}' ";
		}
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		return null;
	}
	public function forgot_password($code_confirm=null,$active=null)
	{
		if (empty($code_confirm) && empty($active)) {
			return null;
		}
		$sql = "SELECT * FROM m_user WHERE code_confirm = '{$code_confirm}'";
		if (!is_null($active)) {
			$sql .= " AND active = '{$active}' ";
		}
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		return null;
	}
	public function load_item($affiliate_code)
	{
		$sql = "SELECT * FROM {$this->_table} WHERE 1 = 1";
		$sql .= " AND affiliate_code = '{$affiliate_code}'";
		$sql .= " ORDER BY {$this->_table}.created_date ASC";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		return null;
	}
	public function phone_exist_affiliate($phone)
	{
		$sql = "SELECT * FROM {$this->_table} WHERE 1 = 1";
		$sql .= " AND affiliate_partner LIKE '%{$phone}%'";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		return null;
	}
}
?>
