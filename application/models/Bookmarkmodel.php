<?php
	class Bookmarkmodel extends CI_Model{
		private $bookmarkTableName = 'bookmark';
		private $tagsTableName = 'tag';
		private $tagOnTableName = 'tag_on';
		public function __construct(){
			parent::__construct();
			$this->load->database();	
		}

		public function GetBookmarksByID($u_id){
			$bookmarks = $this->GetBookmarksFromDB($u_id);
			$tags = $this->GetTagsFromDB($u_id);
			$tag_on = $this->GetTagOnFromDB($this->FetchBookmarkID($bookmarks));

			foreach($tag_on as $match){
				array_push($bookmarks[$match->b_id]->tags,$tags[$match->t_id]);
			}
			return $bookmarks;
		}
		public function GetBookmarksJsonByID($u_id){
			$bookmarks = $this->GetBookmarksFromDB($u_id);
			$tags = $this->GetTagsFromDB($u_id);
			$tag_on = $this->GetTagOnFromDB($this->FetchBookmarkID($bookmarks));

			foreach ($tag_on as $match) {
				array_push($bookmarks[$match->bid]->tags,$match->tid);
			}
			$jsonObj = array(
				'bookmarks' => $bookmarks,
				'tags' => $tags
				);
			return $jsonObj;
		}

		public function SetTag($tagData){
			$sql = "INSERT INTO {$this->tagsTableName} (tid,uid,title,font_color,bg_color)
				VALUE(?,?,?,?,?)
				ON DUPLICATE KEY UPDATE title = ?,font_color = ?,bg_color = ?";
			$this->db->query($sql,$tagData);
			return $this->db->insert_id();
		}
		public function DeleteTag($tagData){
			$tagsql = "DELETE FROM {$this->tagsTableName} WHERE tid = ?";
			$this->db->query($tagsql,$tagData);
			return $this->db->affected_rows();
		}
		public function SetBookmark($bookmarkData){
			$sql = "INSERT INTO {$this->bookmarkTableName} (bid,uid,title,url)
				VALUE(?,?,?,?)
				ON DUPLICATE KEY UPDATE title = ?,url = ?";
			$this->db->query($sql,$bookmarkData);
			return $this->db->insert_id();

		}
		public function DeleteBookmark($bookmarkData){
			$tagsql = "DELETE FROM {$this->bookmarkTableName} WHERE bid = ?";
			$this->db->query($tagsql,$bookmarkData);
			return $this->db->affected_rows();
		}
		public function SetTagon($bid,$tags){
			$tagon = array();
			foreach($tags as $tag){
				array_push($tagon, array('bid'=>$bid,'tid'=>$tag));
			}
			$this->db->insert_batch($this->tagOnTableName, $tagon);
			return $this->db->affected_rows();
		}
		public function DeleteTagon($by,$id){
			$sql = "DELETE FROM {$this->tagOnTableName} WHERE {$by} = ?";
			$this->db->query($sql,$id);
			return $this->db->affected_rows();		
		}
		private function GetBookmarksFromDB($id){
			$sql = 'SELECT * FROM '.$this->bookmarkTableName .' WHERE uid = ?';
			$list = $this->db->query($sql,$id)->result();
			$result = array();
			foreach ($list as  $row) {
				$obj = new BookmarkData();
				$obj->bid = $row->bid;
				$obj->uid = $row->uid;
				$obj->title = $row->title;
				$obj->url = $row->url;
				//array_push($result, $obj);
				$result[$obj->bid] = $obj;
			}
			return $result;
		}
		private function GetTagsFromDB($id){
			$sql = 'SELECT * FROM ' . $this->tagsTableName . ' WHERE uid = ?';
			$list = $this->db->query($sql,$id)->result();
			$result = array();
			foreach($list as $row){
				$obj = new TagData();
				$obj->tid = $row->tid;
				$obj->uid = $row->uid;
				$obj->title = $row->title;
				$obj->font_color = $row->font_color;
				$obj->bg_color = $row->bg_color;
				$result[$obj->tid] = $obj;
			}
			return $result;
		}

		private function GetTagOnFromDB($bids){
			$sql = 'SELECT * FROM ' . $this->tagOnTableName . ' WHERE bid IN ?';
			$list = $this->db->query($sql,array((array)$bids))->result();
			return $list;

		}


		private function FetchBookmarkID($bookmarks){
			$bids = array();
			foreach($bookmarks as $bk){
				array_push($bids,$bk->bid);
			}
			return $bids;
		}
	}



	class BookmarkData{
		public $bid = 0;
		public $uid = 0;
		public $title = '';
		public $url = '';
		public $tags = array();
	}
	class TagData{
		public $t_id = 0;
		public $u_id = 0;
		public $title = '';
		public $font_color = '000000';
		public $bg_color = 'ffffff';

	}
?>