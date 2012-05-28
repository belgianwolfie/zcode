<?php
	
	/**
	 * TODO List assignment for zCode
	 *
	 * This program inits a list of tasks and stores them in a cookie for later reference.
	 * Front end user can add, delete or edit tasks.
	 * Changes are saved to cookie.
	 *
	 * LICENSE: TheAlliance.be 2012 (c)
	 *
	 * @author     Bart Stassen <bart@thealliance.be>
	 * @copyright  2012 The Alliance
	 * @package    zCode
	 */
	 
	 

 
	$taskArr = array();

 	// some tasks to start things off
	function init() {
		global $taskArr;
	
		if (isset($_COOKIE['ztasks'])) {
			$taskArr = getCookieArr($_COOKIE['ztasks']);
		} else {
			$taskArr = array(
				  0 => array(
					   'date' => '06/09/2012',
					   'task' => 'Have to do laundry'
				  ),
				  1 => array(
					   'date' => '06/19/2012',
					   'task' => 'Visit Las Vegas Downtown project'
				  ),
				  2 => array(
					   'date' => '06/21/2012',
					   'task' => 'Have to feed the dragons'
				  ),		  
				  3 => array(
					   'date' => '06/22/2012',
					   'task' => 'My birthday, for real'
				  ),
				  4 => array(
					   'date' => '06/23/2012',
					   'task' => 'Slice chicken at Wynn buffet'
				  ),		  
				  5 => array(
					   'date' => '06/30/2012',
					   'task' => 'Fly over the Grand Canyon'
				  )
				);
			
			$expire = 60 * 60 * 24 * 60 + time(); 
			setcookie('ztasks', serialize($taskArr), $expire); 
		}
		myArrSorter($taskArr,'date',FALSE);
	}
	
	
	
	/*
	 * Helper function to get my array back from the cookie
	 *
	 * Input
	 * @param $cookie : the cookie that needs getting!
	 *
	 * Output
	 * The unserialized (if needed, slashstripped) array from the cookie
	 *
	 */	
	function getCookieArr($cookie) {
		return get_magic_quotes_gpc() ? unserialize(stripslashes($cookie)) : unserialize($cookie);
	}



	/*
	 * Helper function to sort my tasks array (custom made function as stated in assignment guidelines)
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
	
	// let's kick it !
	init();

	// Anything coming in ?
	$newTask = $_POST['task'];
	$newDate = $_POST['date'];

	
	
	if (isset($newTask)) {
	
		$taskArr = getCookieArr($_COOKIE['ztasks']);
		$arrPt = &$taskArr[];
				
		$arrPt['date'] = $newDate;
		$arrPt['task'] = ucfirst(stripslashes($newTask)); // clean up and capitalize new title
		myArrSorter($taskArr,'date',FALSE);
		
		// store new array to cookie
		setcookie('ztasks', serialize($taskArr), $expire); 
				
	} 
 	?>
    
 	  <html>
      <body>
      <head>
 	  <script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
      <script type="text/javascript" src="js/jquery.ui.core.js"></script>
      <script type="text/javascript" src="js/jquery.ui.datepicker.js"></script>   
      <script type="text/javascript" src="js/jquery.tablesorter.min.js"></script>  
      <link rel="stylesheet" type="text/css" href="css/widgets.css">  
      <link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
      <style>
	  	input[type="text"] {
			font-size:1.0em;
		}
		
		.delete_tr {
			background: none repeat scroll 0 0 #993300;
			color: #EFEFEF;
			display: block;
			float: left;
			font-size: 14px;
			font-weight: bold;
			margin-bottom: 20px;
			padding: 5px;
		}
		
		.edit_tr {
			background:#efefef;
		}
		
		.editcta {
			background: none repeat scroll 0 0 #4bb3d3;
			color: #efefef;
			display: block;
			float: left;
			font-size: 14px;
			font-weight: bold;
			margin-bottom: 20px;
			margin-left:5px;
			padding: 5px;
		}
	
	  </style>
      </head>
    
      <script type="text/javascript">
	  $(document).ready(function()
			{
			// provide a hook to the datepicker
			$("#datepicker").datepicker();
			
			// call sort routine
			$("#taskTable").tablesorter(); 
		
			
			// set initial state for editing
			$(".editbox").hide();
			
			$(".edit_tr").click(function()
			{
				var key=$(this).attr('id');
				$("#date_"+key).hide();
				$("#task_"+key).hide();
				$("#date_input_"+key).show();
				$("#task_input_"+key).show();
				}).change(function()
				{
				var key=$(this).attr('id');
				var date=$("#date_input_"+key).val();
				var task=$("#task_input_"+key).val();
				var dataString = 'key='+ key +'&date='+date+'&task='+task+'&action=update';
		
				if(date.length > 0 && task.length > 0)
					{
						$.ajax({
							type: "POST",
							url: "todolist_save.php",
							data: dataString,
							

							success: function(html)
								{
								$("#debug").html("Ajax Posted");
								$("#date_"+key).html(date);
								$("#task_"+key).html(task);
								}
						});
					}
					else
					{
					alert('Someting went wrong.');
					}
				});
				
				// Edit input box click action
				$(".editbox").mouseup(function()
				{
				return false
				});
			
			// Outside click action
			$(document).mouseup(function()
			{
				$(".editbox").hide();
				$(".text").show();
			});
			
			
			// call the delete routine in todolist_save.php
			$(".delete_tr").click(function()
			{
				var key = $(this).attr('id');
				var dataString = 'key='+ key +'&action=delete';
				
				$.ajax({
							type: "POST",
							url: "todolist_save.php",
							data: dataString,

							success: function(html)
								{
								$(".edit_tr[id="+key+"]").animate( {backgroundColor:'yellow'}, 500).fadeOut(100,function() {
									$(this).remove();
								});
								$(".delete_tr[id="+key+"]").animate( {backgroundColor:'yellow'}, 500).fadeOut(100,function() {
									$(this).remove();
								});
								$("#debug").html("Task deleted");
								}
						});
			});
		
		});
		</script>
        
        <div style="width:100%">
            <div style="margin:130px auto;width:970px;font-family:Arial, Helvetica, sans-serif;">
            	<h1>Todo list</h1>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">&nbsp;&nbsp;
                <strong>New task</strong> : <input type="text" name="task" size="30">
                <strong>at date</strong> : <input type="text" name="date" id="datepicker" />
                <input type="submit" value="+ add task"/>
                </form>
                <br />
                <div id="debug"></div>
                <br />
                <table cellpadding="10px" width="800px" id="taskTable" class="tablesorter">
                <thead>
                <tr>
                <th id="datesort" align="left"><div id="datesort">Date <small>(click to sort)</small></div></th>
                <th id="tasksort" align="left">Task <small>(click to sort)</small></th>
                <th id="actions" align="left">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php 
                 foreach ($taskArr as $key => $item) {
				?>
                
                    <tr id="<?php echo $key; ?>" class="edit_tr">
    
                    <td class="edit_td">
                    <span id="date_<?php echo $key; ?>" class="text"><?php echo $item['date']; ?></span>
                    <input type="text" size="10" value="<?php echo $item['date']; ?>" class="editbox" id="date_input_<?php echo $key; ?>"/>
                    </td>
                    
                    <td class="edit_td">
                    <span id="task_<?php echo $key; ?>" class="text"><?php echo $item['task']; ?></span>
                    <input type="text" size="50" value="<?php echo $item['task']; ?>" class="editbox" id="task_input_<?php echo $key; ?>"/>
                    </td>
                                  
                    <td style="padding:20px 0px 0px 10px">
                         <div id="<?php echo $key; ?>" class="delete_tr">delete this task</div>
                         <div class="editcta">edit this task</div>
                    </td>
                    
                    </tr>
                  
                
                <?php } //end foreach ?>
                </tbody>        
                </table>
               	<br />
                <br />
                
                <strong>Features/Usage</strong>
                <ul>
                    <li>Stores all tasks in a cookie.</li>
                    <li>Click on title or date (or the edit link) to edit an entry.</li>
                    <li>Click on delete to remove a task.</li>
                    <li>Click on the tableheaders to alphabetically or chronologically.</li>
                    <li>Add new task at certain date through top form.</li>
                </ul>
        </div>
    </div>
    </body>
    </html>