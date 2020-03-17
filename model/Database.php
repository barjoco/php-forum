<?

// Database PDO used for accessing the database

class Database {
  private $conn;

  // Store new mysql connection
  public function __construct($host, $user, $pass, $db) {
    $mysqli = new mysqli($host, $user, $pass, $db);

    if ($mysqli->connect_errno) {
      send_error($mysqli->connect_error, '/');
    } else {
      $this->conn = $mysqli;
    }
  }

  public function getConn() {
    return $this->conn;
  }

  private function sanitise($string) {
    return $this->conn->real_escape_string($string);
  }

  // Handle various query types (single row, multi-row, execution)
  public function query_handler($exec_only, $single_row, $sql, $param_binds, ...$params) {
    // Initialise statement
    $stmt = mysqli_stmt_init($this->conn);  

    // Prepare and bind params to statement
    if (mysqli_stmt_prepare($stmt, $sql)) {
      if ($param_binds) {
        if (!mysqli_stmt_bind_param($stmt, $param_binds, ...$params)) {
          // Can't bind params
          send_error(mysqli_stmt_error($stmt), '/');
        }
      }

      // Execute statement
      $success = mysqli_stmt_execute($stmt);

      if ($success && $exec_only) {
        // Close statement
        mysqli_stmt_close($stmt);
        return true;

      } else {
        if ($success) {
          // Return results of statement
          $result = mysqli_stmt_get_result($stmt);
          mysqli_stmt_close($stmt);
          
          if ($single_row) {
            return mysqli_fetch_array($result);
          } else {
            return $result;
          }          
        } else {
          // Statement execution failed
          send_error(mysqli_stmt_error($stmt), '/');
        }
      }
    } else {
      // Statement execution failed
      send_error(mysqli_stmt_error($stmt), '/');
    }
  }

  public function query($sql, $param_binds = null, ...$params) {
    return $this->query_handler(false, false, $sql, $param_binds, ...$params);
  }

  public function query_row($sql, $param_binds = null, ...$params) {
    return $this->query_handler(false, true, $sql, $param_binds, ...$params);
  }

  public function exec($sql, $param_binds = null, ...$params) {
    return $this->query_handler(true, false, $sql, $param_binds, ...$params);
  }

  // Checks if username is being used: boolean
  public function check_username($username) {
    $row = $this->query_row('SELECT id FROM users WHERE username = ? LIMIT 1', 's', $username);
    if ($row) {
      return true;
    }
    return false;
  }

  // Checks if email is being used: boolean
  public function check_email($email) {
    $row = $this->query_row('SELECT id FROM users WHERE email = ? LIMIT 1', 's', $email);
    if ($row) {
      return true;
    }
    return false;
  }

  // Insert user into db, returns the insert id
  public function insert_user($first_name, $last_name, $username, $email, $password) {
    $this->exec(
      'INSERT INTO users VALUES (NULL, ?, ?, ?, ?, ?, 1, 1, DEFAULT)',
      'sssss',
      $first_name, $last_name, $username, $email, $password
    );

    return mysqli_insert_id($this->conn);
  }

  // Used to fetch rows from a result set
  public function fetch_from($results) {
    return mysqli_fetch_array($results);
  }

  // Get user handler, depending on query by id or username
  public function get_user_handler($by_id, $value) {
    $col = $by_id ? 'id' : 'username';
    $bind = $by_id ? 'i' : 's';
    return $this->query_row("SELECT * FROM users WHERE $col = ?", $bind, $value);
  }

  // Get user record by id
  public function get_user_by_id($id) {
    return $this->get_user_handler(true, $id);
  }

  // Get user record by username
  public function get_user_by_username($username) {
    return $this->get_user_handler(false, $username);
  }

  // Get a subset of users based on supplied query
  public function get_users_by_query($query) {
    $rows = $this->query('SELECT * FROM users WHERE username LIKE ?', 's', "%$query%");
    $results = [];
    while ($row = $this->fetch_from($rows)) {
      $results[] = $row;
    }
    return json_encode($results);
  }

  // Get categories
  public function get_categories() {
    return $this->query('SELECT * FROM categories');
  }

  // Get a category
  public function get_category($category_id) {
    return $this->query_row('SELECT * FROM categories WHERE id = ?', 'i', $category_id);
  }

  // Gets a custom subset of results from a table, with optional conditional statements
  public function get_page($page_num, $page_len, $table, $columns=null, $conditionals=null) {
    $columns = $columns ? implode(',', $columns) : '*';
    $page_start = $page_len * ($page_num-1);
    return $this->query("SELECT $columns FROM $table $conditionals LIMIT $page_start, $page_len");
  }

  // Gets a page of posts
  public function get_posts($page_num, $page_len, $categories=null) {
    $categories = $categories ? implode(',', $categories) : null;
    $conditionals = 'WHERE replying_to IS NULL';
    $conditionals .= ($categories ? " AND category IN ($categories)" : '') . ' ORDER BY creation_date DESC';
    return $this->get_page($page_num, $page_len, 'posts', null, $conditionals);
  }

  // Get a single post
  public function get_post($post_id) {
    return $this->query_row('SELECT * FROM posts WHERE id = ?', 'i', $post_id);
  }

  // Get posts from a single user
  public function get_user_posts($uid, $page_num, $page_len, $categories=null) {
    $categories = $categories ? implode(',', $categories) : null;
    $conditionals = "WHERE replying_to IS NULL AND author = $uid";
    $conditionals .= ($categories ? " AND category IN ($categories)" : '') . ' ORDER BY id DESC';
    return $this->get_page($page_num, $page_len, 'posts', null, $conditionals);
  }

  // Get a page of messages
  public function get_messages($page_num, $page_len, $uid) {
    $conditionals = "WHERE recipient = $uid ORDER BY id DESC";
    return $this->get_page($page_num, $page_len, 'messages', null, $conditionals);
  }

  // Get a page of watch list posts
  public function get_watch_list($page_num, $page_len, $uid) {
    $conditionals = "WHERE user = $uid ORDER BY post DESC";
    return $this->get_page($page_num, $page_len, 'watch_list', null, $conditionals);
  }

  // Remove a post from watch list
  public function watch_list_remove($n, $uid) {
    $conditional = "WHERE user = $uid" . ($n === '*' ? '' : " AND post = $n");
    $this->exec("DELETE FROM watch_list $conditional");
  }

  // Get cover image or profile image, or default image for either
  // get_user_img('profile_img', 23); or get_user_img('cover_img', 23);
  public function get_user_img($target, $uid) {
    $sql = "SELECT default_$target FROM users WHERE id = ?";
    $default = $this->query_row($sql, 'i', $uid)[0];
    $src = $default ? 'default.png' : $uid.'.jpg';
    return "<img src='static/img/$target/$src'".($default?"class='colour_by_theme'":'').">";
  }

  // Insert post
  public function insert_post($title, $body, $has_img, $category, $author, $replying_to) {
    $this->exec('INSERT INTO posts VALUES (NULL, ?, ?, ?, 0, ?, ?, ?, DEFAULT)', 'ssiiii', $title, $body, $has_img, $category, $author, $replying_to);
  }

  // Insert new message
  public function insert_message($title, $body, $author, $recipient) {
    $this->exec('INSERT INTO messages VALUES (NULL, ?, ?, ?, ?, DEFAULT)', 'ssii', $title, $body, $recipient, $author);
  }
  
  // Gets the last inserted ID
  public function get_last_id() {
    return $this->query_row('SELECT LAST_INSERT_ID()')[0];
  }
}