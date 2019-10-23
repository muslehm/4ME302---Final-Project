<?php
class MenuNav {
  public static function ConstructMenu() {
      $items = array(
        'home'  => array('text'=>'Home',  'url'=>'?p=home'),
        'data'  => array('text'=>'Data',  'url'=>'?p=data'),
        'news' => array('text'=>'News', 'url'=>'?p=news'),
        );
    $html = "<nav class='navbar'>\n";
    if($_SESSION['myrole']!=3)
    {
        $newsbutton = array_pop($items);
    }
    if($_SESSION['myrole']==1)
    {
        $databutton = array_pop($items);
        $videom = array('videos'  => array('text'=>'Exercise Videos',  'url'=>'?p=video'));
        $therapydata = array('therapy'  => array('text'=>'Therapy and Data',  'url'=>'?p=therapy'));
        $items += $videom;
        $items += $therapydata;
    }
    if($_SESSION['myrole']==2)
    {
        
        $therapy = array('therapy'  => array('text'=>'Patients Therapies',  'url'=>'?p=therapy'));
        $items += $therapy;
    }
    foreach($items as $key => $item) {
        
      
      $selected = (isset($_GET['p'])) && $_GET['p'] == $key ? 'selected' : null; 
      $html .= "<a href='{$item['url']}' class='{$selected}'>{$item['text']}</a>\n";
    }
    
    $html .= "<a href='logout.php' class='signout'>Sign out</a>\n</nav>\n";
    return $html;
  }
};
