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
				array_push($bookmarks[$match->b_id]->tags,$match->t_id);
			}
			$jsonObj = array(
				'bookmarks' => $bookmarks,
				'tags' => $tags
				);
			return $jsonObj;
		}

		public function SetTag($tagData){
			$sql = "INSERT INTO {$this->tagsTableName} (t_id,u_id,title,font_color,bg_color)
				VALUE(?,?,'?','?','?')
				ON DUPLICATE KEY UPDATE t_id = ?";
			$result = $this->db->query($sql,$tagData);
		}

		private function GetBookmarksFromDB($id){
			$sql = 'SELECT * FROM '.$this->bookmarkTableName .' WHERE u_id = ?';
			$list = $this->db->query($sql,$id)->result();
			$result = array();
			foreach ($list as  $row) {
				$obj = new BookmarkData();
				$obj->b_id = $row->b_id;
				$obj->u_id = $row->u_id;
				$obj->title = $row->title;
				$obj->url = $row->url;
				//array_push($result, $obj);
				$result[$obj->b_id] = $obj;
			}
			return $result;
		}
		private function GetTagsFromDB($id){
			$sql = 'SELECT * FROM ' . $this->tagsTableName . ' WHERE u_id = ?';
			$list = $this->db->query($sql,$id)->result();
			$result = array();
			foreach($list as $row){
				$obj = new TagData();
				$obj->t_id = $row->t_id;
				$obj->u_id = $row->u_id;
				$obj->title = $row->title;
				$obj->font_color = $row->font_color;
				$obj->bg_color = $row->bg_color;
				$result[$obj->t_id] = $obj;
			}
			return $result;
		}

		private function GetTagOnFromDB($b_ids){
			$sql = 'SELECT * FROM ' . $this->tagOnTableName . ' WHERE b_id IN ?';
			$list = $this->db->query($sql,array((array)$b_ids))->result();
			return $list;

		}


		private function FetchBookmarkID($bookmarks){
			$b_ids = array();
			foreach($bookmarks as $bk){
				array_push($b_ids,$bk->b_id);
			}
			return $b_ids;
		}
	}



	class BookmarkData{
		public $b_id = 0;
		public $u_id = 0;
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