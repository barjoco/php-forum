<?

// User PDO used to represent a user

class User {
  private $id;
  private $firstname;
  private $lastname;
  private $username;
  private $email;
  private $password;
  private $default_profile_img;
  private $default_cover_img;
  private $reg_date;

  public function __construct($db, $username) {
    $sql = 'SELECT * FROM users WHERE username = ? LIMIT 1';
    $user_record = $db->query_row($sql, 's', $username);

    $this->id = $user_record['id'];
    $this->firstname = $user_record['firstname'];
    $this->lastname = $user_record['lastname'];
    $this->username = $user_record['username'];
    $this->email = $user_record['email'];
    $this->password = $user_record['password'];
    $this->default_profile_img = $user_record['default_profile_img'];
    $this->default_cover_img = $user_record['default_cover_img'];
    $this->reg_date = $user_record['reg_date'];
  }

  public function get_id() {
    return $this->id;
  }
  public function get_firstname() {
    return $this->firstname;
  }
  public function get_lastname() {
    return $this->lastname;
  }
  public function get_username() {
    return $this->username;
  }
  public function get_email() {
    return $this->email;
  }
  public function get_password() {
    return $this->password;
  }
  public function get_default_profile_img() {
    return $this->default_profile_img;
  }
  public function get_default_cover_img() {
    return $this->default_cover_img;
  }
  public function get_reg_date() {
    return $this->reg_date;
  }

  public function set_id($id) {
    $this->id = $id;
  }
  public function set_firstname($firstname) {
    $this->firstname = $firstname;
  }
  public function set_lastname($lastname) {
    $this->lastname = $lastname;
  }
  public function set_username($username) {
    $this->username = $username;
  }
  public function set_email($email) {
    $this->email = $email;
  }
  public function set_password($password) {
    $this->password = $password;
  }
  public function set_default_profile_img($default_profile_img) {
    $this->default_profile_img = $default_profile_img;
  }
  public function set_default_cover_img($default_cover_img) {
    $this->default_cover_img = $default_cover_img;
  }
  public function set_reg_date($reg_date) {
    $this->reg_date = $reg_date;
  }

  // Returns the user's profile picture, or default one
  public function retrieve_profile_img($db) {
    $sql = 'SELECT id, default_profile_img FROM users WHERE username = ?';
    $row = $db->query_row($sql, 's', $this->get_username());

    if ($row['default_profile_img'] || $this->get_username() == 'JS Forum') {
      $src = 'static/img/profile_img/default.png';
      return "<img src='$src' class='colour_by_theme'>";
    } else {
      $id = $row['id'];
      $src = "static/img/profile_img/$id.jpg";
      return "<img src='$src'>";
    }
  }

  // Returns the user's cover picture, or default one
  public function retrieve_cover_img($db) {
    $sql = 'SELECT id, default_cover_img FROM users WHERE username = ?';
    $row = $db->query_row($sql, 's', $this->get_username());

    if ($row['default_cover_img'] || $this->get_username() == 'JS Forum') {
      $src = 'static/img/cover_img/default.png';
      return "<img src='$src' class='colour_by_theme'>";
    } else {
      $id = $row['id'];
      $src = "static/img/cover_img/$id.jpg";
      return "<img src='$src'>";
    }
  }
}