<?

// Post PDO used to represent a post

class Post {  	
  private $id;
  private $title;
  private $body;
  private $category;
  private $img_attachment;
  private $top_level;
  private $views;
  private $replying_to;
  private $created_by;
  private $creation_date;

  public function __construct($record) {
    $this->id = $record['id'];
    $this->title = $record['title'];
    $this->body = $record['body'];
    $this->category = $record['category'];
    $this->img_attachment = $record['img_attachment'];
    $this->top_level = $record['top_level'];
    $this->views = $record['views'];
    $this->replying_to = $record['replying_to'];
    $this->created_by = $record['created_by'];
    $this->creation_date = $record['creation_date'];
  }

  public function get_id() {
    return $this->id;
  }
  public function get_title() {
    return $this->title;
  }
  public function get_body() {
    return $this->body;
  }
  public function get_category() {
    return $this->category;
  }
  public function get_img_attachment() {
    return $this->img_attachment;
  }
  public function get_top_level() {
    return $this->top_level;
  }
  public function get_views() {
    return $this->views;
  }
  public function get_replying_to() {
    return $this->replying_to;
  }
  public function get_created_by() {
    return $this->created_by;
  }
  public function get_creation_date() {
    return $this->creation_date;
  }

  public function set_id($id) {
    $this->id = $id;
  }
  public function set_title($title) {
    $this->title = $title;
  }
  public function set_body($body) {
    $this->body = $body;
  }
  public function set_category($category) {
    $this->category = $category;
  }
  public function set_img_attachment($img_attachment) {
    $this->img_attachment = $img_attachment;
  }
  public function set_top_level($top_level) {
    $this->top_level = $top_level;
  }
  public function set_views($views) {
    $this->views = $views;
  }
  public function set_replying_to($replying_to) {
    $this->replying_to = $replying_to;
  }
  public function set_created_by($created_by) {
    $this->created_by = $created_by;
  }
  public function set_creation_date($creation_date) {
    $this->creation_date = $creation_date;
  }
}