<?php

	/* Model for user account management
	** @author James Ho
	** @reviewer Daryl Lim
	*/
	Class User extends CI_Model
	{
		function login($userEmail, $password)
		{
			$this -> db -> select('userID, userEmail, passwd');
			$this -> db -> from('users');
			$this -> db -> where('userEmail', $userEmail);
			$this -> db -> where('passwd', $password);
			$this -> db -> limit(1);
			$query = $this -> db -> get();

			if($query -> num_rows() == 1)
			{
				return $query->result();
			}
			else
			{
				return false;
			}
		}
	}
?>