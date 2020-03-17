<?

//
//  Represents a web page in the system
//

class Page {
    private $title;

    public function __construct($title) {
        $this->title = $title;
    }

    public function get_title() {
        return $this->title;
    }
}
