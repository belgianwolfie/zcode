<?php
	
	/**
	 * Php function to sort booktitles and display them downwards besides eachother.
	 *
	 * LICENSE: TheAlliance.be 2012 (c)
	 *
	 * @author     Bart Stassen <bart@thealliance.be>
	 * @copyright  2012 The Alliance
	 * @project    Part of the Ninja Books zCode application
	 */
	 
	 
	 // helper file to convert text to png
	 require_once('textimage.php');
	 
	 
	 $bookTitles = array();

 	 // some books to start things off
	 function init() {
		global $bookTitles;
		$bookTitles = array(
			  0 => array(
				   'isbn' => '9781402894626',
				   'title' => 'Bad jokes about ninjas'
			  ),
			  1 => array(
				   'isbn' => '9781402894627',
				   'title' => 'Breaking the law: ninja style'
			  ),
			  2 => array(
				   'isbn' => '9781402894629',
				   'title' => 'One hundered year old ninja'
			  ),		  
			  3 => array(
				   'isbn' => '9781402894630',
				   'title' => 'Party like a ninja'
			  ),
			  4 => array(
				   'isbn' => '9781402894631',
				   'title' => 'Chicken dishes for ninjas'
			  ),		  
			  5 => array(
				   'isbn' => '9781402894632',
				   'title' => 'Confessions of a ninja'
			  )	  
		);
	}
 	

	/*
	 * Helper function to generated semi random ISBN number (for the simple demo here incrementing the last ISBN)
	 *
	 * Input
	 * @param $string : last known ISBN number in array
	 *
	 * Output
	 * $isbn : the new isbn number
	 *
	 */
	 function newIsbn($arr) {
		   $largest = 0;
		   $arrCount = count($arr);
		   for($i=0; $i<$arrCount; $i++) {
			   $isbnNum = ($arr[$i]['isbn']);
			   if($isbnNum > $largest) {
				   $largest = $isbnNum;
			   }
		   }
		   return $largest+1;
	 }
	

	/*
	 * Helper function to sort my books array (custom made function as stated in assignment guidelines)
	 *
	 * Input
	 * @param &$arr : passing my unsorted array by reference
	 * @param $sortkey : key that array is being sorted on
	 *
	 * Output
	 * The unsorted array was passed by ref.
	 *
	 */
     function myArrSorter(&$arr,$sortkey) {
        $helper = count($arr);
        for ($i = 0; $i < $helper ; $i++){
            for ($j = $i + 1; $j < $helper ; $j++){
                    if ($arr[$i][$sortkey] > $arr[$j][$sortkey]){
                        $tmp = $arr[$i];
                        $arr[$i] = $arr[$j];
                        $arr[$j] = $tmp;
                    }
            }
         }
     }
	
	
	/* 
	 * Start core page activities
	 */
	$newTitle = $_POST['bookTitle'];
	
	if (isset($newTitle)) {
			session_start();
			
			// get from session
			$bookTitles = $_SESSION['bookTitles'];
		 	$arrPt = &$bookTitles[];
			$arrPt['isbn'] = newIsbn($bookTitles);
			
			// clean up and capitalize new title
			$arrPt['title'] = ucfirst(stripslashes($newTitle));
			myArrSorter($bookTitles,'title',FALSE);
			
			// store new array to session
			$_SESSION['bookTitles'] = $bookTitles;
	} else {
		init();
		session_start();
		myArrSorter($bookTitles,'title',FALSE);
		
		// store initial array to session
		$_SESSION['bookTitles'] = $bookTitles;
	}
 ?>
 
 	<!-- Our page markup -->
    
      <div style="width:100%">
            <div style="margin:130px auto;width:970px;font-family:Arial, Helvetica, sans-serif;">
            	<h1>Ninja books</h1>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <strong>New book title</strong> : <input type="text" name="bookTitle" size="30"><br /><br />
                <input type="submit" value="+ add book"/>
                </form>
                <table width="300px">
                <tr><td>
                <?php 
                 foreach ($bookTitles as $item) {
             	       echo imgText($item['title'],$item['isbn']);
                	   echo '</td><td>';
                  }
                ?>
                </td>
                </tr>
                </table>
                <br />
                <strong>Features</strong>
                <ul>
                    <li>Stores all books in a multidimensional array.</li>
                    <li>Key value pairs for each element (book) consist of an ISBN number and BOOK TITLE.</li>
                    <li>When a book is added, a new unique ISBN nr is auto-generated for that item.</li>
                    <li>ISBNnr is also used to generate unique filenames for the png's that make up the books.</li> 
                    <li>The new array is sorted and stored in a SESSION variable to allow for multiple books to be added.</li>
                </ul>
        </div>
    </div>